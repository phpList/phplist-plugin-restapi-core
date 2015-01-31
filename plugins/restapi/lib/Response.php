<?php

namespace Rapi;

/**
 * Common response as success and error
 * Andreas Ek, 2012-12-26
 */

class Response {

    private $result;

    /**
     * Initialise empty vars
     */
    function __construct()
    {
        $this->result = array();
    }

    /**
     * Save error and error code inside response
     * @param string $code error code to store
     * @param string $message error message to store
     */
    function setError( $code, $message )
    {
        $this->result['status'] = 'error';
        $this->result['type'] = 'Error';
        $this->result['data'] = array (
            'code' => $code,
            'message' => $message
        );
    }

    /**
     * Save data inside response
     * @param string $type data type
     * @param string $data data to be stored
     * @return NULL
     */
    function setData( $type, $data )
    {
        $this->result['status'] = 'success';
        $this->result['type'] = $type;
        $this->result['data'] = $data;
    }

    /**
     * Print error message as JSON and die
     * @return NULL
     */
    function output()
    {
        header( 'Content-Type: application/json' );
        echo $this->jsonEncodeIm( $this->result );
        die( 0 );
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

    /**
     * Convert a value to JSON - improved implementation over stock PHP
     *
     * This function returns a JSON representation of $param. It uses json_encode
     * to accomplish this, but converts objects and arrays containing objects to
     * associative arrays first. This way, objects that do not expose (all) their
     * properties directly but only through an Iterator interface are also encoded
     * correctly.
     */
    function jsonEncodeIm( $param )
    {
        if ( is_object( $param ) || is_array( $param ) ) {
            $param = $this->objectToArray( $param );
        }
        return json_encode( $param );
    }

    static function outputError( $e ){
        $response = new Response();
        $response->setError( $e->getCode(), $e->getMessage() );
        $response->output();
    }

    static function outputErrorMessage( $message ){
        $response = new Response();
        $response->setError( 0, $message );
        $response->output();
    }

    static function outputDeleted( $type, $id ){
        $response = new Response();
        $response->setData( $type, 'Item with ' . $id . ' is successfully deleted!' );
        $response->output();
    }

    static function outputMessage( $message ){
        $response = new Response();
        $response->setData( 'SystemMessage', $message );
        $response->output();
    }

}
