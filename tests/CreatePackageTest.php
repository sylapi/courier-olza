<?php

namespace Sylapi\Courier\Olza;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class CreatePackageTest extends PHPUnitTestCase
{
    private $gls = null;
    private $soapMock = null;

    private $address = [
        'name'     => 'Name Username',
        'company'  => '',
        'street'   => 'Street 1',
        'postcode' => '12-123',
        'city'     => 'Warszawa',
        'country'  => 'PL',
        'phone'    => '600600600',
        'email'    => 'name@example.com',
    ];

    private $options = [
        'weight'      => 3.00,
        'width'       => 30.00,
        'height'      => 50.00,
        'depth'       => 10.00,
        'amount'      => 2.10,
        'bank_number' => '29100010001000100010001000',
        'cod'         => false,
        'saturday'    => false,
        'references'  => 'order #1234',
        'note'        => 'Note',
    ];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->soapMock = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();

        $params = [
            'accessData' => [
                'login'    => 'login',
                'password' => 'password',
            ],
            'sender'   => $this->address,
            'receiver' => $this->address,
            'options'  => $this->options,
        ];

        $this->olza = new Olza();
        $this->olza->setSession('123456abcd');
        $this->olza->initialize($params);
    }

    public function testCreatePackageSuccess()
    {
        $localXml = file_get_contents(__DIR__.'/Mock/createShipmentSuccess.xml');

        $this->soapMock->expects($this->any())->method('__call')->will(
            $this->returnValue(
            simplexml_load_string($localXml, 'SimpleXMLElement', LIBXML_NOCDATA)
        )
        );

        $this->olza->setSoapClient($this->soapMock);
        $this->olza->CreatePackage();

        $this->assertNull($this->olza->getError());
        $this->assertTrue($this->olza->isSuccess());
        $this->assertNotNull($this->olza->getResponse());
    }

    public function testCreatePackageFailure()
    {
        $localXml = file_get_contents(__DIR__.'/Mock/createShipmentFailure.xml');

        $this->soapMock->expects($this->any())->method('__call')->will(
            $this->returnValue(
            simplexml_load_string($localXml, 'SimpleXMLElement', LIBXML_NOCDATA)
        )
        );

        $this->olza->setSoapClient($this->soapMock);
        $this->olza->CreatePackage();

        $this->assertNotNull($this->olza->getError());
        $this->assertFalse($this->olza->isSuccess());
        $this->assertNull($this->olza->getResponse());
    }
}
