<?php
/*
Version History:
  1.0.0 (2017-10-07)
    Initial release
*/
class lst_scrubber_data extends lst_named_type
{
    const VERSION = '1.0.0';

    public function __construct($ID = "")
    {
        parent::__construct($ID, 'lst_scrubber_data', 'Scrubber Data');
    }

    public function importFromFile($filename, $limit, $type, $subtype = '')
    {
        $name_arr = explode("\n", trim(file_get_contents($filename)));
        if (count($name_arr) > $limit) {
            $name_arr = array_rand(array_flip($name_arr), $limit);
        }
        foreach ($name_arr as $value) {
            $data = array(
                'systemID' => 1,
                'value' => $value,
                'seq' => 0,
                'custom_1' => $type,
                'custom_2' => $subtype,
                'textEnglish' => $value,
            );
            $this->insert($data);
        }
        return "Imported $limit '$type'".($subtype ? " ($subtype)" : "")." records\n";
    }
}
