<?php
define('VERSION_PERSON_MERGE_PROFILES','1.0.3');
/*
Version History:
  1.0.3 (2014-02-18)
    1) Now accesses System::tables constant instead of static for
       Person::_merge_set_history_created_by() and
       Person::_merge_set_history_modified_by()

  (Older version history in class.person_merge_profiles.txt)
*/
class Person_merge_profiles extends Person {
  protected $_affected_records = 0;
  protected $_delete_source_profiles;
  protected $_sourceID_csv;
  protected $_targetID;
  protected $_targetValue;

  public function draw() {
    try{
      $this->_draw_setup();
    }
    catch (Exception $e){
      $this->_draw_css();
      $this->_common_draw_status();
      $this->_draw_render();
    }
    $this->_draw_css();
    $this->_common_draw_status();
    $this->_draw_form();
    $this->_draw_render();
  }

  protected function _draw_css(){
    $this->_css.=
       "[\n"
      ."  ['#merge_profiles h1',{color:'#000', margin:0}],\n"
      ."  ['#merge_profiles label',{display:'block', fontWeight:'bold'}],\n"
      ."  ['#merge_profiles table',{width:'100%'}],\n"
      ."  ['#merge_profiles table thead th',{backgroundColor:'#707070',color:'#fff'}],\n"
      ."  ['#merge_profiles table thead th.v',{width:'20px'}]\n"
      ."]\n";
  }

  protected function _draw_form(){
    if (get_var('submode')!=''){
      return;
    }
    $this->_html.=
       "<p>\n"
      ."This tool moves all items relating to one or more source profiles together with their group membership"
      ." records to a single chosen destination profile."
      ."</p>"
      ."<label>Selected Profiles</label>"
      ."<div>Click a profile below to set it as the destination for the merge. Right-click any record to edit it.</div>\n"
      ."<table summary='Profiles' border='1' cellpadding='2' class='report' cellspacing='0'>\n"
      ."  <thead>\n"
      ."    <tr>\n"
      ."      <th>User<br />Name</th>\n"
      ."      <th>Full<br />Name</th>\n"
      ."      <th>Primary<br />Email</th>\n"
      .(System::has_feature('show-home-address') ? "      <th>Home<br />Address</th>\n" : "")
      .(System::has_feature('show-home-address') ? "      <th>Home<br />Phone</th>\n" : "")
      .(System::has_feature('show-work-address') ? "      <th>Work<br />Address</th>\n" : "")
      .(System::has_feature('show-work-address') ? "      <th>Work<br />Phone</th>\n" : "")
      ."      <th class='v'>[LBL]PEACH-num-cases|100|Number of Cases assigned to person[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-case-tasks|100|Number of Case Tasks assigned to person[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-emails-sent|100|Number of Emails sent to person[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-event-registrations|100|Number of Events person has registered[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-groups|100|Number of Groups Member is a part of[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-orders|100|Number of Orders placed by (or for) the person[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-credit-memos|100|Number of Credit Memos issued to person[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-notes|100|Number of Notes assigned to person[/LBL]</th>\n"
      ."      <th class='v'>[LBL]PEACH-num-logins|100|Number of times this person has logged in[/LBL]</th>\n"
      ."      <th class='v'>Last Login</th>\n"
      ."      <th class='v'>Method</th>\n"
      ."      <th>Created</th>\n"
      ."      <th>Modified</th>\n"
      ."    </tr>\n"
      ."  </thead>\n"
      ."  <tbody>\n";
    foreach($this->_records as $r){
      $this->load($r);
      $this->_context_menu_ID = $r['type'];
      $CM = substr((substr($this->BL_context_selection_start(),4)),0,-1);
      $this->_html.=
         "    <tr id='merge_profiles_row_".$r['ID']."'"
        ." onclick=\"merge_profiles_select_destination(".$r['ID'].")\""
        .$CM
        .">\n"
        ."      <td>".$r['PUsername']."</td>\n"
        ."      <td>".$r['name_full']."</td>"
        ."      <td>".$r['PEmail']."</td>\n"
        .(System::has_feature('show-home-address') ? "      <td>".$r['home_address']."</td>\n" : "")
        .(System::has_feature('show-home-address') ? "      <td>".$r['ATelephone']."</td>\n"   : "")
        .(System::has_feature('show-work-address') ? "      <td>".$r['work_address']."</td>\n" : "")
        .(System::has_feature('show-work-address') ? "      <td>".$r['WTelephone']."</td>\n"   : "")
        ."      <td>".$r['num_cases']."</td>\n"
        ."      <td>".$r['num_case_tasks']."</td>\n"
        ."      <td>".$r['num_emails']."</td>\n"
        ."      <td>".$r['num_event_registrations']."</td>\n"
        ."      <td>".$r['num_groups']."</td>\n"
        ."      <td>".$r['num_orders']."</td>\n"
        ."      <td>".$r['num_credit_memos']."</td>\n"
        ."      <td>".$r['num_notes']."</td>\n"
        ."      <td>".$r['PLogonCount']."</td>\n"
        ."      <td"
        .($r['PLogonLastDate']!='0000-00-00 00:00:00' ?
            " title=\"From Host ".$r['PLogonLastHost']." (".$r['PLogonLastIP'].")\">"
           ."<span class='nowrap'>".substr($r['PLogonLastDate'],0,10)."</span> ".substr($r['PLogonLastDate'],11)
         :
           ">&nbsp;"
         )
        ."</td>\n"
        ."      <td>".$r['PLogonLastMethod']."</td>\n"
        ."      <td"
        .($r['history_created_date']!='0000-00-00 00:00:00' ?
            " title=\"By ".$r['created_by']."\">"
           ."<span class='nowrap'>".substr($r['history_created_date'],0,10)."</span> ".substr($r['history_created_date'],11)
         :
           ">&nbsp;"
         )
        ."</td>\n"
        ."      <td"
        .($r['history_modified_date']!='0000-00-00 00:00:00' ?
            " title=\"By ".$r['modified_by']."\">"
           ."<span class='nowrap'>".substr($r['history_modified_date'],0,10)."</span> ".substr($r['history_modified_date'],11)
         :
           ">&nbsp;"
         )
        ."</td>\n"


        ."    </tr>\n";
    }
    $this->_html.=
       "  </tbody>\n"
      ."</table>\n"
      ."<p class='txt_c'>\n"
      ."<input id='merge_profiles_cancel' type='button' style='width:100px' value='Cancel' class='formButton' onclick=\"hidePopWin()\" />\n"
      ."<input id='merge_profiles_submit' type='button' style='width:100px' value='Merge Profiles' disabled='disabled' class='formButton' onclick=\"merge_profiles_process('".$this->_targetID."')\" />\n"
      ."</p>"
      ."</div>"
      ;
  }

