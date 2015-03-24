<?php
namespace Map;

define('VERSION_NS_GOOGLE_MAP', '1.0.0');
/*
Version History:
  1.0.0 (2015-03-23)
    1) Moved here from class.google_map.php which is now just a stub class
    2) References to class Geocode_Cache now point to map\geocodeCache

*/
class GoogleMap
{
    public $function_code;
    public $function_code_loader;
    public $id;
    public $key;
    private $_map_options = array();
    public static $js_lib_included = false;
    public static $status_text = array(
        'OK' =>               'Success',
        'ZERO_RESULTS' =>     'Address unknown',
        'OVER_DAILY_LIMIT' => 'You issued too many requests for today',
        'OVER_QUERY_LIMIT' => 'You issued too many requests too quickly',
        'REQUEST_DENIED' =>   'Request denied - did you include the sensor parameter?',
        'INVALID_REQUEST' =>  'Some important information is missing in your request'
    );

    public function __construct($id = false)
    {
        $this->sourceID = $id;
        $this->id = "google_map_".$id;
        $this->function_code_loader = "";
    }

    public function addCodeLoader($code)
    {
        $this->function_code_loader.=$code;
    }

    public function addControlLarge()
    {
        $this->_map_options[] = "navigationControlOptions: { position: google.maps.ControlPosition.LEFT_TOP }";
    }

    public function addControlSmall()
    {
        $this->addCodeLoader(
            "    _".$this->id.".addControl(new google.maps.SmallMapControl());\n"
        );
    }

    public function addControlScale()
    {
        $this->_map_options[] = "scaleControl: true";
        $this->_map_options[] = "scaleControlOptions: { position: google.maps.ControlPosition.BOTTOM_LEFT }";
    }

    public function addControlOverview()
    {
        $this->addCodeLoader(
            "    _".$this->id.".addControl(new google.maps.OverviewMapControl());\n"
        );
    }

    public function addControlType()
    {
        $this->_map_options[] = "mapTypeControl: true";
    }

    public function addControlZoom()
    {
        $this->addCodeLoader(
            "    _".$this->id.".addControl(new google.maps.SmallZoomControl());\n"
        );
    }

    public function addControlZoomDblclick()
    {
        $this->addCodeLoader(
            "    _".$this->id.".enableContinuousZoom();\n"
            ."    _".$this->id.".enableDoubleClickZoom();\n"
        );
    }

    public function addControlZoomScrollwheel()
    {
        $this->addCodeLoader(
            "    _".$this->id.".enableContinuousZoom();\n"
            ."    _".$this->id.".enableScrollWheelZoom();\n"
        );
    }

    public function addIcon($path, $name)
    {
      // You can make these at http://www.powerhut.co.uk/googlemaps/custom_markers.php
        $doc =  file_get_contents(".".$path."icon.js");
        $doc =  str_replace("myIcon", $name, $doc);
        $doc =  str_replace("marker-images/", $path, $doc);
        $this->addCodeLoader($doc."\n\n");
    }

    public function addCircle(
        $lat,
        $lon,
        $circle_radius,
        $circle_line_color = '#FF0000',
        $circle_line_width = '2',
        $circle_line_opacity = '0.3',
        $circle_fill_color = '#ff0000',
        $circle_fill_opacity = '0.1',
        $title = '',
        $markertype = 'green'
    ) {
        $map_id =       "_".$this->id;
        $shape_id =     "_".$this->id."_shape";
        $marker_id =    $this->add_marker($lat, $lon, false, $markertype, $title);
        $this->addCodeLoader(
            "    var ".$shape_id." = new google.maps.Circle({\n"
            ."      map: ".$map_id.",\n"
            ."      radius: ".$circle_radius.",\n"
            ."      strokeColor: '".$circle_line_color."',\n"
            ."      strokeOpacity: ".$circle_line_opacity.",\n"
            ."      strokeWeight: ".$circle_line_width.",\n"
            ."      fillColor: '".$circle_fill_color."',\n"
            ."      fillOpacity: '".$circle_fill_opacity."'\n"
            ."    });\n"
            ."    ".$shape_id.".bindTo('center', ".$marker_id.", 'position');\n"
        );
        return $shape_id;
    }

