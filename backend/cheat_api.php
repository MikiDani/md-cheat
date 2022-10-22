<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

/// Ha az apache nem modulként van telepítve : Stack Owerflow seítség 
/*
if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
    $arh = array();
    $rx_http = '/\AHTTP_/';
    foreach($_SERVER as $key => $val) {
        if( preg_match($rx_http, $key) ) {
        $arh_key = preg_replace($rx_http, '', $key);
        $rx_matches = array();
        // do some nasty string manipulations to restore the original letter case
        // this should work in most cases
        $rx_matches = explode('_', $arh_key);
        if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
            foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
            $arh_key = implode('-', $rx_matches);
        }
        $arh[$arh_key] = $val;
        }
    }
    return( $arh );
    }
}
*/
////

/*
print_r(apache_request_headers());
$indata = json_decode(file_get_contents('php://input')); echo json_encode($indata); 
exit;
*/

require_once "connection.php";
require "authorization.php";

$querytext = strtok($_SERVER['QUERY_STRING'], '=');

if ($querytext=='users') { require "users.php"; }
if ($querytext=='cheat') { require "cheat.php"; }

echo isset($data) ? json_encode($data) : false ;