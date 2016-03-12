<?php
namespace Component;
/*
Version History:
  1.0.2 (2016-03-12)
    1) Replaced html width and height with inline CSS settings to protect against mangling by bootstrap
*/
class IconSiteMap extends Base
{
    const VERSION = '1.0.2';

    public function __construct()
    {
        $this->_ident =
            "draw_sitemap_button";
        $this->_parameter_spec =    array(
            'disable' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'label'   =>      array(
                'match' =>      '',
                'default' =>    'Sitemap',
                'hint' =>       'Text for label (if shown)'
            ),
            'show_label'   => array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'URL' =>          array(
                'match' =>      '',
                'default' =>    BASE_PATH.'sitemap',
                'hint' =>       'URL to go to'
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
        $this->_html.=
             "<span class='icon_sitemap noprint'><a href=\"#\" onclick=\"print_friendly();return false;\""
            ." title='Click to view Sitemap'>"
            ."<img src='".BASE_PATH."img/spacer' class='toolbar_icon' style='width:26px;height:16px'"
            ." alt='Click to view Sitemap' /></a>"
            .($this->_cp['show_label'] ?
                "<a href=\"".$this->_cp['URL']."\" style=\"float:left\">".$this->_cp['label']."</a>"
            :
                ""
            )
            ."</span>";
    }
}