    public function addMarker($lat, $lon, $dragable = true, $icon = '', $title = '')
    {
        $map_id =       "_".$this->id;
        $marker_id =    "_".$this->id."_marker";
        $i = $this->getMarkerIcon($icon);
        $this->addCodeLoader(
            "    var ".$marker_id." = new google.maps.Marker({\n"
            ."      map: ".$map_id.",\n"
            .($i['icon'] ?   "      icon: ".$i['icon'].",\n" : "")
            .($i['shadow'] ? "      shadow: ".$i['shadow'].",\n" : "")
            .($i['shape'] ?  "      shape: ".$i['shape'].",\n" : "")
            .($title ?       "      title: \"".$title."\",\n" : "")
            ."      draggable: ".($dragable ? 'true' : 'false').",\n"
            ."      position: new google.maps.LatLng(".$lat.",".$lon.")\n"
            ."    });\n"
        );
        return $marker_id;
    }

    public function addMarkerWithHtml(
        $lat,
        $lon,
        $html,
        $id,
        $dragable = false,
        $REDUNDANT_PARAMETER = false,
        $icon = '',
        $infoWindowOpen = false,
        $title = '',
        $circle_radius = false,
        $circle_line_color = '#FF0000',
        $circle_line_width = 2,
        $circle_line_opacity = 0.3,
        $circle_fill_color = '#ff0000',
        $circle_fill_opacity = 0.1
    ) {
        global $page_vars;
        $map_id =               "_".$this->id;
        $marker_id =            "_".$this->id."_marker_".$id;
        $marker_actions_div =   "_".$this->id."_marker_".$id."_actions";
        $html_lines =           explode("<br />", $html);
        $html_sanitized =       array();
        foreach ($html_lines as $h) {
            if ($h!='') {
                $html_sanitized[] = $h;
            }
        }
        $html = implode("<br />", $html_sanitized);
        $html =
             "<div>"
            .$html
            .($dragable ?
                 "<div id='".$marker_actions_div."' style='padding:0;margin:3px 0;clear:both;font-size:8pt;'>"
                ."<b>[ Map marker: "
                ."<a href='#' onclick='return ecc_map.point.s(".$id.",".$marker_id.","
                ."\\\"".BASE_PATH.trim($page_vars['relative_URL'], '/')."\\\","
                ."\\\"".$this->id."_map_save\\\",\\\"".$marker_actions_div."\\\")' title='Save changes to map marker'>"
                ."<b>Save</b></a>"
                ." | "
                ."<a href='#' onclick='return ecc_map.point.r(".$marker_id.")' title='Undo changes to map marker'>"
                ."<b>Reset</b></a>"
                ." ]</b></div>"
             :
                ""
            )
            ."</div>";
        $i = $this->getMarkerIcon($icon);
        $this->addCodeLoader(
            "    ".$marker_id." = new ecc_map.point("
            ."_".$this->id.",".$lat.",".$lon.","
            ."\"".($title ? $title : 'Click for information')."\","
            ."\"".str_replace('/', '\/', $html)."\""
            .($infoWindowOpen || $dragable || $icon ?
                ","
                .($infoWindowOpen ? '1' : '0').","
                .($dragable ? '1' : '0').","
                .($i['icon'] ? $i['icon'] : 0).","
                .($i['shadow'] ? $i['shadow'] : 0).","
                .($i['shape'] ? $i['shape'] : 0)
            :
                ""
            )
            .");\n"
        );
        if ($circle_radius!==false) {
            $map_id =       "_".$this->id;
            $shape_id =     "_".$this->id."_circle";
            $this->addCodeLoader(
                "    var ".$shape_id." = new google.maps.Circle({\n"
                ."      map: ".$map_id.",\n"
                ."      radius: ".$circle_radius.",\n"
                ."      strokeColor: '".$circle_line_color."',\n"
                ."      strokeOpacity: ".$circle_line_opacity.",\n"
                ."      strokeWeight: ".$circle_line_width.",\n"
                ."      fillColor: '".$circle_fill_color."',\n"
                ."      fillOpacity: '".$circle_fill_opacity."'\n"
                ."    });\n"
                ."    ".$shape_id.".bindTo('center', ".$marker_id.", 'position');\n"
            );
        }
        return $marker_id;
    }

