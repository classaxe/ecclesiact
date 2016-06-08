<?php
namespace Component;
/*
Version History:
  1.0.6 (2016-06-08)
    1) Added support for Instagram
*/
class IconSocial extends Base
{
    const VERSION = '1.0.6';

    public function __construct()
    {
        $this->_ident =
            "draw_social_icon";
        $this->_types_csv =
            "delicious,digg,email,facebook,flickr,google,instagram,linkedin,"
            ."rss,skype,stumbleupon,twitter,vimeo,youtube";
        $this->_parameter_spec =    array(
            'disable' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'icon' =>         array(
                'match' =>      'enum|'.$this->_types_csv,
                'default' =>    'facebook',
                'hint' =>       'Icon to use - '.implode('|', explode(',', $this->_types_csv))
            ),
            'size' =>         array(
                'match' =>      'enum|16,32',
                'default' =>    '16',
                'hint' =>       'Size of icon - 16 or 32'
            ),
            'tooltip' =>      array(
                'match' =>      '',
                'default' =>    'Facebook',
                'hint' =>       'Text to place on Tooltip'
            ),
            'url' =>          array(
                'match' =>      '',
                'default' =>    'http://',
                'hint' =>       ''
            ),
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawCSS();
        $this->drawHTML();
        return $this->_html;
    }

    protected function drawHTML()
    {
        $this->drawControlPanel(true);
        if ($this->_cp['disable']) {
            return;
        }
        $this->_html.=
             "<a href=\"".$this->_cp['url']."\" rel=\"external\" class='noprint social_icon_".$this->_cp['size']."'"
            ." title=\"".$this->_cp['tooltip']."\">"
            ."<img id=\"".$this->_js_safe_ID."\" src='".BASE_PATH."img/spacer' class=''"
            ." alt=\"".$this->_cp['tooltip']."\""
            ." style='width:".$this->_cp['size']."px;height:".$this->_cp['size']."px'"
            ."/></a>";
    }

    protected function drawCSS()
    {
        static $sections = array();
        if (!isset($sections['base_'.$this->_cp['size']])) {
            $this->_css.=
                 ".social_icon_".$this->_cp['size']." img {\n"
                ."    width:".$this->_cp['size']."px; height:".$this->_cp['size']."px; border: 0;\n"
                ."    background-image: url("
                .BASE_PATH."img/sysimg/social_icons_".$this->_cp['size']."x".$this->_cp['size'].".png"
                .");\n"
                ."}\n";
            $sections['base_'.$this->_cp['size']] = true;
        }
        if (!isset($sections['base_'.$this->_cp['size'].'_'.$this->_cp['icon']])) {
            $idx =    array_search($this->_cp['icon'], explode(',', $this->_types_csv));
            $offset = -1*$idx*$this->_cp['size'];
            $this->_css.=
                 ".social_icon_".$this->_cp['size']." #".$this->_js_safe_ID." {\n"
                ."    background-position:".$offset."px 0px;\n"
                ."}\n"
                .".social_icon_".$this->_cp['size']." #".$this->_js_safe_ID.":hover {\n"
                ."    background-position:".$offset."px ".(-1*$this->_cp['size'])."px;\n"
                ."}\n";
            $sections['base_'.$this->_cp['size'].'_'.$this->_cp['icon']] = true;
        }
        \Output::push('style', $this->_css);
    }
}
