<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Courier;

class OlzaCourierApiFactory
{
    private $olzaSessionFactory;

    public function __construct(OlzaSessionFactory $olzaSessionFactory)
    {
        $this->olzaSessionFactory = $olzaSessionFactory;
    }
    /**
     * @param array<string, mixed> $parameters
     */
    public function create(array $parameters): Courier
    {
        $session = $this->olzaSessionFactory
                    ->session(OlzaParameters::create($parameters));

        return new Courier(
            new OlzaCourierCreateShipment($session),
            new OlzaCourierPostShipment($session),
            new OlzaCourierGetLabels($session),
            new OlzaCourierGetStatuses($session),
            new OlzaCourierMakeShipment(),
            new OlzaCourierMakeParcel(),
            new OlzaCourierMakeReceiver(),
            new OlzaCourierMakeSender(),
            new OlzaCourierMakeBooking()
        );
    }
}
