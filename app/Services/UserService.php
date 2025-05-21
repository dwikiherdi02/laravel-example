<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Repositories\UserRepository;
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
            throw new \Exception('Data pengguna tidak ditemukan. silahkan coba lagi atau hubungi admin.');
        }

        if ($user->is_protected) {
            throw new \Exception('Data pengguna tidak dapat dihapus karena diproteksi ole sistem.');
        }

        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.delete_error'));
        }
    }
}
