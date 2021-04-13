<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\NewShipmentEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\CourierCreateShipment;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ReferenceHelper;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;
use Sylapi\Courier\Olza\Helpers\OlzaValidateErrorsHelper;

class OlzaCourierCreateShipment implements CourierCreateShipment
{
    private $session;

    public function __construct(OlzaSession $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseContract
    {
        $response = new Response();
        if (!$shipment->validate()) {
            $errors = OlzaValidateErrorsHelper::toArrayExceptions($shipment->getErrors());
            ResponseHelper::pushErrorsToResponse($response, $errors);

            return $response;
        }
        
        try {
            $apiResponse = $this->getApiBatchResponse($shipment);
        } catch (\Exception $e) {
            ResponseHelper::pushErrorsToResponse($response, [$e]);

            return $response;
        }

        if (OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            $errors = OlzaApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList());
            ResponseHelper::pushErrorsToResponse($response, $errors);

            return $response;
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();

        $response->referenceId = $shipment->getApiCustomRef();
        $response->shipmentId = $shipment->getShipmentId();

        return $response;
    }

    private function getApiBatchResponse(Shipment $shipment): ApiBatchResponse
    {
        $apiClient = $this->session->client();
        $request = $this->session
                        ->request()
                        ->addToPayloadFromHelper($this->getNewShipmentEnity($shipment));

        try {
            $apiResponse = $apiClient->createShipments($request);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        return $apiResponse;
    }

    private function getNewShipmentEnity(Shipment $shipment): NewShipmentEnity
    {
        $parameters = $this->session->parameters();

        $newShipmentEnity = new NewShipmentEnity();
        $newShipmentEnity = $newShipmentEnity->setApiCustomRef(ReferenceHelper::generate())
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

        if($parameters->hasProperty('cod') 
            && is_array($parameters->cod)
            && isset($parameters->cod['codAmount'])
            && isset($parameters->cod['codReference'])
        ) {
            $newShipmentEnity = $newShipmentEnity->setCodReference($parameters->cod['codReference'])
                                    ->setCodAmount($parameters->cod['codAmount']);
        }

        if($parameters->hasProperty('services') 
            && is_array($parameters->services)
        ){
            foreach($parameters->services as $serviceKey => $serviceValue) {
                $newShipmentEnity = $newShipmentEnity->addService($serviceKey, $serviceValue);
            }
        }

        return $newShipmentEnity;
    }
}
