<?php

namespace Rapi;

/**
 * Wrapper class for stock PDO{}
 * @return PDO Configured PDO connection
 */
class PdoEx extends \PDO {

    public function __construct()
    {
        // NOTE: This constructor is here to avoid automatic call to the parent
        // constructor of PDF, which requires parameters
    }

    public function connect( $hostname, $username, $pass, $dbname )
    {
        // Create a PDO object
        parent::__construct( "mysql:host=$hostname;dbname=$username", $pass, $dbname );

        // Configure connection parameters
        $this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }
}
