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
    }
}


// .Rapi\Response Object
// (
// [result:Rapi\Response:private] => Array
// (
// [status] => success
// [type] => Users
// [data] => Array
// (
// [0] => stdClass Object
// (
// [id] => 1
// [email] => test-4482729003@sendspamhere.com
// [confirmed] => 0
// [blacklisted] => 0
// [optedin] => 0
// [bouncecount] => 0
// [entered] => 2012-08-26 15:04:03
// [modified] => 2013-01-16 15:34:26
// [uniqid] => 0e69bf630e1d9312d8b6433db7e741eb
// [htmlemail] => 1
// [subscribepage] =>
// [rssfrequency] =>
// [password] => 4f5f282e7e716424bcd5b5a10a82d7acabc87a0ae07ee88d9fd8ae69bbfbbfc9
// [passwordchanged] => 2012-08-26
// [disabled] => 0
// [extradata] =>
// [foreignkey] =>
// )
//
// )
//
// )
//
// )