  protected function _draw_render(){
    $this->_html =
       "<div id='merge_profiles'>\n"
      ."<h1>Profile Merge</h1>\n"
      .$this->_html
      ."</div>";
    $this->_draw_render_JSON();
  }

  protected function _draw_setup(){
    $this->_safe_ID = 'merge_profiles';
    $this->_draw_setup_get_targetID();
    $this->_draw_setup_check_user_is_admin();
    $this->_draw_seup_do_merge();
    $this->_draw_setup_load_records();
  }

  protected function _draw_setup_check_user_is_admin(){
    $this->_common_load_user_rights();
    if (!$this->_current_user_rights['isUSERADMIN']){
      $this->_msg = "<b>Error:</b> You do not have permission to perform this operation.";
      throw new Exception;
    }
  }

  protected function _draw_seup_do_merge(){
    if (get_var('submode')!='merge'){
      return;
    }
    if (!$this->_targetValue = get_var('targetValue')){
      $this->_msg = "<b>Error:</b> No Target Value was given.";
      throw new Exception;
    }
    $source_arr =   explode(',',$this->_targetID);
    if (!in_array($this->_targetValue,$source_arr)){
      $this->_msg = "<b>Error:</b> Target was not one of the items selected for inclusion.";
      throw new Exception;
    }
    $this->_sourceID_csv =  implode(',',array_diff($source_arr, array($this->_targetValue)));
    $deleteSourceAcct =     false;
    $result =               $this->merge($this->_sourceID_csv, $this->_targetValue, $deleteSourceAcct);
    $this->_html.=          "<p><b>Result:</b> Merged ".$result." records.</p>";
  }

  protected function _draw_setup_get_targetID(){
    if (!$this->_targetID = get_var('targetID')){
      $this->_msg = "<b>Error:</b> There are no items selected.";
      throw new Exception;
    }
  }

