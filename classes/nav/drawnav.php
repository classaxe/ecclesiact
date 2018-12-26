<?php
namespace Nav;

/*
Version History:
  1.0.10 (2018-12-24)
    1) Change to drawImageButton() to make accessible text appear correctly for screen readers
*/
class DrawNav extends \Base
{
    const VERSION = '1.0.10';

    protected $buttons;
    protected $buttonsCount = 0;
    protected $currentButton = 0;
    protected $depth;
    protected $hasVisible;
    protected $html;
    protected $isAdmin;
    protected $isMasterAdmin;
    protected $nav;
    protected $navsuite;
    protected $navsuiteID;
    protected $objNavSuite;
    protected $offsetX;
    protected $offsetY;
    protected $siteURL;
    protected $height = 0;
    protected $width =  0;

    public function __construct($nav = "", $navsuiteID = 'root', $depth = 0)
    {
        $this->nav =                $nav;
        $this->navsuiteID =         $navsuiteID;
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
        $this->drawCss();
        switch ($this->navsuite['navstyle_type']) {
            case "Image":
                return $this->drawImageButtons();
                break;
            case "Responsive":
                return $this->drawResponsiveMenu();
                break;
            case "SD Menu":
                return $this->drawSDMenu();
                break;
        }
    }

    protected function drawCss()
    {
        if ($this->navsuiteID!=='root') {
            return;
        }
        if (!$this->navsuite['navstyle_css']) {
            return;
        }
        \Output::push(
            'style',
            str_replace(
                '#ID',
                '#nav_root_'.$this->nav,
                $this->navsuite['navstyle_css']."\n"
            )
        );
    }

    protected function drawImageButton($b)
    {
        if (!$b['enabled'] && !$this->isAdmin) {
            return;
        }
        if (!$b['visible'] && !$this->isAdmin) {
            return;
        }
        $this->html.=
             str_repeat('  ', $this->depth)
            ."  <li"
            ." id=\"btn_".$b['ID']."\""
            .($b['visible'] && $b['enabled']?
                ""
             :
                " class=\"invisible\""
               ." title=\"HINT\nThis button would normally be hidden.\n"
               ."This is "
               .(!$b['enabled'] ? "because it is currently disabled" : "")
               .(!$b['enabled'] && !$b['visible'] ? " and also" : "")
               .(!$b['visible'] ? "due to its permission settings" : "")
               .".\n"
               ."However administrators like you can still see it.\""
             )
            .">"
            ."<a"
            ." href=\"".$b['URL']."\""
            .($b['active'] ? " class='nav_active'" : "")
            .($b['popup'] ?  " rel='external'" : "")
            .(($this->isAdmin && $b['systemID']==SYS_ID) || $this->isMasterAdmin ?
                 " onmouseover=\""
                ."CM_Navbutton_Over("
                .$b['ID'].","
                .($b['enabled'] ? 1 : 0).","
                .$this->navsuite['buttonStyleID'].","
                .$b['canAddSubmenu'].","
                ."'".$b['suiteNameSafe']."',"
                ."'".sanitize('html', $this->navsuite['navstyle_name'])."'"
                .");"
                ."\""
              :
                ""
             )
            .">"
            ."<img role=\"presentation\""
            ." src=\"".BASE_PATH."img/spacer\""
            ." height=\"".$b['img_height']."\""
            ." width=\"".$b['img_width']."\""
            ." style='"
            .((
                $this->navsuiteID=='root' &&
                $this->navsuite['button_spacing'] &&
                $this->currentButton < $this->buttonsCount
             ) ?
                ($this->navsuite['orientation']==='---' ? "margin-right:" : "margin-bottom:")
                .$this->navsuite['button_spacing']."px;"
             :
                ""
             )
            ."background:".$b['src']." no-repeat ".$b['offset']."'"
            ." alt=\"\"/>"
            ."<span class=\"sr-only\">".$b['textSafe']."</span>"
            ."</a>";
        if ($b['childID']) {
            $subnav = new DrawNav('submenu', $b['childID'], $this->depth);
            $this->html.=
                 "\n"
                .$subnav->draw()
                .str_repeat('  ', $this->depth+1);
        }
        $this->html.=
            "</li>\n";
        $this->currentButton++;
    }

