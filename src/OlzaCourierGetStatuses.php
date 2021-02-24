<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\GetStatusesEntity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\CourierGetStatuses;
use Sylapi\Courier\Contracts\Status as StatusContract;
use Sylapi\Courier\Entities\Status;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;

class OlzaCourierGetStatuses implements CourierGetStatuses
{
    private $session;

    public function __construct(OlzaSession $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): StatusContract
    {
        try {
            $apiResponse = $this->getApiBatchResponse([$shipmentId]);
        } catch (\Exception $e) {
            $status = new Status(StatusType::APP_RESPONSE_ERROR);
            ResponseHelper::pushErrorsToResponse($status, [$e]);

            return $status;
        }

        if (OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            $status = new Status(StatusType::APP_RESPONSE_ERROR);
            $errors = OlzaApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList());
            ResponseHelper::pushErrorsToResponse($status, $errors);

            return $status;
        }

        $shipment = $apiResponse->getProcessedList()->getIterator()->current();
        $parcel = $shipment->getParcels()->getFirstParcel();

        return new Status((string) new OlzaStatusTransformer((string) $parcel->getParcelStatus()));
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
