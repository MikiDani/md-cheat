<?php
session_start();

if (isset($_COOKIE['login'])) {

    require "../backend/api_includes.php";
    require "includes.php";

    function draw_monitor($roomusers, $messages) {

        echo "<div class='bg-warning m-0 p-0 ps-2 mb-2 rounded-top'>";
        foreach($roomusers as $user) {
            echo "<span><strong>".$user->username.": </strong>".$user->useremail."</span> ";
        }
        echo"</div>";
        
        echo "<div class='row bg-light m-0 p-0 mb-2 rounded-bottom'>";
        foreach($messages as $row) {

            $converttime = new DateTime($row->messagedatetime);
            $drawtime=$converttime->format('H:i:s');

            echo "<div class='w-75 border-top'><strong>".$row->username.": </strong>".$row->message."</div>
            <div class='w-25 border-top float-right text-end text-secondary'>".$drawtime."</div>";
        }
        echo"</div>";
    }

    $userdatas = api_query_use($_COOKIE['login'], 'GET', '?users=userdata', 'none', false);

    $data = ['userid' => $userdatas->userid, 'roomid' => 1];

    $query = api_query_use($_COOKIE['login'], 'POST', '?cheat=refresh', $data, true);

    if ($query->status_code==200) {

        // checks if the last message is also included in the array
        if (empty($_SESSION['messages'])) { 
            array_push($_SESSION['messages'], $query->response_text->lastmessage);
        }

        // see the session message length
        if (count($_SESSION['messages'])>0) { $countnumber = count($_SESSION['messages'])-1;} else { $countnumber = 0; }

        if ($_SESSION['messages'][$countnumber]->messageid !== $query->response_text->lastmessage->messageid) {
            array_push($_SESSION['messages'], $query->response_text->lastmessage);
        }

        $roomusersid = $query->response_text->roomusers;
        
        $roomusers = [];
        foreach($roomusersid as $userid) {
            $data = ['userid' => $userid];
            $userdata = api_query_use($_COOKIE['login'], 'POST', '?users=userdataid', $data, false);

            array_push($roomusers, $userdata);
        }
        
        draw_monitor($roomusers, $_SESSION['messages']);

    }

}