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
        'NEW'                => StatusType::NEW,
        'STORNO'             => StatusType::CANCELLED,
        'DELIVERED'          => StatusType::DELIVERED,
        'ENTRY_WAIT'         => StatusType::ENTRY_WAIT,
        'ORDERED'            => StatusType::ORDERED,
        'SENT'               => StatusType::SENT,
        'SPEDITION_DELIVERY' => StatusType::SPEDITION_DELIVERY,
        'PICKUP_READY'       => StatusType::PICKUP_READY,
        'LOST'               => StatusType::LOST,
        'REFUND'             => StatusType::REFUND,
        'WAREHOUSE_ENTRY'    => StatusType::WAREHOUSE_ENTRY,
        'RETURN_DELIVERY'    => StatusType::RETURN_DELIVERY,
        'SOLVING'            => StatusType::SOLVING,
        'RETURNING'          => StatusType::RETURNING,
        'RETURNED'           => StatusType::RETURNED,
        'PROCESSING'         => StatusType::PROCESSING,
        'PROCESSING_FAILED'  => StatusType::PROCESSING_FAILED,
        'DELETED'            => StatusType::DELETED,
    ];
}
