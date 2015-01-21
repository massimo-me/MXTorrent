<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\TransmissionBundle\Tests\Services\Process\TransmissionShowTest
 *
 */

namespace MXT\TransmissionBundle\Tests\Services\Process;

use MXT\TransmissionBundle\Services\Process\TransmissionShow;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Parser;

/**
 * @coversDefaultClass MXT\TransmissionBundle\Tests\Services\Process\TransmissionShowTest
 */
class TransmissionShowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|TransmissionShow
     */
    private $transmissionShow = null;

    /**
     * @var null|array
     */
    private $expected = null;

    public function setUp()
    {
        $transmissionShow = new Process('transmission-show -h');
        $transmissionShow->run();

        if ($transmissionShow->getErrorOutput()) {
            $this->markTestSkipped('Command transmission-show is available in your system');
        }

        $parser = new Parser();
        $this->expected = $parser->parse(file_get_contents(sprintf('%s/../../../Test/Result/transmission-show.yml', (__DIR__))));

        $this->transmissionShow = new TransmissionShow();
        $this->transmissionShow->setTorrentPath(sprintf('%s/../../../Test/Files/testTorrent.torrent', (__DIR__)));
    }

    /**
     * @covers TransmissionShow::getInfo
     */
    public function testGetInfo()
    {
        $this->assertEquals($this->expected['info'], $this->transmissionShow->getInfo());
    }

    /**
     * * @covers TransmissionShow::getFiles
     */
    public function testGetFiles()
    {
        $this->assertEquals($this->expected['files'], $this->transmissionShow->getFiles());
    }
}