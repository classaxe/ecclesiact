<?php
define("VERSION_COMPONENT_DAILY_BIBLE_VERSE", "1.0.0");
/*
Version History:
  1.0.0 (2015-02-14)
    1) Initial release - Moved from Church Module

*/


class Component_Daily_Bible_Verse extends Component_Base
{
    public function __construct()
    {
        global $system_vars;
        $this->_ident =             "daily_bible_verse";
        $this->_parameter_spec =    array(
            'version' =>    array(
                'match' =>      'enum|ASV,NIV,AMP,BRE,DRB,KJV,NASB,NIV,NKJV,NLT,NRSV,RSV,WEB,YLT',
                'default' =>    'NIV',
                'hint' =>       'Version to use - ASV|NIV|AMP|BRE|DRB|KJV|NASB|NIV|NKJV|NLT|NRSV|RSV|WEB|YLT'
            ),
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->_setup($instance, $args, $disable_params);
        $this->_draw_control_panel(true);
        $this->_html.=
             "<div id='daily_bible_verse'>Loading from christnotes.org... please wait</div>"
            ."<script type=\"text/javascript\">\n"
            ."//<![CDATA[\n"
            ."include('".BASE_PATH."?command=get_bible_verse&trn=".$this->_cp['version']."','daily_bible_verse');\n"
            ."//]]>\n"
            ."</script>"
            ."<p style=\"text-align:right;color:#000000;padding:0px;margin:3px 4px 0px;border:0px;"
            ."font-family:Arial,sans-serif;font-size:12px;\">"
            ."Provided by <a href=\"http://www.christnotes.org/\" "
            ."style=\"color:#000000;font-family:Arial,sans-serif;font-size:12px;text-decoration:underline;"
            ."padding:0px;margin:0px;border:0px;\" "
            ."onclick=\"javascript:window.open('http://www.christnotes.org/');return false\">Christ Notes</a> "
            ."<a href=\"http://www.christnotes.org/bible.php\" "
            ."style=\"color:#000000;font-family:Arial,sans-serif;font-size:12px;text-decoration:underline;"
            ."padding:0px;margin:0px;border:0px;\" "
            ."onclick=\"javascript:window.open('http://www.christnotes.org/bible.php');return false\">"
            ."Bible Search</a></p>";
        return $this->_html;
    }

    public function get_version()
    {
        return VERSION_COMPONENT_DAILY_BIBLE_VERSE;
    }
}
