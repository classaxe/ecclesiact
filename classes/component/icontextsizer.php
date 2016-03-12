<?php
namespace Component;
/*
Version History:
  1.0.2 (2016-03-12)
    1) Replaced html width and height with inline CSS settings to protect against mangling by bootstrap
*/
class IconTextSizer extends Base
{
    const VERSION = '1.0.2';

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
            ." class='toolbar_icon' style='width:16px;height:16px'/></a>"
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
            ." class='toolbar_icon' style='width:16px;height:16px'/></a>"
            .($this->_cp['show_label'] ?
                "<a href=\"#\" style=\"float:left\" onclick=\"toggleTextSize();return false;\">"
                .$this->_cp['label']
                ."</a>"
             :
                ""
            )
            ."</span>";
    }
}
