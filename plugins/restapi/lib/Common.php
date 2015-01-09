<?php

namespace Rapi;

class Common {

    /**
     * Execute an SQL select query and generate Response object
     * @param  string $type   [description]
     * @param  string $sql SQL string to execute
     * @param  bool $single Whether only one record should be returned
     * @return Response $response Generated Response object
     */
    function select( $type, $sql, $single=false )
    {
        $response = new \Rapi\Response();
        try {
            $db = \Rapi\Pdo::getConnection();
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll( \Pdo::FETCH_OBJ );
            $db = null;

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
    static function apiUrl( $website )
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

        // Rewrite current page URL to API call URL; check for test URIs also
        $rewrittenReqUri1 = str_replace( 'page=main&pi=restapi_test', 'page=call&pi=restapi', $_SERVER['REQUEST_URI'] );
        $rewrittenReqUri2 = str_replace( 'page=main&pi=restapi', 'page=call&pi=restapi', $rewrittenReqUri1 );

        $url = $protocol . $website . $rewrittenReqUri2;
        $trimmedUrl = rtrim( $url, '/' );

        return $trimmedUrl;
    }

}
