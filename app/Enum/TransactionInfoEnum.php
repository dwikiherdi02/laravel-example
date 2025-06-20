<?php

namespace App\Enum;

enum TransactionInfoEnum: string
{
    case TF_SENDER_NAME = 'sender_name';
    case TF_SENDER_ACCOUNT = 'sender_account';
    case TF_RECIPIENT_NAME = 'recipient_name';
    case TF_RECIPIENT_ACCOUNT = 'recipient_account';
    case TF_AMOUNT = 'amount';
    case TF_DATETIME = 'datetime';
    case TF_DATE = 'date';
    case TF_TIME = 'time';
}
