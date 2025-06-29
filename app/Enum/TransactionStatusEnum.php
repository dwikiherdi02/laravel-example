<?php

namespace App\Enum;

enum TransactionStatusEnum: string
{
    case Pending = '01974782-6d96-72ae-91d4-70308d1ae198';
    case Success = '01974782-6d96-72ae-91d4-70308d7d3a69';
    case Failed = '01974782-6db0-7122-8cd8-748d2c460185';
    case Canceled = '0197b15a-1218-7239-89e1-875b4c131c82';
}
