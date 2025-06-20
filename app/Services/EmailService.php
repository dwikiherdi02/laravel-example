<?php

namespace App\Services;

use App\Dto\EmailDto;
use App\Dto\Lib\Imap\FilterDto;
use App\Dto\TransactionDto;
use App\Enum\TransactionMethodEnum;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Events\BalanceCalculationRequested;
use App\Events\ProcessUnseenRequested;
use App\Libraries\Imap;
use App\Repositories\DuesPaymentRepository;
use App\Repositories\EmailRepository;
use App\Repositories\TextTemplateRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
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

    public function fetchCredits()
    {
        DB::beginTransaction();
        try {
            $now = Carbon::now();

            $templates = $this->textTemplateRepo->get(
                ['transaction_type_id' => TransactionTypeEnum::Credit],
                ['id', 'name', 'transaction_type_id', 'email', 'email_subject', 'template'],
                ['name' => 'asc']
            );

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
                        'created_at' => $now->format('Y-m-d H:i:s'),
                        'updated_at' => $now->format('Y-m-d H:i:s'),
                    ]));
                }
            }
            // dd('emails: ', $emails->toArray());
            $this->emailRepo->createMany($emails->toArray());
            DB::commit();
            ProcessUnseenRequested::dispatch(TransactionTypeEnum::Credit);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating credit email: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    public function processUnseenCredits()
    {
        $unseenEmails = $this->emailRepo->getUnseenEmailByTransactionType(TransactionTypeEnum::Credit);
        DB::transaction(function () use ($unseenEmails) {
            $ids = $unseenEmails->pluck('id')->toArray();
            if (count($ids) > 0) {
                $this->emailRepo->updateMany(['id' => $ids], ['is_read' => true]);
            }
        });

        foreach ($unseenEmails as $email) {
            DB::beginTransaction();
            try {
                $template = $email->template;
                if (!$template) {
                    throw new \Exception('Email template not found for email ID: ' . $email->id);
                }

                // fetch transaction data from email body using the template
                $transactionInfo = extract_by_template($template->template, $email->body_text);

                if ($transactionInfo->amount < 1) {
                    return; // skip if no credit data found
                }

                // fecth dues payment record by amount and is_paid status
                $duesPayment = $this->duesPaymentRepo->first(
                    ['final_amount' => $transactionInfo->amount, 'is_paid' => false],
                    ['*'],
                    ['id' => 'asc', 'created_at' => 'asc']
                );

                // create new transaction
                $transactionData = TransactionDto::from([
                    'transaction_method_id' => TransactionMethodEnum::Transfer,
                    'transaction_type_id' => TransactionTypeEnum::Credit,
                    'transaction_status_id' => TransactionStatusEnum::Pending,
                    'email_id' => $email->id,
                    'info' => $transactionInfo,
                    'date' => Carbon::now(),
                ]);

                // if ($transactionInfo->datetime) {
                //     $transactionData->date = Carbon::parse($transactionInfo->datetime)->format('Y-m-d H:i:s');
                // } else {
                //     $transactionData->date = Carbon::now()->format('Y-m-d H:i:s');
                //     if ($transactionInfo->date && $transactionInfo->time) {
                //         $transactionData->date = Carbon::parse($transactionInfo->date . ' ' . $transactionInfo->time)->format('Y-m-d H:i:s');
                //     }
                // }

                if ($duesPayment) {
                    $transactionData->name = 'Saldo Masuk - Bayar Iuran';
                    $transactionData->dues_payment_id = $duesPayment->id;
                    $transactionData->base_amount = $duesPayment->base_amount;
                    $transactionData->point = $duesPayment->unique_code;
                    $transactionData->final_amount = $duesPayment->final_amount;
                } else {
                    $transactionData->name = 'Saldo Masuk - Informasi via Email';
                    $transactionData->dues_payment_id = null;
                    $transactionData->base_amount = $transactionInfo->amount;
                    $transactionData->point = 0;
                    $transactionData->final_amount = $transactionInfo->amount;
                }

                // dd('transactionData: ', $transactionData->toArray());

                $transaction = $this->transactionRepo->create($transactionData->toArray());
                DB::commit();
                BalanceCalculationRequested::dispatch($transaction);
            } catch (\Exception $e) {
                DB::rollBack();

                // rollback the email to unseen
                $email->is_read = false;
                $email->save();

                Log::error('Error processing unseen credit: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
                // throw $e;
            }
        }
    }

    public function fetchDebits()
    {
        DB::beginTransaction();
        try {
            $now = Carbon::now();

            $templates = $this->textTemplateRepo->get(
                ['transaction_type_id' => TransactionTypeEnum::Debit],
                ['id', 'name', 'transaction_type_id', 'email', 'email_subject', 'template'],
                ['name' => 'asc']
            );

            $emails = collect();

            foreach ($templates as $template) {
                $debitEmails = $this->imapLib->get(FilterDto::from([
                    'folder' => 'INBOX',
                    'from' => $template->email,
                    'subject' => $template->email_subject,
                    'limit' => 5,
                    'unseen' => true,
                    'onToday' => true,
                    'setFlagToSeen' => true,
                ]));

                foreach ($debitEmails as $debitEmail) {
                    $emails->push(EmailDto::from([
                        'id' => Str::uuid7(),
                        'text_template_id' => $template->id,
                        'body_text' => $debitEmail->text,
                        'body_html' => $debitEmail->body,
                        'is_read' => false,
                        'created_at' => $now->format('Y-m-d H:i:s'),
                        'updated_at' => $now->format('Y-m-d H:i:s'),
                    ]));
                }
            }
            // dd('emails: ', $emails->toArray());
            $this->emailRepo->createMany($emails->toArray());
            DB::commit();
            ProcessUnseenRequested::dispatch(TransactionTypeEnum::Debit);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating debit email: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    public function processUnseenDebits()
    {

        $unseenEmails = $this->emailRepo->getUnseenEmailByTransactionType(TransactionTypeEnum::Debit);
        DB::transaction(function () use ($unseenEmails) {
            $ids = $unseenEmails->pluck('id')->toArray();
            if (count($ids) > 0) {
                $this->emailRepo->updateMany(['id' => $ids], ['is_read' => true]);
            }
        });

        foreach ($unseenEmails as $email) {
            DB::beginTransaction();
            try {
                $template = $email->template;
                if (!$template) {
                    throw new \Exception('Email template not found for email ID: ' . $email->id);
                }

                // fetch transaction data from email body using the template
                $transactionInfo = extract_by_template($template->template, $email->body_text);

                if ($transactionInfo->amount < 1) {
                    return; // skip if no credit data found
                }

                // create new transaction
                $transactionData = TransactionDto::from([
                    'transaction_method_id' => TransactionMethodEnum::Transfer,
                    'transaction_type_id' => TransactionTypeEnum::Debit,
                    'transaction_status_id' => TransactionStatusEnum::Pending,
                    'email_id' => $email->id,
                    'dues_payment_id' => null,
                    'name' => 'Saldo Keluar - Informasi via Email',
                    'base_amount' => $transactionInfo->amount,
                    'point' => 0,
                    'final_amount' => $transactionInfo->amount,
                    'info' => $transactionInfo,
                    'date' => Carbon::now(),
                ]);

                // dd('transactionData: ', $transactionData->toArray());

                $transaction = $this->transactionRepo->create($transactionData->toArray());
                DB::commit();
                BalanceCalculationRequested::dispatch($transaction);
            } catch (\Exception $e) {
                DB::rollBack();

                // rollback the email to unseen
                $email->is_read = false;
                $email->save();

                Log::error('Error processing unseen debit: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
                // throw $e;
            }
        }
    }
}
