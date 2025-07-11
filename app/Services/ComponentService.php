<?php

namespace App\Services;

use App\Dto\ContributionDto;
use App\Dto\ResidentDto;
use App\Dto\RoleDto;
use App\Dto\TransactionMethodDto;
use App\Dto\TransactionStatusDto;
use App\Dto\TransactionTypeDto;
use App\Repositories\ContributionRepository;
use App\Repositories\MenuGroupRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ResidentRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TransactionMethodRepository;
use App\Repositories\TransactionStatusRepository;
use App\Repositories\TransactionTypeRepository;

class ComponentService
{

    function __construct(
        protected MenuGroupRepository $menuGroupRepo,
        protected MenuRepository $menuRepo,
        protected RoleRepository $roleRepo,
        protected ResidentRepository $residentRepo,
        protected TransactionTypeRepository $transactionTypeRepo,
        protected ContributionRepository $contributionRepo,
        protected TransactionMethodRepository $transactionMethodRepo,
        protected TransactionStatusRepository $transactionStatusRepo,
    ) {
        //
    }

    public function getSidebars(string $authRoleId = '')
    {
        return $this->menuGroupRepo->getSidebars($authRoleId);
    }

    public function getMenuShortcuts(string $authRoleId = '')
    {
        return $this->menuRepo->getMenuShortcuts($authRoleId);
    }

    public function getMenuBySlug(string $slug = '')
    {
        return $this->menuRepo->getMenuBySlug($slug);
    }

    public function getRoleOptions()
    {
        return $this->roleRepo->listOptions()
            ->map(function ($item) {
                return new RoleDto(
                    id: $item->id,
                    name: $item->name
                );
            });
    }

    public function getResidentOptions()
    {
        return $this->residentRepo->listUnsetUserOptions()
            ->map(function ($item) {
                return new ResidentDto(
                    id: $item->id,
                    name: $item->name,
                    housing_block: $item->housing_block,
                    phone_number: $item->phone_number,
                    address: $item->address,
                    unique_code: $item->unique_code
                );
            });
    }

    public function getTransactionTypeOptions()
    {
        return $this->transactionTypeRepo->listOptions()
            ->map(function ($item) {
                return new TransactionTypeDto(
                    id: $item->id,
                    name: $item->name,
                    name_lang_key: $item->name_lang_key,
                    code: $item->code
                );
            });
    }

    public function getContributionList()
    {
        return $this->contributionRepo->getAll()
            ->map(function ($item) {
                return new ContributionDto(
                    id: $item->id,
                    name: $item->name,
                    amount: $item->amount,
                );
            });
    }

    public function getTransactionMethodOptions()
    {
        return $this->transactionMethodRepo->listOptions()
            ->map(function ($item) {
                return new TransactionMethodDto(
                    id: $item->id,
                    name: $item->name,
                    name_lang_key: $item->name_lang_key
                );
            });
    }

    public function getTransactionStatusOptions()
    {
        return $this->transactionStatusRepo->listOptions()
            ->map(function ($item) {
                return new TransactionStatusDto(
                    id: $item->id,
                    name: $item->name,
                    name_lang_key: $item->name_lang_key
                );
            });
    }
}

