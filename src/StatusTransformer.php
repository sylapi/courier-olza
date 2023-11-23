<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Abstracts\StatusTransformer as StatusTransformerAbstract;
use Sylapi\Courier\Enums\StatusType;

class StatusTransformer extends StatusTransformerAbstract
{
    /**
     * @var array<string, string>
     */
    public $statuses = [
        'NEW'                => StatusType::NEW->value,
        'STORNO'             => StatusType::CANCELLED->value,
        'DELIVERED'          => StatusType::DELIVERED->value,
        'ENTRY_WAIT'         => StatusType::ENTRY_WAIT->value,
        'ORDERED'            => StatusType::ORDERED->value,
        'SENT'               => StatusType::SENT->value,
        'SPEDITION_DELIVERY' => StatusType::SPEDITION_DELIVERY->value,
        'PICKUP_READY'       => StatusType::PICKUP_READY->value,
        'LOST'               => StatusType::LOST->value,
        'REFUND'             => StatusType::REFUND->value,
        'WAREHOUSE_ENTRY'    => StatusType::WAREHOUSE_ENTRY->value,
        'RETURN_DELIVERY'    => StatusType::RETURN_DELIVERY->value,
        'SOLVING'            => StatusType::SOLVING->value,
        'RETURNING'          => StatusType::RETURNING->value,
        'RETURNED'           => StatusType::RETURNED->value,
        'PROCESSING'         => StatusType::PROCESSING->value,
        'PROCESSING_FAILED'  => StatusType::PROCESSING_FAILED->value,
        'DELETED'            => StatusType::DELETED->value,
    ];
}
