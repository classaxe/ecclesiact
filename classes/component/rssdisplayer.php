<?php
namespace Component;

/*
Version History:
  1.0.3 (2016-03-29)
    1) Moved to component namespace and now fully PSR-2 compliant
    2) Fix for bad JS call on library file
*/
class RSSDisplayer extends Base
{
    const VERSION = '1.0.3';

    public function __construct()
    {
        $this->_ident =            "rss_displayer";
        $this->_parameter_spec =   array(
            'limit' =>          array(
                'match' =>      'range|0,n',
                'default' =>    '3',
                'hint' =>       '0..n'
            ),
            'linktarget' =>     array(
                'match' =>      '',
                'default' =>    '_blank',
                'hint' =>       'Target window for links to open in'
            ),
            'offset' =>         array(
                'match' =>      'range|0,n',
                'default' =>    '1',
                'hint' =>       '0..n'
            ),
            'sort' =>           array(
                'match' =>      'enum|,title,date',
                'default' =>    'date',
                'hint' =>       '|title|date'
            ),
            'sortasc' =>        array(
                'match' =>      'range|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'show_content' =>   array(
                'match' =>      'range|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'show_date' =>      array(
                'match' =>      'range|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'show_title' =>     array(
                'match' =>      'range|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'show_snippet' =>   array(
                'match' =>      'range|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'url' =>            array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'URL for site'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        static $shown_js =  false;
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        if (!$shown_js) {
            \Output::push(
                "javascript_top",
                "<script type=\"text/javascript\" src=\"".BASE_PATH."sysjs/jquery.zrssfeed\"></script>"
            );
            $shown_js = true;
        }
        \Output::push(
            "javascript",
            "function ".$this->_safe_ID."_init(){\n"
            ."  \$('#".$this->_safe_ID."').rssfeed(\n"
            ."    '".$this->_cp['url']."',\n"
            ."    {\n"
            ."       content: ".($this->_cp['show_content']=='1' ? 'true' : 'false').",\n"
            ."       date: ".($this->_cp['show_date']=='1' ? 'true' : 'false').",\n"
            ."       header: ".($this->_cp['show_title']=='1' ? 'true' : 'false').",\n"
            ."       limit: ".$this->_cp['limit'].",\n"
            ."       linktarget: '".$this->_cp['linktarget']."',\n"
            ."       offset: ".$this->_cp['offset'].",\n"
            ."       snippet: ".($this->_cp['show_snippet']=='1' ? 'true' : 'false').",\n"
            ."       sort: '".$this->_cp['sort']."',\n"
            ."       sortasc: ".($this->_cp['sortasc']=='1' ? 'true' : 'false').",\n"
            ."       titletag: 'span'\n"
            ."    }\n"
            ."  );\n"
            ."}\n"
        );
        \Output::push(
            "javascript_onload",
            "  ".$this->_safe_ID."_init();\n"
        );
        \Output::push(
            "style",
            "  #".$this->_safe_ID." ul { list-style: none; margin: 1em; padding: 0; }\n"
            ."  #".$this->_safe_ID." ul li { padding: 0.5em 0; }\n"
            ."  #".$this->_safe_ID." ul li div { font-size: 80%; color: #444; }\n"
            ."  #".$this->_safe_ID." ul li p { margin: 0; }\n"
        );
        $this->_html.=  "<div id=\"".$this->_safe_ID."\">".$this->_cp['url']."</div>";
        return $this->_html;
    }
}
