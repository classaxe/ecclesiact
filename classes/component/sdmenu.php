<?php
namespace Component;

define("VERSION_NS_COMPONENT_SDMENU", "1.0.8");
/*
Version History:
  1.0.8 (2015-08-11)
    1) Settings greatly simplified, CSS now derived from Navstyle CSS

*/
class SDMenu extends Base
{
    private $_Obj_NS;
    private $_suiteID;

    public function __construct()
    {
        $this->_ident =             "sd_menu";
        $this->_parameter_spec =    array(
            'buttonsuite_name' =>   array(
                'match' =>      '',
                'default' =>    'Home',
                'hint' =>       'name of buttonsuite to render'
            ),
            'debug' =>             array(
                'match' =>      'enum|0,1',
                'default' =>    0,
                'hint' =>       '0|1'
            ),
            'one_only' =>             array(
                'match' =>      'enum|0,1',
                'default' =>    0,
                'hint' =>       '0|1'
            ),
            'speed' =>                array(
                'match' =>      'range|0,10',
                'default' =>    3,
                'hint' =>       '0-10 speed for opening and closing'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel();
        $this->drawCss();
        $this->drawJsOnload();
        if (!$this->_suiteID) {
            $this->_html.=
             "<div style='border:1px solid red; background: #ffe0e0;padding: 0.25em;'>"
            ."<strong>Error for ".__CLASS__."::".__FUNCTION__."()</strong><br />\n"
            ."Specify a valid suite name for the control to render -<br />\n"
            ."'<b>".$this->_cp['buttonsuite_name']."</b>' is not a valid suite name."
            ."</div>";
            return $this->_html;
        }
        $this->_html.= $this->_tree;
        return $this->_html;
    }

    protected function drawCss()
    {

        \Page::push_content(
            'style', $this->css
        );
    }

    protected function drawJsOnload()
    {
        \Page::push_content(
            'javascript_onload',
            "  new SDMenu('".$this->_safe_ID."',".$this->_cp['speed'].",".$this->_cp['one_only'].").init();\n"
        );
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->_Obj_NS =            new \Nav\Suite;
        $this->setupLoadNavsuiteID();
        $this->setupLoadTree();
    }

    protected function setupFixIe7SpacesBug()
    {
      // see http://stackoverflow.com/questions/2923735/css-ul-li-gap-in-ie7
        $this->_tree = str_replace(
            array(
                "</li>\n  <li>",
                "</li>\n    <li>"
            ),
            array(
                "</li><li>",
                "</li><li>"
            ),
            $this->_tree
        );
    }

    protected function setupLoadNavsuiteID()
    {
        $this->_suiteID =           $this->_Obj_NS->get_ID_by_name($this->_cp['buttonsuite_name']);
        $this->navSuite =           new \Nav\Suite($this->_suiteID);
        $this->navStyle =           new \Nav\Style($this->navSuite->get_field('buttonStyleID'));
        $this->css =                str_replace('#ID', '#'.$this->_safe_ID, $this->navStyle->get_field('css'));
    }

    protected function setupLoadTree()
    {
        if (!$this->_suiteID) {
            return;
        }
        $this->_tree =  $this->_Obj_NS->getTree(false, $this->_suiteID, 0, true);
        $ulEnd = strpos($this->_tree, '</ul>');
        $ul = substr($this->_tree, 0, $ulEnd);
        $firstQuote =   1+strpos($this->_tree, "'");
        $firstUL =      1+strpos($this->_tree, "\n");
        $this->_tree =
             "<ul id=\"".$this->_safe_ID."\" class=\"sdmenu\">\n"
            ."  <li class=\"border_top\"></li>\n"
            .substr($this->_tree, $firstUL, -6)
            ."  <li class=\"border_bottom\"></li>\n"
            ."</ul>";
        if ($this->_cp['debug']) {
            print "<textarea style='width:1200px;height:200px'>".$this->_tree."</textarea>";
        }
        $this->setupFixIe7SpacesBug();
    }


    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_SDMENU;
    }
}
