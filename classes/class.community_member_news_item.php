<?php
/*
Version History:
  1.0.4 (2016-01-18)
    1) Fixes for PHP 5.6 - no more 'magic this' passing, now uses delegate pattern throughout
*/

class Community_Member_News_Item extends News_Item
{
    const VERSION = '1.0.4';

    public function __construct()
    {
        parent::__construct();
        $this->_set_object_name('Community Member News Item');
        $this->_set_context_menu_ID('module_cm_news');
        $this->_set_edit_param('report', 'community_member.news-items');
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
}
