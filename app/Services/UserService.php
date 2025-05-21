<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Repositories\UserRepository;
use Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    function __construct(
        protected UserRepository $userRepo,
    ) {
        //
    }

    public function list(ListFilterDto $filter)
    {
        return $this->userRepo->list($filter);
    }

    public function delete(string $id)
    {
        $user = $this->userRepo->findById($id);
        if ($user == null) {
            throw new \Exception(trans('user.error_user_notfound'));
        }

        if ($user->is_protected) {
            throw new \Exception(trans('user.error_user_is_protected'));
        }

        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_delete'));
        }
    }

    public function resetPassword(string $id)
    {
        $user = $this->userRepo->findById($id);
        if ($user == null) {
            throw new \Exception(trans('user.error_user_notfound'));
        }

        DB::beginTransaction();
        try {
            $defaultPassword = env('DEFAULT_PASSWORD', '12345678');

            $user->password = Hash::make($defaultPassword);
            $user->is_initial_login = true;
            $user->save();

            DB::commit();

            return trans('user.success_reset_password', ['password' => $defaultPassword]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('user.error_reset_password'));
        }
    }
}
