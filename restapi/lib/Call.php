<?php

namespace Rapi;

/**
 * Class handle API call functionality and execution
 * @note This class is ignorant of call output and forwards it transparently
 */
class Call {

    public function __construct(
        Admin $admin
        , Lists $lists
        // , Campaigns $campaign
        , Response $response
        , \phpList\SubscriberManager $subscriberManager
        , Templates $templates
    )
    {
        $this->admin = $admin;
        $this->lists = $lists;
        // $this->campaign = $campaign;
        $this->subscriberManager = $subscriberManager;
        $this->response = $response;
        $this->templates = $templates;
    }
    /**
     * Validate a requested call
     * @param
     * @return bool
     */
    public function validateCall( $className, $method )
    {
        // Default result to true / pass
        $result = true;
        $subjects = array( $className, $method );

        // Loop through items to be tested
        foreach ( $subjects as $subject ) {
            // Check for non-word characters
            if ( preg_match( '/\W/', $subject ) ) {
                $result = false;
            }
        }

        // Check that the name of the method uses valid syntax
        if ( ! is_callable( $method, true ) ) {
            $result = false;
        }

        return $result;
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

        // Format the parameters
        $formattedParams = $this->formatParams( $argumentsArray );

        // Execute the desired action
        $result = call_user_func_array( array( $this->$className, $method ), $formattedParams );

        return $result;
    }

    public function formatParams( array $argumentsArray ) {

        // Remove unnecessary params
        unset( $argumentsArray['className'] );
        unset( $argumentsArray['method'] );

        // Sort the parameters alphbetically
        ksort( $argumentsArray );
        
        return $argumentsArray;
    }
}
