<?php

require_once 'vendor/autoload.php';

class TestCommon extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Instantiate necessary objects
        $this->pdoEx = new Rapi\PdoEx(
            $GLOBALS['DB_HOST']
            , $GLOBALS['DB_USER']
            , $GLOBALS['DB_PASSWD']
            , $GLOBALS['DB_NAME']
        );
        $this->response = new \Rapi\Response();
        $this->common = new \Rapi\Common( $this->pdoEx, $this->response );
        $this->domain = 'local.pl';
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
