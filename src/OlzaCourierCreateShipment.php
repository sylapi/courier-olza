<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\NewShipmentEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\CourierCreateShipment;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Exceptions\ResponseException;
use Sylapi\Courier\Helpers\ReferenceHelper;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;

class OlzaCourierCreateShipment implements CourierCreateShipment
{
    private $session;

    public function __construct(OlzaSession $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): array
    {
        $apiResponse = $this->getApiBatchResponse($shipment);

        if (OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            throw new ResponseException(OlzaApiErrorsHelper::toString($apiResponse->getErrorList()));
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();

        return [
            'referenceId' => $shipment->getApiCustomRef(),
            'shipmentId'  => $shipment->getShipmentId(),
        ];
    }

    private function getApiBatchResponse(Shipment $shipment): ApiBatchResponse
    {
        $apiClient = $this->session->client();
        $request = $this->session
                        ->request()
                        ->addToPayloadFromHelper($this->getNewShipmentEnity($shipment));

        $apiResponse = $apiClient->createShipments($request);

        return $apiResponse;
    }

    private function getNewShipmentEnity(Shipment $shipment): NewShipmentEnity
    {
        $parameters = $this->session->parameters();

        $newShipmentEnity = new NewShipmentEnity();
        $newShipmentEnity->setApiCustomRef(ReferenceHelper::generate())
            ->setSenderCountry($shipment->getSender()->getCountryCode())
            ->setRecipientCountry($shipment->getReceiver()->getCountryCode())
            ->setSpeditionCode($parameters->speditionCode)
            ->setShipmentType($parameters->shipmentType)
            ->setRecipientFirstname($shipment->getReceiver()->getFirstName())
            ->setRecipientSurname($shipment->getReceiver()->getSurname())
            ->setRecipientAddress($shipment->getReceiver()->getAddress())
            ->setRecipientCity($shipment->getReceiver()->getCity())
            ->setRecipientZipcode($shipment->getReceiver()->getZipCode())
            ->setRecipientEmail($shipment->getReceiver()->getEmail())
            ->setRecipientPhone($shipment->getReceiver()->getPhone())
            ->setRecipientContactPerson($shipment->getReceiver()->getContactPerson())
            ->setPackageCount($shipment->getQuantity())
            ->setWeight($shipment->getWeight())
            ->setShipmentDescription($shipment->getContent());

        return $newShipmentEnity;
    }
}
