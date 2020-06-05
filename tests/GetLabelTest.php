<?php

namespace Sylapi\Courier\Olza;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class GetLabelTest extends PHPUnitTestCase
{
    private $gls = null;
    private $soapMock = null;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->soapMock = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();

        $this->olza = new Olza();
        $this->olza->setSession('123456abcd');

        $params = [
            'accessData' => [
                'login'    => 'login',
                'password' => 'password',
            ],
            'tracking_id' => '123456',
            'format'      => 'A6',
        ];

        $this->olza->initialize($params);
    }

    public function testGetLabelSuccess()
    {
        $localXml = file_get_contents(__DIR__.'/Mock/getLabelSuccess.xml');

        $this->soapMock->expects($this->any())->method('__call')->will(
            $this->returnValue(
            simplexml_load_string($localXml, 'SimpleXMLElement', LIBXML_NOCDATA)
        )
        );

        $this->olza->setSoapClient($this->soapMock);
        $this->olza->GetLabel();

        $this->assertNull($this->olza->getError());
        $this->assertTrue($this->olza->isSuccess());
        $this->assertNotNull($this->olza->getResponse());
    }

    public function testGetLabelFailure()
    {
        $localXml = file_get_contents(__DIR__.'/Mock/getLabelFailure.xml');

        $this->soapMock->expects($this->any())->method('__call')->will(
            $this->returnValue(
            simplexml_load_string($localXml, 'SimpleXMLElement', LIBXML_NOCDATA)
        )
        );

        $this->olza->setSoapClient($this->soapMock);
        $this->olza->GetLabel();

        $this->assertNotNull($this->olza->getError());
        $this->assertFalse($this->olza->isSuccess());
        $this->assertNull($this->olza->getResponse());
    }
}
