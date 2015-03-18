<?php
define('COMPONENT_EXTENDED_COMMUNITY_VERSION','1.0.0');
/* Custom Fields used:
custom_1 = denomination (must be as used in other SQL-based controls)

/*
Version History:
  1.0.0 (2015-03-17)
    1) Moved into its own file - was originally part of class.component_communities_display.php

*/

class Extended_Community extends Community{
  public function draw_listing(){
    if (!$this->_current_user_rights['canEdit'] && !$this->record['members']){
      return;
    }
    $has_map =  ($this->record['map_lat']!=0 || $this->record['map_lon']!=0);
    return
       "<li".($this->record['enabled'] ? "" : " class='inactive' title='Inactive'").">"
      .$this->BL_context_selection_start()
      ."<a"
      ." href=\"".BASE_PATH.trim($this->record['URL'],'/')."\""
      .($this->_cp['show_map'] && $has_map ?
         " title=\"Show Community of ".htmlentities($this->record['title'])." on map\""
        ." onclick=\"return ecc_map.point.i(_google_map_".$this->_safe_ID."_marker_".$this->record['ID'].");\""
       :
         " title=\"Visit Community of ".htmlentities($this->record['title'])."\""
       )
      .">"
      .htmlentities($this->record['title'])
      ."</a>"
      ." <i>(".$this->record['members'].")</i>"
      .($this->record['URL_external'] ?
           "    <em><a href=\"".$this->record['URL_external']."\" rel=\"external\">"
          .$this->record['URL_external']
          ."</a></em>\n"
        :
          ""
       )
      ."</li>";
  }

  public function _load_user_rights(){
    $this->_current_user_rights['isSYSADMIN'] =         get_person_permission("SYSADMIN") || get_person_permission("MASTERADMIN");
    $this->_current_user_rights['isMASTERADMIN'] =      get_person_permission("MASTERADMIN");
    $this->_current_user_rights['canAdd'] =
       get_person_permission("SYSAPPROVER") ||
       get_person_permission("SYSADMIN") ||
       get_person_permission("MASTERADMIN");
    $this->_current_user_rights['canViewStats'] =
      $this->_current_user_rights['canAdd'];
    $this->_current_user_rights['canEdit'] =
       $this->_current_user_rights['canAdd'] ||
       get_person_permission("SYSEDITOR");
    if ($this->_current_user_rights['canEdit']){
      $this->_edit_form['community'] =      'community';
      $this->_edit_form['member'] =         'community_member';
      $this->_edit_form['sponsor_plan'] =   'community.sponsorship-plans';
      $this->_popup['community'] =          get_popup_size($this->_edit_form['community']);
      $this->_popup['member'] =             get_popup_size($this->_edit_form['member']);
      $this->_popup['sponsor_plan'] =       get_popup_size($this->_edit_form['sponsor_plan']);
    }
  }

  public function get_version(){
    return COMPONENT_EXTENDED_COMMUNITY_VERSION;
  }

}

?>