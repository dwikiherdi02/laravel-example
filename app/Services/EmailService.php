<?php

namespace App\Services;

use App\Dto\EmailDto;
use App\Dto\Lib\Imap\FilterDto;
use App\Libraries\Imap;
use App\Repositories\EmailRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Log;
use Str;

class EmailService 
{
    function __construct(
        protected Imap $imapLib,
        protected EmailRepository $emailRepo,
    ) {

    }

    public function generateCreditEmail(Collection|array $templates) {
        DB::beginTransaction();
        try {
            // $emailCollection = collect();

            foreach ($templates as $template) {
                $creditEmails = $this->imapLib->get(FilterDto::from([
                    'folder' => 'INBOX',
                    'from' => $template->email,
                    'subject' => $template->email_subject,
                    'unseen' => true,
                    'onToday' => true,
                    // 'setFlagToSeen' => true,
                ]));

                foreach ($creditEmails as $creditEmail) {
                    /* $emailCollection->push(EmailDto::from([
                        'id' => Str::uuid7(),
                        'text_template_id' => $template->id,
                        'body_text' => $email->text,
                        'body_html' => $email->body,
                        'is_read' => false,
                    ])); */

                    $email = $this->emailRepo->create(EmailDto::from([
                        // 'id' => Str::uuid7(),
                        'text_template_id' => $template->id,
                        'body_text' => $creditEmail->text,
                        'body_html' => $creditEmail->body,
                        'is_read' => false,
                    ])->toArray());

                    dd($email->template);
                }
            }

            // $this->emailRepo->createMany($emailCollection->toArray());

            // dd('emailCollection', $emailCollection->toArray());
            DB::commit();  
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating credit email: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e; 
        }
    }
}