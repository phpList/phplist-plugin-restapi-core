<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Rapi;

require_once 'vendor/autoload.php';

// Check that the plugin has been initiatlised
defined('PHPLISTINIT') || die;

// No HTML-output, please!
ob_end_clean();

// Getting phpList globals for this plugin
$plugin = $GLOBALS['plugins'][$_GET['pi']];

// Create Symfony DI service container object for use by other classes
$container = new ContainerBuilder();
// Create new Symfony file loader to handle the YAML service config file
$loader = new YamlFileLoader( $container, new FileLocator(__DIR__) );
// Load the service config file, which is in YAML format
$loader->load( 'services.yml' );

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

// Check if this is called outside phpList auth, this should never occur!
if ( empty( $plugin->coderoot ) )
{
    $response->outputErrorMessage( 'Not authorized! Please login with [login] and [password] as admin first!' );
}

// Check the command is callable
if ( ! $call->isCallable( $cmd ) ) {
    // Add error message if not callable
    $response->outputMessage( 'For action, please provide Post Param Key [cmd] !' );
}

// Execute the requested call
$call->doCall( $cmd, $params );
