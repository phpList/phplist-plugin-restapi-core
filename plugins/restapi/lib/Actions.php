<?php

namespace Rapi;

/**
 * Class for account related commands
 */
class Actions {

    public function __construct( Response $response )
    {
        $this->response = $response;
    }

    /**
     * Function to call for login.
     * @todo: Finish implementing this method
     * [*login] {string} loginname as an admin to phpList
     * [*password] {string} the password
     */
    public function login()
    {
        $this->response->outputMessage( 'Not implemented' );
    }

    /**
     * Processes the Message Queue in phpList.
     * @todo: Finish implementing this method
     * @note: Perhaps this is done via CRON or manually through the admin interface?
     * [*login] {string} loginname as an admin to phpList
     */
    public function processQueue()
    {
        $this->response->outputMessage( 'Not implemented' );
    }
}
