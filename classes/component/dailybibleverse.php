<?php
namespace Component;
/*
Version History:
  1.0.2 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class DailyBibleVerse extends Base
{
    const VERSION = '1.0.2';

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
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
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
}
