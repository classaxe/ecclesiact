<?php
namespace Component;

/*
Version History:
  1.0.4 (2017-01-02)
    1) CommunityCalendar::getSharedSourceLink() modified to look like its parent
*/

class CommunityCalendar extends \Component\CalendarLarge
{
    const VERSION = '1.0.4';

    protected $_event_category_name =       'Community Posting Category';
    protected $_event_report_name =         'community_member.events';
    protected $_event_context_menu_name =   'module_cm_event';
    protected $_ObjEventClass =             '\Community_Event';

    protected function setupLoadEvents()
    {
        $this->_ObjEvent->community_record = $this->community_record;
        $this->_arr_cal =   $this->_ObjEvent->get_calendar_dates($this->_MM, $this->_YYYY, $this->_memberID);
    }

    protected function getSharedSourceLink($event)
    {
        // $event here is thrown away - it is just for method compatability with parent
        return \Community_Posting::BLMiniSharedSourceLinkWithDelegate($this, '#calendar');
    }
}
