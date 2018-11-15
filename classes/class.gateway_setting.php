<?php
define('VERSION_GATEWAY_SETTING','1.0.5');
/*
Version History:
  1.0.5 (2018-11-15)
    1) Changed reference to Beanstream to Bambora
*/
class Gateway_Setting extends Record{

  public function __construct($ID="") {
    parent::__construct("gateway_settings",$ID);
    $this->_set_object_name('Gateway Setting');
  }

  public function do_donation() {
    $Obj_gateway = new System(SYS_ID);
    $gateway_record = $Obj_gateway->get_gateway();
    if ($gateway_record===false) {
      do_log(3,'Gateway_Setting::do_donation()','(none)','There is no gateway defined for this system.');
      return 'There is no gateway defined for this system.';
    }
    switch($gateway_record['type']['name']) {
      case "Bambora":
        return "The Donation button is not currently supported with Bambora gateways";
      break;
      case "Paypal (Live)":
      case "Paypal (Test)":
        return
           "<html><body onload=\"document.getElementById('form').submit();\">\n"
          ."<form id=\"form\" action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\n"
          .draw_form_field('cmd','_s-xclick','hidden')
          .draw_form_field('encrypted',$_REQUEST['targetValue'],'hidden')
          ."</form></body></html>";
      break;
      default:
        return "The Donation button is not currently supported with the ".$gateway_record['type']['name']." gateway type for this system.";
      break;
    }
  }

  public function export_sql($targetID,$show_fields) {
    return  $this->sql_export($targetID,$show_fields);
  }

  public static function get_selector_sql() {
    return
       "SELECT\n"
      ."  `gateway_settings`.`name` AS `text`,\n"
      ."  `gateway_settings`.`ID` AS `value`\n"
      ."FROM\n"
      ."  `gateway_settings`\n"
      ."WHERE\n"
      ."  `gateway_settings`.`systemID` IN(1,".SYS_ID.")\n"
      ."ORDER BY\n"
      ."  `gateway_settings`.`name`";

  }

  public function handle_report_copy(&$newID,&$msg,&$msg_tooltip,$name){
    return parent::try_copy($newID,$msg,$msg_tooltip,false);
  }

  public function test_requiresSSL() {
    $sql =
       "SELECT\n"
      ."  `gateway_settings`.`forceSSL` AS `forceSSL`\n"
      ."FROM\n"
      ."  `gateway_settings`\n"
      ."WHERE\n"
      ."  `gateway_settings`.`ID` = ".$this->_get_ID();
    return $this->get_field_for_sql($sql);
  }

  public static function getVersion(){
    return VERSION_GATEWAY_SETTING;
  }
}
?>