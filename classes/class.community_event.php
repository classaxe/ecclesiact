<?php
/*
Version History:
  1.0.5 (2017-04-09)
    1) Anchors for shared source links in community events now anchor to Christmas or Easter if either category
       has been applies to the event in question.
*/

class Community_Event extends Event
{
    const VERSION = '1.0.5';

    public function __construct()
    {
        parent::__construct();
        $this->_set_context_menu_ID('module_cm_event');
        $this->_show_latest_for_each_member = false;
        $this->_set_edit_param('report', 'community_member.events');
    }

    protected function _get_records_get_sql()
    {
        return Community_Posting::getRecordsGetSqlWithDelegate($this);
    }

    protected function BL_shared_source_link()
    {
        $categories =   explode(', ', strtolower($this->record['category']));
        if (in_array('christmas', $categories)) {
            $anchor =   '#christmas';
        } elseif (in_array('easter', $categories)) {
            $anchor =   '#easter';
        } else {
            $anchor =       '';
        }
        return Community_Posting::BLsharedSourceLinkWithDelegate($this, $anchor);
    }

    protected function BL_category()
    {
        return Community_Posting::BLcategoryWithDelegate($this);
    }
}
