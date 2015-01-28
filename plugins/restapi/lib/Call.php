<?php

namespace Rapi;

/**
 * Class handle API call functionality and execution
 * @note This class is ignorant of call output and forwards it transparently
 */
class Call {

    public function __construct( Actions $actions, Lists $lists, Messages $messages, Response $response, Subscribers $subscribers, Templates $templates )
    {
        $this->actions = $actions;
        $this->lists = $lists;
        $this->messages = $messages;
        $this->subscribers = $subscribers;
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
        if ( empty( $cmd ) ){
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
     * @param string $cmd command to execute
     * @param array $params Parameters required for the call, e.g. list ID
     */
    public function doCall( $cmd, array $params )
    {
        // Create array of handler classes for processing
        $handlers = array( $this->actions, $this->lists, $this->messages, $this->subscribers, $this->templates );

        foreach( $handlers as $handler ) {
            // Check if the handler class has the requested method
            if ( method_exists( $handler, $cmd ) ) {
                // Make the call
                $result = $handler->$cmd( $params );
                // End the loop and pass on the output from the called method
                return $result;
            }
        }

        //If no command found, return error message!
        $this->response->outputErrorMessage( 'No function for provided [cmd] found!' );
    }
}
