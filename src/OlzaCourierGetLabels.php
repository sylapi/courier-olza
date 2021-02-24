<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use OlzaApiClient\Entities\Helpers\GetLabelsEnity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use Sylapi\Courier\Contracts\CourierGetLabels;
use Sylapi\Courier\Contracts\Label as LabelContract;
use Sylapi\Courier\Entities\Label;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;

class OlzaCourierGetLabels implements CourierGetLabels
{
    private $session;

    public function __construct(OlzaSession $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $shipmentId): LabelContract
    {
        try {
            $apiResponse = $this->getApiBatchResponse([$shipmentId]);
        } catch (\Exception $e) {
            $label = new Label(null);
            ResponseHelper::pushErrorsToResponse($label, [$e]);

            return $label;
        }

        if (OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
            $label = new Label(null);
            $errors = OlzaApiErrorsHelper::toArrayExceptions($apiResponse->getErrorList());
            ResponseHelper::pushErrorsToResponse($label, $errors);

            return $label;
        }

        return new Label($apiResponse->getDataStream()->getData());
    }

    private function getApiBatchResponse(array $shipmentsNumbers): ApiBatchResponse
    {
        $apiClient = $this->session->client();
        $request = $this->session
                        ->request()
                        ->setPayloadFromHelper($this->getLabelsEntity($shipmentsNumbers));

        try {
            $apiResponse = $apiClient->getLabels($request);
        } catch (\Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        return $apiResponse;
    }

    private function getLabelsEntity(array $shipmentsNumbers): GetLabelsEnity
    {
        $labelsEntity = new GetLabelsEnity();
        $labelsEntity->addToListFromArray($shipmentsNumbers);

        $parameters = $this->session->parameters();
        $labelsEntity->setPageFormat($parameters->getLabelType());

        return $labelsEntity;
    }
}
