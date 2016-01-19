<?php
namespace Component;
/*
Version History:
  1.0.3 (2016-01-18)
    1) Now Community_Member_Posting::getSharedSourceLink() calls renamed
       Community_Member_Posting::BLMiniSharedSourceLinkWithDelegate()
    2) Now uses class constant for version control
*/

class CommunityMemberCalendar extends \Component\CalendarLarge
{
    const VERSION = '1.0.3';

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
        return \Community_Member_Posting::BLMiniSharedSourceLinkWithDelegate($this, '#calendar');
    }
}