    public function getMarkerIcon($name)
    {
        $shadow =   '';
        $shape =    '';
        switch(strToLower($name)){
            case '':
                $icon =     '';
                break;
            case 'h':
                $icon =     "'".BASE_PATH."img/icon/6044/19'";
                break;
            case 'c':
            case 'w':
                $icon =     "'".BASE_PATH."img/icon/6063/19'";
                break;
            case 'red':
            case 'blue':
            case 'purple':
            case 'orange':
            case 'pink':
            case 'lightblue':
            case 'yellow':
            case 'green':
                $icon =     "'//maps.google.com/mapfiles/ms/icons/".strToLower($name).".png'";
                break;
            default:
                $icon =     $name.".image";
                $shadow =   $name.".shadow";
                $shape =    $name.".shape";
                break;
        }
        return array(
            'icon' =>     $icon,
            'shadow' =>   $shadow,
            'shape' =>    $shape
        );
    }

    public function draw($args = array())
    {
        global $system_vars;
        $map_height =                   (isset($args['map_height']) ?
            $args['map_height']
         :
            500
        );
        $map_width =                    (isset($args['map_width']) ?
            $args['map_width']
         :
            500
        );
        $control_large =                (isset($args['control_large']) ?
            $args['control_large']
         :
            0
        );
        $control_overview =             (isset($args['control_overview']) ?
            $args['control_overview']
         :
            0
        );
        $control_scale =                (isset($args['control_scale']) ?
            $args['control_scale']
         :
            0
        );
        $control_small =                (isset($args['control_small']) ?
            $args['control_small']
         :
            0
        );
        $control_type =                 (isset($args['control_type']) ?
            $args['control_type']
         :
            0
        );
        $control_zoom =                 (isset($args['control_zoom']) ?
            $args['control_zoom']
         :
            0
        );
        $control_zoom_ondblclick =      (isset($args['control_zoom_ondblclick']) ?
            $args['control_zoom_ondblclick']
         :
            0
        );
        $control_zoom_onscrollwheel =   (isset($args['control_zoom_onscrollwheel']) ?
            $args['control_zoom_onscrollwheel']
         :
            0
        );

        if ($control_large) {
            $this->addControlLarge();
        }
        if ($control_small) {
            $this->addControlSmall();
        }
        if ($control_overview) {
            $this->addControlOverview();
        }
        if ($control_scale) {
            $this->addControlScale();
        }
        if ($control_type) {
            $this->addControlType();
        }
        if ($control_zoom) {
            $this->addControlZoom();
        }
        if ($control_zoom_ondblclick) {
            $this->addControlZoomDblclick();
        }
        if ($control_zoom_onscrollwheel) {
            $this->addControlZoomScrollwheel();
        }

        $this->jsSetup();
        if (!$system_vars['debug_no_internet']) {
            \Page::pushContent(
                'javascript_onload',
                "  ".$this->id."_code.push(new function(){\n"
                .$this->function_code_loader."\n"
                ."  });\n"
            );
        }
        return
             "<div class=\"google_map\""
            ." id=\"".$this->id."\""
            ." style=\"width:".$map_width."px;height:".$map_height."px;"
            .($system_vars['debug_no_internet'] ? "background:#a0c0a0;" : "")
            ."\">"
            .($system_vars['debug_no_internet'] ?
                 "<div style=\"line-height:".$map_height."px;text-align: center; font-size:24pt;\">"
                ."(No Internet Connection)</div>"
             :
                ""
            )
            ."</div>";
    }

    public static function drawObjectMapHtml()
    {
        $type =     get_var('type');
        if (!$type) {
            $reportID = sanitize('ID', get_var('reportID'));
            if (!$reportID) {
                print __CLASS__."::".__FUNCTION__."()<br />\nNeither type nor reportID was provided.";
                die;
            }
            $Obj_Report = new \Report($reportID);
            $type =       '\\'.$Obj_Report->get_field('primaryObject');
        }
        if (class_exists($type)) {
            $Obj = new $type;
            if (method_exists($Obj, 'drawObjectMapHtml')) {
                return $Obj->drawObjectMapHtml();
            }
            if (method_exists($Obj, 'draw_object_map_html')) {
                return $Obj->draw_object_map_html();
            }
        }
        print __CLASS__."::".__FUNCTION__."()<br />\nNot implemented for ".$type;
        die;
    }

