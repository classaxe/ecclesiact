<?php
/*
Version History:
  1.0.5 (2017-12-01)
    1) Archived old version details, now uses class constant for version control
*/
class State_Province extends lst_named_type {
    const VERSION = '1.0.5';

    public function __construct($ID="") {
        parent::__construct($ID,'lst_sp','State / Province Name');
    }

    public function get_records_by_country($country='',$systemID='',$sortBy='') {
        $countryID = false;
        if ($country){
            $Obj = new Country;
            $countryID = $Obj->get_ID_by_name($country,$systemID='');
        }
        return $this->get_records_by_parentID($countryID, $systemID, $sortBy);
    }
}
