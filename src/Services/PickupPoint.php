<?php

namespace Sylapi\Courier\Olza\Services;

use Sylapi\Courier\Abstracts\Services\PickupPoint as PickupPointAbstract;
use Sylapi\Courier\Contracts\Services\PickupPoint as PickupPointContract;

class PickupPoint extends PickupPointAbstract
{

    public function handle(): array
    {
    
        if(null !== $this->getRequest()){
           return  $this->getRequest();
        }
        
        return [
            'pickupId' => $this->getPickupId(),
        ];
    }
}
