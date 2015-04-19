<?php
namespace Component;

define("VERSION_NS_COMPONENT_ICONSITEMAP", "1.0.0");
/*
Version History:
  1.0.0 (2015-04-19)
    1) Moved from HTML::draw_icon('sitemap') and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class IconSiteMap extends Base
{
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
            ."<img src='".BASE_PATH."img/spacer' class='toolbar_icon' width='26' height='16'"
            ." alt='Click to view Sitemap' /></a>"
            .($this->_cp['show_label'] ?
                "<a href=\"".$this->_cp['URL']."\" style=\"float:left\">".$this->_cp['label']."</a>"
            :
                ""
            )
            ."</span>";
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_ICONSITEMAP;
    }
}