  protected function _draw_setup_load_records(){
    $sql =
       "SELECT\n"
      ."  `ID`,\n"
      ."  `type`,\n"
      ."  `systemID`,\n"
      ."  `PEmail`,\n"
      ."  `PUsername`,\n"
      ."  `PLogonCount`,\n"
      ."  `PLogonLastDate`,\n"
      ."  `PLogonLastHost`,\n"
      ."  `PLogonLastIP`,\n"
      ."  `PLogonLastMethod`,\n"
      ."  `history_created_date`,\n"
      ."  (SELECT `PUsername` FROM `person`      AS `x` WHERE `x`.`ID` = `person`.`history_created_by`)  AS `created_by`,\n"
      ."  `history_modified_date`,\n"
      ."  (SELECT `PUsername` FROM `person`      AS `x` WHERE `x`.`ID` = `person`.`history_modified_by`)  AS `modified_by`,\n"
      ."  `ATelephone`,\n"
      ."  `WTelephone`,\n"
      ."  TRIM(CONCAT(`NTitle`,IF(`NTitle`!='',' ',''),`NFirst`,IF(`NFirst`!='',' ',''),`NMiddle`,IF(`NMiddle`!='',' ',''),`NLast`)) `name_full`,\n"
      .(System::has_feature('show-home-address') ? "  TRIM(CONCAT(`AAddress1`,IF(`AAddress1`!='',' ',''),`AAddress2`,IF(`AAddress2`!='',' ',''),`ACity`,IF(`ACity`!='',' ',''),`ASpID`,IF(`ASpID`!='',' ',''),`APostal`,IF(`APostal`!='',' ',''),`ACountryID`)) `home_address`,\n" : "")
      .(System::has_feature('show-work-address') ? "  TRIM(CONCAT(`WCompany`,IF(`WCompany`!='',' ',''),`WAddress1`,IF(`WAddress1`!='',' ',''),`WAddress2`,IF(`WAddress2`!='',' ',''),`WCity`,IF(`WCity`!='',' ',''),`WSpID`,IF(`WSpID`!='',' ',''),`WPostal`,IF(`WPostal`!='',' ',''),`WCountryID`)) `work_address`,\n" : "")
      ."  (SELECT COUNT(*) FROM `cases`          AS `x` WHERE `x`.`assigned_personID` = `person`.`ID`)  AS `num_cases`,\n"
      ."  (SELECT COUNT(*) FROM `case_tasks`     AS `x` WHERE `x`.`assigned_personID` = `person`.`ID`)  AS `num_case_tasks`,\n"
      ."  (SELECT COUNT(*) FROM `registerevent`  AS `x` WHERE `x`.`attender_personID` = `person`.`ID` OR  `x`.`inviter_personID` = `person`.`ID`)  AS `num_event_registrations`,\n"
      ."  (SELECT COUNT(*) FROM `group_members`  AS `x` WHERE `x`.`personID` = `person`.`ID`)  AS `num_groups`,\n"
      ."  (SELECT COUNT(*) FROM `mailqueue_item` AS `x` WHERE `x`.`personID` = `person`.`ID`)  AS `num_emails`,\n"
      ."  (SELECT COUNT(*) FROM `orders`         AS `x` WHERE `x`.`personID` = `person`.`ID` AND `x`.`archive` = 0 AND `x`.`credit_memo_for_orderID` = 0)  AS `num_orders`,\n"
      ."  (SELECT COUNT(*) FROM `orders`         AS `x` WHERE `x`.`personID` = `person`.`ID` AND `x`.`archive` = 0 AND `x`.`credit_memo_for_orderID` !=0)  AS `num_credit_memos`,\n"
      ."  (SELECT COUNT(*) FROM `postings`       AS `x` WHERE `x`.`personID` = `person`.`ID` AND `x`.`type`='note')  AS `num_notes`\n"
      ."FROM\n"
      ."  `person`\n"
      ."WHERE\n"
      ."  `ID` IN(".$this->_targetID.")\n"
      ."ORDER BY\n"
      ."  `history_modified_date` DESC,\n"
      ."  `history_created_date` DESC";
//    $this->_html.="<pre>".$sql."</pre>";
    $this->_records = $this->get_records_for_sql($sql);
  }

  public function merge($sourceID_csv, $targetID, $delete_source_profiles=false){
    $this->_merge_setup($sourceID_csv, $targetID, $delete_source_profiles);
    $this->_merge_delete_source_profiles();
    $this->_merge_group_membership();
    $this->_merge_set_history_created_by();
    $this->_merge_set_history_modified_by();
    $this->_merge_update_cases();
    $this->_merge_update_case_tasks();
    $this->_merge_update_community_members();
    $this->_merge_update_event_registrations();
    $this->_merge_update_mailqueue_items();
    $this->_merge_update_orders_and_credit_memos();
    $this->_merge_update_postings();
    return $this->_affected_records;
  }

  protected function _merge_delete_source_profiles(){
    if (!$this->_delete_source_profiles) {
      return;
    }
    $this->_Obj_s->delete();
  }

