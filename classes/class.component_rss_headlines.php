<?php
  define ("VERSION_COMPONENT_RSS_HEADLINES","1.0.1");
/*
Version History:
  1.0.1 (2015-09-13)
    1) References to Page::push_content() now changed to Output::push()

*/
class Component_RSS_Headlines extends Component_Base {

  function draw($instance='',$args=array(),$disable_params=false){
    static $shown_js = false;
    $ident =            "rss_headlines";
    $safe_ID =          Component_Base::get_safe_ID($ident,$instance);
    $parameter_spec =   array(
        'limit' =>      array('match' => 'range|0,n',		'default'=>'3',   'hint'=>'0..n'),
        'show_title' => array('match' => 'range|0,1',		'default'=>'0',   'hint'=>'0|1'),
        'url' =>        array('match' => '',				'default'=>'',    'hint'=>'URL for site')
    );
    $cp_settings =
      Component_Base::get_parameter_defaults_and_values(
        $ident, $instance, $disable_params, $parameter_spec, $args
      );
    $cp_defaults =  $cp_settings['defaults'];
    $cp =           $cp_settings['parameters'];
    $out =          Component_Base::get_help($ident, $instance, $disable_params, $parameter_spec, $cp_defaults);
    $out.=
       "<a rel=\"rssreader\" href=\"".$cp['url']."#".$cp['limit']."|".$cp['show_title']."\"></a>";
    if (!$shown_js){
      Output::push(
        "javascript_top",
        "<script type=\"text/javascript\" src=\"".BASE_PATH."sysjs/rss_reader\"></script>"
      );
      Output::push(
        "body_bottom",
         "<script type=\"text/javascript\">\n"
        ."//<![CDATA[\n"
        ."cDomExtensionManager.register(\n"
        ."  new cDomExtension(\n"
        ."    document,\n"
        ."    ['a[rel=rssreader]'],\n"
        ."    cRSSParser.attachTo\n"
        ."  )\n"
        .")\n"
        ."//]]>\n"
        ."</script>"
      );
      $shown_js = true;
    }
    return $out;
  }

  public static function getVersion(){
    return VERSION_COMPONENT_RSS_HEADLINES;
  }
}
?>