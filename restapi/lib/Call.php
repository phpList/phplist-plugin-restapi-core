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

    /**
     * Convert any var type to an array suitable for passing to a response
     * @param mixed $callResult Returned value of an executed API call
     */
    public function callResultToArray( $callResult )
    {
        $varType = gettype( $callResult );

        switch( $varType ) {
            case 'array':
                // Nothing to do, var is already correct type
                return $callResult;
            case 'object':
                // Convert object to array
                $objectToArray = $this->objectToArray( $callResult );
                return $objectToArray;
            case 'resource':
                // Resource vars probably aren't useful; generate error
                throw new \Exception( 'Forbidden variable type returned by call: \'' . $varType . '\'' );
        }

        // Looks like the the var must be simple (string/int/bool etc.)
        // Put the var in a simple array
        $callResultArray = array( $callResult );

        return $callResultArray;
    }

    /**
    * Convert an object into an associative array
    *
    * This function converts an object into an associative array by iterating
    * over its public properties. Because this function uses the foreach
    * construct, Iterators are respected. It also works on arrays of objects.
    *
    * @return array
    */
    function objectToArray( $var )
    {
        $result = array();
        $references = array();

        // loop over elements/properties
        foreach ( $var as $key => $value ) {
            // recursively convert objects
            if (is_object( $value) || is_array( $value ) ) {
                // but prevent cycles
                if (!in_array( $value, $references ) ) {
                    $result[$key] = $this->objectToArray( $value );
                    $references[] = $value;
                }
            } else {
                // simple values are untouched
                $result[$key] = utf8_encode( $value );
            }
        }
        return $result;
    }
}
