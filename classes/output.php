<?php
/*
Version History:
  1.0.1 (2015-09-19)
    1) Now includes Output::drawCssInclude() and Output::drawJsInclude() taken from System class
    2) Updated version for sysjs/jqueryjson included and changed CDN for JS from Google to code.jquery.com

*/

class Output
{
    const VERSION = '1.0.1';
    public static $content = array(
        'body' =>                     array(),
        'body_bottom' =>              array(),
        'body_top' =>                 array(),
        'head_bottom' =>              array(),
        'head_include' =>             array(),
        'head_top' =>                 array(),
        'html_bottom' =>              array(),
        'html_top' =>                 array(),
        'javascript_top' =>           array(),
        'javascript' =>               array(),
        'javascript_onload'=>         array(),
        'javascript_onload_bottom'=>  array(),
        'javascript_onunload' =>      array(),
        'javascript_bottom' =>        array(),
        'style' =>                    array(),
        'style_bottom' =>             array(),
        'style_include' =>            array(),
    );

    public static function drawCssInclude()
    {
        global $page_vars;
        $isSystemEdit =   (substr($page_vars['path'], 0, 17) == '//details/system/');
        return
             "<link rel=\"stylesheet\" type=\"text/css\""
            ." href=\"".BASE_PATH."css/".System::get_item_version('css')."\" />\n"
            ."<link rel=\"stylesheet\" type=\"text/css\""
            ." href=\"".BASE_PATH."css/labels/".System::get_item_version('css_labels')."\" />\n"
            .(!$isSystemEdit ?
                "<link rel=\"stylesheet\" type=\"text/css\""
                ." href=\"".BASE_PATH."css/system/".System::get_css_checksum(SYS_ID)."\" />\n"
             :
                ''
            );
    }

    public static function drawJsInclude($full = false, $context_adminLevel = 0)
    {
        global $system_vars;
        return
         "<script type=\"text/javascript\""
        ." src=\""
         .($system_vars['debug_no_internet']==1 ?
            BASE_PATH."sysjs/jquery/1.11.0"
          :
            "//code.jquery.com/jquery-1.11.0.min.js"
         )
        ."\"></script>\r\n"
        ."<script type=\"text/javascript\""
        ." src=\""
        .($system_vars['debug_no_internet']==1 ?
            BASE_PATH."sysjs/jquerymigrate/1.2.1"
         :
            "//code.jquery.com/jquery-migrate-1.2.1.min.js"
        )
        ."\">"
        ."</script>\r\n"
        ."<script type=\"text/javascript\""
        ." src=\""
        .($system_vars['debug_no_internet']==1 ?
            BASE_PATH."sysjs/jqueryui/1.10.4"
         :
            "//code.jquery.com/ui/1.10.4/jquery-ui.min.js"
        )
        ."\">"
        ."</script>\r\n"
        ."<script type=\"text/javascript\" src=\"".BASE_PATH."sysjs/jqueryjson/2.5.1"."\"></script>\r\n"
        ."<script type=\"text/javascript\""
        ." src=\"".BASE_PATH."sysjs/sys/".System::get_item_version('js_functions')."\">"
        ."</script>\r\n"
        .($full || get_userID() ?
             "<script type=\"text/javascript\""
            ." src=\"".BASE_PATH."sysjs/member/".System::get_item_version('js_member')."\">"
            ."</script>\r\n"
         :
            ''
        )
        .($context_adminLevel>0 ?
             "<script type=\"text/javascript\""
            ." src=\"".BASE_PATH."sysjs/context/".$context_adminLevel."/".System::get_item_version('codebase')."\">"
            ."</script>\r\n"
         :
            ''
        );
    }

    public static function push($part, $code)
    {
        Output::$content[$part][] = $code;
    }

    public static function pull($part)
    {
        if (!Output::isPresent($part)) {
            return "";
        }
        return implode("", Output::$content[$part]);
    }

    public function isPresent($part)
    {
        if (!isset(Output::$content[$part])) {
            return false;
        }
        if (Output::$content[$part]==='') {
            return false;
        }
        return true;
    }

    public static function reset()
    {
        foreach (Output::$content as &$part) {
            $part = array();
        }
    }

    public static function getVersion()
    {
        return Output::VERSION;
    }
}
