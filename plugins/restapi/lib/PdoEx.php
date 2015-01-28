<?php

namespace Rapi;

/**
 * Wrapper class for stock PDO{}
 * @return PDO Configured PDO connection
 */
class PdoEx extends \PDO {

    public function __construct( $hostname, $username, $pass, $dbname )
    {
        // NOTE: If this constructor isn't present, the parent class'
        // constructor will automatically be called.

        // Create a PDO object
        parent::__construct( "mysql:host=$hostname;dbname=$username", $pass, $dbname );

        // Configure connection parameters
        $this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }
}
