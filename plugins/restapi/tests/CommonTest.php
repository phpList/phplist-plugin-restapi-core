<?php

require_once 'vendor/autoload.php';

class TestCommon extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Mock necessary globals
        $this->common = new \Rapi\Common();
        $this->domain = 'local.pl';
    }

    public function testSelect()
    {
        $response = $this->common->select( 'Users', 'SELECT * FROM ' . $GLOBALS['usertable_prefix'] . 'user_user LIMIT 1' );
        // Check that a response object was returned
        $this->assertInstanceOf( '\Rapi\Response', $response );
        // Get private property: response status
        $responseStatus = PHPUnit_Framework_Assert::readAttribute( $response, 'result' );
        // Check that response status indicates success
        $this->assertEquals( 'success', $responseStatus['status'] );
        // Check that the user ID is numeric
        $this->assertTrue( is_numeric( $responseStatus['data'][0]->id ) );
    }

    public function testApiUrl()
    {
        // Get an API call URL
        $apiUrl = $this->common->apiUrl( $this->domain, PAGE_ROOT, ADMIN_PATH );
        // TODO: Add more detailed tests here checking path is valid
        $this->assertNotEmpty( $apiUrl );
        // Check protocol is set
        $this->assertEquals( 'http://', substr( $apiUrl, 0, 7 ) );
        $domainLen = strlen( $this->domain );
        // Check domain is correct within URL
        $this->assertEquals( $this->domain, substr( $apiUrl, 7, $domainLen ) );
        // Check page root is correct within URL
        $this->assertEquals( PAGE_ROOT, substr( $apiUrl, 7 + $domainLen, strlen( PAGE_ROOT ) ) );
        // Check admin path is correct within URL
        $this->assertEquals( ADMIN_PATH, substr( $apiUrl, 7 + $domainLen + strlen( PAGE_ROOT ), strlen( ADMIN_PATH ) ) );
    }
}
