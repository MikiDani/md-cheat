<?php
//echo "http://".$_SERVER['HTTP_HOST']."/".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."<br>";
//echo "http://".$_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI']."<br>";
/*
echo "indata! <br>";
print_r($indata);
echo "QUERY_STRING:<br>";
if (isset($_SERVER['QUERY_STRING'])) {
    $querytext = strtok($_SERVER['QUERY_STRING'], '=');
} else {
    $querytext = null;
}

echo  $querytext;


if (isset($_GET['users']) && $_GET['login'] == 'login') {
    echo "Login bent!!!";
}
*/
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
//print_r(headers_list());


//$indata = json_decode(file_get_contents('php://input'));
//echo json_encode($indata);
//echo json_encode(file_get_contents('php://input'));

$valami = ["name"=>"Dani", "pass"=>"1234"];

$indata = json_decode(file_get_contents('php://input'));

/*
if (isset($indata->usernameoremail)) { echo "usernameoremailx: ".$indata->usernameoremail."<br>"; }
if (isset($indata->userpassword)) { echo "userpasswordx: ".$indata->userpassword."<br>"; }
*/

$vegyes = [$valami, $indata];
echo json_encode($vegyes);
