<?php
namespace Component;

define("VERSION_NS_COMPONENT_ICONPRINTFRIENDLY", "1.0.0");
/*
Version History:
  1.0.0 (2015-04-16)
    1) Moved from HTML::draw_icon('text_sizer') and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class IconPrintFriendly extends Base
{
    public function __construct()
    {
        $this->_ident =
            "draw_print_friendly_button";
        $this->_parameter_spec =    array(
            'disable' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'label'   =>      array(
                'match' =>      '',
                'default' =>    'Print Friendly',
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
        $this->_html.=
             "<span class='icon_print noprint'><a href=\"#\" onclick=\"print_friendly();return false;\""
            ." title='Click to see a Print-Friendly \nversion of this ".$page_vars['object_name']."'>"
            ."<img src='".BASE_PATH."img/spacer' class='toolbar_icon' width='20' height='16'"
            ." alt='Click to print' /></a>"
            .($this->_cp['show_label'] ?
                "<a href=\"#\" style=\"float:left\" onclick=\"print_friendly();return false;\">"
                .$this->_cp['label']
                ."</a>"
            :
                ""
            )
            ."</span>";
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_ICONPRINTFRIENDLY;
    }
}
