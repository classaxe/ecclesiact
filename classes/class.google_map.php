<?php
define('VERSION_GOOGLE_MAP', '1.0.48');
/*
Version History:
  1.0.48 (2015-03-23)
    1) Moved main code into new namespaced class under map
    2) This class is now just a stub for backward compatability
    3) Method get_version() renamed to getVersion() and made static

*/
class Google_Map extends \map\GoogleMap
{
    public function add_code_loader($code)
    {
        $this->addCodeLoader($code);
    }

    public function add_control_large()
    {
        $this->addControlLarge();
    }

    public function add_control_small()
    {
        $this->addControlSmall();
    }

    public function add_control_scale()
    {
        $this->addControlScale();
    }

    public function add_control_overview()
    {
        $this->addControlOverview();
    }

    public function add_control_type()
    {
        $this->addControlType();
    }

    public function add_control_zoom()
    {
        $this->addControlZoom();
    }

    public function add_control_zoom_dblclick()
    {
        $this->addControlZoomDblclick();
    }

    public function add_control_zoom_scrollwheel()
    {
        $this->addControlZoomScrollwheel();
    }

    public function add_icon($path, $name)
    {
        $this->addIcon($path, $name);
    }

    public function add_circle(
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
        return $this->addCircle(
            $lat,
            $lon,
            $circle_radius,
            $circle_line_color,
            $circle_line_width,
            $circle_line_opacity,
            $circle_fill_color,
            $circle_fill_opacity,
            $title,
            $markertype
        );
    }

    public function add_marker($lat, $lon, $dragable = true, $icon = '', $title = '')
    {
        return $this->addMarker($lat, $lon, $dragable, $icon, $title);
    }

    public function add_marker_with_html(
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
        return $this->addMarkerWithHtml(
            $lat,
            $lon,
            $html,
            $id,
            $dragable,
            $REDUNDANT_PARAMETER,
            $icon,
            $infoWindowOpen,
            $title,
            $circle_radius,
            $circle_line_color,
            $circle_line_width,
            $circle_line_opacity,
            $circle_fill_color,
            $circle_fill_opacity
        );
    }

    public function _get_marker_icon($name)
    {
        return $this->getMarkerIcon($name);
    }

    public static function draw_object_map_html()
    {
        return static::drawObjectMapHtml();
    }

    public static function find_geocode($address, $noCache=false)
    {
        return static::findGeocode($address, $noCache=false);
    }

    public static function get_bounds($records = array(), $prefix = '')
    {
        return static::getBounds($records, $prefix);
    }

    public static function get_bounds_area($lat_a, $lon_a, $lat_b, $lon_b)
    {
        return static::getBoundsArea($lat_a, $lon_a, $lat_b, $lon_b);
    }

    public static function get_geocode($address)
    {
        return static::getGeocode($address);
    }

    protected static function _get_geocode_test_literal($address)
    {
        return static::getGeocodeTestLiteral($address);
    }


    public static function get_sql_map_range($args)
    {
        return static::getSqlMapRange($args);
    }

    public static function get_sql_map_range_filter($args)
    {
        return static::getSqlMapRangeFilter($args);
    }

    public function js_setup()
    {
        return $this->jsSetup();
    }

    public function map_load()
    {
        return $this->mapLoad();
    }

    public function map_centre($lat, $lon, $zoom = 13)
    {
        $this->mapCentre($lat, $lon, $zoom);
    }

    public function map_zoom_to_fit($range)
    {
        $this->mapZoomToFit($range);
    }

    public function map_zoom_to_fit_shape($shape_id)
    {
        $this->mapZoomToFitShape($shape_id);
    }

    public static function on_schedule_update_pending()
    {
        self::onScheduleUpdatePending();
    }

    public static function getVersion()
    {
        return VERSION_GOOGLE_MAP;
    }
}
