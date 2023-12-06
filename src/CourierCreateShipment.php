<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\NewShipmentEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\CourierCreateShipment as CourierCreateShipmentContract;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Helpers\ReferenceHelper;
use Sylapi\Courier\Olza\Responses\Shipment as ShipmentResponse;
use Sylapi\Courier\Olza\Helpers\ApiErrorsHelper;
use Sylapi\Courier\Olza\Helpers\ValidateErrorsHelper;
use Sylapi\Courier\Olza\Services\COD;
use Sylapi\Courier\Olza\Services\PickupPoint;




class CourierCreateShipment implements CourierCreateShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseContract
    {
        $response = new ShipmentResponse();
        
        if (!$shipment->validate()) {
            throw new ValidateException('Invalid Shipment: ' . ValidateErrorsHelper::getError($shipment->getErrors()));
        }

        try {
            $apiResponse = $this->getApiBatchResponse($shipment);
            $response->setRequest($apiResponse);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        if (ApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            throw new TransportException(ValidateErrorsHelper::getError(ApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList())));
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();

        $response->setResponse($shipment);
        $response->setReferenceId((string) $shipment->getApiCustomRef());
        $response->setShipmentId((string) $shipment->getShipmentId());

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
        $options = $shipment->getOptions();

        $newShipmentEnity = new NewShipmentEnity();
        $newShipmentEnity = $newShipmentEnity->setApiCustomRef(ReferenceHelper::generate())
            ->setSenderCountry($shipment->getSender()->getCountryCode())
            ->setRecipientCountry($shipment->getReceiver()->getCountryCode())
            ->setSpeditionCode($options->get('speditionCode'))
            ->setShipmentType($options->get('shipmentType')) 
            ->setRecipientFirstname($shipment->getReceiver()->getFirstName())
            ->setRecipientSurname($shipment->getReceiver()->getSurname())
            ->setRecipientAddress($shipment->getReceiver()->getAddress())
            ->setRecipientCity($shipment->getReceiver()->getCity())
            ->setRecipientZipcode($shipment->getReceiver()->getZipCode())
            ->setRecipientEmail($shipment->getReceiver()->getEmail())
            ->setRecipientPhone($shipment->getReceiver()->getPhone())
            ->setRecipientCounty($shipment->getReceiver()->getProvince())
            ->setRecipientContactPerson($shipment->getReceiver()->getContactPerson())
            ->setPackageCount($shipment->getQuantity())
            ->setWeight($shipment->getWeight())
            ->setShipmentDescription($shipment->getContent());


            $services = $shipment->getServices();
            if($services) {
                foreach($services as $service) {
                    if($service instanceof COD) {
                        $newShipmentEnity = $newShipmentEnity
                            ->setCodAmount($service->getAmount())
                            ->setCodReference($service->getReference());
                    } else if ($service instanceof PickupPoint) {
                        $newShipmentEnity = $newShipmentEnity
                            ->setPickupPlaceId($service->getPickupId());
                    } else {
                        $serviceData = $service->handle();
                        $newShipmentEnity = $newShipmentEnity->addService($serviceData['code'], $serviceData['value']);
                    }
                }

            }
        
        return $newShipmentEnity;
    }
}
