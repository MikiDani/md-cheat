<?php

require "session_url.php";

function api_query($token, $method, $request, $data) {

    $headers = array("token: $token","Content-Type: application/json");
    $options = array(
        "http" => array(
            "method" => $method,
            "header" => $headers,
            "content" => json_encode($data)
        )
    );
    
    $context = stream_context_create($options);
    $result = file_get_contents($_SESSION['url'].$request, false, $context);
    $response = json_decode($result);

    return $response;
}