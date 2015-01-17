<?php

namespace Rapi;

/**
 * Wrapper class for stock PDO{}
 * @return PDO Configured PDO connection
 */
class PdoEx extends \PDO {

    public function __construct()
    {
        // Set necessary parameters from globals for the connection
        $dbhost = $GLOBALS['database_host'];
        $dbuser = $GLOBALS['database_user'];
        $dbpass = $GLOBALS['database_password'];
        $dbname = $GLOBALS['database_name'];

        // Create a PDO object
        parent::__construct( "mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass );

        // Configure connection parameters
        $this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }
}
