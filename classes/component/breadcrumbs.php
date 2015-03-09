<?php
namespace Component;

define('VERSION_NS_COMPONENT_BREADCRUMBS', '1.0.5');
/*
Version History:
  1.0.5 (2015-03-08)
    1) Moved from class.component_breadcrumbs.php and reworked to use namespaces
    2) Now with much more modern component setup

*/
class Breadcrumbs extends Base
{
    public function __construct()
    {
        $this->_ident =         'breadcrumbs';
        $this->_parameter_spec = array(
            'skin' =>               array(
                'match' =>      'range|0,1',
                'default' =>    '0',
                'hint' =>       '0..1'
            ),
            'color_background' =>   array(
                'match' =>      '',
                'default' =>    'FFFFFF',
                'hint' =>       'Colour for background on which breadcrumbs sit'
            ),
            'color_button' =>       array(
                'match' =>      '',
                'default' =>    '004584',
                'hint' =>       'csv list of hex colour codes'
            ),
            'color_button_over' =>  array(
                'match' =>      '',
                'default' =>    '007CF0',
                'hint' =>       'csv list of hex colour codes'
            ),
            'color_house' =>        array(
                'match' =>      '',
                'default' =>    'FFFFFF',
                'hint' =>       'csv list of hex colour codes'
            ),
            'color_house_over' =>   array(
                'match' =>      '',
                'default' =>    'FFFF40',
                'hint' =>       'csv list of hex colour codes'
            ),
            'color_text' =>         array(
                'match' =>      '',
                'default' =>    'FFFFFF',
                'hint' =>       'csv list of hex colour codes'
            ),
            'color_text_over' =>    array(
                'match' =>      '',
                'default' =>    'FFFFFF',
                'hint' =>       'csv list of hex colour codes'
            )
        );
    }


    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        global $page_vars, $selectID;
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        if ($this->_cp['skin']) {
            $css =
             BASE_PATH.'css/breadcrumbs/'
            .\System::get_item_version('css_breadcrumbs').'/'
            .$this->_cp['color_button'].','
            .$this->_cp['color_button_over'].','
            .$this->_cp['color_background'].','
            .$this->_cp['color_house'].','
            .$this->_cp['color_house_over'].','
            .$this->_cp['color_text'].','
            .$this->_cp['color_text_over']
            ;
            \Page::push_content("style_include", "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$css."\" />");
        }
        $URL = \Page::get_URL($page_vars);
        $URL = (substr($URL, 0, strlen(BASE_PATH))==BASE_PATH ?
            substr($URL, strlen(BASE_PATH))
         :
            $URL
        );
        $path_arr = array();
        $url_arr = explode("/", $URL);
        $path = "";
        $path_arr[] = "  <li class='root'><a href='/".trim(BASE_PATH, '/')."'>Home</a></li>\n";
        if ($URL=='home') {
            return
                 $this->_html
                ."<ul id='breadcrumbs' class='breadcrumbs'>\n"
                .implode('', $path_arr)
                ."</ul>\n";
        }
        for ($i=0; $i<count($url_arr); $i++) {
            $u = $url_arr[$i];
            $path.= trim($u, '/').'/';
            $lbl = get_title_for_path($u);
            if ($path == 'register/' && $selectID) {
                $Obj_Event = new \Event($selectID);
                $Obj_Event->load();
                $path_arr[] =
                     "  <li class='sub'><a href='/".trim(BASE_PATH.$path, '/')."?selectID=".$selectID."'>"
                    ."Registering for Event '".title_case_string($Obj_Event->record['title'])."'"
                    ." (".$Obj_Event->record['effective_date_start'].")</a></li>\n";
            } else {
                if ($i!=0 || $u!='home') {
                    $path_arr[] =
                        "  <li class='sub'><a href='/".trim(BASE_PATH.$path, '/')."'>".$lbl."</a></li>\n";
                }
            }
        }
        return
             $this->_html
            ."<ul id='breadcrumbs' class='breadcrumbs'>\n"
            .implode('', $path_arr)
            ."</ul>\n";
    }

    public function getVersion()
    {
        return VERSION_NS_COMPONENT_BREADCRUMBS;
    }
}
