<?php
/*
Version History:
  1.0.4 (2017-11-10)
    1) Now respects visibility and enabled status of mapped buttons but now also allows admins
       to see ghosted options for normally invisible entries.
    2) Now uses class constant for version control
*/
class Component_Sitemap extends Component_Base
{
    const VERSION = '1.0.4';

    protected $isAdmin;
    protected $isMasterAdmin;

    public static function get_sitemap($full = false, $navsuiteID = '', $flatList = false, $depth = 0)
    {
        global $page_vars;
        $depth++;
        $isMASTERADMIN =        get_person_permission("MASTERADMIN");
        $isSYSADMIN =           get_person_permission("SYSADMIN");
        $isSYSAPPROVER =        get_person_permission("SYSAPPROVER");
        $isSYSEDITOR =          get_person_permission("SYSEDITOR");
        $isAdmin =              ($isSYSEDITOR||$isSYSAPPROVER||$isSYSADMIN||$isMASTERADMIN);
        if ($navsuiteID=='') {
            if ($full) {
                $navsuiteID = $page_vars['navsuite1ID'];
            } else {
                $Obj_Nav_Button = new \Nav\Button;
                $sql =
                     "SELECT\n"
                    ."  `ID`,\n"
                    ."  `suiteID`\n"
                    ."FROM\n"
                    ."  `navbuttons`\n"
                    ."WHERE\n"
                    ."  `navbuttons`.`systemID` = ".$page_vars['systemID']." AND\n"
                    ."  `navbuttons`.`URL` = \"./?page=".$page_vars['page']."\"";
                $records = $Obj_Nav_Button->get_records_for_sql($sql);
                foreach ($records as $record) {
                    $possible_root_suiteID = $Obj_Nav_Button->getRootNavsuiteID($record['ID']);
                    if ($possible_root_suiteID==$page_vars['navsuite1ID']) {
                        $navsuiteID = $record['suiteID'];
                        break;
                    }
                }
                if ($navsuiteID=='') {
                    return "No Navbuttons to map associated with this page.";
                }
            }
        }
        $out = "";
        $Obj_Nav_Suite =    new \Nav\Suite($navsuiteID);
        $buttons =          $Obj_Nav_Suite->getButtons();
        if (!$buttons===false) {
            foreach ($buttons as $button) {
                $Obj = new \Nav\Button($button['ID']);
                if (!$button['enabled'] && !$isAdmin) {
                    continue;
                }
                if (!$button['visible'] && !$isAdmin) {
                    continue;
                }
                $bID =        $button['ID'];
                $bText =      $button['text1'].($button['text2'] ? "<br />\n".$button['text1'] : "");
                $bPopup =     $button['popup'];
                $childID =    $button['childID'];
                $bURL =       $button['URL'];
                if (substr($bURL, 0, 8)=='./?page=') {
                    $bURL = BASE_PATH.substr($bURL, 8);
                }
                $bURL = htmlentities(html_entity_decode($bURL));
                if ($flatList) {
                    if (!$bPopup) {
                        $out.=
                        "[!]".htmlentities($bText)."[?]".$bURL
                        .($childID ? static::get_sitemap(false, $childID, $flatList) : "");
                    }
                } else {
                    $out.=
                     str_repeat("  ", $depth)
                    ."<li"
                    .(!$button['enabled'] || !$button['visible'] ?
                        " style='font-style:italic; opacity:0.5'"
                       ." title='This option would normally be hidden due to the visibility of the button represented'"
                     :
                        ""
                    )
                    .">"
                    .($bURL ?
                         "<a"
                        .($bPopup ? " rel='external'" : "")
                        ." href=\"".$bURL."\">"
                     :
                        "<span>"
                    )
                    .$bText
                    .($bPopup ? HTML::draw_icon('external') : "")
                    .($bURL ? "</a>" : "</span>")
                    .($childID ?
                        "\n".static::get_sitemap(false, $childID, $flatList, $depth).str_repeat("  ", $depth)."</li>\n"
                     :
                        "</li>\n"
                    );
                }
            }
        }
        if ($flatList) {
            return $out;
        }
        return
             str_repeat("  ", $depth-1)."<ul>\n"
            .$out
            .str_repeat("  ", $depth-1)."</ul>\n";
    }
}
