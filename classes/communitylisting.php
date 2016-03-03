<?php
/*
Version History:
  1.0.1 (2016-03-03)
    1) Formerly Extended_Community - now CommunityListing
    2) CommunityListing::draw_listing() renamed to drawListing() and now takes single parameter for show_map
       rather than requiring complete CP list to be set for it by parent 
*/

class CommunityListing extends Community
{
    const VERSION = '1.0.1';
    
    public function draw($show_map = false, $content = '')
    {
        if (!$this->_current_user_rights['canEdit'] && !$this->record['members']) {
            return;
        }
        $has_map =  ($this->record['map_lat']!=0 || $this->record['map_lon']!=0);
        return
             "<li".($this->record['enabled'] ? "" : " class='inactive' title='Inactive'").">"
            .$this->BL_context_selection_start()
            ."<a"
            ." href=\"".BASE_PATH.trim($this->record['URL'], '/')."\""
            .($show_map && $has_map ?
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
            .$this->BL_context_selection_end()
            .$content
            ."</li>";
    }

    public function loadUserRights()
    {
        $this->_current_user_rights['isSYSADMIN'] =
            get_person_permission("SYSADMIN") || get_person_permission("MASTERADMIN");
        $this->_current_user_rights['isMASTERADMIN'] =
            get_person_permission("MASTERADMIN");
        $this->_current_user_rights['canAdd'] =
            get_person_permission("SYSAPPROVER") ||
            get_person_permission("SYSADMIN") ||
            get_person_permission("MASTERADMIN");
        $this->_current_user_rights['canViewStats'] =
            $this->_current_user_rights['canAdd'];
        $this->_current_user_rights['canEdit'] =
            $this->_current_user_rights['canAdd'] || get_person_permission("SYSEDITOR");
        if ($this->_current_user_rights['canEdit']) {
            $this->_edit_form['community'] =      'community';
            $this->_edit_form['member'] =         'community_member';
            $this->_edit_form['sponsor_plan'] =   'community.sponsorship-plans';
            $this->_popup['community'] =          get_popup_size($this->_edit_form['community']);
            $this->_popup['member'] =             get_popup_size($this->_edit_form['member']);
            $this->_popup['sponsor_plan'] =       get_popup_size($this->_edit_form['sponsor_plan']);
        }
    }
}
