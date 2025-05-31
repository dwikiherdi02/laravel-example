<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Dto\TextTemplateDto;
use App\Repositories\TextTemplateRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TextTemplateService
{
    function __construct(
        protected TextTemplateRepository $textTemplateRepo,
    ) {

    }

    public function list(ListFilterDto $filter)
    {
        return $this->textTemplateRepo->list($filter);
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

    public function delete(string $id)
    {
        $item = $this->textTemplateRepo->findById($id);

        if ($item == null) {
            throw new \Exception(trans('Data template tidak ditemukan. silahkan coba lagi atau hubungi admin.'));
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
