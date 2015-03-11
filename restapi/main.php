<?php

namespace Rapi;

// Symfony namespaces
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

// NOTE: All include classes used to have this init check, now it's only here
defined('PHPLISTINIT') || die;

require_once 'vendor/autoload.php';

$plugin = $GLOBALS['plugins'][$_GET['pi']];

// Create Symfony DI service container object for use by other classes
$container = new ContainerBuilder();
// Create new Symfony file loader to handle the YAML service config file
$loader = new YamlFileLoader( $container, new FileLocator(__DIR__) );
// Load the service config file, which is in YAML format
$loader->load( 'services.yml' );

// Set necessary config class parameter
$container->setParameter( 'config.configfile', '/var/www/pl4/config.ini' );
// Set service parameters for the database connection
// These service parameters will be used as constructor arguments for pdoEx{}
$container->setParameter( 'pdoEx.hostname', $GLOBALS['database_host'] );
$container->setParameter( 'pdoEx.username', $GLOBALS['database_user'] );
$container->setParameter( 'pdoEx.pass', $GLOBALS['database_password'] );
$container->setParameter( 'pdoEx.dbname', $GLOBALS['database_name'] );

// Get Common{} object
$common = $container->get( 'Common' );

// TODO: Replace hardcoded admin url with one set centrally
$url = $common->apiUrl( $website, $pageroot, '/admin/' );

?>

<h1>REST API</h1>

    <h2>Version 0.2.5</h2>
    <p>The plugin provides a REST API to phpList.<br/> Development by <a href='http://samtuke.com'>Sam Tuke, <a href='http://phplist.com'>Michiel Dethmers</a>. Original development by <a href='https://twitter.com/ekandreas'>Andreas Ek</a> of Flowcom AB.</p>

    <h2>Commands</h2>
    <p>
        To discover all commands to this API just make a GET request or click here:<br/>
        <a href='<?php echo $url; ?>'>phpList API Command Reference list</a><br/>
        The documentation is generated in realtime.
    </p>

    <h2>Access</h2>
    <p>
        Autentication required as admin in phpList.<br/>
        All requests to the RESTAPI is made by method POST.<br/>
        RESTAPI-Url to this installation:<br/>
        <a href='<?php echo $url; ?>'><?php echo $url; ?></a>
    </p>
    <p>
        First login to phpList with method POST and body parameters 'login' and 'password'.<br/>
    </p>

    <h2>Client</h2>
    <p>
        To try the RESTAPI, please use a client like CocaRestClient or eqvivalent!<br/>
        There is an example class in restapi-test/phplist_restapi_helper.php if you like to try it in PHP.<br/>
        For examples check commands in restapi-test/main.php
    </p>

    <h2>More information</h2>
    <p>
        See the readme file in this plugin's root directory for further instructions (<code>README.md</code>).
    </p>

    <h2>Issues</h2>
    <p>
        Report issues to the central phpList <a href='https://mantis.phplist.com/'>bug tracker</a>.
    </p>
