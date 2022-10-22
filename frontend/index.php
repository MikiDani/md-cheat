<?php
session_start();
require "../backend/api_includes.php";
require "includes.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Cheat portal</title>
</head>
<body class="bg-dark">
    <?php if (isset($_GET['backerror'])) { echo "<div class='text-white'>".$_GET['backerror']."</div>"; } ?>
    <div class="container container-fluid bg-warning shadow rounded p-3 mt-3">
        <h2 class="text-center">MD Cheat Login</h2>
        <div class="row">
            <?php
            if (!isset($_COOKIE['login'])) {
            ?>
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="login_nameoremail">Email address</label>
                    <input type="text" value="<?php post_value('login_nameoremail') ?>" class="form-control" id="login_nameoremail" name="login_nameoremail" placeholder="Name or Email..." autocomplete="off">
                </div>
                <div class="form-group mt-2">
                    <label for="login_password">Password</label>
                    <input type="password" value="<?php post_value('login_password') ?>" class="form-control" id="login_password" name="login_password" placeholder="Password...">
                </div>
                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="login_robotbutton" name="login_robotbutton">
                    <label class="form-check-label" for="exampleCheck1">Robot button</label>
                </div>
                <div class="text-center"><small id="loginerror" class="form-text text-muted"><?php echo isset($login_error) ? $login_error : false ; unset($login_error); ?></small></div>
                <div class="text-center mt-2"><button type="submit" name="login_submit" id="login_submit" class="btn btn-primary">Login</button></div>
            </form>
            <?php } else {
                $userdatas = api_query_use($_COOKIE['login'], 'GET', '?users=userdata', 'none', false);
                ?>
                <div>
                    <div class="text-center">
                        <span>Id: <?=$userdatas->userid?></span>
                        <span> <?=$userdatas->username?></span>
                        <span> | <?=$userdatas->useremail?><br></span>
                        <span>Start: <?=$userdatas->tokendatetimestart?><br></span>
                        <span>End: <?=$userdatas->tokendatetimeend?></span>
                    </div>
                    <div class="text-center mt-2"><form action="index.php" method="post"><button type="submit" name="logout_submit" id="logout_submit" class="btn btn-primary">Log out</button></form></div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
    if (isset($_COOKIE['login'])) {
    ?>
    <div id="cheatdiv" class="container container-fluid bg-secondary p-2 mt-3 rounded shadow">
        <?php 
        if (isset($_COOKIE['login'])) {
            include "cheat.php"; 
        }
        ?>
    </div>
    <?php 
    }
    ?>
</body>
</html>