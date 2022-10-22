<?php
// USERS API

if ($_SERVER['REQUEST_METHOD']=="GET") {
    
    if ($_GET['users'] == 'userdata') {
        
        $token = apache_request_headers()['token'];

        $stmt = $pdo->prepare("SELECT * FROM users
        INNER JOIN tokens ON users.userid = tokens.userid
        WHERE tokencode=?");
        $stmt->execute([$token]);
        $userdata = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data=['response_text' => $userdata, 'status_code' => 200];
    }
    
}

if ($_SERVER['REQUEST_METHOD']=="POST") {
    
    $indata = json_decode(file_get_contents('php://input'));
    
    if ($_GET['users'] == 'login') {
        if (isset($indata->usernameoremail) && !empty($indata->usernameoremail)) {

            $userdata = user_identification($pdo, $indata);
            if ($userdata==false) { $checkonline=false; } else {
                $checkonline = check_online($pdo, $userdata['userid']);
            }
            
            if ($checkonline==false) {
                if ($userdata) {
                    // Identification correct
                    $tokendata = token_inquest($pdo, $userdata);
                    
                    $alldatas = [
                        'tokendata' => $tokendata,
                        'userdata' => $userdata
                    ];
                    
                    return $data=['response_text' => $alldatas, 'status_code' => 200];
                    
                } else { return $data=['response_text' => 'Autorization error.', 'status_code' => 401]; }
            } else { return $data=['response_text' => 'The user is logged in!', 'status_code' => 400]; }
        } else { return $data=['response_text' => 'Input is exist!', 'status_code' => 400]; }
    }

    if ($_GET['users'] == 'insert') {
        if (isset($indata->username) && !empty($indata->username) && isset($indata->useremail) && !empty($indata->useremail) && isset($indata->userpassword) && !empty($indata->userpassword) && isset($indata->userrank) && !empty($indata->userrank)) {

            $write=true;
            $errortext="";

            if (see_exists($pdo, $indata->username, 'users', 'username')) {
                $write=false;
                $errortext.="The username exists in the database.";
            }

            if (see_exists($pdo, $indata->useremail, 'users', 'useremail')) {
                $write=false;
                $errortext.="The email exists in the database.";
            }
            
            if ($write) {
                $time = time();
                $datetime = new DateTime("@$time");
                $userdatetime = $datetime->format('Y-m-d H:i:s');

                $stmt=$pdo->prepare("INSERT INTO users (username, useremail, userpassword, userrank, userdatetime) VALUES (?,?,?,?,?)");
                if ($stmt->execute([$indata->username, $indata->useremail, $indata->userpassword, $indata->userrank, $userdatetime])) {
                    return $data=['response_text' => 'User inserted the database.', 'status_code' => 200];
                } else {
                    return $data=['response_text' => 'Insert sql error.', 'status_code' => 400];
                }
                
            } else { return $data=['response_text' => $errortext, 'status_code' => 400]; }
        
        } else { return $data=['response_text' => 'Input data is missing.', 'status_code' => 400]; }

    }

    if ($_GET['users'] == 'userdataid') {

        if (isset($indata->userid) && !empty($indata->userid)) {
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE userid=?");
            $stmt->execute([$indata->userid]);

            $userdata = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userdata) {
                return $data=['response_text' => $userdata, 'status_code' => 200];
            } else { return $data=['response_text' => 'No have user this id.', 'status_code' => 400]; }

        } else { return $data=['response_text' => 'Input not exist.', 'status_code' => 400]; }
    }


}

//-- functions start --//

function user_identification ($pdo, $indata) {
    $stmt = $pdo->prepare('SELECT userid, username, useremail, userpassword FROM users');
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($userdata = $stmt->fetch()) {
        if ($userdata['username'] == $indata->usernameoremail || $userdata['useremail'] == $indata->usernameoremail) {
            if ($userdata['userpassword'] == $indata->userpassword) {
                return $userdata;
            }
        }
    }
    return false;
}

function token_inquest ($pdo, $userdata) {
    $stmt = $pdo->prepare('SELECT * FROM tokens WHERE userid = ?');
    $stmt->execute([$userdata['userid']]);
    $tokendata = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $time = time();
    $datetime = new DateTime("@$time");
    $tokendatetimestart = $datetime->format('Y-m-d H:i:s');
    $datetimeplus = strtotime("+6 hours");
    $datetimeend = new DateTime("@$datetimeplus");
    $tokendatetimeend = $datetimeend->format('Y-m-d H:i:s');

    $token = md5($time.$userdata['username']);

    $backtokendata = [
        'tokenepochstart' => $time,
        'tokenepochend' => $datetimeplus,
        'tokencode' => $token
    ];
    
    if ($tokendata) {
        // Old token owerwrite
        $tokenid = $tokendata['tokenid'];
        $stmt = $pdo->prepare('UPDATE tokens SET tokencode=:token, tokendatetimestart=:tokendatetimestart, tokendatetimeend=:tokendatetimeend WHERE tokenid=:tokenid');
        $stmt->execute(['token' => $token, 'tokendatetimestart' => $tokendatetimestart, 'tokendatetimeend' => $tokendatetimeend, 'tokenid' => $tokenid]);
        return $backtokendata;
        
    } else {
        // Insert new tokencode in table
        $userid = $userdata['userid'];
        $stmt = $pdo->prepare('INSERT INTO tokens (userid, tokencode, tokendatetimestart, tokendatetimeend) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userid, $token, $tokendatetimestart, $tokendatetimeend]);
        return $backtokendata;
    }
}

function see_exists($pdo, $value, $table, $tablerow) {

    $stmt = $pdo->prepare("SELECT * FROM $table WHERE $tablerow=?");
    $stmt->execute([$value]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        return true;
    } else {
        return false;
    }
}

function check_online($pdo, $userid) {

    // echo "userid: "; print_r($userid);

    $stmt = $pdo->prepare("SELECT * FROM roomusers WHERE userid=?");
    $stmt->execute([$userid]);

    $row = $stmt->fetchall(PDO::FETCH_ASSOC);

    if ($row) {

        //print_r($row[0]);
                
        $lasttime = $row[0]['activeepoch']+5;
        $timenow = time();
        
        if ($timenow < $lasttime) {
            // user logged in.
            return true;  
        } else {
            // no have user in roomusers. Go login.
            return false;
        }
    }

}