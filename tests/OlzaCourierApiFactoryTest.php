<?php

namespace Sylapi\Courier\Olza\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Courier;
use Sylapi\Courier\Olza\OlzaBooking;
use Sylapi\Courier\Olza\OlzaCourierApiFactory;
use Sylapi\Courier\Olza\OlzaParameters;
use Sylapi\Courier\Olza\OlzaParcel;
use Sylapi\Courier\Olza\OlzaReceiver;
use Sylapi\Courier\Olza\OlzaSender;
use Sylapi\Courier\Olza\OlzaSession;
use Sylapi\Courier\Olza\OlzaSessionFactory;
use Sylapi\Courier\Olza\OlzaShipment;

class OlzaCourierApiFactoryTest extends PHPUnitTestCase
{
    private $parameters = [
        'login'           => 'login',
        'password'        => 'password',
        'sandbox'         => true,
        'requestLanguage' => 'pl',
        'labelType'       => 'A4',
        'speditionCode'   => 'GLS',
        'shipmentType'    => 'WAREHOUSE',
    ];

    public function testOlzaSessionFactory()
    {
        $olzaSessionFactory = new OlzaSessionFactory();
        $olzaSession = $olzaSessionFactory->session(
            OlzaParameters::create($this->parameters)
        );
        $this->assertInstanceOf(OlzaSession::class, $olzaSession);
    }

    public function testCourierFactoryCreate()
    {
        $olzaCourierApiFactory = new OlzaCourierApiFactory(new OlzaSessionFactory());
        $courier = $olzaCourierApiFactory->create($this->parameters);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(OlzaBooking::class, $courier->makeBooking());
        $this->assertInstanceOf(OlzaParcel::class, $courier->makeParcel());
        $this->assertInstanceOf(OlzaReceiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(OlzaSender::class, $courier->makeSender());
        $this->assertInstanceOf(OlzaShipment::class, $courier->makeShipment());
    }
}
