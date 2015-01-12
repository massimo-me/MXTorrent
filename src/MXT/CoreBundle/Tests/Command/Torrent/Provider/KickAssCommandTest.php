<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Tests\Command\Torrent\KickAssCommandTest.php
 *
 */
namespace MXT\CoreBundle\Tests\Command\Torrent\Provider;

use MXT\CoreBundle\Command\Torrent\Provider\KickAssCommand;
use MXT\CoreBundle\Test\MXTWebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class KickAssCommandTest extends MXTWebTestCase
{
    private $application;

    public function setUp()
    {
        $this->application = new Application($this->getContainer()->get('kernel'));
    }

    public function testExecute()
    {
        $this->application->add(new KickAssCommand());

        $command = $this->application->find('mxt:torrent:kickass');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'searchQuery' => 'test',
            'order' => 'age'
        ]);

        $this->assertContains('Title: The Trip to Italy', $commandTester->getDisplay());
    }
}