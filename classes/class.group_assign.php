<?php
define('VERSION_GROUP_ASSIGN', '1.0.3');
/*
Version History:
  1.0.3 (2016-01-16)
    1) Method get_selector_sql() is now statically defined
    2) Now more PSR-2 compliant
*/
class Group_Assign extends Record
{
    const VERSION = '1.0.3';
    
    public function __construct($ID = "")
    {
        parent::__construct("group_assign", $ID);
    }

    public function delete_for_assignment($assign_type, $assignID)
    {
        $sql =
             "DELETE FROM\n"
            ."  `group_assign`\n"
            ."WHERE\n"
            ."  `assign_type` = \"$assign_type\" AND\n"
            ."  `assignID` IN($assignID)";
  //    z($sql);
        $this->do_sql_query($sql);
    }

    public function delete_for_group($groupID)
    {
        $sql =
             "DELETE FROM\n"
            ."  `group_assign`\n"
            ."WHERE\n"
            ."  `groupID` IN($groupID)";
  //    z($sql);
        $this->do_sql_query($sql);
    }

    public static function get_selector_sql()
    {
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        if (!$isMASTERADMIN) {
            return
                 "SELECT\n"
                ."  `groups`.`name` AS `text`,\n"
                ."  `groups`.`ID` AS `value`\n"
                ."FROM\n"
                ."  `groups`\n"
                ."WHERE\n"
                ."  `groups`.`systemID` = ".SYS_ID."\n"
                ."ORDER BY\n"
                ."  `text`";
        }
        return
             "SELECT\n"
            ."  IF(`system`.`ID`=1,'e0e0e0',IF(`system`.`ID`=".SYS_ID.",'c0ffc0','ffe0e0')) AS `color_background`,\n"
            ."  CONCAT('[',`system`.`textEnglish`,'] ',`groups`.`name`) AS `text`,\n"
            ."  `groups`.`ID` AS `value`\n"
            ."FROM\n"
            ."  `groups`\n"
            ."INNER JOIN `system` ON\n"
            ."  `groups`.`systemID` = `system`.`ID`\n"
            ."ORDER BY\n"
            ."  `text`";
    }

    public function set_for_assignment($assign_type, $assignID, $group_csv, $systemID = SYS_ID)
    {
        $this->delete_for_assignment($assign_type, $assignID);
        if (!$group_csv) {
            return;
        }
        $group_csv_arr = explode(",", $group_csv);
        for ($i=0; $i<count($group_csv_arr); $i++) {
            $data = array(
                'assign_type' =>    $assign_type,
                'assignID' =>       $assignID,
                'groupID' =>        $group_csv_arr[$i],
                'systemID' =>       $systemID
            );
            $this->insert($data);
        }
    }
}
