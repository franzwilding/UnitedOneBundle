<?php

namespace United\OneBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class FunctionalControllerTestCase extends WebTestCase
{

    /**
     * @var Client $anonClient
     */
    protected $anonClient;

    /**
     * @var Client $adminClient
     */
    protected $adminClient;

    public function setUp()
    {
        parent::setUp();
        $this->anonClient = static::createClient();
        $this->adminClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
    }

    protected function p($path)
    {
        return 'http://' . $this->anonClient->getRequest()->getHost() . $path;
    }

    protected function u($path = '', $auth = false)
    {
        $str = '/functional/';

        if($auth) {
            $str .= 'auth';
        } else {
            $str .= 'anon';
        }

        $str .= $path;
        return $str;
    }

    /**
     * Attempts to guess the kernel location.
     *
     * When the Kernel is located, the file is required.
     *
     * @return string The Kernel class name
     *
     * @throws \RuntimeException
     */
    protected static function getKernelClass()
    {
        require_once '../tests/AppKernel.php';
        return 'AppKernel';
    }
}