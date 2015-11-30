<?php
define('VERSION_JUMPLOADER_JAR', '2.19.0.b');
/*
Version History:
  1.1.0 (2015-11-23)
    1) Now completely rewritten to use HTML5 ajax driven uploader instead of Java as before
*/
class Jumploader extends Base
{
    const VERSION = '1.1.0';
    private $_extensions;
    private $_height;
    private $_html;
    private $_js;
    private $_mode;
    private $_Popup;
    private $_safe_ID;
    private $_show_summary;
    private $_type;
    private $_URL;
    private $_width;

    public function __construct()
    {
    }

    public function init(
        $safe_ID,
        $width = 140,
        $height = 44,
        $mode = 'framed',
        $type = 'image',
        $ext = 'jpg|jpeg|gif|png',
        $show_summary = false
    ) {
        global $page_vars;
        $this->_safe_ID =       $safe_ID;
        $this->_ext =           $ext;
        $this->_height =        $height;
        $this->_mode =          $mode;
        $this->_show_summary =  $show_summary;
        $this->_type =          $type;
        $this->_URL =           BASE_PATH.trim($page_vars['path'], '/')."?submode=".$this->_safe_ID."_upload";
        $this->_Popup =         BASE_PATH.trim($page_vars['path'], '/')."?submode=popup";
        $this->_width =         $width;
    }

    public function draw()
    {
        $this->setup_code();
        Output::push('javascript', $this->get_js());
        return $this->get_html();
    }

    public function popup($url)
    {
        return
            "<!DOCTYPE html>\n"
           ."<html>\n"
           ."<head>\n"
           ."<meta charset=\"utf-8\"/>\n"
           ."<title>Mini Ajax File Upload Form</title>\n"
           ."<style type=\"text/css\">\n"
           ."*{margin:0;padding:0}\n"
           ."html{background-color:#ebebec;background-image:-webkit-radial-gradient(center,#ebebec,#b4b4b4);"
           ."background-image:-moz-radial-gradient(center,#ebebec,#b4b4b4);"
           ."background-image:radial-gradient(center,#ebebec,#b4b4b4)}\n"
           ."#upload{font-family:sans-serif;background-color:#373a3d;"
           ."background-image:-webkit-linear-gradient(top,#373a3d,#313437);"
           ."background-image:-moz-linear-gradient(top,#373a3d,#313437);"
           ."background-image:linear-gradient(top,#373a3d,#313437);padding:30px}\n"
           ."#upload #drop{background-color:#2E3134;padding:40px 15px;margin-bottom:30px;"
           ."border:20px solid transparent;border-radius:3px;"
           ."border-image:url(/img/sysimg/uploadborder.png) 25 repeat;"
           ."text-align:center;font-size:16px;font-weight:700;color:#7f858a}\n"
           ."#upload #drop a{background-color:#007a96;padding:12px 26px;color:#fff;font-size:14px;border-radius:2px;"
           ."cursor:pointer;display:inline-block;margin-top:12px;line-height:1}\n"
           ."#upload #drop a:hover{background-color:#0986a3}\n"
           ."#upload #drop input,#upload ul li input{display:none}\n"
           ."#upload #drop p{ margin: 1em; font-size: 70%; }\n"
           ."#upload ul{list-style:none;margin:0 -30px;border-top:1px solid #2b2e31;border-bottom:1px solid #3d4043}\n"
           ."#upload ul li{background-color:#333639;"
           ."background-image:-webkit-linear-gradient(top,#333639,#303335);"
           ."background-image:-moz-linear-gradient(top,#333639,#303335);"
           ."background-image:linear-gradient(top,#333639,#303335);"
           ."border-top:1px solid #3d4043;border-bottom:1px solid #2b2e31;padding:15px;height:52px;"
           ."position:relative}\n"
           ."#upload ul li p{overflow:hidden;white-space:nowrap;color:#EEE;"
           ."font-size:16px;font-weight:700;position:absolute;top:20px;left:100px}\n"
           ."#upload ul li i{font-weight:400;font-style:normal;color:#7f7f7f;display:block}\n"
           ."#upload ul li canvas{top:15px;left:32px;position:absolute}\n"
           ."#upload ul li span,#upload ul li.error span{width:15px;position:absolute;top:34px;right:33px;"
           ."cursor:pointer}\n"
           ."#upload ul li span{height:12px;background:url(/img/sysimg/uploadicons.png) no-repeat}\n"
           ."#upload ul li.working span{height:16px;background-position:0 -12px}\n"
           ."#upload ul li.error p{color:red}\n"
           ."#upload ul li.error span{height:12px;background:url(/img/sysimg/uploadicons.png) 0 100% no-repeat}\n"
           ."</style>\n"
           ."</head>\n"
           ."<body>\n"
           ."<form id=\"upload\" method=\"post\" action=\"".$this->_URL."\" enctype=\"multipart/form-data\">\n"
           ."    <div id=\"drop\" data-max-bytes=\"".get_max_upload_size()."\" data-file-types=\"".$this->_ext."\">\n"
           ."        DROP HERE<br />\n"
           ."        <a>Browse</a>\n"
           ."        <input type=\"file\" name=\"file\" multiple />"
           ."    </div>\n"
           ."    <ul></ul>\n"
           ."</form>\n"
           ."<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script>\n"
           ."<script src=\"/sysjs/jquery.knob\"></script>\n"
           ."<script src=\"/sysjs/jquery.ui.widget\"></script>\n"
           ."<script src=\"/sysjs/jquery.iframe-transport\"></script>\n"
           ."<script src=\"/sysjs/jquery.fileupload\"></script>\n"
           ."<script src=\"/sysjs/ajaxupload\"></script>\n"
           ."</body>\n"
           ."</html>";
    }

