<?php
define("DB_CONNECT", "1.0.4");
/*
Version History:
  1.0.4 (2021-03-04)
    1) Preparation to work with Mysql 8
*/

function db_connect()
{
    global $db, $dsn, $li, $Obj_MySQLi;
    $b = parse_url($dsn);
    $db = trim($b['path'], '/');
    @$Obj_MySQLi = new MySQLi($b['host'], $b['user'], $b['pass'], $db);
    if ($Obj_MySQLi -> connect_errno > 0) {
        die("<b>Fatal error:</b><br />\n".$Obj_MySQLi->connect_error);
    }
    $li =  mysqli_connect($b['host'], $b['user'], $b['pass']);
    mysqli_select_db($li, $db);
//    $Obj_MySQLi->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
}
db_connect();
