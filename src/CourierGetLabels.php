<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Olza\Helpers\ApiErrorsHelper;
use Sylapi\Courier\Exceptions\TransportException;
use OlzaApiClient\Entities\Helpers\GetLabelsEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Olza\Helpers\ValidateErrorsHelper;
use Sylapi\Courier\Olza\Responses\Label as LabelResponse;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\CourierGetLabels as CourierGetLabelsContract;
use Sylapi\Courier\Contracts\LabelType as LabelTypeContract;
use Sylapi\Courier\Olza\Entities\LabelType;

class CourierGetLabels implements CourierGetLabelsContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $shipmentId, LabelTypeContract $labelType): ResponseContract
    {
        try {
            $apiResponse = $this->getApiBatchResponse([$shipmentId], $labelType);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        if (ApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            throw new TransportException(ValidateErrorsHelper::getError(ApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList())));
        }

        return new LabelResponse($apiResponse->getDataStream()->getData());
    }

    private function getApiBatchResponse(array $shipmentsNumbers, LabelTypeContract $labelType): ApiBatchResponse
    {
        $apiClient = $this->session->client();
        $request = $this->session
                        ->request()
                        ->setPayloadFromHelper($this->getLabelsEntity($shipmentsNumbers, $labelType));

        try {
            $apiResponse = $apiClient->getLabels($request);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        return $apiResponse;
    }

    private function getLabelsEntity(array $shipmentsNumbers, LabelTypeContract $labelType): GetLabelsEnity
    {
        $labelsEntity = new GetLabelsEnity();
        $labelsEntity->addToListFromArray($shipmentsNumbers);
        $labelsEntity->setPageFormat($labelType->getLabelType());

        return $labelsEntity;
    }
}
