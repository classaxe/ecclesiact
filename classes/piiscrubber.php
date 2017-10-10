<?php
/*
Version History:
  1.0.0 (2017-10-09)
    Initial release
*/
class PIIScrubber extends Base
{
    const VERSION = '1.0.0';
    
    private static $Address =   array();
    private static $NFirst =    array();
    private static $NLast =     array();
    private static $NTitle =    array();
    private static $Countries = array();

    private function initialize()
    {
        if (!empty(self::$Address)) {
            return;
        }
        $ObjPIIData = new lst_scrubber_data;
        $records = $ObjPIIData->get_listdata();
        foreach ($records as $record) {
            $value = trim($record['value']);
            switch ($record['custom_1']) {
                case 'Address':
                    self::$Address[] = $value;
                    break;
                case 'First Name':
                    self::$NFirst[] = $value;
                    break;
                case 'Last Name':
                    self::$NLast[] = $value;
                    break;
            }
        }
        $ObjTitles =    new lst_persontitle();
        $records =      $ObjTitles->get_listdata();
        foreach ($records as $record) {
            self::$NTitle[] = trim($record['value']);
        }
    }

    public function scrubName(&$InitialRecord)
    {
        $this->initialize();
        $NTitle =       self::$NTitle[array_rand(self::$NTitle)];
        $NFirst =       self::$NFirst[array_rand(self::$NFirst)];
        $NLast =        self::$NLast[array_rand(self::$NLast)];
        $PUsername =    strtolower(substr($NFirst, 0, 1).'.'.$NLast);
        $AEmail =       $PUsername.'@home.example.com';
        $WEmail =       $PUsername.'@work.example.com';
        $PEmail =       (rand(0, 1) ? $AEmail : $WEmail);
        $data = array(
            'NTitle' =>         $NTitle,
            'NFirst' =>         $NFirst,
            'NLast' =>          $NLast,
            'NGreetingName' =>  $NFirst,
            'AEmail' =>         $AEmail,
            'PEmail' =>         $PEmail,
            'WEmail' =>         $WEmail,
            'PUsername' =>      $PUsername
        );
        $InitialRecord = array_merge($InitialRecord, $data);
    }

    public function scrubHomeAddress(&$InitialRecord)
    {
        if (!System::has_feature('show-home-address')) {
            return;
        }
        $this->scrubAddress($InitialRecord, 'A');
    }

    public function scrubWorkAddress(&$InitialRecord)
    {
        if (!System::has_feature('show-work-address')) {
            return;
        }
        $this->scrubAddress($InitialRecord, 'W');
    }

    public function scrubAddress(&$InitialRecord, $type)
    {
        if ((
             $InitialRecord[$type.'Address1']
            .$InitialRecord[$type.'Address2']
            .$InitialRecord[$type.'City']
            .$InitialRecord[$type.'SpID']
            .$InitialRecord[$type.'Postal']
        )==='') {
            return;
        }
        $this->initialize();
        $bits =  explode(',', self::$Address[array_rand(self::$Address)]);
        $Address1 =    trim($bits[0]);
        $Address2 =    '';
        $City =        trim($bits[1]);
        $SpID =        trim($bits[2]);
        $Postal =      str_replace('-', ' ', trim($bits[3]));
        $CountryID =   str_replace('CA', 'CAN', trim($bits[4]));
        if ($CountryID && !isset(self::$Countries[$CountryID])) {
            $Obj = new Country;
            self::$Countries[$CountryID] = $Obj->get_text_for_value($CountryID);
        }
        $Telephone =   trim($bits[5]);
        $Map_lat =     trim($bits[6]);
        $Map_lon =     trim($bits[7]);
        $NFull =
             $InitialRecord['NTitle'].($InitialRecord['NTitle'] ? ' ' : '')
            .$InitialRecord['NFirst'].($InitialRecord['NFirst'] ? ' ' : '')
            .$InitialRecord['NLast'];
        $MapDescription = trim(
            str_replace(
                array("  ","\r\n\r\n","\r\n "),
                array(" ","\r\n","\r\n"),
                (trim($NFull) ? $NFull."\r\n" : "")
                .($type ==='W' && $InitialRecord[$type.'Company'] ?    $InitialRecord[$type.'Company']."\r\n" : '')
                .($type ==='W' && $InitialRecord[$type.'Department'] ? $InitialRecord[$type.'Department']."\r\n" : '')
                .title_case_string(
                    ($Address1 ? $Address1."\r\n" : "")
                     .($Address2 ? $Address2."\r\n" : "")
                )
                .$City." ".$SpID." ".$Postal
                .($CountryID=='CAN' ?
                    ''
                 :
                    (isset(self::$Countries[$CountryID]) ? "\r\n".self::$Countries[$CountryID] : '')
                )
            )
        );
        $Map_location = trim(
            str_replace(
                "  ",
                " ",
                title_case_string(
                    $Address1." ".$Address2." ".$City
                )
                ." ".$SpID." ".$Postal
                .($SpID === 'PR' ?
                    ''
                 :
                    (isset(self::$Countries[$CountryID]) ? ' '.self::$Countries[$CountryID] : '')
                )
            ),
            " "
        );
        $data = array(
            $type.'Address1' =>             $Address1,
            $type.'Address2' =>             $Address2,
            $type.'Cellphone' =>            '',
            $type.'City' =>                 $City,
            $type.'Fax' =>                  '',
            $type.'Facebook' =>             '',
            $type.'GooglePlus' =>           '',
            $type.'LinkedIn' =>             '',
            $type.'Postal' =>               $Postal,
            $type.'CountryID' =>            $CountryID,
            $type.'Map_description' =>      $MapDescription,
            $type.'Map_geocodeID' =>        '0',
            $type.'Map_geocode_address' =>  '',
            $type.'Map_geocode_area' =>     '0',
            $type.'Map_geocode_quality' =>  '100',
            $type.'Map_geocode_type' =>     'Fake Address - PII Scrubbed',
            $type.'Map_lat' =>              $Map_lat,
            $type.'Map_lon' =>              $Map_lon,
            $type.'Map_location' =>         $Map_location,
            $type.'SpID' =>                 $SpID,
            $type.'Telephone' =>            $Telephone,
            $type.'Twitter' =>              '',
            $type.'Web' =>                  '',
            $type.'Youtube' =>              '',
        );
        $InitialRecord = array_merge($InitialRecord, $data);
    }
}
