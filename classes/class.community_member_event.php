<?php
/*
Version History:
  1.0.4 (2016-01-18)
    1) Fixes for PHP 5.6 - no more 'magic this' passing, now uses delegate pattern throughout
*/

class Community_Member_Event extends Event
{
    const VERSION = '1.0.4';

    public function __construct()
    {
        parent::__construct();
        $this->_set_object_name('Community Member Event');
        $this->_set_context_menu_ID('module_cm_event');
        $this->_set_edit_param('report', 'community_member.events');
    }

    protected function _get_records_get_sql()
    {
        return Community_Member_Posting::getRecordsGetSqlWithDelegate($this);
    }

    protected function BL_category()
    {
        return Community_Member_Posting::BLCategoryWithDelegate($this);
    }

    protected function BL_shared_source_link()
    {
        return Community_Member_Posting::BLSharedSourceLinkWithDelegate($this);
    }

    public function manage_recurrences()
    {
        if (get_var('command')=='report') {
            return draw_auto_report('community_member.event_recurrences', 1);
        }
        if (!$selectID = get_var('selectID')) {
            return
                 "<h3 style='margin:0.25em'>Recurrences for ".$this->_get_object_name()."</h3>"
                ."<p style='margin:0.25em'>"
                ."No Recurrences - this ".$this->_get_object_name()." has not been saved yet."
                ."</p>";
        }
        $sql =
             "SELECT\n"
            ."  COUNT(*)\n"
            ."FROM\n"
            ."  `postings`\n"
            ."WHERE\n"
            ."  `postings`.`parentID` = ".$selectID;
        if (!$row = $this->get_record_for_sql($sql)) {
            return
                 "<h3 style='margin:0.25em'>Recurrences for ".$this->_get_object_name()."</h3>"
                ."<p style='margin:0.25em'>"
                ."Sorry - the related ".$this->_get_object_name()." cannot be found - perhaps it was deleted?"
                ."</p>";
        }
        return draw_auto_report('community_member.event_recurrences', 1);
    }
}
