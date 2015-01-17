<?php

namespace Rapi;

require_once 'vendor/autoload.php';

// Check that the plugin has been initiatlised
defined('PHPLISTINIT') || die;

// No HTML-output, please!
ob_end_clean();

// Getting phpList globals for this plugin
$plugin = $GLOBALS['plugins'][$_GET['pi']];

if (function_exists('api_request_log'))
{
    api_request_log();
}

$response = new Response();
$pdoEx = new PdoEx();
$common = new Common( $pdoEx );
$actions = new Actions( $response );
$subscribers = new Subscribers( $common, $pdoEx, $response );
$templates = new Templates( $common );
$messages = new Messages();

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
