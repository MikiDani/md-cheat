<?php

define("host", "localhost");
define("username", "root");
define("pwd", "");
define("dbname", "mdcheat");

define("mysql", "mysql:dbname=".dbname.";host=".host);



$pdo = new PDO(mysql, username, pwd);
