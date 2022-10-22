<?php
define("host", "localhost");
define("username", "mdcheat");
define("pwd", "123456");
define("dbname", "mdcheat");

define("mysql", "mysql:dbname=".dbname.";host=".host);

$pdo = new PDO(mysql, username, pwd);