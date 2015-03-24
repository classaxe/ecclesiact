<?php
define('COMMUNITY_MEMBERSHIP_VERSION','1.0.5');
/*
Version History:
  1.0.5 (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static

*/
class Community_Membership extends Record {
  static $member_record = false;
  var $url;

  function __construct($ID="") {
    parent::__construct('community_membership',$ID);
    $this->_set_assign_type('Community Membership');
    $this->_set_object_name('Community Membership Record');
    $this->set_edit_params(
      array(
        'report' =>                 'community.membership',
        'report_rename' =>          false,
        'report_rename_label' =>    ''
      )
    );
  }

  function export_sql($targetID,$show_fields){
    return $this->sql_export($targetID,$show_fields);
  }

  function handle_report_copy(&$newID,&$msg,&$msg_tooltip,$name){
    return parent::try_copy($newID,$msg,$msg_tooltip);
  }

  public static function getVersion(){
    return COMMUNITY_MEMBERSHIP_VERSION;
  }
}
?>