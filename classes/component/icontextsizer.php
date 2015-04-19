<?php
namespace Component;

define("VERSION_NS_COMPONENT_ICONTEXTSIZER", "1.0.0");
/*
Version History:
  1.0.0 (2015-04-16)
    1) Moved from HTML::draw_icon('text_sizer') and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class IconTextSizer extends Base
{
    public function __construct()
    {
        $this->_ident =
            "draw_text_sizer_button";
        $this->_parameter_spec =    array(
            'disable' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'label'   =>      array(
                'match' =>      '',
                'default' =>    'Text Size',
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
        $this->drawHTML();
        return $this->_html;
    }

    protected function drawHTML()
    {
        $this->drawControlPanel(true);
        if ($this->_cp['disable']) {
            return;
        }
        $size = (isset($_COOKIE['textsize']) ? $_COOKIE['textsize'] : "small");
        $this->_html.=
             "<span id='text_sizer_enlarge' class='icon_textsizer_enlarge noprint' title='Click to Enlarge Text'"
            .($size=='big' ? " style='display:none'" : "")
            .">"
            ."<a href=\"#\" onclick=\"toggleTextSize();return false;\">"
            ."<img alt='Enlarge Text' src='".BASE_PATH."img/spacer'"
            ." class='toolbar_icon' height=\"16\" width=\"16\" /></a>"
            .($this->_cp['show_label'] ?
                "<a href=\"#\" style=\"float:left\" onclick=\"toggleTextSize();return false;\">"
                .$this->_cp['label']
                ."</a>"
             :
                ""
            )
            ."</span>"
            ."<span id='text_sizer_reduce' class='icon_textsizer_reduce noprint' title='Click to Reduce Text'"
            .($size=='big' ? "" : " style='display:none'")
            .">"
            ."<a href=\"#\" onclick=\"toggleTextSize();return false;\">"
            ."<img alt='Reduce Text' src='".BASE_PATH."img/spacer'"
            ." class='toolbar_icon' height=\"16\" width=\"16\"/></a>"
            .($this->_cp['show_label'] ?
                "<a href=\"#\" style=\"float:left\" onclick=\"toggleTextSize();return false;\">"
                .$this->_cp['label']
                ."</a>"
             :
                ""
            )
            ."</span>";
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_ICONTEXTSIZER;
    }
}
