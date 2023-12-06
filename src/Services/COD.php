<?php

namespace Sylapi\Courier\Olza\Services;

use Sylapi\Courier\Abstracts\Services\COD as CODAbstract;
use Sylapi\Courier\Contracts\Services\COD as CODContract;

class COD extends CODAbstract
{

    public function getReference(): ?string
    {
        return $this->get('reference', null);
    }

    public function setReference(string $reference): CODContract
    {
        $this->set('reference', $reference);
        return $this;
    }

    public function handle(): array
    {
    
        if(null !== $this->getRequest()){
           return  $this->getRequest();
        }
        
        return [
            'amount' => $this->getAmount(),
            'reference' => $this->getReference(),
        ];
    }
}
