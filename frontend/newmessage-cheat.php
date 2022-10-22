<?php
session_start();
require "../backend/api_includes.php";
require "includes.php";
$userdatas = api_query_use($_COOKIE['login'], 'GET', '?users=userdata', 'none', false);

$data = ['userid' => $userdatas->userid, 'roomid' => 1, 'messagetext' => $_POST['newmessage']];
$query = api_query_use($_COOKIE['login'], 'POST', '?cheat=insertmessage', $data, true);