<?php

// endpoints that do not require identification
if (isset($_SERVER['QUERY_STRING'])) {
    if (($_SERVER['REQUEST_METHOD']=="GET" && $_SERVER['QUERY_STRING']=='refresh') || 
        ($_SERVER['REQUEST_METHOD']=="POST" && $_SERVER['QUERY_STRING']=='users=login') ||
        ($_SERVER['REQUEST_METHOD']=="POST" && $_SERVER['QUERY_STRING']=='cheat=useriddelrooms') ||
        ($_SERVER['REQUEST_METHOD']=="POST" && $_SERVER['QUERY_STRING']=='users=insert')) {
        return;
    }
}

// check the active tokens
if (isset(apache_request_headers()['token'])) {
    $token = apache_request_headers()['token'];

    $stmt = $pdo->prepare("SELECT * FROM tokens WHERE tokencode=?");
    $stmt->execute([$token]);

    if ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
        $moment = time();       
        $tokenendepochtime = date_format(date_create($row['tokendatetimeend']), "U");
        // When the token has expired, it will refresh it.
        if ($moment > $tokenendepochtime) {
            $stmt2 = $pdo->prepare('DELETE FROM tokens WHERE tokenid=?');
            $stmt2->execute([$row['tokenid']]);
            echo json_encode($data=['response_text' => 'Request Timeout', 'status_code' => 408]);
            die();
        } else {
            return;
        }
    }
}

$data = ['response_text' => 'Unauthorized', 'status_code' => 401];
echo json_encode($data);
die();