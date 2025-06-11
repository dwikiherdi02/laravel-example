<?php

namespace App\Enum;

enum IsMergeEnum: int
{
    case NoMerge = 0;
    case HouseBillMerge = 1;
    case MonthlyBillMerge = 2;
}
