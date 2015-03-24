<?php
define('VERSION_CATEGORY_ASSIGN','1.0.5');
/*
Version History:
  1.0.5 (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static

*/
class Category_Assign extends Record {
  function __construct($ID=""){
    parent::__construct("category_assign",$ID);
    $this->_set_object_name('Category Assign');
  }

  function delete_for_assignment($assign_type,$assignID) {
    $sql =
       "DELETE FROM\n"
      ."  `".$this->_get_table_name()."`\n"
      ."WHERE\n"
      ."  `assign_type` = \"".$assign_type."\" AND\n"
      ."  `assignID` IN(".$assignID.")";
//    z($sql);
    $this->do_sql_query($sql);
  }

  function set_for_assignment($assign_type,$assignID,$csv,$systemID) {
    $this->delete_for_assignment($assign_type,$assignID);
    if (!$csv) {
      return;
    }
    $arr = explode(",",$csv);
    $Obj = new Category;
    foreach ($arr as $category) {
      $categoryID = $Obj->get_ID_by_name(trim($category),"1,".$systemID);
      if ($categoryID) {
        $data =
          array(
            'assign_type' =>$assign_type,
            'assignID' =>   $assignID,
            'categoryID' => $categoryID,
            'systemID' =>   $systemID
          );
//        y($data);
        $this->insert($data);
      }
    }
  }

  public static function getVersion(){
    return VERSION_CATEGORY_ASSIGN;
  }
}
?>