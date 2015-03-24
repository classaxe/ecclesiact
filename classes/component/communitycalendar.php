<?php
namespace Component;

define('COMPONENT_NS_COMMUNITY_CALENDAR_LARGE', '1.0.2');
/*
Version History:
  1.0.2 (2015-03-13)
    1) Now uses namespaces and is fully PSR-2 Compliant
    2) Now extends \Component\CalendarLarge and modified to suit that object

*/

class CommunityCalendar extends \Component\CalendarLarge
{
    protected $_event_category_name =       'Community Posting Category';
    protected $_event_report_name =         'community_member.events';
    protected $_event_context_menu_name =   'module_cm_event';
    protected $_ObjEventClass =             '\Community_Event';

    protected function setupLoadEvents()
    {
        $this->_ObjEvent->community_record = $this->community_record;
        $this->_arr_cal =   $this->_ObjEvent->get_calendar_dates($this->_MM, $this->_YYYY, $this->_memberID);
    }

    protected function getSharedSourceLink()
    {
        return \Community_Posting::BL_mini_shared_source_link($this, '#calendar');
    }

    public static function getVersion()
    {
        return COMPONENT_NS_COMMUNITY_CALENDAR_LARGE;
    }
}
