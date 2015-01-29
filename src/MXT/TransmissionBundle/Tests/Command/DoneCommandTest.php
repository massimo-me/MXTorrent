<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\TransmissionBundle\Tests\Command\DoneCommand
 *
 */
namespace MXT\TransmissionBundle\Test\Command;

use MXT\CoreBundle\Document\Torrent;
use MXT\CoreBundle\Test\MXTWebTestCase;

class DoneCommandTest extends MXTWebTestCase
{
    private $torrent;

    private $dm;

    public function setUp()
    {
        parent::setUp();

        $this->dm = $this->getContainer()->get('doctrine_mongodb');
        $this->createTestTorrent();
        $this->setEnv();
    }

    public function testExecute()
    {
        $this->runCommand('mxt:transmission-done');

        $this->dm->getManager()->refresh($this->torrent);

        $this->assertCount(4, $this->torrent->getFiles());
    }

    private function createTestTorrent()
    {
        $this->torrent = new Torrent();
        $this->torrent->setTitle('Test Torrent');
        $this->torrent->setHash('1BDDDAD4858116655F74231711B9456E607436B1');
        $this->dm->getManager()->persist($this->torrent);
        $this->dm->getManager()->flush();
    }

    private function setEnv()
    {
        putenv(sprintf('TR_TORRENT_DIR=%s', $this->getContainer()->getParameter('mxt_core.torrent.folder')));
        putenv('TR_TORRENT_NAME=testTorrent');
        putenv('TR_TORRENT_HASH=1BDDDAD4858116655F74231711B9456E607436B1');
    }
}