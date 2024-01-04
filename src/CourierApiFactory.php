<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Courier;
use Sylapi\Courier\Olza\Entities\Credentials;
class CourierApiFactory
{
    private $sessionFactory;

    public function __construct(SessionFactory $sessionFactory)
    {
        $this->sessionFactory = $sessionFactory;
    }

    public function create(array $credentials): Courier
    {
        $credentials = Credentials::from($credentials);

        $session = $this->sessionFactory
                    ->session($credentials);

        return new Courier(
            new CourierCreateShipment($session),
            new CourierPostShipment($session),
            new CourierGetLabels($session),
            new CourierGetStatuses($session),
            new CourierMakeShipment(),
            new CourierMakeParcel(),
            new CourierMakeReceiver(),
            new CourierMakeSender(),
            new CourierMakeService(),
            new CourierMakeOptions(),
            new CourierMakeBooking(),
            new CourierMakeLabelType(),
        );
    }
}
