<?php
/*
Version History:
  1.0.8 (2016-04-27)
    1) Refactored for PSR-2
*/
class MailIdentity extends Record
{
    const VERSION = '1.0.8';

    public function __construct($ID = "")
    {
        parent::__construct("mailidentity", $ID);
        $this->_set_name_field('name');
        $this->_set_object_name('Mail Identit');
        $this->set_plural_append('y', 'ies');
        $this->set_edit_params(
            array(
                'report' =>                 'mailidentity',
                'report_rename' =>          true,
                'report_rename_label' =>    'name'
            )
        );
    }

    public function exportSql($targetID, $show_fields)
    {
        return $this->sqlExport($targetID, $show_fields);
    }

    public static function getSelectorSQL()
    {
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        if ($isMASTERADMIN) {
            return
                 "SELECT\n"
                ."  1 as `seq`,\n"
                ."  '' AS `value`,\n"
                ."  '(None)' as `text`,\n"
                ."  'd0d0d0' as `color_background`\n"
                ."UNION SELECT\n"
                ."  2,\n"
                ."  `mailidentity`.`ID`,\n"
                ."  CONCAT(\n"
                ."    UPPER(`system`.`textEnglish`),' | ',`mailidentity`.`name`,\n"
                ."    IF (`mailidentity`.`email`!='',CONCAT(' [',`mailidentity`.`email`,']'),'')\n"
                ."  ),\n"
                ."  IF(`systemID`=1,'e0e0e0',IF(`systemID`=".SYS_ID.",'c0ffc0','ffe0e0'))\n"
                ."FROM\n"
                ."  `mailidentity`\n"
                ."INNER JOIN `system` ON\n"
                ."  `system`.`ID` = `mailidentity`.`systemID`\n"
                ."ORDER BY\n"
                ."  `seq`, `text`";
        }
        return
             "SELECT\n"
            ."  1 as `seq`,\n"
            ."  '' AS `value`,\n"
            ."  '(None)' as `text`,\n"
            ."  'd0d0d0' as `color_background`\n"
            ."UNION SELECT\n"
            ."  2,\n"
            ."  `mailidentity`.`ID`,\n"
            ."  CONCAT(\n"
            ."    IF(`mailidentity`.`systemID`=1,'* ',''),\n"
            ."    `mailidentity`.`name`,\n"
            ."    IF(`mailidentity`.`email`!='',CONCAT(' [',`mailidentity`.`email`,']'),'')\n"
            ."  ),\n"
            ."  'c0ffc0'\n"
            ."FROM\n"
            ."  `mailidentity`\n"
            ."WHERE\n"
            ."  `mailidentity`.`systemID`=".SYS_ID."\n"
            ."ORDER BY\n"
            ."  `seq`,`text`";
    }

    public function handleReportCopy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::try_copy($newID, $msg, $msg_tooltip, $name);
    }
}
