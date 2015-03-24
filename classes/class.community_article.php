<?php
define('COMMUNITY_ARTICLE','1.0.3');
/*
Version History:
  1.0.3 (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static

*/


class Community_Article extends Article{
  public function __construct(){
    parent::__construct();
    $this->_set_context_menu_ID('module_cm_article');
    $this->_show_latest_for_each_member = false;
    $this->_set_edit_param('report','community_member.articles');
  }

  protected function _get_records_get_sql(){
    return Community_Posting::_get_records_get_sql($this);
  }

  protected function BL_shared_source_link(){
    return Community_Posting::BL_shared_source_link($this,'#articles');
  }

  protected function BL_category(){
    return Community_Posting::BL_category();
  }

  public static function getVersion(){
    return COMMUNITY_ARTICLE;
  }
}

?>