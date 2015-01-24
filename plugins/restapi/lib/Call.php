<?php

namespace Rapi;

class Call {
    /**
     * Check that the requested command is callable
     * @param string $cmd Command to execute
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
     * @param Actions $actions
     * @param Lists $lists
     * @param Messages $messages
     * @param Subscribers $subscribers
     * @param Templates $templates
     */
    public function doCall( $cmd, $actions, $lists, $messages, $subscribers, $templates )
    {
        $handlers = array( $actions, $lists, $messages, $subscribers, $templates );

        foreach( $handlers as $handler ) {
            // Check if the handler class has the requested method
            if ( method_exists( $handler, $cmd ) ) {
                // Make the call
                $handler->$cmd();
            }
        }

        //If no command found, return error message!
        $response->outputErrorMessage( 'No function for provided [cmd] found!' );
    }
}
