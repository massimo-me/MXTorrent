<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Tests\Command\Torrent\KickAssCommandTest.php
 *
 */
namespace MXT\CoreBundle\Tests\Command\Torrent;

use MXT\CoreBundle\Command\Torrent\KickAssCommand;
use MXT\CoreBundle\Test\MXTWebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class KickAssCommandTest extends MXTWebTestCase
{
    private $application;

    public function setUp()
    {
        parent::setUp();
        
        $this->application = new Application($this->getContainer()->get('kernel'));
    }

    public function testExecute()
    {
        $this->application->add(new KickAssCommand());

        $command = $this->application->find('mxt:torrent:kickass');
        $commandTester = new CommandTester($command);

        $commandOptions = [
            'command'     => $command->getName(),
            'searchQuery' => 'test',
            'order'       => 'time_add',
            '-m'          => true
        ];
        $commandTester->execute($commandOptions);
        $this->assertContains('Title: Ubuntu 14.10 Desktop 64bit ISO', $commandTester->getDisplay());

        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $this->assertCount(14, $dm->getRepository('MXTCoreBundle:Torrent')->findAll());

        $commandTester->execute($commandOptions);
        $this->assertCount(14, $dm->getRepository('MXTCoreBundle:Torrent')->findAll());
    }
}