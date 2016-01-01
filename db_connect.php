<?php
define("DB_CONNECT", "1.0.3");
/*
Version History:
  1.0.3 (2016-01-01)
    1) Replaced mysql calls with mysqli calls
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
}
db_connect();
