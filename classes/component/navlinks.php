<?php
namespace Component;

define("VERSION_NS_COMPONENT_NAVLINKS", "1.0.2");
/*
Version History:
  1.0.2 (2015-08-03)
    1) Moved here from class.component_nav_links.php
    2) Now PSR-2 Compliant

*/
class NavLinks extends Base
{
    protected $_buttons;
    public function __construct()
    {
        $this->_ident =             'nav_links';
        $this->_parameter_spec =   array(
            'navsuite_number' =>                array(
                'match' =>      'range|1,3',
                'default' =>    '1',
                'hint' =>       'Number of navsuite to use 1-3'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawLinks();
        return $this->_html;
    }

    protected function drawLinks()
    {
        if (!$this->_buttons) {
            return;
        }
        $this->_html.= "<ul>";
        foreach ($this->_buttons as $button) {
            if ($button['visible']) {
                $URL = $button['URL'];
                if (substr($URL, 0, 8)=='./?page=') {
                    $URL = substr($URL, 8);
                }
                $this->_html.=
                "<li><a href=\"".$URL."\""
                .($button['popup'] ? " rel=\"external\"" : "")
                .($button['text2'] ? " title=\"".$button['text2']."\"" : "")
                .">"
                .$button['text1']
                ."</a></li>\n";
            }
        }
        $this->_html.= "</ul>";
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadNav();
    }

    protected function setupLoadNav()
    {
        global $page_vars;
        $index = 'navsuite'.$this->_cp['navsuite_number'].'ID';
        $Obj_NS = new \Nav\Suite($page_vars[$index]);
        $this->_buttons = $Obj_NS->getButtons();
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_NAVLINKS;
    }
}