    public static function findGeocode($address, $noCache = false)
    {
        global $msg;
        if (trim($address)=='') {
            return array(
                'ID' =>                     '',
                'lat' =>                    '',
                'lon' =>                    '',
                'code' =>                   '',
                'error' =>                  '',
                'match_address' =>          '',
                'match_area' =>             '',
                'match_quality' =>          '',
                'match_type' =>             '',
                'output_json' =>            '',
                'partial' =>                '',
                'query_date' =>             ''
            );
        }
        if ($result = static::getGeocodeTestLiteral($address)) {
            return $result;
        }
        $Obj_GC = new GeocodeCache;
        if (!$noCache) {
            $cache = $Obj_GC->getCachedLocation($address);
            if ($cache) {
                $date = new \DateTime('-'.GeocodeCache::MAX_CACHE_AGE.' days');
                if ($cache['query_date'] > $date->format('Y-m-d')) {
                    return array(
                        'ID' =>                 $cache['ID'],
                        'lat' =>                $cache['output_lat'],
                        'lon' =>                $cache['output_lon'],
                        'code' =>               'cached',
                        'error' =>              '',
                        'match_address' =>      $cache['match_address'],
                        'match_area' =>         $cache['match_area'],
                        'match_type' =>         $cache['match_type'],
                        'match_quality' =>      $cache['match_quality'],
                        'output_json' =>        $cache['output_json'],
                        'partial' =>            $cache['partial_match'],
                        'query_date' =>         $cache['query_date']
                    );
                }
                if ($Obj_GC->getDailyCount()>GeocodeCache::QUERIES_PER_DAY) {
                    return array(
                        'ID' =>                 $cache['ID'],
                        'lat' =>                $cache['output_lat'],
                        'lon' =>                $cache['output_lon'],
                        'code' =>               'old_result',
                        'error' =>              '',
                        'match_address' =>      $cache['match_address'],
                        'match_area' =>         $cache['match_area'],
                        'match_type' =>         $cache['match_type'],
                        'match_quality' =>      $cache['match_quality'],
                        'output_json' =>        $cache['output_json'],
                        'partial' =>            $cache['partial_match'],
                        'query_date' =>         $cache['query_date']
                    );
                }
                $Obj_GC->_set_ID($cache['ID']);
                $Obj_GC->delete();
            }
            if ($Obj_GC->getDailyCount()>=GeocodeCache::QUERIES_PER_DAY) {
                return array(
                    'ID' =>                     '',
                    'lat' =>                    '',
                    'lon' =>                    '',
                    'code' =>                   'OVER_DAILY_LIMIT',
                    'error' =>
                        'Lookup prevented - we are close to exceeding the maximum number of lookup per day',
                    'match_address' =>          '',
                    'match_area' =>             '',
                    'match_quality' =>          '',
                    'match_type' =>             '',
                    'output_json' =>            '',
                    'partial' =>                '',
                    'query_date' =>             ''
                );
            }
        }
        $url =
             "http://maps.googleapis.com/maps/api/geocode/json?address="
            .str_replace(' ', '+', urlencode(trim($address)))."&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!$response = json_decode(curl_exec($ch), true)) {
            return array(
                'ID' =>                     '',
                'lat' =>                    '',
                'lon' =>                    '',
                'code' =>                   "Connection Error",
                'error' =>                  "Couldn't connect to server. Please try again later.",
                'match_address' =>          '',
                'match_area' =>             '',
                'match_quality' =>          '',
                'match_type' =>             '',
                'output_json' =>            '',
                'partial' =>                '',
                'query_date' =>             ''
            );
        }
  //    y($response);
        if ($response['status']!='OK') {
            return array(
                'ID' =>                     '',
                'lat' =>                    '',
                'lon' =>                    '',
                'code' =>                   trim($response['status']),
                'error' =>
                    GoogleMap::$status_text[trim($response['status'])]."<br />Address: ".$address,
                'match_address' =>          '',
                'match_area' =>             '',
                'match_quality' =>          '',
                'match_type' =>             '',
                'output_json' =>            '',
                'partial' =>                '',
                'query_date' =>             date('Y-m-d', time())
            );
        }
        if (count($response['results'])>1) {
            $error =
             count($response['results'])
            ." possible matches for &quot;".$address."&quot;"
            ." - please refine your search:<ol class='map_choices'>";
            foreach ($response['results'] as $idx => $r) {
                $point = $r['geometry']['location']['lat'].' '.$r['geometry']['location']['lng'];
                $error.=
                     "<li>"
                    ."<a href='"
                    ."https://maps.google.com/maps?q=".$point."&amp;ie=UTF8&amp;z=17"
                    ."' onclick='popWin(this.href,\\\"map_".$idx."\\\","
                    ."\\\"location=1,status=0,scrollbars=0,resizable=1\\\",800,600,1);return false;'"
                    .">"
                    .$r['formatted_address']
                    ."</a></li>";
            }
            $error.=          "</ol>";
            return array(
                'ID' =>                     '',
                'lat' =>                    '',
                'lon' =>                    '',
                'code' =>                   'Multiple',
                'error' =>                  $error,
                'match_address' =>          '',
                'match_area' =>             '',
                'match_quality' =>          '',
                'match_type' =>             '',
                'output_json' =>            '',
                'partial' =>                '',
                'query_date' =>             date('Y-m-d', time())
            );
        }
  //    y($response);
        $result =   $response['results'][0]['geometry'];
        $lat =      $response['results'][0]['geometry']['location']['lat'];
        $lon =      $response['results'][0]['geometry']['location']['lng'];
        $partial =  (isset($response['results'][0]['partial_match']) ? $response['results'][0]['partial_match'] : 0);
        $match_type =       $response['results'][0]['geometry']['location_type'];
        $match_area =       0;
        $match_quality =    0;
        $match_address =    $response['results'][0]['formatted_address'];
        switch($match_type){
            case 'ROOFTOP':
                $match_quality = 100;
                break;
            case 'RANGE_INTERPOLATED':
            case 'GEOMETRIC_CENTER':
            case 'APPROXIMATE':
                if (isset($response['results'][0]['geometry']['bounds'])) {
                    $lat_1 = $response['results'][0]['geometry']['bounds']['northeast']['lat'];
                    $lon_1 = $response['results'][0]['geometry']['bounds']['northeast']['lng'];
                    $lat_2 = $response['results'][0]['geometry']['bounds']['southwest']['lat'];
                    $lon_2 = $response['results'][0]['geometry']['bounds']['southwest']['lng'];
                    $match_area = GoogleMap::getBoundsArea($lat_1, $lon_1, $lat_2, $lon_2);
                    $match_quality = 100*((14.5 - log(1+$match_area, 10))/14.5);
                } else {
                    $match_quality = 100;
                }
                break;
        }
        $result = array(
            'lat' =>                  $lat,
            'lon' =>                  $lon,
            'code' =>                 'live',
            'error' =>                '',
            'match_address' =>        $match_address,
            'match_area' =>           $match_area,
            'match_quality' =>        $match_quality,
            'match_type' =>           $match_type,
            'output_json' =>          $Obj_GC->escape_string(serialize($response['results'][0])),
            'partial' =>              $partial,
            'query_date' =>           date('Y-m-d', time())
        );
        if (!$noCache) {
            $data = array(
                'systemID' =>             SYS_ID,
                'input_address' =>        $Obj_GC->escape_string($address),
                'match_address' =>        $match_address,
                'match_area' =>           $match_area,
                'match_quality' =>        $match_quality,
                'match_type' =>           $match_type,
                'output_json' =>          $Obj_GC->escape_string(serialize($response['results'][0])),
                'output_lat' =>           $lat,
                'output_lon' =>           $lon,
                'partial_match' =>        $partial,
                'query_date' =>           date('Y-m-d', time())
            );
            $result['ID'] = $Obj_GC->insert($data);
        }
        return $result;
    }

    public static function getBounds($records = array(), $prefix = '')
    {
        $valid = false;
        $range = array(
            'min_lat'=>90,
            'max_lat'=>-90,
            'min_lon'=>180,
            'max_lon'=>-180
        );
        $field_lat =    $prefix.'map_lat';
        $field_lon =    $prefix.'map_lon';
        foreach ($records as $item) {
            if ($item[$field_lat]!=0 || $item[$field_lon]!=0) {
                $valid = true;
                if ($item[$field_lat]<$range['min_lat']) {
                    $range['min_lat'] = $item[$field_lat];
                }
                if ($item[$field_lat]>$range['max_lat']) {
                    $range['max_lat'] = $item[$field_lat];
                }
                if ($item[$field_lon]<$range['min_lon']) {
                    $range['min_lon'] = $item[$field_lon];
                }
                if ($item[$field_lon]>$range['max_lon']) {
                    $range['max_lon'] = $item[$field_lon];
                }
            }
        }
        if ($range['min_lat']==$range['max_lat'] && $range['min_lon']==$range['max_lon']) {
            return false;
        }
        return $range;
        return ($valid ? $range : false);
    }

    public static function getBoundsArea($lat_a, $lon_a, $lat_b, $lon_b)
    {
        $radius = 6372795.477598; // at equator;
        $circum = 2*$radius*M_PI;
        $lat_mid = ($lat_a+$lat_b)/2;
        $width =
        (2*$radius * asin(
            min(
                1,
                sqrt(
                    cos(deg2rad($lat_mid)) *
                    cos(deg2rad($lat_mid)) *
                    sin(deg2rad(($lon_b - $lon_a)/2)) *
                    sin(deg2rad(($lon_b - $lon_a)/2))
                )
            )
        ));
        $lat_a_pos = (($lat_a*($radius*M_PI))/180);
        $lat_b_pos = (($lat_b*($radius*M_PI))/180);
        $height =     abs($lat_a_pos-$lat_b_pos);
        return $width*$height;
    }

    public static function getGeocode($address)
    {
        global $msg;
        $result = GoogleMap::findGeocode($address);
        if ($result['error']=='') {
            return $result;
        }
        $msg.= "<li>Google Maps Lookup error:<br />".$result['error']."</li>";
        return false;
    }

    protected static function getGeocodeTestLiteral($address)
    {
        $a = preg_split('/[, ]+/', $address);
        if (count($a)!=2) {
            return false;
        }
        if (sanitize('range', trim($a[0]), -90, 90, false)===false) {
            return false;
        }
        if (sanitize('range', trim($a[1]), -180, 180, false)===false) {
            return false;
        }
        return array(
            'ID' =>                     $address,
            'lat' =>                    trim($a[0]),
            'lon' =>                    trim($a[1]),
            'code' =>                   '',
            'error' =>                  '',
            'match_address' =>          '',
            'match_area' =>             0,
            'match_quality' =>          100,
            'match_type' =>             'ACTUAL',
            'output_json' =>            '',
            'partial' =>                0,
            'query_date' =>             ''
        );
    }


    public static function getSqlMapRange($args)
    {
        if (
        !isset($args) ||
        !isset($args['lat']) ||
        !isset($args['lon']) ||
        !isset($args['units']) ||
        !isset($args['lat_field']) ||
        !isset($args['lon_field'])
        ) {
            die(__CLASS__."::".__FUNCTION__."() expects array with lat, lon, units (km|mile), lat_field, lon_field");
        }
        switch(strToLower($args['units'])){
            case "km":
                $multiplier = 111.05;
                break;
            case "mile":
                $multiplier = 69;
                break;
            default:
                die(__CLASS__."::".__FUNCTION__."() Units must be either km or mile");
            break;
        }
        return
             "  DEGREES(\n"
            ."    ACOS(\n"
            ."      SIN(\n"
            ."        RADIANS(".$args['lat'].")\n"
            ."      ) *\n"
            ."      SIN(\n"
            ."        RADIANS(".$args['lat_field'].")\n"
            ."      ) +\n"
            ."      COS(\n"
            ."        RADIANS(".$args['lat'].")\n"
            ."      ) *\n"
            ."      COS(\n"
            ."        RADIANS(".$args['lat_field'].")\n"
            ."      ) *\n"
            ."      COS(\n"
            ."        RADIANS(".$args['lon']." - ".$args['lon_field'].")\n"
            ."      )\n"
            ."    )\n"
            ."  ) * ".$multiplier;
    }

    public static function getSqlMapRangeFilter($args)
    {
        if (
            !isset($args) ||
            !isset($args['lat']) ||
            !isset($args['lon']) ||
            !isset($args['range']) ||
            !isset($args['units']) ||
            !isset($args['lat_field']) ||
            !isset($args['lon_field'])
        ) {
            die(
                __CLASS__."::".__FUNCTION__."() expects array with lat, lon, range,"
               ." units (km|mile), lat_field, lon_field"
            );
        }
        switch(strToLower($args['units'])){
            case "km":
                $multiplier = 111.05;
                break;
            case "mile":
                $multiplier = 69;
                break;
            default:
                die(__CLASS__."::".__FUNCTION__."() Units must be either km or mile");
            break;
        }
        return
             "  ROUND(\n"
            ."    DEGREES(\n"
            ."      ACOS(\n"
            ."        SIN(\n"
            ."          RADIANS(".$args['lat'].")\n"
            ."        ) *\n"
            ."        SIN(\n"
            ."          RADIANS(".$args['lat_field'].")\n"
            ."        ) +\n"
            ."        COS(\n"
            ."          RADIANS(".$args['lat'].")\n"
            ."        ) *\n"
            ."        COS(\n"
            ."          RADIANS(".$args['lat_field'].")\n"
            ."        ) *\n"
            ."        COS(\n"
            ."          RADIANS(".$args['lon']." - ".$args['lon_field'].")\n"
            ."        )\n"
            ."      )\n"
            ."    ) *\n"
            ."    ".$multiplier." < ".$args['range']."\n"
            ."  )";
    }

    public function jsSetup()
    {
        global $system_vars;
        if ($system_vars['debug_no_internet']) {
            \Page::pushContent(
                'javascript',
                "var ".$this->id."_code=[];\n"
            );
            return "";
        }
        if (!GoogleMap::$js_lib_included) {
            \Page::pushContent(
                'javascript_top',
                "<script type=\"text/javascript\" src=\"//maps.google.com/maps/api/js?sensor=false\"></script>\n"
            );
            GoogleMap::$js_lib_included = true;
        }
        \Page::pushContent(
            'javascript',
            "var ".$this->id."_code=[];\n"
            ."function ".$this->id."_load(){\n"
            ."  var options = {\n"
            ."    mapTypeId: google.maps.MapTypeId.ROADMAP".($this->_map_options ? ',' : '')."\n"
            ."    ".implode(",\n    ", $this->_map_options)."\n"
            ."  };\n"
            ."  _".$this->id." = new google.maps.Map(geid(\"".$this->id."\"),options);\n"
            ."  infoWindow = new google.maps.InfoWindow();\n"
            ."  for(var i=0; i<".$this->id."_code.length; i++){\n"
            ."     ".$this->id."_code[i]();\n"
            ."  }\n"
            ."}\n"
        );
        \Page::pushContent("javascript_onload", "  try{".$this->id."_load();}catch(err){}\n");
    }

    public function mapLoad()
    {
        return;
    }

    public function mapCentre($lat, $lon, $zoom = 13)
    {
        $this->_map_options[] = "center: new google.maps.LatLng(".$lat.",".$lon.")";
        $this->_map_options[] = "zoom: ".$zoom;
    }

    public function mapZoomToFit($range)
    {
        $this->addCodeLoader(
            "    var bounds = new google.maps.LatLngBounds(\n"
            ."      new google.maps.LatLng(".$range['min_lat'].",".$range['min_lon']."),\n"
            ."      new google.maps.LatLng(".$range['max_lat'].",".$range['max_lon'].")\n"
            ."    );\n"
            ."    _".$this->id.".fitBounds(bounds);\n"
        );
    }

    public function mapZoomToFitShape($shape_id)
    {
        $this->addCodeLoader(
            "    _".$this->id.".fitBounds($shape_id.getBounds());\n"
        );
    }

    public static function onScheduleUpdatePending()
    {
        $Obj = new \Person;
        $Obj->on_schedule_update_pending(15);
        $Obj = new \Posting;
        $Obj->on_schedule_update_pending(15);
    }

    public static function getVersion()
    {
        return VERSION_NS_GOOGLE_MAP;
    }
}
