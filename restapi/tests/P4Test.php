<?php

// Phplist 4 namespaces
use phpList\Config;
use phpList\EmailAddress;
use phpList\entities\SubscriberEntity;
use phpList\helper\Database;
use phpList\Password;
use phpList\phpList;
use phpList\Subscriber;
use phpList\helper\Util;

// Symfony namespaces
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Tests access to phplist 4 classes. Duplicates cases from that package.
 */
class Pl4Test extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Create a randomised email addy to register with
        $this->emailAddress = $emailAddress = 'unittest-' . rand( 0, 999999 ) . '@example.com';
        // Create Symfony DI service container object for use by other classes
        $this->container = new ContainerBuilder();
        // Create new Symfony file loader to handle the YAML service config file
        $loader = new YamlFileLoader( $this->container, new FileLocator(__DIR__) );
        // Load the service config file, which is in YAML format
        $loader->load( '../services.yml' );
        // Set necessary config class parameter
        $this->container->setParameter( 'config.configfile', '/var/www/pl4/config.ini' );
        // Get objects from container
        $this->config = $this->container->get( 'Config' );
        $this->subscriber = $this->container->get( 'Subscriber' );
    }

    public function testSave()
    {
        $emailCopy = $this->emailAddress;
        $scrEntity = new SubscriberEntity(
        new EmailAddress( $this->config, $this->emailAddress ),
        new Password( $this->config, 'IHAVEANEASYPASSWORD' )
    );
    $this->subscriber->save( $scrEntity );

    return array( 'id' => $scrEntity->id, 'email' => $emailCopy );
    }

    /**
    * @depends testSave
    * @param SubscriberEntity $scrEntity [description]
    */
    public function testGetSubscriber( array $vars )
    {
        $scrEntity = $this->subscriber->getSubscriber( $vars['id'] );
        // Check that the saved passwords can be retrieved and are equal
        $this->assertEquals(
        $scrEntity->password->getEncryptedPassword()
        , $scrEntity->password->getEncryptedPassword()
    );
    // Check that retrieved email matches what was set
    $this->assertEquals(
    $vars['email']
    , $scrEntity->email_address->getAddress()
    );

    // Delete the testing subscribers
    // NOTE: These entities are used in other tests and must be deleted in
    // whatever method users them last
    $this->subscriber->delete( $vars['id'] );

    return $scrEntity;
    }
}
