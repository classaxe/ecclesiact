<?php
namespace Component;

define('VERSION_NS_COMPONENT_BIBLE_LINKS', '1.0.1');
/*
Version History:
  1.0.1 (2015-03-04)
    1) Moved from Component_Bible_Links and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/

class BibleLinks extends Base
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
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        \Page::push_content(
            'style',
            "a.rtBibleRef       { text-decoration: none; "
            .($this->_cp['link_bold'] ? "font-weight: bold; " : "")
            .($this->_cp['link_color'] ? "color:".$this->_cp['link_color']."; " : "")
            ."}\n"
            ."a.lbsBibleRef:hover { text-decoration: underline; }\n"
        );
        \Page::push_content(
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

    public function getVersion()
    {
        return VERSION_NS_COMPONENT_BIBLE_LINKS;
    }
}
