<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\Booking;

use Sylapi\Courier\Olza\Responses\Parcel as ParcelResponse;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Olza\Helpers\ApiErrorsHelper;
use Sylapi\Courier\Exceptions\TransportException;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Olza\Helpers\ValidateErrorsHelper;
use OlzaApiClient\Entities\Helpers\PostShipmentsEnity;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\CourierPostShipment as CourierPostShipmentContract;
use Sylapi\Courier\Olza\Entities\Parcel;

class CourierPostShipment implements CourierPostShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ParcelResponse
    {
        $response = new ParcelResponse();

        if (!$booking->validate()) {
            throw new ValidateException('Invalid Shipment: ' . ValidateErrorsHelper::getError($booking->getErrors()));
        }

        try {
            $apiResponse = $this->getApiBatchResponse([$booking->getShipmentId()]);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        if (ApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            throw new TransportException(ValidateErrorsHelper::getError(ApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList())));
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();
        $parcel = $shipment->getParcels()->getFirstParcel();


        $response->setResponse($parcel);
        $response->setShipmentId($parcel->getShipmentId());
        $response->setTrackingId($parcel->getSpeditionExternalId());
        $response->setTrackingBarcode($parcel->getSpeditionExternalBarcode());
        $response->setTrackingUrl($parcel->getSpeditionExternalTrackingUrl());


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
