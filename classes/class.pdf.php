<?php
define('VERSION_PDF','1.0.2');
/*
Version History:
  1.0.2 (2016-12-26)
    1) Constructor renamed to __construct for PHP 7.0
  1.0.1 (2009-07-11)
    Changes to paths for cpdf and cezpdf
  1.0.0 (2009-07-02)
    Initial release
*/
class PDF {
  function __construct() {
    include_once(SYS_CLASSES.'class.cpdf.php');
    include_once(SYS_CLASSES.'class.cezpdf.php');
  }
  public static function getVersion(){
    return VERSION_PDF;
  }
}
?>