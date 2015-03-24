<?php
define('VERSION_BASE_ERROR','1.0.2');
/*
Version History:
  1.0.2 (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static

*/
class Base_Error extends Exception{
  private $errorMessage;
  public function __construct($errorMessage='') {
    $this->errorMessage = $errorMessage;
  }
  public function __call($methodName, $parameters) {
    return $this->errorMessage;
  }
  public function __get($propertyName) {
    return $this->errorMessage;
  }
  public function __set($propertyName, $propertyValue) {
    return $this->errorMessage;
  }
  public static function getVersion(){
    return VERSION_BASE_ERROR;
  }
}

?>