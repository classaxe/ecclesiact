<?php
define('COMMUNITY_MEMBER_PODCAST_VERSION', '1.0.3');
/*
Version History:
  1.0.4 (2016-01-18)
    1) Fixes for PHP 5.6 - no more 'magic this' passing, now uses delegate pattern throughout
*/

class Community_Member_Podcast extends Podcast
{
    const VERSION = '1.0.4';

    public function __construct()
    {
        parent::__construct();
        $this->_set_object_name('Community Member Podcast');
        $this->_set_context_menu_ID('module_cm_podcast');
        $this->_set_edit_param('report', 'community_member.podcasts');
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
