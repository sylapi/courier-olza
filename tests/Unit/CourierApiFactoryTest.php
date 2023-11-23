<?php

namespace Sylapi\Courier\Olza\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Courier;
use Sylapi\Courier\Olza\Booking;
use Sylapi\Courier\Olza\CourierApiFactory;
use Sylapi\Courier\Olza\Parameters;
use Sylapi\Courier\Olza\Parcel;
use Sylapi\Courier\Olza\Receiver;
use Sylapi\Courier\Olza\Sender;
use Sylapi\Courier\Olza\Session;
use Sylapi\Courier\Olza\SessionFactory;
use Sylapi\Courier\Olza\Shipment;

class CourierApiFactoryTest extends PHPUnitTestCase
{
    /**
     * @var array<string,mixed>
     */
    private $parameters = [
        'login'           => 'login',
        'password'        => 'password',
        'sandbox'         => true,
        'requestLanguage' => 'pl',
        'labelType'       => 'A4',
        'speditionCode'   => 'GLS',
        'shipmentType'    => 'WAREHOUSE',
    ];

    public function testOlzaSessionFactory(): void
    {
        $olzaSessionFactory = new SessionFactory();
        $olzaSession = $olzaSessionFactory->session(
            Parameters::create($this->parameters)
        );
        $this->assertInstanceOf(Session::class, $olzaSession);
    }

    public function testCourierFactoryCreate(): void
    {
        $olzaCourierApiFactory = new CourierApiFactory(new SessionFactory());
        $courier = $olzaCourierApiFactory->create($this->parameters);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(Booking::class, $courier->makeBooking());
        $this->assertInstanceOf(Parcel::class, $courier->makeParcel());
        $this->assertInstanceOf(Receiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(Sender::class, $courier->makeSender());
        $this->assertInstanceOf(Shipment::class, $courier->makeShipment());
    }
}
