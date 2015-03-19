<?php

// Symfony namespaces
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require '/var/www/pl/configdev.php';

// Include Symfony autoloader
require_once( 'vendor/autoload.php' );

// Include config file as it includes necessary config vars
if (
    isset( $_SERVER['ConfigFile'] )
    && is_file( $_SERVER['ConfigFile'] )
) {
    require_once( $_SERVER['ConfigFile'] );
}

// Create Symfony DI service container object for use by other classes
$container = new ContainerBuilder();
// Create new Symfony file loader to handle the YAML service config file
$loader = new YamlFileLoader( $container, new FileLocator(__DIR__) );
// Load the service config file, which is in YAML format
$loader->load( '../services.yml' );
// Set necessary config class parameter
$container->setParameter( 'config.configfile', $GLOBALS['phpunit4-config-file-path'] );
// These service parameters will be used as constructor arguments for pdoEx{}
$container->setParameter( 'pdoEx.hostname', $GLOBALS['database_host'] );
$container->setParameter( 'pdoEx.username', $GLOBALS['database_user'] );
$container->setParameter( 'pdoEx.pass', $GLOBALS['database_password'] );
$container->setParameter( 'pdoEx.dbname', $GLOBALS['database_name'] );
