<?php

namespace MXT\CoreBundle\Test;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class MXTWebTestCase extends WebTestCase
{
    public function setUp()
    {
        $this->runCommand('doctrine:mongodb:schema:drop');
        $this->runCommand('doctrine:mongodb:schema:create');
    }
}