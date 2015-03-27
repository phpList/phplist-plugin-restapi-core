<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require_once 'vendor/autoload.php';

// Disable HTML output as HTML cannot be easily read during HTTP POST testing
ini_set( 'html_errors', 0 );
// Disable xdebug HTML output
if ( function_exists( 'xdebug_disable' ) ) {
    xdebug_disable();
}

// Check that the plugin has been initiatlised
defined( 'PHPLISTINIT' ) || die;

// No HTML-output, please!
ob_end_clean();

// Getting phpList globals for this plugin
$plugin = $GLOBALS['plugins'][$_GET['pi']];

// Create Symfony DI service container object for use by other classes
$container = new ContainerBuilder();
// Create new Symfony file loader to handle the YAML service config file
$loader = new YamlFileLoader( $container, new FileLocator( __DIR__ ) );
// Load the service config file, which is in YAML format
$loader->load( 'services.yml' );

// Set default path to host phpList instance config file
//$hostParentConfigPath = $_SERVER['DOCUMENT_ROOT'] . '/lists/config/config.php';

// Set necessary config class parameter
$container->setParameter( 'config.configfile', $GLOBALS['configfile'] );
// Set service parameters for the database connection
// These service parameters will be used as constructor arguments for pdoEx{}
$container->setParameter( 'pdoEx.hostname', $GLOBALS['database_host'] );
$container->setParameter( 'pdoEx.username', $GLOBALS['database_user'] );
$container->setParameter( 'pdoEx.pass', $GLOBALS['database_password'] );
$container->setParameter( 'pdoEx.dbname', $GLOBALS['database_name'] );

if ( function_exists( 'api_request_log' ) )
{
    api_request_log();
}

$call = $container->get( 'Call' );
$response = $container->get( 'Response' );

// Check if this is called outside phpList auth, this should never occur!
if ( empty( $plugin->coderoot ) )
{
    $response->outputErrorMessage( 'Not authorized! Please login with [login] and [password] as admin first!' );
}

// Check if the request received was via HTTP post
if ( $_SERVER['REQUEST_METHOD'] != "POST" ) {
    $response->outputErrorMessage( 'Requests must be made via HTTP POST. Method of this call: ' . $_SERVER['REQUEST_METHOD'] );
}

// NOTE: Login authentication is handled by the main phpList application. HTTP
// POST parameters 'login' and 'password' are required to validate login, else
// an HTML login form will be returned.

// Check if a command was specified
if (
    empty( $_REQUEST['className'] )
    || empty( $_REQUEST['method'] )
) {
    $response->outputErrorMessage( 'No action requested: specify commands via parameters \'className\' and \'method\'' );
} else {
    // Set command for use later
    $className = $_REQUEST['className'];
    $method = $_REQUEST['method'];
}

// Check the command is callable
if ( ! $call->validateCall( $className, $method ) ) {
    // Add error message if not callable
    $response->outputErrorMessage( 'Requested command is not callable' );
}

try {
    // Execute the requested call
    $callResult = $call->doCall( $className, $method, $_POST );
} catch ( \Exception $e ) {
    // If call handler encounters error, turn it into a response
    $response->outputErrorMessage( 'Call handler error: ' . $e->getMessage() );
}

// Format call output for making a response
$resultArray = $call->callResultToArray( $callResult );

// Save output to response
$response->setData( 'foo', $resultArray );

// Output the response
$response->output();
