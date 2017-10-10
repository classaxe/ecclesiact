<?php
/*
Version History:
  1.0.5 (2017-10-07)
    1) Now uses class constant for version control - necessary for child classes to use this method
    2) PSR-2 fixes
*/
class lst_named_type extends Record
{
    const VERSION = '1.0.5';

    public function __construct($ID = "", $listtype = "", $object_name = "")
    {
        parent::__construct('listdata', $ID);
        $this->_set_name_field('value'); // Default for exists_named() etc - may be overridden
        if ($listtype!="") {
            $this->_set_listtype($listtype);
            $Obj = new Record('listtype');
            $this->_set_listTypeID($Obj->get_ID_by_name($listtype));
        }
        if ($object_name) {
            $this->_set_object_name($object_name);
        }
    }

    public function get_listdata($sortBy = '')
    {
        $out = array();
        $sql =
             "SELECT\n"
            ."  `listdata`.*\n"
            ."FROM\n"
            ."  `listdata`\n"
            ."WHERE\n"
            ."  `listdata`.`listtypeID` = ".$this->_get_listtypeID()." AND\n"
            ."  `listdata`.`systemID` IN (1,".SYS_ID.")\n"
            ."ORDER BY\n  "
            .($sortBy!='' ?
                $sortBy
            :
                "  `seq`,"
                ."  `textEnglish`"
            );
        return $this->get_records_for_sql($sql);
    }

    public function get_text_for_value($value)
    {
        $sql =
             "SELECT\n"
            ."  `textEnglish`\n"
            ."FROM\n"
            ."  `listdata`\n"
            ."WHERE\n"
            ."  `listtypeID` = ".$this->_get_listtypeID()." AND\n"
            ."  `value` = \"".$value."\"\n"
            ."ORDER BY\n"
            ."  `systemID` = ".SYS_ID." DESC\n"
            ."LIMIT 0,1";
        return $this->get_field_for_sql($sql);
    }

    public function get_value_for_text($text)
    {
        $sql =
             "SELECT\n"
            ."  `value`\n"
            ."FROM\n"
            ."  `listdata`\n"
            ."WHERE\n"
            ."  `listtypeID` = ".$this->_get_listtypeID()." AND\n"
            ."  `textEnglish` = \"".$text."\"\n"
            ."ORDER BY\n"
            ."  `systemID` = ".SYS_ID." DESC\n"
            ."LIMIT 0,1";
        return $this->get_field_for_sql($sql);
    }

    public function export_sql($targetID, $show_fields)
    {
        return  $this->sql_export($targetID, $show_fields);
    }

    public function handle_report_copy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::try_copy($newID, $msg, $msg_tooltip, false);
    }
}