  protected function _merge_group_membership(){
    $Obj_GM = new Group_Member;
    $sql =
       "SELECT\n"
      ."  `group_members`.*\n"
      ."FROM\n"
      ."  `group_members`\n"
      ."WHERE\n"
      ."  `personID` IN (".$this->_sourceID_csv.",".$this->_targetID.")\n"
      ."ORDER BY `groupID`, `history_modified_date` DESC, `history_created_date` DESC";
    $records = $Obj_GM->get_records_for_sql($sql);
    $group_arr = array();
    foreach ($records as $r){
      $groupID = $r['groupID'];
      $r['personID'] = $this->_targetID;
      if (!isset($group_arr[$groupID])){
        unset($r['ID']);
        $group_arr[$groupID] = $r;
      }
      else {
        if ($r['permEMAILRECIPIENT']=='1') {   $group_arr[$groupID]['permEMAILRECIPIENT']=1; }
        if ($r['permEMAILOPTOUT']=='1') {      $group_arr[$groupID]['permEMAILOPTOUT']=1; }
        if ($r['permVIEWER']=='1') {           $group_arr[$groupID]['permVIEWER']=1; }
        if ($r['permEDITOR']=='1') {           $group_arr[$groupID]['permEDITOR']=1; }
      }
    }
    $sql =
       "DELETE FROM\n"
      ."  `group_members`\n"
      ."WHERE\n"
      ."  `personID` IN (".$this->_sourceID_csv.",".$this->_targetID.")";
    $Obj_GM->do_sql_query($sql);
    $this->_affected_records+= Record::get_affected_rows()+count($group_arr);
    foreach ($group_arr as $data) {
      $Obj_GM->insert($data);
    }
    $Obj_Person = new Person;
    foreach ($records as $r){
      $Obj_Person->_set_ID($r['personID']);
      $Obj_Person->set_groups_list_description(false);
    }
  }

  protected function _merge_set_history_created_by(){
    $system_tables = explode(',',str_replace(' ','',System::tables));
    foreach ($system_tables as $table){
      $this->_Obj->_set_table_name($table);
      $this->_affected_records+= $this->_Obj->set_field_on_value('history_created_by',$this->_sourceID_csv,$this->_targetID);
    }
  }

  protected function _merge_set_history_modified_by(){
    $system_tables = explode(',',str_replace(' ','',System::tables));
    foreach ($system_tables as $table){
      $this->_Obj->_set_table_name($table);
      $this->_affected_records+= $this->_Obj->set_field_on_value('history_modified_by',$this->_sourceID_csv,$this->_targetID);
    }
  }

  protected function _merge_update_cases(){
    $this->_Obj->_set_table_name('cases');
    $this->_affected_records+= $this->_Obj->set_field_on_value('assigned_personID',$this->_sourceID_csv,$this->_targetID);
    $this->_affected_records+= $this->_Obj->set_field_on_value('related_personID',$this->_sourceID_csv,$this->_targetID);
  }

  protected function _merge_update_case_tasks(){
    $this->_Obj->_set_table_name('case_tasks');
    $this->_affected_records+= $this->_Obj->set_field_on_value('assigned_personID',$this->_sourceID_csv,$this->_targetID);
  }

  protected function _merge_update_community_members(){
    $this->_Obj->_set_table_name('community_member');
    $this->_affected_records+= $this->_Obj->set_field_on_value('contactID',$this->_sourceID_csv,$this->_targetID);
  }

  protected function _merge_update_event_registrations(){
    $this->_Obj->_set_table_name('registerevent');
    $this->_affected_records+= $this->_Obj->set_field_on_value('attender_personID',$this->_sourceID_csv,$this->_targetID);
    $this->_affected_records+= $this->_Obj->set_field_on_value('inviter_personID',$this->_sourceID_csv,$this->_targetID);
  }

  protected function _merge_update_mailqueue_items(){
    $this->_Obj->_set_table_name('mailqueue_item');
    $this->_affected_records+= $this->_Obj->set_field_on_value('personID',$this->_sourceID_csv,$this->_targetID);
  }

  protected function _merge_update_orders_and_credit_memos(){
    $this->_Obj->_set_table_name('orders');
    $this->_affected_records+= $this->_Obj->set_field_on_value('personID',$this->_sourceID_csv,$this->_targetID);
  }

  protected function _merge_update_postings(){
    $this->_Obj->_set_table_name('postings');
    $this->_affected_records+= $this->_Obj->set_field_on_value('personID',$this->_sourceID_csv,$this->_targetID);
  }

  protected function _merge_setup($sourceID_csv, $targetID, $delete_source_profiles=false){
    $this->_sourceID_csv =              $sourceID_csv;
    $this->_targetID =                  $targetID;
    $this->_delete_source_profiles =    $delete_source_profiles;
    $this->_Obj =                       new Record;
    $this->_Obj_s =                     new Person($this->_sourceID_csv);
    $this->_Obj_t =                     new Person($this->_targetID);
  }

  public function get_version(){
    return VERSION_PERSON_MERGE_PROFILES;
  }
}
?>