<?php
namespace Component;
/*
Version History:
  1.0.2 (2016-03-12)
    1) Replaced html width and height with inline CSS settings to protect against mangling by bootstrap
*/
class IconBookmark extends Base
{
    const VERSION = '1.0.2';

    public function __construct()
    {
        $this->_ident =
            "draw_bookmark_button";
        $this->_parameter_spec =    array(
            'disable' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'label'   =>      array(
                'match' =>      '',
                'default' =>    'Bookmark',
                'hint' =>       'Text for label (if shown)'
            ),
            'show_label'   => array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawHTML();
        return $this->_html;
    }

    protected function drawHTML()
    {
        global $page_vars;
        if ($this->_cp['disable']) {
            return;
        }
        $URL =
            ($_SERVER["SERVER_PORT"]==443 ? "https://" : "http://")
            .$_SERVER["HTTP_HOST"]
            .BASE_PATH
            .trim($page_vars['path'], '/');
        $this->_html.=
             "<span class='icon_bookmark noprint' title='Click to add a bookmark'>"
            ."<a href=\"#\""
            ." onclick=\"add_bookmark('".$URL."','".addslashes($page_vars['title'])."');return false;\">"
            ."<img alt='Add Bookmark' src='".BASE_PATH."img/spacer' class='toolbar_icon'"
            ." style='width:15px;height:15px'"
            ."/></a>"
            .($this->_cp['show_label'] ?
                "<a href=\"#\""
                ." onclick=\"add_bookmark('".$URL."','".addslashes($page_vars['title'])."');return false;\""
                ." style=\"float:left\">".$this->_cp['label']."</a>"
            :
                ""
            )
            ."</span>";
    }
}
