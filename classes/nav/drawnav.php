<?php
namespace Nav;

define('VERSION_NS_NAV_DRAWNAV', '1.0.2');
/*
Version History:
  1.0.2 (2015-08-20)
    1) DrawNav::setupGetDimensions() now silently handles errors caused by user specifying a nav suite that doesn't exist

*/
class DrawNav
{
    protected $buttons;
    protected $depth;
    protected $hasVisible;
    protected $isAdmin;
    protected $isMasterAdmin;
    protected $nav;
    protected $navsuite;
    protected $navsuiteID;
    protected $objNavSuite;
    protected $offsetX;
    protected $offsetY;
    protected $rootOrientation;
    protected $siteURL;
    protected $height = 0;
    protected $width =  0;

    public function __construct($nav = "", $navsuiteID = 'root', $rootOrientation = 'notInitialisedYet', $depth = 0)
    {
        $this->nav =                $nav;
        $this->navsuiteID =         $navsuiteID;
        $this->rootOrientation =    $rootOrientation;
        $this->depth =              $depth;
    }

    public function draw()
    {
        $this->setup();
        if (!$this->buttons) {
            return "";
        }
        if (!$this->checkHasVisible()) {
            return "";
        }
        if (!$this->navsuite) {
            return "";
        }
        $this->depth++;
        $bStyleID =         $this->navsuite['buttonStyleID'];
        $bStyleName =       $this->navsuite['navstyle_name'];
        $bSubnavStyleID =   $this->navsuite['subnavStyleID'];
        if ($this->rootOrientation=='notInitialisedYet') {
            $this->rootOrientation = $this->navsuite['orientation'];
        }
        $buttons_count = 0;
        $current_button = 0;
        foreach ($this->buttons as $button) {
            if ($button['visible'] || $this->isAdmin) {
                $buttons_count++;
            }
        }
        $styleClass = 'nav_style_'.get_js_safe_ID($bStyleName);
        $out = '';
        switch ($this->navsuite['orientation']) {
            case '|':
                $out.=
                    str_repeat('  ', $this->depth)
                    ."<ul id='nav_".$this->navsuite['ID']."'"
                    .($this->navsuiteID=='root' ?
                        " class='vnavmenu ".$styleClass."'>\n"
                     :
                        " class='".$styleClass."' style=\"left:".$this->offsetX."px; top:".$this->offsetY."px;\""
                        .">\n"
                    );
                foreach ($this->buttons as $b) {
                    if ($b['visible'] || $this->isAdmin) {
                        $bID =      $b['ID'];
                        $bSystemID =      $b['systemID'];
                        $bCS =      $b['img_checksum'];
                        $bPos =     $b['position'];
                        $bURL =     $b['URL'];
                        $bPopup =   $b['popup'];
                        $bSuiteID = $b['suiteID'];
                        $bText =    $b['text1'];
                        $bTextSafe = str_replace(
                            array("'", "\r\n", "\n"),
                            array("&rsquo;", " ", " "),
                            sanitize('html', $bText)
                        );
                        $sNameSafe = str_replace(
                            "'",
                            "&rsquo;",
                            sanitize('html', $this->navsuite['name'])
                        );
                        $active =   \Nav\Button::isActive($bURL, $this->siteURL);
                        $childID =  $b['childID'];
                        $canAddSubmenu = ($childID ? -1 : ($bSubnavStyleID==1 ? 0 : 1));
                        $dropdown = \Nav\Button::hasVisibleChildren($bID);
                        $bHeight =  $b['img_height'];
                        $bWidth =   $b['img_width'];
                        $bSrc =     "url(./img/button/".$bID."/".$b['img_checksum'].")";
                        $bOffset =  ($dropdown ? '100%' : '0')." ".($active ? '0' : -2 * $bHeight).'px';
                        if (substr($bURL, 0, 8)=='./?page=') {
                            $bURL = BASE_PATH.substr($bURL, 8);
                        }
                        $bURL = htmlentities(html_entity_decode($bURL));
                        $out.=
                             str_repeat('  ', $this->depth)
                            ."  <li"
                            ." id=\"btn_".$bID."\""
                            .($b['visible'] ?
                                ""
                              :
                                " class=\"invisible\""
                               ." title=\"This button would normally be hidden,\nbut administrators can still see it.\""
                             )
                            .">"
                            ."<a"
                            .($active ? " class='nav_active'" : "")
                            .($bPopup ? " rel='external'" : "")
                            ." href=\"".$bURL."\""
                            .(($this->isAdmin && $bSystemID==SYS_ID) || $this->isMasterAdmin ?
                                 " onmouseover=\""
                                ."CM_Navbutton_Over("
                                .$bID.","
                                .$bStyleID.","
                                .$canAddSubmenu.","
                                ."'".$sNameSafe."',"
                                ."'".sanitize('html', $bStyleName)."'"
                                .");"
                                ."\""
                              :
                                ""
                             )
                            .">"
                            ."<img role=\"presentation\""
                            ." src=\"".BASE_PATH."img/spacer\""
                            ." height=\"".$bHeight."\""
                            ." width=\"".$bWidth."\""
                            ." style='"
                            .((
                                $this->navsuiteID=='root' &&
                                $this->navsuite['button_spacing'] &&
                                $current_button<$buttons_count
                             ) ?
                                "margin-bottom:".$this->navsuite['button_spacing']."px;"
                              :
                                ""
                             )
                            ."background:".$bSrc." no-repeat ".$bOffset."'"
                            ." alt=\"".$bTextSafe."\"/>"
                            ."<span style='display:none'>".$bTextSafe."</span>"
                            ."</a>";
                        $current_button++;
                        if ($childID) {
                            $subnav = new DrawNav('submenu', $childID, $this->rootOrientation, $this->depth);
                            $out.=
                            "\n"
                            .$subnav->draw()
                            .str_repeat('  ', $this->depth+1);
                        }
                        $out.=
                        "</li>\n";
                    }
                }
                $out.=
                     str_repeat('  ', $this->depth)
                    ."</ul>\n";
                break;
            case '---':
                // Can ONLY be root menu - only root is permitted to be horizontal
                $out.=
                    str_repeat('  ', $this->depth)
                    ."<ul id='nav_".$this->navsuite['ID']."'"
                    .($this->navsuiteID=='root' ?
                        " class='hnavmenu ".$styleClass."'>\n"
                     :
                        " class='".$styleClass."' style=\"left:".$this->offsetX."px; top:".$this->offsetY."px;\">\n"
                    );
                foreach ($this->buttons as $b) {
                    if ($b['visible'] || $this->isAdmin) {
                        $bID =      $b['ID'];
                        $bSystemID =      $b['systemID'];
                        $bPos =     $b['position'];
                        $bURL =     $b['URL'];
                        $bPopup =   $b['popup'];
                        $bSuiteID = $b['suiteID'];
                        $bText =    $b['text1'];
                        $bTextSafe = str_replace(
                            array("'", "\r\n", "\n"),
                            array("&rsquo;", " ", " "),
                            sanitize('html', $bText)
                        );
                        $sNameSafe = str_replace(
                            "'",
                            "&rsquo;",
                            sanitize('html', $this->navsuite['name'])
                        );
                        $active =   \Nav\Button::isActive($bURL, $this->siteURL);
                        $childID =  $b['childID'];
                        $canAddSubmenu = ($childID ? -1 : ($bSubnavStyleID==1 ? 0 : 1));
                        $dropdown = \Nav\Button::hasVisibleChildren($bID);
                        $bHeight =  $b['img_height'];
                        $bWidth =   $b['img_width'];
                        $bSrc =     "url(./img/button/".$bID."/".$b['img_checksum'].")";
                        $bOffset =  ($dropdown ? '100%' : '0')." ".($active ? '0' : -2*$bHeight).'px';
                        if (substr($bURL, 0, 8)=='./?page=') {
                            $bURL = BASE_PATH.substr($bURL, 8);
                        }
                        $bURL = htmlentities(html_entity_decode($bURL));
                        $out.=
                             str_repeat('  ', $this->depth)
                            ."  <li"
                            ." id=\"btn_".$bID."\""
                            .($b['visible'] ?
                                ""
                             :
                                " class=\"invisible\""
                               ." title=\"This button would normally be hidden,\nbut administrators can still see it.\""
                             )
                            .">"
                            ."<a"
                            ." href=\"".$bURL."\""
                            .($active ? " class='nav_active'" : "")
                            .($bPopup ? " rel='external'" : "")
                            .(($this->isAdmin && $bSystemID==SYS_ID) || $this->isMasterAdmin ?
                                 " onmouseover=\""
                                ."CM_Navbutton_Over("
                                .$bID.","
                                .$bStyleID.","
                                .$canAddSubmenu.","
                                ."'".$sNameSafe."',"
                                ."'".sanitize('html', $bStyleName)."'"
                                .");"
                                ."\""
                              :
                                ""
                             )
                            .">"
                            ."<img role=\"presentation\""
                            ." src=\"".BASE_PATH."img/spacer\""
                            ." height=\"".$bHeight."\""
                            ." width=\"".$bWidth."\""
                            ." style='"
                            .((
                                $this->navsuiteID=='root' &&
                                $this->navsuite['button_spacing'] &&
                                $current_button<$buttons_count
                             ) ?
                                "margin-right:".$this->navsuite['button_spacing']."px;"
                             :
                                ""
                             )
                            ."background:".$bSrc." no-repeat ".$bOffset."'"
                            ." alt=\"".$bTextSafe."\"/>"
                            ."<span style='display:none'>".$bTextSafe."</span>"
                            ."</a>";
                        if ($childID) {
                            $subnav = new DrawNav('submenu', $childID, $this->rootOrientation, $this->depth);
                            $out.=
                                 "\n"
                                .$subnav->draw()
                                .str_repeat('  ', $this->depth+1);
                        }
                        $out.= "</li>\n";
                    }
                }
                $out.=
                    str_repeat('  ', $this->depth)
                    ."</ul>\n";
                break;
        }
        return
            ($this->navsuiteID=='root' ?
                 "<div id='nav_root_".$this->nav."' style='width:".$this->width."px;height:".$this->height."px;'>\n"
                .$out
                ."</div>\n"
             :
                $out
            );
    }

