<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Courier;
use Sylapi\Courier\Contracts\Credentials;

class CourierApiFactory
{
    private $olzaSessionFactory;

    public function __construct(SessionFactory $olzaSessionFactory)
    {
        $this->olzaSessionFactory = $olzaSessionFactory;
    }

    public function create(Credentials $credentials): Courier
    {
        $session = $this->olzaSessionFactory
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
