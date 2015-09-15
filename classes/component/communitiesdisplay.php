<?php
namespace Component;

define('COMPONENT_NS_COMMUNITIES_DISPLAY_VERSION', '1.0.7');
/*
Version History:
  1.0.7 (2015-09-14)
    1) References to Page::push_content() now changed to Output::push()

*/

class CommunitiesDisplay extends Base
{
    protected $_Obj_Community =   false;
    protected $_records =         array();

    public function __construct()
    {
        $this->_ident =             'communities_display';
        $this->_parameter_spec = array(
            'filter_active' =>  array(
                'match' =>      'enum|,0,1',
                'default' =>    '1',
                'hint' =>       '|0|1 - 0 for inactive, 1 for active, blank for all'
            ),
            'map_height' =>     array(
                'match' =>      'range|0,n',
                'default' =>    '400',
                'hint' =>       '0-n'
            ),
            'map_width' =>      array(
                'match' =>      'range|0,n',
                'default' =>    '490',
                'hint' =>       '0-n'
            ),
            'show_list' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1 - Whether or not to show the listing of members'
            ),
            'show_map' =>       array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1 - Whether or not to allow map to show'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawCss();
        $this->drawControlPanel();
        $this->drawMap();
        $this->drawList();
        return $this->render();
    }

    protected function drawCss()
    {
        $css =
             "#".$this->_safe_ID."_frame {\n"
            ."  float: right;\n"
            ."  margin: 0 0 0 10px;\n"
            ."  border: 1px solid #888;\n"
            ."  width:".$this->_cp['map_width']."px;\n"
            ."  height:".$this->_cp['map_height']."px;\n"
            ."}\n"
            ."#".$this->_safe_ID."_listing {\n"
            ."  padding: 0 0 0 5px; margin: 5px 0 0 0; list-style-type: none;\n"
            ."}\n"
            ."#".$this->_safe_ID."_listing ul {\n"
            ."  background-image: url(/UserFiles/Image/layout/bullet_cross.gif) no-repeat;\n"
            ."}\n"
            ."#".$this->_safe_ID."_listing ul li em a{\n"
            ."  float: right;\n"
            ."}\n"
            ."#".$this->_safe_ID."_listing uli.inactive{\n"
            ."  color: #888 !important;\n"
            ."}\n"
            ."#".$this->_safe_ID."_listing ul li.inactive a{\n"
            ."  color: #888 !important;\n"
            ."}\n";
        \Output::push('style', $css);
    }

    protected function drawList()
    {
        if (!$this->_cp['show_list']) {
            return;
        }
        $this->_html.=
             "<div id='".$this->_safe_ID."_listing'>\n"
            ."<ul class='cross'>";
        foreach ($this->_records as $r) {
            $this->_Obj_Community->load($r);
            $this->_html.= $this->_Obj_Community->draw_listing($this->_cp['show_map']);
        }
        $this->_html.=
             "</ul>"
            ."</div>\n";
    }

    protected function drawMap()
    {
        if (!$this->_cp['show_map']) {
            return;
        }
        $Obj_Map =      new \Google_Map($this->_safe_ID, SYS_ID);
        if (count($this->_records)>1) {
            $points = array();
            foreach ($this->_records as $r) {
                $points[] = array(
                    'map_lat' =>    $r['map_lat'],
                    'map_lon' =>    $r['map_lon']
                );
            }
            $range = \Google_Map::get_bounds($points);
            $Obj_Map->map_zoom_to_fit($range);
        } else {
            $Obj_Map->map_centre($this->_records[0]['map_lat'], $this->_records[0]['map_lon'], 6);
        }
        foreach ($this->_records as $r) {
            if ($r['map_lat']==0 && $r['map_lon']==0) {
                continue;
            }
            $html_info  =
                 "<a href='".$r['URL']."'>".$r['title']."</a>"
                ." <i>(".$r['members']." members)</i>"
                ."<p><i>Public link:<br />"
                ."<a rel='external' href='".$r['URL_external']."'>".$r['URL_external']."</a></i></p>";
            $Obj_Map->add_marker_with_html(
                $r['map_lat'],
                $r['map_lon'],
                $html_info,
                $r['ID'],
                0,
                true,
                '',
                (count($this->_records)==1 ? true : false),
                htmlentities($r['title'])
            );
        }

        $args =     array(
            'map_width'=>($this->_cp['map_width']),
            'map_height'=>$this->_cp['map_height']
        );
        $this->_html.=
             "<div id='".$this->_safe_ID."_frame'>\n"
            .$Obj_Map->draw($args)
            ."</div>\n";
    }

    protected function render()
    {
        return
         "<div id=\"".$this->_safe_ID."\">\n"
        .$this->_html
        ."</div>\n";
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadRecords();
    }

    protected function setupLoadRecords()
    {
        $this->_Obj_Community = new \Extended_Community;
        $this->_Obj_Community->_load_user_rights();
        $this->_Obj_Community->_safe_ID = $this->_safe_ID;
        $this->_Obj_Community->_set_multiple(array('_cp'=>$this->_cp));
        $this->_records =       $this->_Obj_Community->get_communities();
    }

    public static function getVersion()
    {
        return COMPONENT_NS_COMMUNITIES_DISPLAY_VERSION;
    }
}