    protected function setup()
    {
        global $page_vars;
        $this->siteURL =            ($_SERVER["SERVER_PORT"]==443 ? "https://" : "http://").$_SERVER["HTTP_HOST"];
        if (!$this->nav) {
            return;
        }
        $this->setupPermissions();
        $suiteID = ($this->navsuiteID!=='root' ? $this->navsuiteID : $page_vars['navsuite'.$this->nav.'ID']);
        if ($suiteID=="1") {
            return;
        }
        $this->objNavSuite =    new \Nav\Suite($suiteID);
        $this->buttons =        $this->objNavSuite->getButtons();
        $this->setupLoadNavsuite();
        $this->setupGetDimensions();
        $this->setupGetOffsets();
    }

    protected function checkHasVisible()
    {
        if ($this->isAdmin) {
            return true;
        }
        foreach ($this->buttons as $button) {
            if ($button['visible']) {
                return true;
            }
        }
        return false;
    }

    protected function setupGetDimensions()
    {
        if ($this->navsuiteID!=='root') {
            return;
        }
        if (!$this->buttons) {
            return;
        }
        foreach ($this->buttons as $button) {
            if ($button['visible'] || $this->isAdmin) {
                if ($this->navsuite['orientation']=="|") {
                    $this->height+=$button['img_height'];
                    $this->height+=$this->navsuite['button_spacing'];
                } else {
                    $this->width+=$button['img_width'];
                    $this->width+=$this->navsuite['button_spacing'];
                }
            }
        }
        if ($this->navsuite['orientation']=="---") {
            $this->height = $this->buttons[0]['img_height'];
        } else {
            $this->width = $this->buttons[0]['img_width'];
        }
    }

