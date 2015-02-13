<?php

require_once 'vendor/autoload.php';

class TestCall extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->response = new Rapi\Response();
        $this->campaign = new Rapi\Campaign;
        $this->pdoEx = new Rapi\PdoEx(
            $GLOBALS['DB_HOST']
            , $GLOBALS['DB_USER']
            , $GLOBALS['DB_PASSWD']
            , $GLOBALS['DB_NAME']
        );

        // Instantiate objects
        // TODO: Mock these and test separately as well
        $this->actions = new Rapi\Actions( $this->response );
        $this->common = new Rapi\Common( $this->pdoEx, $this->response );
        $this->lists = new Rapi\Lists( $this->common, $this->pdoEx, $this->response );
        $this->subscribers = new Rapi\Subscribers( $this->common, $this->pdoEx, $this->response );
        $this->templates = new Rapi\Templates( $this->common );
        $this->call = new Rapi\Call( $this->actions, $this->lists, $this->campaign, $this->response, $this->subscribers, $this->templates );
    }

    public function testIsCallable()
    {
        // Set command to call
        $cmd = "listGet";
        // Check if its callable
        $result = $this->call->isCallable( $cmd );
        // Test result
        $this->assertTrue( $result );
    }

    public function testDoCall()
    {
        // Set command to call
        $cmd = "listGet";
        // Set params for command
        $params = array( 'id' => 2 );
        // Execute the call
        $result = $this->call->doCall( $cmd, $params );

        // TODO: refactor doCall and Common{}->select() so result of doCall() is raw data not a Response{} object
    }
}
