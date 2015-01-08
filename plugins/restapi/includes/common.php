<?php

namespace phpListRestapi;

defined('PHPLISTINIT') || die;

class Common {

    /**
     * Execute an SQL select query and generate Response object
     * @param  [type] $type   [description]
     * @param  [type] $sql    [description]
     * @param  [type] $single [description]
     * @return [type]         [description]
     */
    static function select( $type, $sql, $single=false )
    {
        $response = new Response();
        try {
            $db = PDO::getConnection();
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            if ($single && is_array($result) && isset($result[0])) $result = $result[0];
            $response->setData($type, $result);
        } catch( \PDOException $e ) {
            $response->setError( $e->getCode(), $e->getMessage() );
        }
        $response->output();
    }

    /**
     * Generate a URL for executing API calls
     * @param [type] $website [description]
     */
    static function apiUrl( $website )
    {
        $protocol = '';
        if( !empty( $_SERVER["HTTPS"] ) ){
            if($_SERVER["HTTPS"]!=="off")
                $protocol = 'https://'; //https
            else
                $protocol = 'http://'; //http
        }
        else
            $protocol = 'http://'; //http

        $api_url = str_replace( 'page=main&pi=restapi_test', 'page=call&pi=restapi', $_SERVER['REQUEST_URI'] );
        $api_url = str_replace( 'page=main&pi=restapi', 'page=call&pi=restapi', $api_url );

        $url = $protocol . $website . $api_url;
        $trimmedUrl = rtrim( $url, '/' );

        return $trimmedUrl;
    }

}
