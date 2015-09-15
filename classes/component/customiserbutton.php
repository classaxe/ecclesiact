<?php
namespace Component;

define("VERSION_NS_COMPONENT_CUSTOMISER_BUTTON", "1.0.4");
/*
Version History:
  1.0.4 (2015-09-14)
    1) References to Page::push_content() now changed to Output::push()

*/
class CustomiserButton extends Base
{
    public function __construct()
    {
        $this->_ident =             "draw_customiser_button";
        $this->_parameter_spec =    array(
            'cancel' =>     array(
                'default' =>    'Cancel',
                'hint' =>       'Title for Cancel button in dialog'
            ),
            'ok' =>         array(
                'default' =>    'OK',
                'hint' =>       'Title for OK button in dialog'
            ),
            'presets' =>    array(
                'default' =>    '',
                'hint' =>       'Preset sequences that have been defined'
            ),
            'disable' =>    array(
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'targets' =>    array(
                'default' =>    'body|1|0,h1|0|1,h2|0|1',
                'hint' =>       'CSV list of css targets each with pipe-delimited parameters 0 or 1 '
                               .'for background-color and color palette tools'),
            'title' =>      array(
                'default' =>    'Site Customiser',
                'hint' =>       'Title for icon and for dialog'
            ),
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawHtml();
        return $this->_html;
    }

    protected function drawHtml()
    {
        $this->drawControlPanel(true);
        if (\Person::get_permission("SYSADMIN") && !$this->_cp['disable']) {
            $this->_html.=
                 "<span class='icon_customiser_button noprint' title='Click to customise colour scheme'>"
                ."<a href=\"#\" onclick=\"customise_colours.dialog("
                ."'".htmlentities($this->_cp['targets'])."',"
                ."'".htmlentities($this->_cp['title'])."',"
                ."'".htmlentities($this->_cp['ok'])."',"
                ."'".htmlentities($this->_cp['cancel'])."'"
                .");return false;\">"
                ."<img src='".BASE_PATH."img/spacer' class='toolbar_icon' alt='".$this->_cp['title']."'/></a>"
                ."</span>";
            \Output::push('javascript_top', '<script type="text/javascript" src="/sysjs/spectrum"></script>'."\n");
            \Output::push('style_include', '<link rel="stylesheet" href="/css/spectrum" />'."\n");
            \Output::push(
                'style',
                ".customiser label{ display: block; float: left; padding: 0 0.5em 0 0; }\n"
                .".customiser input.swatch { display: block; float: left; }\n"
                .".customiser input.spectrum { display: block; float: left; width:5em; height: 14px; line-height:14px;"
                ." padding: 0px; font-family: courier-new, monospace; font-size:8pt; }\n"
            );
            \Output::push('javascript', "customise_colours_presets = \"".$this->_cp['presets']."\";\n");
        }
    }

    public function save()
    {
        global $system_vars;
        $Obj_System =   new \System(SYS_ID);
        $parameters = explode(OPTION_SEPARATOR, $system_vars['component_parameters']);
        $existing = false;
        foreach ($parameters as &$entry) {
            $bits = explode('=', $entry);
            if ($bits[0]==='draw_customiser_button.presets') {
                $existing = true;
                $entry.=','.urldecode(get_var('targetValue'));
            }
        }
        if (!$existing) {
            $parameters[] = 'draw_customiser_button.presets='.urldecode(get_var('targetValue'));
        }
        $Obj_System->set_field('component_parameters', addslashes(implode(OPTION_SEPARATOR, $parameters)));
        \Output::push(
            'javascript_onload',
            "geid_set('command','');\n"
            ."geid('form').submit();\n"
        );
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_CUSTOMISER_BUTTON;
    }
}
