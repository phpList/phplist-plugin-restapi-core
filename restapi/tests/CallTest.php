<?php

// Symfony namespaces
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

// Phplist 4 namespaces
use phpList\SubscriberManager;

class TestCall extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Create Symfony DI service container object for use by other classes
        $this->container = new ContainerBuilder();
        // Create new Symfony file loader to handle the YAML service config file
        $loader = new YamlFileLoader( $this->container, new FileLocator(__DIR__) );
        // Load the service config file, which is in YAML format
        $loader->load( '../services.yml' );
        // Set necessary config class parameter
        $this->container->setParameter( 'config.configfile', $GLOBALS['phpunit4-config-file-path'] );
        // These service parameters will be used as constructor arguments for pdoEx{}
        $this->container->setParameter( 'pdoEx.hostname', $GLOBALS['database_host'] );
        $this->container->setParameter( 'pdoEx.username', $GLOBALS['database_user'] );
        $this->container->setParameter( 'pdoEx.pass', $GLOBALS['database_password'] );
        $this->container->setParameter( 'pdoEx.dbname', $GLOBALS['database_name'] );

        // Instantiate objects
        $this->subscriberManager = $this->container->get( 'SubscriberManager' );
        $this->call = $this->container->get( 'Call' );
    }

    public function testIsCallable()
    {
        // Set API call arguments
        $goodClassName = 'subscriberManager';
        $goodMethod = 'getSubscriber';
        // Check if its callable
        $result = $this->call->validateCall( $goodClassName, $goodMethod );
        // Test result
        $this->assertTrue( $result );

        // Set bad call args
        $badClassName = 'subscriber Manager';
        $badMethod = 'get Subscriber';
        // Check if its callable
        $result = $this->call->validateCall( $badClassName, $badMethod );
        // Test result
        $this->assertFalse( $result );

        // Test with mixed good and bad
        $result = $this->call->validateCall( $goodClassName, $badMethod );
        // Test result
        $this->assertFalse( $result );

        // Test with mixed good and bad
        $result = $this->call->validateCall( $badClassName, $goodMethod );
        // Test result
        $this->assertFalse( $result );
    }

    public function testDoCall()
    {
        // Set API call arguments
        $className = 'subscriberManager';
        $method = 'getSubscriber';
        // Set params for command
        $params = array( 'id' => 2 );
        // Execute the call
        $result = $this->call->doCall( $className, $method, $params );

        // TODO: refactor doCall and Common{}->select() so result of doCall() is raw data not a Response{} object
    }
}
