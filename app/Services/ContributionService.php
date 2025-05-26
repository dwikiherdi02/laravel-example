<?php

namespace App\Services;

use App\Dto\ContributionDto;
use App\Dto\ListDto\ListFilterDto;
use App\Repositories\ContributionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContributionService
{
    function __construct(
        protected ContributionRepository $contributionRepo,
    ) {
        
    }

    public function list(ListFilterDto $filter)
    {
        return $this->contributionRepo->list($filter);
    }

    public function findById(string $id): ?ContributionDto
    {
        $item = $this->contributionRepo->findById($id);

        if ($item) {
            return ContributionDto::from($item);
        }

        return null;
    }

    public function create(ContributionDto $data)
    {
        DB::beginTransaction();
        try {
            $this->contributionRepo->create($data->toArray());
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

    public function update(ContributionDto $data)
    {
        $item = $this->contributionRepo->findById($data->id);

        if ($item == null) {
            throw new \Exception(trans('contribution.error_contribution_not_found'));
        }

        DB::beginTransaction();
        try {
            $item->name = $data->name;
            $item->amount = $data->amount;
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
        $item = $this->contributionRepo->findById($id);

        if ($item == null) {
            throw new \Exception(trans('contribution.error_contribution_not_found'));
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
