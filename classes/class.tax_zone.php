<?php
/*
Version History:
  1.0.5 (2017-08-26)
    1) Gave Poll::copy() method fourth parameter 'data' to look like recently modified Record::copy()
    2) Now uses VERSION constant for version numbering
*/
class Tax_Zone extends Record {
    const VERSION = '1.0.5';

  function __construct($ID="") {
    parent::__construct("tax_zone",$ID);
    $this->_set_object_name("Tax Zone");
    $this->_set_message_associated('');
  }

  public function copy($new_name=false,$new_systemID=false,$new_date=true, $data = false) {
    $newID =    parent::copy($new_name,$new_systemID,$new_date, $data);
    $rules =    $this->get_tax_regimes();
    $Obj =      new Tax_Regime;
    foreach ($rules as $data) {
      unset($data['ID']);
      unset($data['archive']);
      unset($data['archiveID']);
      if ($new_date){
        unset($data['history_created_by']);
        unset($data['history_created_date']);
        unset($data['history_created_IP']);
        unset($data['history_modified_by']);
        unset($data['history_modified_date']);
        unset($data['history_modified_IP']);
      }
      $data['tax_zoneID'] = $newID;
      if ($new_systemID) {
        $data['systemID'] = $new_systemID;
      }
      $new_tax_regimeID =    $Obj->insert($data);
    }
    return $newID;
  }

  function delete() {
    $Obj = new Tax_Regime;
    $regimes = $this->get_tax_regimes();
    foreach ($regimes as $regime) {
      $Obj->_set_ID($regime['ID']);
      $Obj->delete();
    }
    parent::delete();
  }

  function export_sql($targetID,$show_fields) {
    $header =
      "Selected ".$this->_get_object_name().$this->plural($targetID)."\n"
     ."(with associated Tax Regimes and their Rules)";
    $extra_delete =
       "DELETE FROM `tax_rule`               WHERE `tax_regimeID` IN(SELECT `ID` FROM `tax_regime` WHERE `tax_zoneID` IN(".$targetID.")) AND `systemID` IN(1,".SYS_ID.");\n"
      ."DELETE FROM `tax_regime`             WHERE `tax_zoneID` IN(".$targetID.") AND `systemID` IN(1,".SYS_ID.");\n"
      ;
    $Obj = new Backup;
    $extra_select =
       $Obj->db_export_sql_query("`tax_regime`            ","SELECT * FROM `tax_regime` WHERE `tax_zoneID` IN (".$targetID.") AND `systemID` IN(1,".SYS_ID.")",$show_fields)
       //.$Obj->db_export_sql_query("`tax_rule`              ","SELECT * FROM `tax_rule` WHERE `tax_regimeID` IN (SELECT GROUP_CONCAT(`ID`) FROM `tax_regime` WHERE `tax_zoneID` IN(".$targetID.")) AND `systemID` IN(1,".SYS_ID.")",$show_fields)
       // Broken on mysql 5.0.44 - see http://bugs.mysql.com/bug.php?id=22855
      .$Obj->db_export_sql_query("`tax_rule`              ","SELECT `tax_rule`.* FROM `tax_rule` LEFT JOIN `tax_regime` AS `e` ON `e`.`ID` = `tax_rule`.`tax_regimeID` WHERE `e`.`tax_zoneID` IN(".$targetID.") AND `tax_rule`.`systemID` IN(1,".SYS_ID.")",$show_fields)
      ;
    return parent::sql_export($targetID,$show_fields,$header,'',$extra_delete,$extra_select);
  }

  function get_tax_regimes(){
    $sql =
       "SELECT\n"
      ."  *\n"
      ."FROM\n"
      ."  `tax_regime`\n"
      ."WHERE\n"
      ."  `tax_zoneID` IN(".$this->_get_ID().")"
      ."ORDER BY\n"
      ."  `seq`";
//    z($sql);die;
    return $this->get_records_for_sql($sql);
  }

  function handle_report_copy(&$newID,&$msg,&$msg_tooltip,$name){
    return parent::try_copy($newID,$msg,$msg_tooltip);
  }
}
