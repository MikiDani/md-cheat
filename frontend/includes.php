<?php

// LOGIN
if (isset($_POST['login_submit'])) {
    unset($_POST['login_submit']);
    if (!empty($_POST['login_nameoremail']) && !empty($_POST['login_password'])) {
    
        $robotbutton = isset($_POST['login_robotbutton']) ? "on" : "off" ;
        
        if ($robotbutton=='on') {
            
            $data = ['usernameoremail' => $_POST['login_nameoremail'], 'userpassword' => $_POST['login_password']];
            unset($_POST['login_nameoremail']); unset($_POST['login_password']);
            
            $response = api_query_use('none', 'POST', '?users=login', $data, true);

            
            if ($response->status_code==200) {
                // login
                $logintimeend = $response->response_text->tokendata->tokenepochend;
                $tokencode = $response->response_text->tokendata->tokencode;
                $userdatas = api_query_use($tokencode, 'GET', '?users=userdata', 'none', false);
                
                setcookie("login", $tokencode, $logintimeend);

                $_SESSION['messages']=[];
                
                header("Refresh:0; url=index.php");

            } else {
                $login_error=$response->response_text;
            }
            
        } else { $login_error="Robot button is not selected!"; }
    } else { $login_error="Inputs are not filled!"; }
}

// LOG OUT
if (isset($_POST['logout_submit'])) {
    unset($_POST['logout_submit']);
    
    // del uderid-s about roomusers table.
    $userdatas = api_query_use($_COOKIE['login'], 'GET', '?users=userdata', 'none', false);
        
    $data = ['userid' => $userdatas->userid];
    $useriddelrooms = api_query_use('none', 'POST', '?cheat=useriddelrooms', $data, true);
    
    //deleting user cookie
    setcookie('login', "", time()-3600);
    
    header("Refresh:0; url=index.php");
}

// functions

function post_value($value) {
    if (isset($_POST[$value])) { echo $_POST[$value]; }
}

// use the backend api_query in this project
function api_query_use($token, $method, $query, $data, $backdata) {

    $response = api_query($token, $method, $query, $data);

    if ($backdata) {
        return $response;
    } else {
        if ($response->status_code==200 || $response->status_code==201) {
            return $response->response_text;
        } else {
            $backerror = $response->response_text;
            setcookie("login", "", time() - 3600);
            
            header("Refresh:0; url=index.php?backerror=$backerror");
        }
    }

}