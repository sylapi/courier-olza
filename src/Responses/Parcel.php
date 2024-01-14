<?php

namespace Sylapi\Courier\Olza\Responses;

use Sylapi\Courier\Responses\Parcel as ParcelResponse;
use Sylapi\Courier\Contracts\Response as ResponseContract;

class Parcel extends ParcelResponse
{
    private string $trackingBarcode;

    public function setTrackingBarcode(string $trackingBarcode): ResponseContract
    {
        $this->trackingBarcode = $trackingBarcode;

        return $this;
    }

    public function getTrackingBarcode(): ?string
    {
        return $this->trackingBarcode;
    }
}
