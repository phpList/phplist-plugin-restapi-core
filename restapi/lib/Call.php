<?php

namespace Rapi;

/**
 * Class handle API call functionality and execution
 * @note This class is ignorant of call output and forwards it transparently
 */
class Call {

    public function __construct(
        Actions $actions
        , Lists $lists
        // , Campaigns $campaign
        , Response $response
        , \phpList\SubscriberManager $subscriberManager
        , Templates $templates
    )
    {
        $this->actions = $actions;
        $this->lists = $lists;
        // $this->campaign = $campaign;
        $this->subscriberManager = $subscriberManager;
        $this->response = $response;
        $this->templates = $templates;
    }
    /**
     * Check that the requested command is callable
     * @param string $cmd Command to execute
     * @return bool
     */
    public function isCallable( $cmd )
    {
        // Remove any non-word characters
        $cmd = preg_replace( '/\W/','',$cmd );
        // Check if command is empty
        if ( empty( $cmd ) ) {
            return false;
        }

        // Check that the name of the method uses valid syntax
        if ( ! is_callable( $cmd, true ) ) {
            return false;
        }

        return true;
    }

    /**
     * Execute an api call
     * @param string $className to execute method on
     * @param string $method name of method to execute
     * @param array $argumentsArray arguments to pass to method
     * @return \phpList\Entity\SubscriberEntity Data object
     */
    public function doCall( $className, $method, array $argumentsArray )
    {
        // NOTE: Consider adding use of isCallable() here and making that method
        // private

        // Check if desired class is accessible as a property
        if ( ! property_exists( $this, $className ) ) {
            throw new \Exception( "Object '$className' is not an accessible Call class property" );
        }
        // Check that desired method is callable
        if ( ! is_callable( array( $this->$className, $method ) ) ) {
            throw new \Exception( "API call method '$method' not callable on object '$className'" );
        }
        // Execute the desired action
        $result = call_user_func_array( array( $this->$className, $method ), $argumentsArray );

        return $result;
    }
}
