<?php
define("VERSION_COMPONENT_BIBLE_LINKS", "1.0.0");
/*
Version History:
  1.0.0 (2015-02-14)
    1) Initial release - Moved from Church Module
    2) Updated API code for Reftagger calls to use newer API

*/

class Component_Bible_Links extends Component_Base
{
    public function __construct()
    {
        $versions = 'AB,ASV,DAR,ESV,GW,HCSB,KJV,LEB,MESSAGE,NASB,NCV,NIV,NIRV,NKJV,NLT,DOUAYRHEIMS,YLT';
        $this->_ident = "bible_links";
        $this->_parameter_spec = array(
            'bible_version' =>  array(
                'match' =>      'enum|'.$versions,
                'default' =>    'NIV',
                'hint' =>       'Version - choose from '.implode('|', explode(',', $versions))
            ),
            'link_bold' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'link_color' =>     array(
                'match' =>      'hex3|#0000ff',
                'default' =>    '#0000ff',
                'hint' =>       'Hex Colour for links'
            )
        );
    }


    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->_setup($instance, $args, $disable_params);
        $this->_draw_control_panel(true);
        Page::push_content(
            'style',
            "a.rtBibleRef       { text-decoration: none; "
            .($this->_cp['link_bold'] ? "font-weight: bold; " : "")
            .($this->_cp['link_color'] ? "color:".$this->_cp['link_color']."; " : "")
            ."}\n"
            ."a.lbsBibleRef:hover { text-decoration: underline; }\n"
        );
        Page::push_content(
            "body_bottom",
            "<script type=\"text/javascript\">\n"
            ."var refTagger = {\n"
            ."  settings: {\n"
            ."    bibleVersion: \"".$this->_cp['bible_version']."\",\n"
            ."    roundCorners: true,\n"
            ."    socialSharing: [\"facebook\", \"faithlife\", \"google\", \"twitter\"],\n"
            ."    tagChapters: true\n"
            ."  }\n"
            ."};\n"
            ."(function(d, t) {\n"
            ."  var g = d.createElement(t), s = d.getElementsByTagName(t)[0]; \n"
            ."  g.src = \"//api.reftagger.com/v2/RefTagger.js\";\n"
            ."  s.parentNode.insertBefore(g, s);\n"
            ."}(document, \"script\"));\n"
            ."</script>\n"
        );
        return $this->_html;
    }

    public function get_version()
    {
        return VERSION_COMPONENT_BIBLE_LINKS;
    }
}
