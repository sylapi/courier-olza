<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\PostShipmentsEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierPostShipment as CourierPostShipmentContract;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Olza\Helpers\ApiErrorsHelper;
use Sylapi\Courier\Olza\Helpers\ValidateErrorsHelper;

class CourierPostShipment implements CourierPostShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseContract
    {
        $response = new Response();

        if (!$booking->validate()) {
            $errors = ValidateErrorsHelper::toArrayExceptions($response->getErrors());
            ResponseHelper::pushErrorsToResponse($response, $errors);

            return $response;
        }

        try {
            $apiResponse = $this->getApiBatchResponse([$booking->getShipmentId()]);
        } catch (\Exception $e) {
            ResponseHelper::pushErrorsToResponse($response, [$e]);

            return $response;
        }

        if (ApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            $errors = ApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList());
            ResponseHelper::pushErrorsToResponse($response, $errors);

            return $response;
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();
        $parcel = $shipment->getParcels()->getFirstParcel();

        $response->shipmentId = $parcel->getShipmentId();
        $response->trackingId = $parcel->getSpeditionExternalId();
        $response->trackingBarcode = $parcel->getSpeditionExternalBarcode();
        $response->trackingUrl = $parcel->getSpeditionExternalTrackingUrl();

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
