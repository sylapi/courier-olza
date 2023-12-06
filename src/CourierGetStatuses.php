<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Olza\Helpers\ApiErrorsHelper;
use Sylapi\Courier\Exceptions\TransportException;
use OlzaApiClient\Entities\Helpers\GetStatusesEntity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Olza\Helpers\ValidateErrorsHelper;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Olza\Responses\Status as StatusResponse;
use Sylapi\Courier\Contracts\CourierGetStatuses as CourierGetStatusesContract;


class CourierGetStatuses implements CourierGetStatusesContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): ResponseContract
    {
        try {
            $apiResponse = $this->getApiBatchResponse([$shipmentId]);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        if (ApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            throw new TransportException(ValidateErrorsHelper::getError(ApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList())));
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();
        $parcel = $shipment->getParcels()->getFirstParcel();


        return new StatusResponse((string) new StatusTransformer((string) $parcel->getParcelStatus()));
    }

    private function getStatusesEntity(array $shipmentsNumbers): GetStatusesEntity
    {
        $statusesEntity = new GetStatusesEntity();
        $statusesEntity->addToListFromArray($shipmentsNumbers);

        return $statusesEntity;
    }

    private function getApiBatchResponse(array $shipmentsNumbers): ApiBatchResponse
    {
        $apiClient = $this->session->client();
        $request = $this->session
                        ->request()
                        ->setPayloadFromHelper($this->getStatusesEntity($shipmentsNumbers));

        try {
            $apiResponse = $apiClient->getStatuses($request);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        return $apiResponse;
    }
}
