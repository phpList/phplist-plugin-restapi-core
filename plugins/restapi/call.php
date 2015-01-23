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

if (function_exists('api_request_log'))
{
    api_request_log();
}

// Instantiate objects for use
$pdoEx = $container->get( 'PdoEx' );
$response = $container->get( 'Response' );
$common = $container->get( 'Common' );
$actions = $container->get( 'Actions' );
$subscribers = $container->get( 'Subscribers' );
$templates = $container->get( 'Templates' );
$messages = $container->get( 'Messages' );
$lists = $container->get( 'Lists' );

// Connect to database
$pdoEx->connect(
    $GLOBALS['database_host']
    , $GLOBALS['database_user']
    , $GLOBALS['database_password']
    , $GLOBALS['database_name']
);

// Check if this is called outside phpList auth, this should never occur!
if ( empty( $plugin->coderoot ) )
{
    $response->outputErrorMessage( 'Not authorized! Please login with [login] and [password] as admin first!' );
}

// Check if command is empty!
$cmd = $_REQUEST['cmd'];
$cmd = preg_replace( '/\W/','',$cmd );
if ( empty($cmd) ){
    $response->outputMessage( 'OK! For action, please provide Post Param Key [cmd] !' );
}

// Try calling the requested method on all of the available classes
// NOTE: This looks inefficient
if ( is_callable( array( 'Rapi\Lists',       $cmd ) ) ) $lists->$cmd();
if ( is_callable( array( 'Rapi\Actions',     $cmd ) ) ) $actions->$cmd();
if ( is_callable( array( 'Rapi\Subscribers', $cmd ) ) ) $subscribers->$cmd();
if ( is_callable( array( 'Rapi\Templates',   $cmd ) ) ) $templates->$cmd();
if ( is_callable( array( 'Rapi\Messages',    $cmd ) ) ) $messages->$cmd();

//If no command found, return error message!
$response->outputErrorMessage( 'No function for provided [cmd] found!' );
