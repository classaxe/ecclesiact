<?php
/*
Version History:
  1.1.1 (2015-11-29)
    1) Now includes safeID as source in path for popup Ajax loader for safer targetting of correct component
*/
class Jumploader extends Base
{
    const VERSION = '1.1.1';
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
        $ext = 'jpg,jpeg,gif,png',
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
        $this->_Popup =         BASE_PATH.trim($page_vars['path'], '/')."?submode=popup&source=".$this->_safe_ID;
        $this->_width =         $width;
    }

    public function draw()
    {
        switch(get_var('submode')) {
            case 'popup':
                print $this->popup();
                die;
                break;
        }
        $this->setup_code();
        Output::push('javascript', $this->get_js());
        return $this->get_html();
    }

    public function popup()
    {
        return
            "<!DOCTYPE html>\n"
           ."<html>\n"
           ."<head>\n"
           ."<meta charset=\"utf-8\"/>\n"
           ."<title>File Upload Form</title>\n"
           ."<link rel=\"stylesheet\" type=\"text/css\" href=\"".BASE_PATH."css/uploader/".CODEBASE_VERSION."\" />\n"
           ."</head>\n"
           ."<body id='uploader'>\n"
           ."<form id=\"upload\" method=\"post\" action=\"".$this->_URL."\" enctype=\"multipart/form-data\">\n"
           ."    <div id=\"drop\" data-max-bytes=\"".get_max_upload_size()."\" data-file-types=\"".$this->_ext."\">\n"
           ."        DROP HERE<br />\n"
           ."        <a>Browse</a>\n"
           ."        <input type=\"file\" name=\"file\" multiple /><br />\n"
           ."        <p>Types: ".str_replace(',', ', ', $this->_ext)."<br />\n"
           ."        Max size: ".format_bytes(get_max_upload_size())."</p>"
           ."    </div>\n"
           ."    <ul></ul>\n"
           ."</form>\n"
           ."<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script>\n"
           ."<script src=\"".BASE_PATH."sysjs/jquery.knob\"></script>\n"
           ."<script src=\"".BASE_PATH."sysjs/jquery.ui.widget\"></script>\n"
           ."<script src=\"".BASE_PATH."sysjs/jquery.iframe-transport\"></script>\n"
           ."<script src=\"".BASE_PATH."sysjs/jquery.fileupload\"></script>\n"
           ."<script src=\"".BASE_PATH."sysjs/ajaxupload\"></script>\n"
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
        switch ($this->_mode) {
            case 'framed':
                $this->_html.=
                     "<div id=\"container_".$this->_safe_ID."\" style='margin:auto;'>\n"
                    ."<a href=\"".$this->_Popup."\" onclick=\""
                    ."popWin(this.href, 'uploader', 'location=0,status=1,scrollbars=1,resizable=1', 400, 400);"
                    ."return false;\">"
                    ."<img src=\"".BASE_PATH."img/sysimg/uploader.png\" width=\"107\" height=\"32\""
                    ." alt=\"Uploader\" title=\"Click here to upload files\" />"
                    ."</a>"
                    ."</div>";
                break;
            case 'embedded':
                $this->_html.=
                     "<div id=\"container_".$this->_safe_ID."\">\n"
                    ."<a class=\"iframe\" href=\"".$this->_Popup."\""
                    ." rel=\"style=width:".$this->_width."px;height:".$this->_height."px;border:none;margin:1em 0;\" />Embedded Content</a>\n"
                    ."</div>";
                break;
        }
    }
}
