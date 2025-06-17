<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Dto\TextTemplateDto;
use App\Libraries\Imap;
use App\Repositories\TextTemplateRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TextTemplateService
{
    function __construct(
        protected TextTemplateRepository $textTemplateRepo,
        protected Imap $imapLib,
    ) {

    }

    public function list(ListFilterDto $filter)
    {
        return $this->textTemplateRepo->list($filter);
    }

    public function findById(string $id): ?TextTemplateDto
    {
        $item = $this->textTemplateRepo->findById($id);

        if ($item) {
            return TextTemplateDto::from($item);
        }

        return null;
    }

    public function generateTestData(string $id): string
    {
        $item = $this->textTemplateRepo->findById($id);

        if ($item == null) {
            throw new \Exception(trans('text-template.error_notfound'));
        }

        try {
            $body = $this->imapLib->generateTemplate($item->email, $item->email_subject);

            if ($body == null) {
                throw new \Exception(trans('Body email tidak ditemukan. Silahkan coba lagi.'));
            }


            $data = extract_by_template($item->template, $body);
            // dd($body, $item->template, $data);
            return 'Pengirim/Penerima: ' . $data['TF_FROM_TO'] . ', Nominal: ' . $data['TF_NOMINAL'] . ', Tanggal Kirim/Terima: ' . $data['TF_DATE'];
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    public function getPaymentDuesEmailBody()
    {
        dd('test ea');
    }

    public function create(TextTemplateDto $data)
    {
        DB::beginTransaction();
        try {
            $this->textTemplateRepo->create($data->toArray());
            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_save'));
        }
    }

    public function update(TextTemplateDto $data)
    {
        $item = $this->textTemplateRepo->findById($data->id);

        if ($item == null) {
            throw new \Exception(trans('text-template.error_notfound'));
        }

        DB::beginTransaction();
        try {
            $item->name = $data->name;
            $item->transaction_type_id = $data->transaction_type_id;
            $item->email = $data->email;
            $item->email_subject = $data->email_subject;
            $item->template = $data->template;

            $item->save();

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_save'));
        }
    }

    public function delete(string $id)
    {
        $item = $this->textTemplateRepo->findById($id);

        if ($item == null) {
            throw new \Exception(trans('text-template.error_notfound'));
        }

        DB::beginTransaction();
        try {
            $item->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_delete'));
        }
    }
}
