<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Dto\ResidentDto;
use App\Dto\UserDto;
use App\Enum\RoleEnum;
use App\Repositories\ResidentRepository;
use App\Repositories\UserRepository;
use Hash;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class ResidentService
{
    function __construct(
        protected ResidentRepository $residentRepo,
        protected UserRepository $userRepo,
    ) {
        //
    }

    public function create(ResidentDto $data)
    {
        DB::beginTransaction();
        try {
            $resident = $this->residentRepo->create($data->toArray());

            $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $resident->housing_block));
            if ($this->userRepo->findByUsername($username) != null) {

                throw ValidationException::withMessages([
                    'housing_block' => trans('resident.error_housing_block_username'),
                ]);
            }

            $user = UserDto::from([
                'role_id' => RoleEnum::Warga,
                'resident_id' => $resident->id,
                'name' => $resident->name,
                'username' => $username,
                'password' => Hash::make(env('DEFAULT_PASSWORD', '12345678')),
                'is_initial_login' => true,
            ]);

            $this->userRepo->create($user->toArray());
            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('resident.error_save'));
        }
    }

    public function list(ListFilterDto $filter)
    {
        return $this->residentRepo->list($filter);
    }

    public function findById(string $id): ?ResidentDto
    {
        $resident = $this->residentRepo->findById($id);

        if ($resident) {
            return ResidentDto::from($resident);
        }

        return null;
    }

    public function update(ResidentDto $data)
    {
        $resident = $resident = $this->residentRepo->findById($data->id);

        if ($resident == null) {
            throw new \Exception(trans('resident.error_resident_not_found'));
        }

        DB::beginTransaction();
        try {
            $resident->name = $data->name;
            $resident->phone_number = $data->phone_number;
            $resident->address = $data->address;
            $resident->save();

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('resident.error_save'));
        }
    }

    public function delete(string $id)
    {
        $resident = $this->residentRepo->findById($id);

        if ($resident == null) {
            throw new \Exception(trans('resident.error_resident_not_found'));
        }

        DB::beginTransaction();
        try {
            $resident->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('resident.error_delete'));
        }
    }
}
