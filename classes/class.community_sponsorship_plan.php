<?php
define('VERSION_COMMUNITY_SPONSORSHIP_PLAN','1.0.3');
/*
Version History:
  1.0.3 (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static

*/
class Community_Sponsorship_Plan extends Sponsorship_Plan {
  public function __construct($ID='',$systemID=SYS_ID) {
    parent::__construct($ID,$systemID);
    $this->_set_object_name('Community Sponsorship Plan');
  }

  public function set_container_path(){
    $this->load();
    $parentID = $this->record['parentID'];
    if ($parentID==0){
      if (!$this->record['communityID']){
        return;
      }
      $Obj_Community = new Community($this->record['communityID']);
      $parentID = $Obj_Community->get_field('sponsorship_gallery_albumID');
    }
    $this->set_field('parentID',$parentID,true,false);
    parent::set_container_path();
  }

  public static function getVersion(){
    return VERSION_COMMUNITY_SPONSORSHIP_PLAN;
  }
}
?>