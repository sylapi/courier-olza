<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Contracts\CourierGetLabels;
use OlzaApiClient\Entities\Helpers\GetLabelsEnity;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;
use OlzaApiClient\Entities\Response\ApiBatchResponse;

class OlzaCourierGetLabels implements CourierGetLabels
{
	private $session;
	
	public function __construct(OlzaSession $session)
	{
		$this->session = $session;
	}

	public function getLabel(string $shipmentId) : ?string
	{
		$apiResponse = $this->getApiBatchResponse([$shipmentId]);

		if(OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
			return null;
		}

		return $apiResponse->getDataStream()->getData();
	}
	
	private function getApiBatchResponse(array $shipmentsNumbers): ApiBatchResponse
	{
		$apiClient = $this->session->client();
		$request = $this->session
						->request()
						->setPayloadFromHelper($this->getLabelsEntity($shipmentsNumbers));
		$apiResponse = $apiClient->getLabels($request);

		return $apiResponse;
	}

	private function getLabelsEntity(array $shipmentsNumbers) : GetLabelsEnity
	{
		$labelsEntity = new GetLabelsEnity();
		$labelsEntity->addToListFromArray($shipmentsNumbers);

		$parameters = $this->session->parameters();
		$labelsEntity->setPageFormat($parameters->getLabelType());
		
		return $labelsEntity;
	}


}
