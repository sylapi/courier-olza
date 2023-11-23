<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Courier;

class CourierApiFactory
{
    private $olzaSessionFactory;

    public function __construct(SessionFactory $olzaSessionFactory)
    {
        $this->olzaSessionFactory = $olzaSessionFactory;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function create(array $parameters): Courier
    {
        $session = $this->olzaSessionFactory
                    ->session(Parameters::create($parameters));

        return new Courier(
            new CourierCreateShipment($session),
            new CourierPostShipment($session),
            new CourierGetLabels($session),
            new CourierGetStatuses($session),
            new CourierMakeShipment(),
            new CourierMakeParcel(),
            new CourierMakeReceiver(),
            new CourierMakeSender(),
            new CourierMakeBooking()
        );
    }
}
