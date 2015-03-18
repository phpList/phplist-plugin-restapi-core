<?php

namespace rapi\Test\Handler;

// Symfony namespaces
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TestSubscriberHandler extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Create Symfony DI service container object for use by other classes
        $this->container = new ContainerBuilder();
        // Create new Symfony file loader to handle the YAML service config file
        $loader = new YamlFileLoader( $this->container, new FileLocator(__DIR__) );
        // Load the service config file, which is in YAML format
        $loader->load( '../../services.yml' );
        // Set necessary config class parameter
        $this->container->setParameter( 'config.configfile', $GLOBALS['phpunit4-config-file-path'] );
        // These service parameters will be used as constructor arguments for pdoEx{}
        $this->container->setParameter( 'pdoEx.hostname', $GLOBALS['database_host'] );
        $this->container->setParameter( 'pdoEx.username', $GLOBALS['database_user'] );
        $this->container->setParameter( 'pdoEx.pass', $GLOBALS['database_password'] );
        $this->container->setParameter( 'pdoEx.dbname', $GLOBALS['database_name'] );

        // Get objects from container
        $this->scrHandler = $this->container->get( 'SubscriberHandler' );
        $this->scrEntity = $this->container->get( 'SubscriberEntity' );
        $this->subscriberManager = $this->container->get( 'SubscriberManager' );

        // Create a randomised email addy to register with
        $this->emailAddress = 'unittest-' . rand( 0, 999999 ) . '@example.com';
    }

    public function testAdd()
    {
        // Insert a new subscriber
        $scrId = $this->scrHandler->add( $this->emailAddress, 'bar', 'baz' );

        // Check that a valid ID was returned
        $this->assertTrue( is_numeric( $scrId ) );
    }
}
