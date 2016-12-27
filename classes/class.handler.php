<?php
define('VERSION_HANDLER','1.0.1');
/*
Version History:
  1.0.1 (2016-12-26)
    1) Constructor renamed to __construct for PHP 7.0
  1.0.0 (2009-07-02)
    Initial release
*/
class Handler {
  private $context;
  function __construct($context=''){
    $this->context = $context;
  }
  function handle($args) {
    $modules_arr = explode(',',str_replace(' ','',Base::get_modules_available()));
    foreach ($modules_arr as $module){
      if ($result = Base::module_handle($module,$this->context,$args)) {
        if (isset($result['handled']) && $result['handled']){
          return $result;
        }
      }
    }

    if (function_exists("cus_handler")) {
      return cus_handler($this->context,$args);
    }
    return array('handled'=>false);
  }
  public static function getVersion(){
    return VERSION_HANDLER;
  }
}
?>