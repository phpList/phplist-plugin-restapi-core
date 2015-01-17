<?php

require_once 'vendor/autoload.php';

class TestLists extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Instantiate necessary objects
        // TODO: Consider mocking these
        $this->pdoEx = new \Rapi\PdoEx;
        $this->common = new \Rapi\Common( $this->pdoEx );
        $this->request = array();
        $this->response = new \Rapi\Response();
        $this->lists = new \Rapi\Lists( $this->common, $this->response, $this->request );
    }

    public function testListsGet()
    {
    }

    public function testListGet()
    {
        $listId = 2;
        $response = $this->lists->listGet( $listId );
        var_dump($response);
    }

    public function testListAdd()
    {
    }

    public function testListUpdate()
    {
    }

    public function testListDelete()
    {
    }

    public function testListsSubscriber()
    {
    }

    public function testListSubscriberAdd()
    {
    }

    public function testListSubscriberDelete()
    {
    }

    public function testListMessageAdd()
    {
    }

    public function testListMessageDelete()
    {
    }
}