    public function clear_status()
    {
        unset($_SESSION[$this->_safe_ID.'_results']);
    }

    public function get_html()
    {
        return $this->_html;
    }

    public function get_js()
    {
        return $this->_js;
    }

    public function get_status()
    {
        if (!isset($_SESSION[$this->_safe_ID.'_results'])) {
            $_SESSION[$this->_safe_ID.'_results'] = array();
        }
        usort($_SESSION[$this->_safe_ID.'_results'], array($this, "sort_status"));
        return $_SESSION[$this->_safe_ID.'_results'];
    }

    public function sort_status($a, $b)
    {
        $al = strtolower($a['filename']);
        $bl = strtolower($b['filename']);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    public function get_uploaded_count()
    {
        $result = $this->get_status();
        return count($result);
    }

    public function files_upload($mode, $path)
    {
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        $isSYSADMIN =        get_person_permission("SYSADMIN");
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER");
        $this->_isAdmin =   ($isMASTERADMIN || $isSYSADMIN || $isSYSAPPROVER);
        if ($this->_isAdmin) {
            mkdirs('.'.$path, 0777);
            $Obj_Uploader = new Uploader($mode, $path);
            $result = $Obj_Uploader->do_upload();
        } else {
            $result = array('status'=>'403', 'message'=>'Unauthorised');
        }
        switch ($result['status']){
            case '100':
              // In progress - do nothing
                break;
            case '200':
                $this->on_uploaded($result);
                break;
            default:
                header("HTTP/1.0 200", $result['status']);
                header('Content-type: text/plain');
                print "Error: ".$result['status']." ".$result['message']."\n";
                die();
            break;
        }
    }

    public function files_uploader($folder)
    {
        if ($this->isUploading()) {
            $this->files_upload($this->_type, $folder);
            die();
        }
    }

    public function isUploading()
    {
        return get_var('submode')==$this->_safe_ID."_upload";
    }

    protected function on_uploaded($result)
    {
        if (!isset($_SESSION[$this->_safe_ID.'_results'])) {
            $_SESSION[$this->_safe_ID.'_results'] = array();
        }
        $_SESSION[$this->_safe_ID.'_results'][] = $result;
        do_log(1, __CLASS__.'::'.__FUNCTION__.'()', '', 'Result: '.print_r($result, 1));
    }

    public function setup_code()
    {
        $this->_js.=
             "function uploaderStatusChanged( uploader ) {\n"
            ."  if(uploader.getStatus()===0){\n"
            ."    if (uploader.getFileCountByStatus(2)===uploader.getFileCount()){\n"
            ."      geid('form').submit();\n"
            ."    }\n"
            ."  }\n"
            ."}\n"
            ."applet_register(\"".$this->_safe_ID."\");\n"
            ;
        $extensions_arr = explode(',', $this->_ext);
        $this->_html.=
             "<div id=\"container_".$this->_safe_ID."\""
            ." style='width:".$this->_width."px;height:".$this->_height."px;margin:auto'>\n"
            ."<a href=\"".$this->_Popup."\" onclick=\"popWin(this.href, 'uploader', 'location=0,status=1,scrollbars=1,resizable=1', 400, 400);return false;\">"
            ."<img src=\"".BASE_PATH."img/sysimg/uploader.png\" width=\"107\" height=\"32\""
            ." alt=\"Uploader\" title=\"Click here to upload files\" />"
            ."</a>"
            ."</div>";
    }
}
