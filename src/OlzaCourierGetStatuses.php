<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Contracts\CourierGetStatuses;
use Sylapi\Courier\Olza\Helpers\OlzaApiErrorsHelper;
use OlzaApiClient\Entities\Helpers\GetStatusesEntity;
use OlzaApiClient\Entities\Response\ApiBatchResponse;
use OlzaApiClient\Entities\Response\Parcel;
class OlzaCourierGetStatuses implements CourierGetStatuses
{
	private $session;

	public function __construct(OlzaSession $session)
	{
		$this->session = $session;
	}

	public function getStatus(string $shipmentId): string
	{
		$apiResponse = $this->getApiBatchResponse([$shipmentId]);

		if(OlzaApiErrorsHelper::hasErrors($apiResponse->getErrorList())) {
			return StatusType::APP_RESPONSE_ERROR;
		}
		
		$shipment = $apiResponse->getProcessedList()->getIterator()->current();
		$parcel = $shipment->getParcels()->getFirstParcel();

		if(!($parcel instanceof Parcel)) {
			return StatusType::APP_RESPONSE_ERROR;
		}

		return (string) new OlzaStatusTransformer((string) $parcel->getParcelStatus());

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
						->setPayloadFromHelper($this->getStatusesEntity($shipmentsNumbers));;
		$apiResponse = $apiClient->getStatuses($request);

		return $apiResponse;
	}
}
