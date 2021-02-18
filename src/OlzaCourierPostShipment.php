<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\PostShipmentsEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierPostShipment;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;

class OlzaCourierPostShipment implements CourierPostShipment
{
    private $session;

    public function __construct(OlzaSession $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseContract
    {
        $response = new Response();

        try {
            $apiResponse = $this->getApiBatchResponse([$booking->getShipmentId()]);
        } catch (\Exception $e) {
            $response->addError($e);

            return $response;
        }

        if (OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            $iterator = $apiResponse->getErrorList()->getIterator();
            for ($iterator; $iterator->valid(); $iterator->next()) {
                $response->addError($iterator->current());
            }

            return $response;
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();
        $parcel = $shipment->getParcels()->getFirstParcel();

        $response->shipmentId = $parcel->getShipmentId();
        $response->trackingId = $parcel->getSpeditionExternalId();
        $response->trackingBarcode = $parcel->getSpeditionExternalBarcode();

        return $response;
    }

    private function getPostShipmentsEnity(array $shipmentsNumbers): PostShipmentsEnity
    {
        $postShipmentsEnity = new PostShipmentsEnity();
        foreach ($shipmentsNumbers as $shipmentsNumber) {
            $postShipmentsEnity->addShipmentId($shipmentsNumber);
        }

        return $postShipmentsEnity;
    }

    private function getApiBatchResponse(array $shipmentsNumbers): ApiBatchResponse
    {
        $apiClient = $this->session->client();
        $request = $this->session
                        ->request()
                        ->setPayloadFromHelper($this->getPostShipmentsEnity($shipmentsNumbers));

        try {
            $apiResponse = $apiClient->postShipments($request);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        return $apiResponse;
    }
}
