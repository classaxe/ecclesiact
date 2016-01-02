<?php
define ("VERSION_PHPMAILEREXCEPTION","2.0.1");
/*
Version History:
  2.0.1 (2016-01-02)
    1) Initial release based on PHPMailer 5.2.14
       Code was previously included within class.phpmailer.php, now moved to single class file 
*/

/**
 * PHPMailer exception handler
 * @package PHPMailer
 */
class phpmailerException extends Exception
{
    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage()
    {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
        return $errorMsg;
    }

    public static function getVersion(){
        return VERSION_PHPMAILEREXCEPTION;
    }
}
