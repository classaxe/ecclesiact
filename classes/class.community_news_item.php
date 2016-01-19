<?php
/*
Version History:
  1.0.4 (2016-01-18)
    1) Fixes for PHP 5.6 - no more 'magic this' passing, now uses delegate pattern throughout
*/

class Community_News_Item extends News_Item
{
    const VERSION = '1.0.4';

    public function __construct()
    {
        parent::__construct();
        $this->_set_context_menu_ID('module_cm_news');
        $this->_show_latest_for_each_member = false;
        $this->_set_edit_param('report', 'community_member.news-items');
    }

    protected function _get_records_get_sql()
    {
        return Community_Posting::getRecordsGetSqlWithDelegate($this);
    }

    protected function BL_shared_source_link()
    {
        return Community_Posting::BLsharedSourceLinkWithDelegate($this, '#news');
    }

    protected function BL_category()
    {
        return Community_Posting::BLcategoryWithDelegate($this);
    }
}