    protected function setupGetOffsets()
    {
        switch($this->navsuite['parentOrientation']) {
            case "|":
                $this->offsetX = $this->navsuite['parentWidth']+$this->navsuite['subnavOffsetX'];
                $this->offsetY = $this->navsuite['subnavOffsetY'];
                break;
            default:
                $this->offsetX = $this->navsuite['subnavOffsetX'];
                $this->offsetY = $this->navsuite['parentHeight']+$this->navsuite['subnavOffsetY'];
                break;
        }
    }

    protected function setupLoadNavsuite()
    {
        $sql =
             "SELECT\n"
            ."  `ns`.`ID`,\n"
            ."  `ns`.`buttonStyleID`,\n"
            ."  `ns`.`name`,\n"
            ."  `ns`.`parentButtonID`,\n"
            ."  `ns`.`width`,\n"
            ."  `nst`.`name` AS `navstyle_name`,\n"
            ."  `nst`.`orientation`,\n"
            ."  `nst`.`subnavStyleID`,\n"
            ."  `nst`.`button_spacing`,\n"
            ."  `p_nst`.`subnavOffsetX`,\n"
            ."  `p_nst`.`subnavOffsetY`,\n"
            ."  `p_nst`.`orientation` AS `parentOrientation`,\n"
            ."  `p_nb`.`img_width` AS `parentWidth`,\n"
            ."  `p_nb`.`img_height` AS `parentHeight`\n"
            ."FROM\n"
            ."  `navsuite` as `ns`\n"
            ."INNER JOIN `navstyle` AS `nst` ON\n"
            ."  `nst`.`ID` = `ns`.`buttonStyleID`\n"
            ."LEFT JOIN `navbuttons` AS `p_nb` ON\n"
            ."  `p_nb`.`ID` = `ns`.`parentButtonID`\n"
            ."LEFT JOIN `navsuite` AS `p_ns` ON\n"
            ."  `p_ns`.`ID` = `p_nb`.`suiteID`\n"
            ."LEFT JOIN `navstyle` AS `p_nst` ON\n"
            ."  `p_nst`.`ID` = `p_ns`.`buttonStyleID`\n"
            ."WHERE\n"
            ."  `ns`.`ID` IN(".$this->objNavSuite->_get_ID().")";
        $this->navsuite = $this->objNavSuite->get_record_for_sql($sql);
    }

    protected function setupPermissions()
    {
        $isMASTERADMIN =        get_person_permission("MASTERADMIN");
        $isSYSADMIN =           get_person_permission("SYSADMIN");
        $isSYSAPPROVER =        get_person_permission("SYSAPPROVER");
        $isSYSEDITOR =          get_person_permission("SYSEDITOR");
        $this->isAdmin =        ($isSYSEDITOR||$isSYSAPPROVER||$isSYSADMIN||$isMASTERADMIN);
        $this->isMasterAdmin =  $isMASTERADMIN;

    }


    public static function getVersion()
    {
        return VERSION_NS_NAV_DRAWNAV;
    }
}
