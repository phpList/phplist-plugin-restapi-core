<?php

require_once 'vendor/autoload.php';

class TestActions extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Mock necessary globals
        $response = new \Rapi\Response();
        $this->actions = new \Rapi\Actions( $response );
    }

    public function testLogin()
    {
        $response = $this->actions->login();
        // Check that a response object was returned
        $this->assertInstanceOf( '\Rapi\Response', $response );
    }

    public function testProcessQueue()
    {
        $response = $this->actions->processQueue();
        // Check that a response object was returned
        $this->assertInstanceOf( '\Rapi\Response', $response );
    }
}
