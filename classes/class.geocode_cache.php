<?php
define('VERSION_GEOCODE_CACHE', '1.0.3');
/*
Version History:
  1.0.3 (2015-03-21)
    1) Now extends Displayable_Item and has its own _draw_object_map_html_get_data() method to make maps work


*/
class Geocode_Cache extends Displayable_Item
{
    const FIELDS = 'ID, archive, archiveID, deleted, systemID, input_address, match_address, match_area, match_quality, match_type, output_address, output_json, output_lat, output_lon, partial_match, query_date, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';
    const QUERIES_PER_DAY = 2400;   // 100 less than Google's daily maximum
    const MAX_CACHE_AGE =   90;     // Maximum number of days to cache previous results

    public function __construct($ID = "")
    {
        parent::__construct("geocode_cache", $ID);
        $this->_set_object_name('Geocode Cache Entry');
        $this->_set_has_groups(false);
    }

    protected function _draw_object_map_html_get_data()
    {
        if (!$this->load()) {
            return;
        }
        if ($this->record[$this->_field_lat] || $this->record[$this->_field_lon]) {
            $this->_data_items[] = array(
                'ID' =>         $this->record['ID'],
                'map_lat' =>    $this->record[$this->_field_lat],
                'map_lon' =>    $this->record[$this->_field_lon],
                'map_area' =>   $this->record[$this->_field_area],
                'map_loc' =>    $this->record[$this->_field_info],
                'map_icon' =>   '',
                'map_name' =>   trim(title_case_string($this->record[$this->_field_info])),
                'record' =>     $this->record
            );
        }
    }

    public function export_sql($targetID, $show_fields)
    {
        return $this->sql_export($targetID, $show_fields);
    }

    public function getDailyCount($systemID = SYS_ID)
    {
        $sql =
             "SELECT\n"
            ."  COUNT(*)\n"
            ."FROM\n"
            ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `systemID` = ".$systemID." AND\n"
            ."  `query_date`='".date('Y-m-d', time())."'";
        return (int)$this->get_field_for_sql($sql);
    }

    public function getCachedLocation($address, $systemID = SYS_ID)
    {
        $sql =
             "SELECT\n"
            ."  *\n"
            ."FROM\n"
            ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `systemID` = ".$systemID." AND\n"
            ."  `input_address` = \"".$address."\"";
  //    z($sql);
        return $this->get_record_for_sql($sql);
    }

    public function get_version()
    {
        return VERSION_GEOCODE_CACHE;
    }
}