    protected function drawImageButtons()
    {
        $this->getVisibleButtonsCount();
        $this->setupGetDimensions();
        $this->setupGetOffsets();
        $this->depth++;
        $this->html =
             str_repeat('  ', $this->depth)
            ."<ul id='nav_".$this->navsuite['ID']."'"
            ." class='"
            .($this->navsuiteID=='root' ?
                ($this->navsuite['orientation']==='|' ? 'vnavmenu' : 'hnavmenu').' '
             :
                ""
             )
            ."nav_style_".get_js_safe_ID($this->navsuite['navstyle_name'])."'"
            .($this->navsuiteID=='root' ?
                ""
             :
                " style='left:".$this->offsetX."px; top:".$this->offsetY."px;'"
             )
            .">\n";
        $this->currentButton = 0;
        foreach ($this->buttons as $b) {
            static::setupButton($b);
            $this->drawImageButton($b);
        }
        $this->html.=
            str_repeat('  ', $this->depth)
            ."</ul>\n";
        return
            ($this->navsuiteID=='root' ?
                 "<div id='nav_root_".$this->nav."' style='width:".$this->width."px;height:".$this->height."px;'>\n"
                .$this->html
                ."</div>\n"
             :
                $this->html
            );
    }

    protected function drawResponsiveMenu()
    {
        $this->depth++;
        $this->html.=
             str_repeat('  ', $this->depth)
            ."        <ul"
            .($this->navsuiteID=='root' ?
                 " class='"
                .($this->navsuite['orientation']==='|' ? 'rvnavmenu' : 'rhnavmenu')
                ." navbar-nav sf-menu navbar-left'"
                .($this->nav === 1 ? " data-type='navbar'" : "")
             :
                " class='dropdown-menu'"
             )
            ." id='nav_".$this->navsuite['ID']."'"
            .">\n";
        foreach ($this->buttons as $b) {
            $this->drawResponsiveMenuButton($b);
        }
        $this->html.=
             str_repeat('  ', $this->depth)
            ."</ul>\n";
        return
            ($this->navsuiteID=='root' ?
                 "<div id='nav_root_".$this->nav."'>\n"
                .$this->html
                ."</div>\n"
             :
                $this->html
            );
    }

    protected function drawResponsiveMenuButton($b)
    {
        if (!$b['visible'] && !$this->isAdmin) {
            return;
        }
        $this->setupButton($b);
        $this->html.=
             "          <li"
            ." id=\"btn_".$b['ID']."\""
            .($b['active'] || ($b['dropdown'] && $this->depth<2) ?
                 " class=\""
                .($b['active'] ? "active" : "")
                .($b['dropdown'] && $this->depth<2 ? ($b['active'] ? " " : "")."dropdown" : "")
                ."\""
             :
                ""
             )
            .($b['visible'] ?
                ""
             :
                " title=\"This button would normally be hidden,\nbut administrators can still see it.\""
             )
             ."><a href=\"".$b['URL']."\""
            .($b['popup'] ?  " rel='external'" : "")
            .(($this->isAdmin && $b['systemID']==SYS_ID) || $this->isMasterAdmin ?
                 " onmouseout=\"this.style.backgroundColor=''\""
                ." onmouseover=\""
                ."this.style.backgroundColor='#80ff80';CM_Responsive_Over("
                .$b['ID'].","
                .$this->navsuite['buttonStyleID'].","
                .$b['canAddSubmenu'].","
                ."'".$b['suiteNameSafe']."',"
                ."'".sanitize('html', $this->navsuite['navstyle_name'])."'"
                .");"
                ."\""
              :
                ""
             )
            .">"
            .$b['text1']
            ."</a>\n";
        if ($b['childID']) {
            $subnav = new DrawNav('submenu', $b['childID'], $this->depth);
            $this->html.=
                 $subnav->draw()
                .str_repeat('  ', $this->depth+1);
        }
        $this->html.=
             "          </li>\n";
    }

