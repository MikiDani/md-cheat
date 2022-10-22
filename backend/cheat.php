<?php
// USERS API

if ($_SERVER['REQUEST_METHOD']=="POST") {
    
    $indata = json_decode(file_get_contents('php://input'));

    if ($_GET['cheat'] == 'refresh') {

        if (isset($indata->userid) && !empty($indata->userid) && isset($indata->roomid) && !empty($indata->roomid)) {
            
            $userid = $indata->userid;
            $roomid = $indata->roomid;
            $usercheck = data_load($pdo, 'users', 'userid', $userid);
            $roomcheck = data_load($pdo, 'rooms', 'roomid', $roomid);
            //echo "userdatas: "; print_r($usercheck); echo "roomdatas: "; print_r($roomcheck);
            if (($usercheck) && ($roomcheck)) {

                //insert user in roomusers
                $stmt = $pdo->prepare("SELECT * FROM roomusers WHERE roomid=? AND userid=?");
                $stmt->execute([$roomid, $userid]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $newepoch=time()+3;

                if ($row) {
                    //refresh
                    $stmt = $pdo->prepare("UPDATE roomusers SET activeepoch=? WHERE roomid=? AND userid=?");
                    $stmt->execute([$newepoch, $roomid, $userid]);
                } else {
                    //insert
                    $stmt = $pdo->prepare("INSERT INTO roomusers (roomid, userid, activeepoch) VALUES (?, ?, ?)")->execute([$roomid, $userid, $newepoch]);
                }
                
                //delete all timeout roomusers
                $roomusers = roomusers_load($pdo, $roomid, '*');
                $timenow = time();
                foreach($roomusers as $list) {
                    if ($list['activeepoch'] < $timenow) {
                        $stmt = $pdo->prepare("DELETE FROM roomusers WHERE roomid=? AND userid=?");
                        $stmt->execute([$list['roomid'], $list['userid']]);
                    }
                }
                
                //actual roomusers load
                $loadroomusers = roomusers_load($pdo, $roomid, 'userid');
                $roomusers=array();
                foreach ($loadroomusers as $user) { foreach ($user as $key => $value) { array_push($roomusers, $value); } }

                //last message load
                $stmt = $pdo->prepare("SELECT messages.roomid, messages.messageid, messages.messagedatetime, users.userid, users.username, users.userrank, messages.message FROM messages
                INNER JOIN users ON users.userid = messages.userid
                WHERE roomid=? ORDER BY messageid DESC LIMIT 1");
                $stmt->execute([$roomid]);
                $lastmessage = $stmt->fetch(PDO::FETCH_ASSOC);

                $refreshdata = ['roomusers' => $roomusers, 'lastmessage' => $lastmessage];
                                
                return $data=['response_text' => $refreshdata, 'status_code' => 200];

            } else { return $data=['response_text' => 'Input id-s are not correct.', 'status_code' => 400]; }
        } else { return $data=['response_text' => 'inputs are missing.', 'status_code' => 400]; }

    }
    
    if ($_GET['cheat'] == 'insertmessage') {

        if (isset($indata->messagetext) && !empty($indata->messagetext)) {
            //echo $indata->messagetext;

            $userdatas = data_load($pdo, 'users', 'userid', $indata->userid);
            $roomdatas = data_load($pdo, 'rooms', 'roomid', $indata->roomid);

            if (!empty($userdatas) && !empty($roomdatas)) {

                //date_default_timezone_set("Europe/Budapest");
                $time = strtotime("+2 hours"); $datetime = new DateTime("@$time");
                $messagedatetime = $datetime->format('Y-m-d H:i:s');

                $stmt = $pdo->prepare("INSERT INTO messages (userid, roomid, message, messagedatetime) VALUES (?,?,?,?)");
                if ($stmt->execute([$indata->userid, $indata->roomid, $indata->messagetext, $messagedatetime])) {
                    return $data=['response_text' => 'Message inserted.', 'status_code' => 200];
                } else { return $data=['response_text' => 'Insert sql error.', 'status_code' => 400]; }

            } else { return $data=['response_text' => 'Input id-s are not correct.', 'status_code' => 400]; }

        }

    }

    if ($_GET['cheat'] == 'useriddelrooms') {

        if (isset($indata->userid) && !empty($indata->userid)) {
            
            $stmt= $pdo->prepare("DELETE FROM roomusers WHERE userid=?");
            $stmt->execute([$indata->userid]);

            return $data=['response_text' => 'userid-s deleted the roomusers table.', 'status_code' => 400];

        } else { return $data=['response_text' => 'Input id is missing.', 'status_code' => 400]; }

    }
 
}

//-- functions start --//

function roomusers_load($pdo, $roomid, $query) {
    $stmt = $pdo->prepare("SELECT $query FROM roomusers WHERE roomid=?");
    $stmt->execute([$roomid]);
    $roomusers = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $roomusers;
}

function data_load($pdo, $tablename, $idname, $idvalue) {
    $stmt = $pdo->prepare("SELECT * FROM $tablename WHERE $idname=?");
    $stmt->execute([$idvalue]);
    if ($datas = $stmt->fetchall(PDO::FETCH_ASSOC)) {
        return $datas;
    } else {
        return false;
    }

}