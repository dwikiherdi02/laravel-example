<?php

namespace App\Services;

use App\Dto\ResidentPointDto;
use App\Repositories\ResidentPointRepository;

class ResidentPointService
{
    function __construct(
        protected ResidentPointRepository $residentPointRepo,
    ) {
        //
    }

    public function getPointByResidentId(string $residentId)
    {
        if (empty($residentId)) {
            return null;
        }

        $point = $this->residentPointRepo->first(['resident_id' => $residentId], ['*']);
        if ($point) {
            return ResidentPointDto::from($point);
        }
        return null;
    }
}
