<?php

namespace Rapi;

class Common {

    // Extended pdo object
    protected $pdoEx;

    public function __construct( PdoEx $pdoEx, Response $response )
    {
        $this->pdoEx = $pdoEx;
    }

    /**
     * Execute an SQL select query and generate Response object
     * @param  string $type   [description]
     * @param  string $sql SQL string to execute
     * @param  bool $single Whether only one record should be returned
     * @return Response $response Generated Response object
     */
    public function select( $type, $sql, $single = false )
    {
        $response = new Response();
        try {
            $stmt = $this->pdoEx->query( $sql );
            $result = $stmt->fetchAll( \PDO::FETCH_OBJ );

            // If just one result is requested and more are available, use 1st
            if (
                $single && is_array( $result )
                && isset( $result[0] )
            ) {
                $result = $result[0];
            }

            $response->setData( $type, $result );
        } catch( \Rapi\PDOException $e ) {
            $response->setError( $e->getCode(), $e->getMessage() );
        }
        return $response;
    }

    /**
     * Generate a URL for executing API calls
     * @param [type] $website [description]
     */
    public function apiUrl( $website, $pageRoot, $adminDir )
    {
        $protocol = '';
        // If server is using SSL rewrite URI accordingly
        if( !empty( $_SERVER['HTTPS'] ) ) {
            if( $_SERVER['HTTPS'] !== 'off' ) {
                $protocol = 'https://'; //https
            } else {
                $protocol = 'http://'; //http
            }
        } else {
            $protocol = 'http://'; //http
        }

        // Generate the path plus get vars
        $path = $pageRoot . $adminDir . '?page=call&pi=restapi';

        $url = $protocol . $website . $path;
        $trimmedUrl = rtrim( $url, '/' );

        return $trimmedUrl;
    }

}
