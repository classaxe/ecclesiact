<?php
define('VERSION_TABLE','1.0.6');
/*
Version History:
  1.0.6 (2024-03-28)
    1) Updates to Table::get_table_create_sql() to normalise for newer database engines
*/
class Table extends Record{
    static $cache_create_table_array =  array();

    function __construct($table="") {
        if ($table!="") {
            $this->_set_table_name($table);
            $this->_set_object_name('Table');
        }
    }

    function get_checksum($ignore_custom_fields = true) {
        $sql = $this->get_table_create_sql($this->table, true, true);
        if ($ignore_custom_fields) {
            $sql = preg_replace('/  `cus_([^\n])*\n/','',$sql);
        }
        return crc32($sql);
    }

    function get_table_create_sql($name, $remove_autonum=true, $normalise=true) {
        if (isset(Table::$cache_create_table_array[$name])) {
            return Table::$cache_create_table_array[$name];
        }
        $sql =      "SHOW CREATE TABLE ".$name."";
        $record =   $this->get_record_for_sql($sql);
        $out =      $record['Create Table'];

        if ($normalise) {
            $out = str_replace('default', 'DEFAULT', $out);
            $out = str_replace('PRIMARY KEY  ', 'PRIMARY KEY ', $out);
            $out = str_replace('auto_increment', 'AUTO_INCREMENT', $out);
            $out = str_replace(' DEFAULT 0,', ' DEFAULT \'0\',', $out);
            $out = str_replace(' DEFAULT 0.25,', ' DEFAULT \'0.25\',', $out);
            $out = str_replace(' DEFAULT 1,', ' DEFAULT \'1\',', $out);
            $out = str_replace(' DEFAULT 3,', ' DEFAULT \'3\',', $out);
            $out = str_replace(' DEFAULT 25,', ' DEFAULT \'25\',', $out);
            $out = str_replace(' DEFAULT 110,', ' DEFAULT \'110\',', $out);
            $out = str_replace(' DEFAULT 0.00', ' DEFAULT \'0.00\'', $out);
            $out = str_replace(' text DEFAULT NULL,', ' text,', $out);
            $out = str_replace(' CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci', ' CHARSET=utf8', $out);
        }
        if ($remove_autonum) {
          $out =      preg_replace('/ AUTO_INCREMENT=([0-9])*' . '/', '', $out);
        }
        Table::$cache_create_table_array[$name] = $out;
        //    z($out);die;
        return $out;
    }

    function get_tables_names() {
        $sql =      "SHOW TABLE STATUS";
        $records =  $this->get_records_for_sql($sql);
        $out =      array();
        foreach ($records as $record) {
            $out[] =  array('Name'=>$record['Name']);
        }
        return $out;
    }

    function hasSystemID($table){
        $sql =  "SHOW COLUMNS FROM `".$table."`";
        $rows = $this->get_records_for_sql($sql);
        foreach ($rows as $row) {
            if ($row['Field'] == 'systemID') {
                return true;
            }
        }
        return false;
    }

    public static function getVersion(){
        return VERSION_TABLE;
    }
}