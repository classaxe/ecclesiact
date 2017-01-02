<?php
namespace Component;

/*
Version History:
  1.0.11 (2017-01-02)
    1) PSR-2 changes
*/
class CommunitiesDisplay extends Base
{
    const VERSION = '1.0.11';

    protected $CommunityListing =   false;
    protected $CommunityMemberListing =   false;
    protected $records =            array();

    public function __construct()
    {
        $this->_ident =             'communities_display';
        $this->_parameter_spec = array(
            'filter_active' =>         array(
                'match' =>      'enum|,0,1',
                'default' =>    '',
                'hint' =>       'Blank to ignore, 0 for not active, 1 for active'
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
            'show_communities_url' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1 - Whether or not to show the URL for communities'
            ),
            'show_list' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1 - Whether or not to show the listing of communities'
            ),
            'show_map' =>       array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1 - Whether or not to show the communities map'
            ),
            'show_member_count' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1 - Whether or not to show the number of members'
            ),
            'show_members' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1 - Whether or not to show the listing of members'
            ),
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
        foreach ($this->records as $r) {
            $this->CommunityListing->load($r);
            $inner_content = "";
            if ($this->_cp['show_members']) {
                $inner_content = "<ul>\n";
                foreach ($r['membersList'] as $m) {
                    $this->CommunityMemberListing->load($m);
                    $inner_content.= $this->CommunityMemberListing->draw();
                }
                $inner_content .= "</ul>\n";
            }
            $this->_html.= $this->CommunityListing->draw(
                $this->_cp['show_map'],
                $this->_cp['show_communities_url'],
                $this->_cp['show_member_count'],
                $inner_content
            );
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
        if (count($this->records)===0) {
            $this->_html.=
                 "<div id='".$this->_safe_ID."_frame' style='background:#e0e0e0'>\n"
                ."<h2 style='text-align:center'>Error</h2>"
                ."<p style='text-align:center'>No communities to map</p>"
                ."</div>\n";
            return;
        }
        $Obj_Map =      new \Google_Map($this->_safe_ID, SYS_ID);
        if (count($this->records)>1) {
            $points = array();
            foreach ($this->records as $r) {
                $points[] = array(
                    'map_lat' =>    $r['map_lat'],
                    'map_lon' =>    $r['map_lon']
                );
            }
            $range = \Google_Map::get_bounds($points);
            $Obj_Map->map_zoom_to_fit($range);
        } else {
            $Obj_Map->map_centre($this->records[0]['map_lat'], $this->records[0]['map_lon'], 6);
        }
        foreach ($this->records as $r) {
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
                (count($this->records)==1 ? true : false),
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
        $this->setupInitialiseObjects();
        $this->setupLoadRecords();
    }

    protected function setupInitialiseObjects()
    {
        $this->CommunityListing = new \CommunityListing;
        $this->CommunityListing->loadUserRights();
        $this->CommunityListing->_safe_ID = $this->_safe_ID;
        $this->CommunityMemberListing = new \CommunityMemberListing;
        $this->CommunityMemberListing->loadUserRights();
    }

    protected function setupLoadRecords()
    {
        $communities = $this->CommunityListing->get_communities();
        foreach ($communities as $c) {
            if ($this->_cp['filter_active']==='' ||
                ($this->_cp['filter_active']==='0' && $c['enabled']==='0') ||
                ($this->_cp['filter_active']==='1' && $c['enabled']==='1')
            ) {
                $this->records[] = $c;
            }
        }
        if ($this->_cp['show_members']) {
            foreach ($this->records as &$record) {
                $this->CommunityListing->_set_ID($record['ID']);
                $record['membersList'] = $this->CommunityListing->get_members();
            }
        }
    }
}
