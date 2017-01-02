<?php
/*
Version History:
  1.0.6 (2016-12-31)
    1) Component_Events_Map::_setup_load_event_IDs() now uses newly named getFilteredSortedAndPagedRecords() method
    2) PSR-2 fixes
*/
class Component_Events_Map extends Component_Base
{
    const VERSION = '1.0.6';

    protected $eventIDs;
    protected $ObjEvent;

    public function __construct()
    {
        $this->_ident =             "events_map";
        $this->_parameter_spec = array(
            'filter_category_list' =>       array(
                'match' =>      '',
                'default' =>    '*',
                'hint' =>       '*|CSV value list'
            ),
            'filter_category_master' =>     array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Optionally INSIST on this category'
            ),
            'filter_date_duration' =>       array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'If filter_what is future or past, this further limits to given date range'
            ),
            'filter_date_units' =>          array(
                'match' =>      'enum|,day,week,month,quarter,year',
                'default' =>    '',
                'hint' =>
                    ' |day|week|month|quarter|year -'
                   .' If filter_what is future or past, this provides units for given date range'
            ),
            'filter_important' =>           array(
                'match' =>      'enum|,0,1',
                'default' =>    '',
                'hint' =>       'Blank to ignore, 0 for not important, 1 for important'
            ),
            'filter_memberID' =>            array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Community Member to restrict by that criteria'
            ),
            'filter_personID' =>            array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Person to restrict by that criteria'
            ),
            'filter_range_address' =>       array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Address to search for to obtain lat and lon'
            ),
            'filter_range_distance' =>      array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'Limits results to those events occuring within this distance of given location'
            ),
            'filter_range_lat' =>           array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Latitude of search point'
            ),
            'filter_range_lon' =>           array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Longitude of search point'
            ),
            'filter_range_units' =>         array(
                'match' =>      'enum|km,mile',
                'default' =>    'km',
                'hint' =>       'Units of measurement to search point'
            ),
            'filter_what' =>                array(
                'match' =>      'enum|all,future,month,past,year',
                'default' =>    'month',
                'hint' =>       'all|future|month|past'
            ),
            'height' =>                     array(
                'match' =>      'range|100,n',
                'default' =>    '600',
                'hint' =>       'Height of map and listings panel'
            ),
            'list_fixed_height' =>          array(
                'match' =>      'range|0,1',
                'default' =>    '1',
                'hint' =>       'Whether or not to limit the list to the overall height'
            ),
            'list_width' =>                 array(
                'match' =>      'range|100,n',
                'default' =>    '315',
                'hint' =>       'Width for listing column'
            ),
            'map_title' =>                  array(
                'match' =>      'html',
                'default' =>    'Person addresses',
                'hint' =>       'Label to use for items shown'
            ),
            'maximize' =>                   array(
                'match' =>      'range|0,1',
                'default' =>    '0',
                'hint' =>       'Whether or not to maximize size'
            ),
            'results_limit' =>              array(
                'match' =>      'range|0,n',
                'default' =>    '3',
                'hint' =>       '0..n'
            ),
            'width' =>                      array(
                'match' =>      'range|100,n',
                'default' =>    '600',
                'hint' =>       'Width of map and listings panel'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawCss();
        $this->drawMap();
        return $this->_html;
    }

    protected function drawCss()
    {
        if (!$this->_cp['list_fixed_height']) {
            return;
        }
        $css =
             "#google_map_".$this->_safe_ID."_listing {\n"
            ."  height: ".$this->_cp['height']."px; width: ".$this->_cp['list_width']."px; overflow:auto;\n"
            ."}\n"
            ."#google_map_".$this->_safe_ID."_listing .when {\n"
            ."  display:inline-block; width:10.5em; font-weight: 900;\n"
            ."}\n";
        Output::push('style', $css);
    }

    protected function drawMap()
    {
        $this->_html.=          $this->ObjEvent->drawObjectMapHtml($this->_safe_ID);
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->ObjEvent =   new Event;
        $this->_filter_offset =     (isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0);
        $this->setupLoadEventIDs();
        $this->setupPersonMapPostVariables();
    }

    protected function setupLoadEventIDs()
    {
        global $YYYY, $MM;
        $this->ObjEvent->set_group_concat_max_len(1000000);
        $results = $this->ObjEvent->getFilteredSortedAndPagedRecords(
            array(
                'byRemote' =>
                    false,
                'filter_category_list' =>
                    $this->_cp['filter_category_list'],
                'filter_category_master' =>
                    (isset($this->_cp['filter_category_master']) ?    $this->_cp['filter_category_master'] : false),
                'filter_container_path' =>
                    (isset($this->_cp['filter_container_path']) ?     $this->_cp['filter_container_path'] : ''),
                'filter_container_subs' =>
                    (isset($this->_cp['filter_container_subs']) ?     $this->_cp['filter_container_subs'] : ''),
                'filter_date_DD' =>
                    '',
                'filter_date_MM' =>
                    $MM,
                'filter_date_YYYY' =>
                    $YYYY,
                'filter_date_duration' =>
                    (isset($this->_cp['filter_date_duration']) ?      $this->_cp['filter_date_duration'] : ''),
                'filter_date_units' =>
                    (isset($this->_cp['filter_date_units']) ?         $this->_cp['filter_date_units'] : ''),
                'filter_range_address' =>
                    (isset($this->_cp['filter_range_address']) ?      $this->_cp['filter_range_address'] : ''),
                'filter_range_distance' =>
                    (isset($this->_cp['filter_range_distance']) ?     $this->_cp['filter_range_distance'] : ''),
                'filter_range_lat' =>
                    (isset($this->_cp['filter_range_lat']) ?          $this->_cp['filter_range_lat'] : ''),
                'filter_range_lon' =>
                    (isset($this->_cp['filter_range_lon']) ?          $this->_cp['filter_range_lon'] : ''),
                'filter_range_units' =>
                    (isset($this->_cp['filter_range_units']) ?        $this->_cp['filter_range_units'] : ''),
                'filter_important' =>
                    (isset($this->_cp['filter_important']) ?          $this->_cp['filter_important'] : ''),
                'filter_memberID' =>
                    (isset($this->_cp['filter_memberID']) ?           $this->_cp['filter_memberID'] : ''),
                'filter_personID' =>
                    (isset($this->_cp['filter_personID']) ?           $this->_cp['filter_personID'] : ''),
                'results_limit' =>
                    $this->_cp['results_limit'],
                'results_offset' =>
                    $this->_filter_offset,
                'results_order' =>
                    (isset($this->_cp['results_order']) ?             $this->_cp['results_order'] : 'date'),
                'filter_what' =>
                    (isset($this->_cp['filter_what']) ?               $this->_cp['filter_what'] : 'all')
            )
        );
        $this->_records =           $results['data'];
        $this->_records_total =     $results['total'];
        $IDs = array();
        foreach ($this->_records as $r) {
            $IDs[] = $r['ID'];
        }
        $this->eventIDs =        implode(',', $IDs);
    }

    protected function setupPersonMapPostVariables()
    {
        $_POST['ID'] =              $this->eventIDs;
        $_POST['height'] =          $this->_cp['height'];
        $_POST['width'] =           $this->_cp['width'];
        $_POST['map_title'] =       $this->_cp['map_title'];
        $_POST['lat_field'] =       'map_lat';
        $_POST['lon_field'] =       'map_lon';
        $_POST['loc_field'] =       'map_description';
        $_POST['maximize'] =        $this->_cp['maximize'];
    }
}
