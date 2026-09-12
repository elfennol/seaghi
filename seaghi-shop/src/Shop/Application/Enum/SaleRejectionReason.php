<?php

declare(strict_types=1);

namespace App\Shop\Application\Enum;

/**
 * The codes of the message when there is a problem with the purchase.
 */
enum SaleRejectionReason
{
    case NOT_READY_TO_FIGHT;
    case DIFFICULT_END_OF_MONTH;
}