    protected function drawSDMenuJS()
    {
        \Output::push(
            'javascript_onload',
            "  new SDMenu("
            ."'nav_root_".$this->nav."',"
            .$this->navsuite['sdmenu_speed'].","
            .($this->navsuite['sdmenu_exclusive'] ? 'true' : 'false')
            .").init();\n"
        );
    }

    protected function drawSDMenu()
    {
        $this->drawSDMenuJS();
        $this->_suiteID = $this->objNavSuite->record['ID'];
        $this->_tree =  $this->objNavSuite->getTree(false, $this->_suiteID, 0, true);
        $ulEnd = strpos($this->_tree, '</ul>');
        $ul = substr($this->_tree, 0, $ulEnd);
        $firstQuote =   1+strpos($this->_tree, "'");
        $firstUL =      1+strpos($this->_tree, "\n");
        return
             "<ul id=\"nav_root_".$this->nav."\" class=\"sdmenu\">\n"
            ."  <li class=\"border_top\"></li>\n"
            .substr($this->_tree, $firstUL, -6)
            ."  <li class=\"border_bottom\"></li>\n"
            ."</ul>";
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
        $this->setupLoadNavsuite();
        switch ($this->navsuite['navstyle_type']) {
            case "Image":
                $legacyMode = false;
                break;
            case "Responsive":
                $legacyMode = true;
                break;
            case "SD Menu":
                $legacyMode = true;
                break;
        }
        $this->buttons =        $this->objNavSuite->getButtons(false, false, $legacyMode);
    }

    protected function checkHasVisible()
    {
        if ($this->isAdmin) {
            return true;
        }
        foreach ($this->buttons as $button) {
            if ($button['visible'] && $button['enabled']) {
                return true;
            }
        }
        return false;
    }

    protected function getVisibleButtonsCount()
    {
        foreach ($this->buttons as $button) {
            if ($button['visible'] && $button['enabled'] || $this->isAdmin) {
                $this->buttonsCount++;
            }
        }
    }

    protected function setupButton(&$b)
    {
        $b['textSafe'] = str_replace(
            array("'", "\r\n", "\n"),
            array("&rsquo;", " ", " "),
            sanitize('html', $b['text1'])
        );
        $b['suiteNameSafe'] = str_replace(
            "'",
            "&rsquo;",
            sanitize('html', $this->navsuite['name'])
        );
        $b['active'] =          \Nav\Button::isActive($b['URL'], $this->siteURL);
        $b['canAddSubmenu'] =   ($b['childID'] ? -1 : ($this->navsuite['subnavStyleID']==1 ? 0 : 1));
        $b['dropdown'] =         \Nav\Button::hasVisibleChildren($b['ID']);
        $b['src'] =             "url(./img/button/".$b['ID']."/".$b['img_checksum'].")";
        $b['offset'] =          ($b['dropdown'] ? '100%' : '0')." ".($b['active'] ? '0' : -2 * $b['img_height']).'px';
        if (substr($b['URL'], 0, 8)=='./?page=') {
            $b['URL'] = BASE_PATH.substr($b['URL'], 8);
        }
        $b['URL'] = htmlentities(html_entity_decode($b['URL']));
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
            ."  `nst`.`css` AS `navstyle_css`,\n"
            ."  `nst`.`name` AS `navstyle_name`,\n"
            ."  `nst`.`type` AS `navstyle_type`,\n"
            ."  `nst`.`orientation`,\n"
            ."  `nst`.`subnavStyleID`,\n"
            ."  `nst`.`button_spacing`,\n"
            ."  `nst`.`sdmenu_exclusive`,\n"
            ."  `nst`.`sdmenu_speed`,\n"
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
}
