<?php
/*
Version History:
  1.0.0 (2015-03-25)
    1) Initial release
*/
class HttpError extends Base
{
    const VERSION = '1.0.0';

    public static function draw($status)
    {
        global $page, $mode, $report_name, $selectID, $command, $targetID, $print;
        if ($page=='home') {
            $page='';
        }
        $uri_arr = array();
        if ($command!='') {
            $uri_arr[] = "command=$command";
        }
        if ($mode!='') {
            $uri_arr[] = "mode=$mode";
        }
        if ($report_name!='') {
            $uri_arr[] = "report_name=$report_name";
        }
        if ($selectID!='') {
            $uri_arr[] = "selectID=$selectID";
        }
        if ($print!='') {
            $uri_arr[] = "print=$print";
        }
        if ($targetID!='') {
            $uri_arr[] = "targetID=$targetID";
        }
        $uri =  implode('&', $uri_arr);
        $uri =  BASE_PATH.$page.($uri ? "?".$uri : "");
        switch ($status) {
            case "403":
                $title =    "403 Permission Denied";
                $problem =  "Sorry, you are not authorised to access this resource.";
                break;
            case "404":
                $title =    "404 Page not found";
                $problem =  "Sorry, we can't find the page you requested.";
                break;
        }
        return
             "<div style='background-color:#f0f0f0;margin:auto; border:2px solid #000; padding:10px;'>\n"
            ."<h1>".$title."</h1>"
            ."<p><a href=\"".$uri."\">".$uri."</a><br />\n<br />\n"
            .$problem."<br />\n"
            ."<a href='#' onclick=\"history.back();return false;\"><b>Click here</b></a> to go back</p>\n"
            ."<p>Please contact us if you believe this to be an error.</p>\n"
            ."</div>&nbsp;<br class='clr_b' />";
    }
}
