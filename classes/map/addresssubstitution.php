<?php
namespace Map;
/*
Version History:
  1.0.2 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class AddressSubstitution extends \Record
{
    const VERSION = '1.0.2';
    const FIELDS =  'ID, archive, archiveID, deleted, systemID, input, output, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';

    public function __construct($ID = "")
    {
        parent::__construct("address_substitution", $ID);
        $this->_set_object_name('Address Substitution Entry');
        $this->_set_has_groups(false);
    }

    public function getAddressForInput($input)
    {
        $sql =
             "SELECT\n"
            ."    `output`\n"
            ."FROM\n"
            ."    `".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."    `input` = '".static::escape_string($input)."'";
        if ($result = $this->get_field_for_sql($sql)) {
            return $result;
        }
        return $input;
    }

    public function exportSql($targetID, $show_fields)
    {
        return $this->sql_export($targetID, $show_fields);
    }

    public function handleReportCopy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::try_copy($newID, $msg, $msg_tooltip);
    }
}
