<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Enum;

enum PaymentMethodEnum: string
{
	case BANK_TRANSFER = 'BANK_TRANSFER';
	case CACHE = 'CACHE';
}
