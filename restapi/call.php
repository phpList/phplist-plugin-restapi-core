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
// NOTE: This config file must be in phpList 4 ini format
// NOTE: Parent phpList 3 config file path available via: $GLOBALS['configfile']
$configFilePath = dirname( __FILE__ ) . '/config-phplist4.php';

// Set necessary config class parameter
$container->setParameter( 'config.configfile', $configFilePath );
// Set service parameters for the RAPI database connection
// NOTE: phpList4 database connection configured elsewhere
// These service parameters will be used as constructor arguments for pdoEx{}
$container->setParameter( 'pdoEx.hostname', $GLOBALS['database_host'] );
$container->setParameter( 'pdoEx.username', $GLOBALS['database_user'] );
$container->setParameter( 'pdoEx.pass', $GLOBALS['database_password'] );
$container->setParameter( 'pdoEx.dbname', $GLOBALS['database_name'] );

// Get a phpList4 configuration object so we can configure the database
$pl4Config = $container->get( 'Config' );

// Set necessasry phpList4 settings from host app globals and save to session
// NOTE: phpList 4 by default loads its configuration from a config file.
// When shipped as a library, manual config is not possible, and a default
// config file is used. To override default config we must set options here
// using a phpList4 config object so that they're stored in session and override
// the default config options which will be loaded from file in client code.
$pl4Config->setRunningConfig( 'DATABASE_USER', $GLOBALS['database_user'] );
$pl4Config->setRunningConfig( 'DATABASE_PASSWORD', $GLOBALS['database_password'] );
$pl4Config->setRunningConfig( 'DATABASE_DSN', 'mysql:host=' . $GLOBALS['database_host'] . ';port=3306;dbname=' . $GLOBALS['database_name'] );
$pl4Config->setRunningConfig( 'TABLE_PREFIX', $GLOBALS['table_prefix'] );
$pl4Config->setRunningConfig( 'USERTABLE_PREFIX', $GLOBALS['usertable_prefix'] );
// FIXME: Set this hashing algo from phpList3's config instead
$pl4Config->setRunningConfig( 'ENCRYPTION_ALGO', 'md5' );

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
