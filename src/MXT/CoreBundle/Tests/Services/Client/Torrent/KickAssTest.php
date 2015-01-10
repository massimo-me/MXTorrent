<?php
/**
* MXTorrent
* © Chiarillo Massimo
*
* MXT\CoreBundle\Services\Client\Torrent\KickAss.php
*
*/

namespace MXT\CoreBundle\Tests\Services\Client\Torrent;

use MXT\CoreBundle\Services\Client\Torrent\KickAss;

class KickAssTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestSuccess()
    {
        $containerMock = $this->getContainerMock();

        $clientMock = $this->getMockBuilder('MXT\CoreBundle\Services\MXTClient')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $response = $this->getMockBuilder('GuzzleHttp\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods(['json'])
            ->getMock();

        $response->expects($this->any())
            ->method('json')
            ->will($this->returnValue([
                'list' => [
                    ['title' => 'test'],
                    ['title' => 'test'],
                ]
            ]));

        $clientMock->expects($this->any())
            ->method('get')
            ->willReturn($response);

        $kickAss = new KickAss($containerMock, $clientMock);

        $this->assertCount(2, $kickAss->request([]));
    }

    public function testRequestFailed()
    {
        $containerMock = $this->getContainerMock();

        $clientMock = $this->getMockBuilder('MXT\CoreBundle\Services\MXTClient')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $clientMock->expects($this->any())
            ->method('get')
            ->will($this->throwException(new \Exception()));

        $kickAss = new KickAss($containerMock, $clientMock);

        $this->assertEquals(null, $kickAss->request([]));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getContainerMock()
    {
        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Container')
            ->disableOriginalConstructor()
            ->setMethods(['getParameter'])
            ->getMock();

        $containerMock->expects($this->at(0))
            ->method('getParameter')
            ->with($this->equalTo('kickass.api'))
            ->will($this->returnValue([
                'url'     => 'test_url',
                'filters' => []
            ]));

        $containerMock->expects($this->at(1))
            ->method('getParameter')
            ->with($this->equalTo('kickass.timeout'))
            ->will($this->returnValue([
                'connection' => 0,
                'response'   => 0
            ]));

        return $containerMock;
    }
}