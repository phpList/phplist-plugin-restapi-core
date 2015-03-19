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
        // Access the Symfony DI container object which was setup during bootstrap
        global $container;
        $this->container = $container;

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
