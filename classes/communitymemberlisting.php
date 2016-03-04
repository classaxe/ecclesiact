<?php
/*
Version History:
  1.0.1 (2016-03-04)
    1) Removed some unused code and fixed closing tag for each list items
*/

class CommunityMemberListing extends Community_Member
{
    const VERSION = '1.0.1';
    
    public function draw()
    {
        return
             "<li>\n"
            ."<a href=\"".$this->record['member_URL']."\"".$this->drawContextMenuMember().">"
            .htmlentities($this->record['title'])
            ."</a>"
            ."</li>\n";
    }

    protected function drawContextMenuMember()
    {
        if (!$this->_current_user_rights['canEdit']) {
            return;
        }
        return
             " onmouseover=\""
            ."if(!CM_visible('CM_community_member')) {"
            ."this.style.backgroundColor='"
            .($this->record['systemID']==SYS_ID ? '#ffff80' : '#ffe0e0')
            ."';"
            ."_CM.type='community_member';"
            ."_CM.ID='".$this->record['ID']."';"
            ."_CM.communityID='".$this->record['primary_communityID']."';"
            ."_CM.full_member=".($this->record['full_member']=='1' ? '1' : '0').";"
            ."_CM.ministerial_member=".($this->record['primary_ministerialID'] ? '1' : '0').";"
            ."_CM.map_location='".($this->record['service_map_loc'] ? htmlentities($this->record['service_map_loc']) : '')."';"
            ."_CM.map_description='"
            .(isset($this->record['service_map_desc']) && $this->record['service_map_desc']!=='' ?
                addslashes(str_replace("\r\n", "<<br>>", htmlentities($this->record['service_map_desc'])))
              :
                ''
             )
            ."';"
            ."_CM_text[0]='&quot;".str_replace(array("'","\""), '', htmlentities($this->record['title']))."&quot;';"
            ."_CM.path='".$this->record['member_URL']."';"
            ."}\""
            ." onmouseout=\"this.style.backgroundColor='';_CM.type=''\"";
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
