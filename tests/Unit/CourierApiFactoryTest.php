<?php

namespace Sylapi\Courier\Olza\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Courier;
use Sylapi\Courier\Olza\Entities\Booking;
use Sylapi\Courier\Olza\CourierApiFactory;
use Sylapi\Courier\Olza\Entities\Parcel;
use Sylapi\Courier\Olza\Entities\Receiver;
use Sylapi\Courier\Olza\Entities\Sender;
use Sylapi\Courier\Olza\Session;
use Sylapi\Courier\Olza\SessionFactory;
use Sylapi\Courier\Olza\Entities\Shipment;
use Sylapi\Courier\Olza\Entities\Credentials;

class CourierApiFactoryTest extends PHPUnitTestCase
{
    public function testOlzaSessionFactory(): void
    {
        $credentials = new Credentials();
        $credentials->setLogin('login');
        $credentials->setPassword('password');
        $credentials->setSandbox(true);
        $sessionFactory = new SessionFactory();
        $session = $sessionFactory->session(
            $credentials
        );
        $this->assertInstanceOf(Session::class, $session);
    }

    public function testCourierFactoryCreate(): void
    {
        $credentials = [
            'login' => 'login',
            'password' => 'password',
            'sandbox' => true,
        ];

        $courierApiFactory = new CourierApiFactory(new SessionFactory());
        $courier = $courierApiFactory->create($credentials);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(Booking::class, $courier->makeBooking());
        $this->assertInstanceOf(Parcel::class, $courier->makeParcel());
        $this->assertInstanceOf(Receiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(Sender::class, $courier->makeSender());
        $this->assertInstanceOf(Shipment::class, $courier->makeShipment());
    }
}
