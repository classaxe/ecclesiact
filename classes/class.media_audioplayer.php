<?php
define('VERSION_MEDIA_AUDIOPLAYER','1.0.10');
/*
Version History:
  1.0.10 (2015-09-14)
    1) References to Page::push_content() now changed to Output::push()

*/
class Media_Audioplayer {
  // Implements swf mp3 player from http://www.1pixelout.net/code/audio-player-wordpress-plugin
  static $controls = 0;
  var $control_id;
  var $params;

  public function __construct($params="") {
    $this->params = $params;
  }

  public function draw_clip(){
    static $libraries_shown = false;
    if (!$libraries_shown){
      $version = System::get_item_version('js_jdplayer');
      $path =   BASE_PATH."sysjs/jdplayer/".$version."/";
      Output::push(
        'javascript_top',
        "<script type=\"text/javascript\" src=\"".$path."mediaelement-and-player.min.js\"></script>"
      );
      Output::push(
        'style_include',
        "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$path."mediaelementplayer.css\" />"
      );
      Output::push(
        'javascript_onload',
        "  \$('audio').mediaelementplayer();\n"
      );
      $libraries_shown = true;
    }
    $params_arr =               explode("|",$this->params);
    $media_url =                array_shift($params_arr);
    $_args = array();
    foreach($params_arr as $param) {
      $param_bits =             explode("=",$param);
      $_args[$param_bits[0]] =   $param_bits[1];
    }
    $width =    (isset($_args['width'])  ? $_args['width']  : '180');
    return
       "\n"
      ."<audio controls=\"controls\" style=\"width:".$width."px\">\n"
      ."  <source src=\"".$media_url."\" type=\"audio/mp3\" />\n"
      ."</audio>\n";
  }

  public static function getVersion(){
    return VERSION_MEDIA_AUDIOPLAYER;
  }

}
?>