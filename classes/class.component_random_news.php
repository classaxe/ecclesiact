<?php
  define ("VERSION_COMPONENT_RANDOM_NEWS","1.0.0");
/*
Version History:
  1.0.0 (2011-12-31)
    1) Initial release - moved from Component class
*/
class Component_Random_News extends Component_Base {

  function draw($instance='',$args=array(),$disable_params=false) {
    $ident =            "random_news";
    $safe_ID =          Component_Base::get_safe_ID($ident,$instance);
    $parameter_spec =   array(
      'category_csv' =>         array('match' => '',		'default'=>'MM DD YYYY',  'hint'=>'csv list of categories to pick from')
    );
    $cp_settings =
      Component_Base::get_parameter_defaults_and_values(
        $ident, $instance, $disable_params, $parameter_spec, $args
      );
    $cp_defaults =  $cp_settings['defaults'];
    $cp =           $cp_settings['parameters'];
    $out =          Component_Base::get_help($ident, $instance, $disable_params, $parameter_spec, $cp_defaults);

    $Obj_News_Item = new News_Item;
    $record = $Obj_News_Item->get_random_record($cp['category_csv']);
    if ($record) {
      $out.= $record['content'];
    }
    return $out;
  }

  public function get_version(){
    return VERSION_COMPONENT_RANDOM_NEWS;
  }
}
?>