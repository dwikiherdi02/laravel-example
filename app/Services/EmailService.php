<?php

namespace App\Services;

use App\Dto\EmailDto;
use App\Dto\Lib\Imap\FilterDto;
use App\Libraries\Imap;
use App\Repositories\DuesPaymentRepository;
use App\Repositories\EmailRepository;
use App\Repositories\TextTemplateRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Log;
use Str;

class EmailService 
{
    function __construct(
        protected DuesPaymentRepository $duesPaymentRepo,
        protected EmailRepository $emailRepo,
        protected Imap $imapLib,
        protected TextTemplateRepository $textTemplateRepo,
        protected TransactionRepository $transactionRepo,
    ) {

    }

    public function fetchCredits() {
        DB::beginTransaction();
        try {
            $templates = $this->textTemplateRepo->getCreditTemplates();

            $emails = collect();

            foreach ($templates as $template) {
                $creditEmails = $this->imapLib->get(FilterDto::from([
                    'folder' => 'INBOX',
                    'from' => $template->email,
                    'subject' => $template->email_subject,
                    'limit' => 5,
                    'unseen' => true,
                    'onToday' => true,
                    'setFlagToSeen' => true,
                ]));

                foreach ($creditEmails as $creditEmail) {
                    $emails->push(EmailDto::from([
                        'id' => Str::uuid7(),
                        'text_template_id' => $template->id,
                        'body_text' => $creditEmail->text,
                        'body_html' => $creditEmail->body,
                        'is_read' => false,
                    ]));
                }
            }
            // dd('emails: ', $emails->toArray());
            $this->emailRepo->createMany($emails->toArray());
            DB::commit();  
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating credit email: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e; 
        }
    }

    public function processUnseenCredits() {
        
        $unseenEmails = $this->emailRepo->get([ 'is_read' => false ]);
        /* DB::transaction(function () use ($unseenEmails) {
            $ids = $unseenEmails->pluck('id')->toArray();
            if (count($ids) > 0) {
                $this->emailRepo->updateMany(['id' => $ids], [ 'is_read' => true ]);
            }
        }); */
        
        // dd('unseenEmails: ', $unseenEmails->toArray());

        foreach ($unseenEmails as $email) {
            DB::beginTransaction();
            try {
                $template = $email->template;
                if (!$template) {
                    throw new \Exception('Email template not found for email ID: ' . $email->id);
                }

                $creditData = extract_by_template($template->template, $email->body_text);

                dd('creditData: ', $creditData);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                
                // rollback the email to unseen
                $email->is_read = false;
                $email->save();
                
                Log::error('Error processing unseen credits: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
                throw $e; 
            }
        }

    }
}