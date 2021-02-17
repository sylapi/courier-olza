<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\PostShipmentsEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierPostShipment;
use Sylapi\Courier\Exceptions\ResponseException;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;

class OlzaCourierPostShipment implements CourierPostShipment
{
    private $session;

    public function __construct(OlzaSession $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): array
    {
        $apiResponse = $this->getApiBatchResponse([$booking->getShipmentId()]);

        if (OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            throw new ResponseException(OlzaApiErrorsHelper::toString($apiResponse->getErrorList()));
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();
        $parcel = $shipment->getParcels()->getFirstParcel();

        return [
            'shipmentId'      => $parcel->getShipmentId(),
            'trackingId'      => $parcel->getSpeditionExternalId(),
            'trackingBarcode' => $parcel->getSpeditionExternalBarcode(),
        ];
    }

    private function getApiBatchResponse(array $shipmentsNumbers): ApiBatchResponse
    {
        $apiClient = $this->session->client();
        $request = $this->session
                        ->request()
                        ->setPayloadFromHelper($this->getPostShipmentsEnity($shipmentsNumbers));
        $apiResponse = $apiClient->postShipments($request);

        return $apiResponse;
    }

    private function getPostShipmentsEnity(array $shipmentsNumbers): PostShipmentsEnity
    {
        $postShipmentsEnity = new PostShipmentsEnity();
        foreach ($shipmentsNumbers as $shipmentsNumber) {
            $postShipmentsEnity->addShipmentId($shipmentsNumber);
        }

        return $postShipmentsEnity;
    }
}
