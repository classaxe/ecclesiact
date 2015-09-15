<?php
define ("VERSION_PHP_EXCEL","1.0.3");
/*
Version History:
  1.0.3 (2015-09-14)
    1) References to Page::push_content() now changed to Output::push()

*/

@include_once("PHPExcel/PHPExcel.php");
if (class_exists('PHPExcel')){
  class PHP_Excel extends PHPExcel{
    function __construct(){
      parent::__construct();
    }
    public static function getVersion(){
      return VERSION_PHP_EXCEL;
    }
  }
}
else {
  class PHP_Excel{
    function __construct(){
      Output::push(
        'javascript_onload',
         "showPopWin("
        ."\"Missing Library\","
        ."\"<div style='padding:5px;'>\\n"
        ."<p><b>Cannot find PEAR Library PHPExcel.php</b><br />\\n"
        ."To install it, open a shell console and enter the following commands:</p>\\n"
        ."<p><code><b>pear channel-discover pear.pearplex.net</b></code><br />\\n"
        ."<code><b>pear install pearplex/PHPExcel</b></code></p>\\n"
        ."<div style='text-align:center'>\\n"
        ."<input type='button' value='&nbsp;OK&nbsp;' class='formButton' onclick='hidePopWin()' />\\n"
        ."</div>\","
        ."400,200)");
      return;
    }
    public static function getVersion(){
      return VERSION_PHP_EXCEL;
    }
  }
}
?>