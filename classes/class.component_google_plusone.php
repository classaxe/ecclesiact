<?php
define ("VERSION_COMPONENT_GOOGLE_PLUSONE","1.0.1");
/*
Version History:
  1.0.1 (2015-09-13)
    1) References to Page::push_content() now changed to Output::push()

*/

class Component_Google_Plusone extends Component_Base {
  public function __construct(){
    global $system_vars;
    $this->_ident =             "google_plusone";
    $this->_parameter_spec =    array(
      'size' =>     array('match' => 'enum|small,medium,standard,tall',   'default'=>'standard', 'hint'=>'small|medium|standard|tall')
    );
  }

  public function draw($instance='', $args=array(), $disable_params=false){
    $this->_setup($instance,$args,$disable_params);
    $this->_draw_control_panel();
    $this->_draw_content();
    return $this->_html;
  }

  protected function _draw_content(){
    $this->_html.=
       "<div class=\"g-plusone\""
      .($this->_cp['size']!='standard' ? " data-size=\"".$this->_cp['size']."\"" : "")
      ."></div>";
    Output::push(
       "javascript_onload",
       "  (function(){\n"
      ."    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;\n"
      ."    po.src = 'https://apis.google.com/js/plusone.js';\n"
      ."    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);\n"
      ."  })();\n"
    );
  }

  public static function getVersion(){
    return VERSION_COMPONENT_GOOGLE_PLUSONE;
  }
}
?>