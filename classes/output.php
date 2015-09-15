<?php
/*
Version History:
  1.0.0 (2015-09-13)
    1) Initial release - performs streaming operations previously handled by Page class

*/

class Output
{
    const VERSION = '1.0.0';
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
