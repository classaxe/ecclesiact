<?php
namespace Component;

define('COMPONENT_NS_COMMUNITY_MEMBER_CALENDAR_VERSION', '1.0.2');
/*
Version History:
  1.0.2 (2015-03-13)
    1) Now extends \Component\CalendarLarge and modified to suit that object

*/

class CommunityMemberCalendar extends \Component\CalendarLarge
{
    protected $_event_category_name =       'Community Posting Category';
    protected $_event_report_name =         'community_member.events';
    protected $_event_context_menu_name =   'module_cm_event';
    protected $_ObjEventClass =             '\Community_Event';

    protected function setupLoadEvents()
    {
        $this->_ObjEvent->memberID =            $this->memberID;
        $this->_ObjEvent->partner_csv =         $this->partner_csv;
        $this->_ObjEvent->communityID =         $this->communityID;
        $this->_ObjEvent->community_record =    $this->community_record;
        $this->_arr_cal =   $this->_ObjEvent->get_calendar_dates($this->_MM, $this->_YYYY, $this->memberID);
    }

    protected function getSharedSourceLink()
    {
        return \Community_Member_Posting::BL_mini_shared_source_link($this, '#calendar');
    }

    public function getVersion()
    {
        return COMPONENT_NS_COMMUNITY_MEMBER_CALENDAR_VERSION;
    }
}
