<?php

class TestResponse extends \PHPUnit_Framework_TestCase
{
    public function SetUp()
    {
        // Create object for use
        $this->response = new \Rapi\Response();
    }

    public function testSetError()
    {
        // Set data to be used in the error
        $errorCode = 1;
        $errorMessage = "Test error message";

        // Set the error
        $this->response->setError( $errorCode, $errorMessage );

        // Check it's the right type of object
        $this->assertInstanceOf( '\Rapi\Response', $this->response );

        // Check that the result property was set
        $this->assertTrue( property_exists( $this->response, 'result' ) );

        // Map the private result array to a readable variable
        $responseArray = PHPUnit_Framework_Assert::readAttribute( $this->response, 'result' );

        // Check that status exists
        $this->assertTrue( array_key_exists( 'status', $responseArray ) );

        // Check that type exists
        $this->assertTrue( array_key_exists( 'type', $responseArray ) );

        // Check correct response type was set
        $this->assertEquals( 'Error', $responseArray['type'] );

        // Check that data exists
        $this->assertTrue( array_key_exists( 'data', $responseArray ) );

        // Check that error code exists
        $this->assertTrue( array_key_exists( 'code', $responseArray['data'] ) );

        // Check that error message exists
        $this->assertTrue( array_key_exists( 'message', $responseArray['data'] ) );

        // Check correct error code was set
        $this->assertEquals( $errorCode, $responseArray['data']['code'] );

        // Check correct error message was set
        $this->assertEquals( $errorMessage, $responseArray['data']['message'] );

    }

    public function testSetData()
    {
        $type = 'json';
        $data = 'This is test data';
        $this->response->setData( $type, $data );

        $this->assertInstanceOf( '\Rapi\Response', $this->response );

        // Check that the result property was set
        $this->assertTrue( property_exists( $this->response, 'result' ) );

        // Map the private result array to a readable variable
        $responseArray = PHPUnit_Framework_Assert::readAttribute( $this->response, 'result' );

        // Check that status exists
        $this->assertTrue( array_key_exists( 'status', $responseArray ) );

        // Check that type exists
        $this->assertTrue( array_key_exists( 'type', $responseArray ) );

        // Check correct response type was set
        $this->assertEquals( $type, $responseArray['type'] );

        // Check that data exists
        $this->assertTrue( array_key_exists( 'data', $responseArray ) );

        // Check correct data was set
        $this->assertEquals( $data, $responseArray['data'] );
    }

    public function testOutput()
    {
        // Todo: test jsonEncodeIm() first
    }
    public function testObjectToAray()
    {
        // Create dummy array for testing
        $object = new stdClass();
        $object->prop1 = 1;
        $object->prop2 = 2;
        $object->prop3 = 3;
        $object->array = array(
            1 => 1
            , 2 => 'two'
            , 'three' => 3
        );

        // Convert object to array
        $convertedObject = $this->response->objectToArray( $object );

        // Check result is an array
        $this->assertTrue( is_array( $convertedObject ) );

        // Check all properties were converted
        $this->assertEquals( 4, count( $convertedObject ) );

        // Check properties were converted and labelled
        $this->assertEquals( 1, $convertedObject['prop1'] );
        $this->assertEquals( 2, $convertedObject['prop2'] );
        $this->assertEquals( 3, $convertedObject['prop3'] );

        // Test array members were converted and labelled
        $this->assertEquals( 1, $convertedObject['array'][1] );
        $this->assertEquals( 'two', $convertedObject['array'][2] );
        $this->assertEquals( 3, $convertedObject['array']['three'] );


    }
    public function testJsonEncodeIm()
    {
    }

    public function testOutputError()
    {
    }

    public function testOutputErrorMessage()
    {
    }

    public function testOutputDeleted()
    {
    }

    public function testOutputMessage()
    {
    }
}
