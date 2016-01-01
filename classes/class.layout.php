<?php
/*
Version History:
  1.0.35 (2016-01-01)
    1) Now Layout::prepareResponsiveHead() includes responsive css file 
*/

class Layout extends Record
{
    const VERSION = '1.0.35';
    const FIELDS = 'ID, archive, archiveID, deleted, systemID, name, colour1, colour2, colour3, colour4, component_parameters, content, include_body_bottom, include_head_top, language, languageOptionParentID, navsuite1ID, navsuite2ID, navsuite3ID, responsive, style, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';

    public function __construct($ID = "")
    {
        parent::__construct("layout", $ID);
        $this->_set_object_name('Layout');
        $this->set_edit_params(
            array(
                'report_rename' =>          true,
                'report_rename_label' =>    'new name'
            )
        );
    }

    public function exportSql($targetID, $show_fields)
    {
        return $this->sqlExport($targetID, $show_fields);
    }

    public function getCssChecksum($ID)
    {
        if (!$ID) {
            return "";
        }
        $sql =
             "SELECT\n"
            ."  CONCAT(`colour1`,`colour2`,`colour3`,`colour4`,`style`) AS `code`\n"
            ."FROM\n"
            ."  `".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `ID`=".$ID;
        $code = $this->get_field_for_sql($sql);
        return dechex(crc32($code));
    }

    public function getLanguageOptions()
    {
        $sql =
             "SELECT\n"
            ."  `ID`,\n"
            ."  `language`\n"
            ."FROM\n"
            ."  `".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `languageOptionParentID` IN(".$this->_get_ID().")\n"
            ."ORDER BY\n"
            ."  `language`";
        return $this->get_records_for_sql($sql);
    }

    public static function getSelectorSql($include_system_default = true)
    {
        if (!get_person_permission("MASTERADMIN")) {
            return
                 "SELECT\n"
                ."  CONCAT(IF(`systemID`=1,IF(`layout`.`ID`=1,' ','* '),' '),`layout`.`name`) AS `text`,\n"
                ."  `layout`.`ID` AS `value`,\n"
                ."  IF(`ID`=1,'f0f0f0',IF(`systemID`=1,'e0e0ff','c0ffc0')) AS `color_background`\n"
                ."FROM\n"
                ."  `layout`\n"
                ."WHERE\n"
                .($include_system_default ? "" : "  `layout`.`ID`!=1 AND\n")
                ."  `layout`.`languageOptionParentID`=1 AND\n"
                ."  `systemID` IN(1,SYS_ID)\n"
                ."ORDER BY\n"
                ."  `text`";
        }
        return
            "SELECT\n"
            ."    `layout`.`ID` AS `value`,\n"
            ."    CONCAT(\n"
            ."        IF(\n"
            ."            `layout`.`ID`=1,\n"
            ."            ' ',\n"
            ."            CONCAT(\n"
            ."                IF(\n"
            ."                    `systemID` = 1,\n"
            ."                    '* ',\n"
            ."                    CONCAT(\n"
            ."                        UPPER(`system`.`textEnglish`),\n"
            ."                        ' | '\n"
            ."                    )\n"
            ."                )\n"
            ."            )\n"
            ."        ),\n"
            ."        `layout`.`name`\n"
            ."    ) AS `text`,\n"
            ."    IF(\n"
            ."        `layout`.`ID`=1,\n"
            ."        'f0f0f0',\n"
            ."        IF(\n"
            ."            `layout`.`systemID`=1,\n"
            ."            'e0e0ff',\n"
            ."            IF(\n"
            ."                `layout`.`systemID`=".SYS_ID.",\n"
            ."                'c0ffc0',\n"
            ."                'ffe0e0'\n"
            ."            )\n"
            ."        )\n"
            ."    ) AS `color_background`\n"
            ."FROM\n"
            ."    `layout`\n"
            ."INNER JOIN `system` ON\n"
            ."    `layout`.`systemID` = `system`.`ID`\n"
            ."WHERE\n"
            .($include_system_default ? "" : "    `layout`.`ID`!=1 AND\n")
            ."    `layout`.`languageOptionParentID`=1 AND\n"
            ."    1\n"
            ."ORDER BY\n"
            ."    `layout`.`systemID`!=1,`text`";
    }

    public function handleReportCopy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::tryCopy($newID, $msg, $msg_tooltip, $name);
    }

    public function handleReportDelete(&$msg)
    {
        $targetID = $this->_get_ID();
        $is_are =   (count(explode(",", $targetID))>1 ? 'are' : 'is');
        $usage =    $this->usage();
        if ($usage['internal']==0 && $usage['system']==0 && $usage['page']==0) {
            return parent::try_delete($msg);
        }
        $errors = array();
        if ($usage['internal']>0) {
            $errors[] =
                ($usage['internal']==1 ? " specified as an internal layout" : " specified as internal layouts");
        }
        if ($usage['system']>0) {
            $errors[] =
                " specified by ".$usage['system']." system".($usage['system']==1 ? "" : "s")." as default layout";
        }
        if ($usage['page']>0) {
            $errors[] =
                " required for ".$usage['page']." page".($usage['page']==1 ? "" : "s");
        }
        $msg = status_message(
            2,
            true,
            $this->_get_object_name(),
            '',
            $is_are." ".implode(', ', $errors)." - deletion has therefore been cancelled.",
            $targetID
        );
        return false;
    }

    public function prepare()
    {
        global $page_vars;
        $content = $page_vars['layout']['content'];
        if ($include = $page_vars['layout']['include_head_top']) {
            Output::push('head_include', $include);
        }
        if ($personID = get_userID()) {
            $Obj_Person = new Person($personID);
            $Obj_Person->load_profile_fields();
        }
        if ($page_vars['layout']['responsive']) {
            $this->prepareResponsiveHead();
            Output::push('body', convert_safe_to_php($content));
            $this->prepareResponsiveFoot();
        } else {
            $this->prepareXhtmlHead();
            Output::push('body', convert_safe_to_php($content));
            $this->prepareXhtmlFoot();
        }
        if ($include = $page_vars['layout']['include_body_bottom']) {
            Output::push('body_bottom', $include);
        }

    }

    public function prepareXhtmlFoot()
    {
        global $system_vars;
        Output::push(
            'body_bottom',
            "<div id='CM'></div>\n"
        );
        Output::push(
            'html_bottom',
            "</form>\n"
            ."</body>\n"
            ."</html>"
        );
    }

    public function prepareXhtmlHead()
    {
        global $page_vars, $system_vars, $print, $report_name, $ID, $mode;
        global $anchor, $bulk_update, $component_help, $DD, $limit, $memberID, $offset, $page, $sortBy;
        global $MM, $YYYY, $selectID, $selected_section, $preset_values;
        global $search_categories, $search_date_end, $search_date_start, $search_keywords;
        global $search_name, $search_offset, $search_text, $search_type;
        global $filterExact,$filterField,$filterValue;
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        $isUSERADMIN =      get_person_permission("USERADMIN");
        $isSYSADMIN =       get_person_permission("SYSADMIN");
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER");
        $isSYSEDITOR =      get_person_permission("SYSEDITOR");
        $CM_level =
            $isMASTERADMIN ? 3 : ($isSYSADMIN ? 2 : ($isUSERADMIN || $isSYSAPPROVER || $isSYSEDITOR ? 1 : 0));
        $isIE =                strpos(getenv("HTTP_USER_AGENT"), "MSIE");
        $isAdmin =
            get_person_permission("GROUPEDITOR") ||
            get_person_permission("SYSEDITOR") ||
            get_person_permission("SYSADMIN") ||
            get_person_permission("SYSAPPROVER") ||
            get_person_permission("MASTERADMIN");
        if (isset($page_vars)) {
            $layoutID =       ($page_vars['layoutID']!='1' ? $page_vars['layoutID'] : $system_vars['defaultLayoutID']);
        } else {
            $layoutID =       $system_vars['defaultLayoutID'];
        }
        switch ($print) {
            case 1:
                $Obj_Layout = new layout();
                $layoutID = $Obj_Layout->get_ID_by_name("_print", SYS_ID.',1');
                break;
            case 2:
                $Obj_Layout = new layout();
                $layoutID = $Obj_Layout->get_ID_by_name("_popup", SYS_ID.',1');
                break;
        }
        $page_vars['layoutID'] = $layoutID;
        $favicon =          (isset($system_vars['favicon']) ? $system_vars['favicon'] : "");
        $Obj_System =       new System();
        $Obj_Layout =       new Layout();
        $cs_layout =        $Obj_Layout->getCssChecksum($page_vars['layoutID']);
        $showCM =           $isAdmin && (!isset($mode) || ($mode!='details' && $mode!='print_form'));
        $showLoading =      $isAdmin && ($page_vars['layoutID']!=2);
        Output::push(
            'html_top',
            str_replace('%HOST%', trim($system_vars['URL'], '/'), DOCTYPE)."\n"
            ."<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"".$system_vars['defaultLanguage']."\""
            ." xml:lang=\"".$system_vars['defaultLanguage']."\">\n"
            ."<head>\n"
            ."<title>".strip_tags(convert_safe_to_php($page_vars['title']))."</title>\n"
            ."<meta http-equiv=\"content-type\" content=\"text/html;"
            ." charset=".(ini_get('default_charset') ? ini_get('default_charset') : "UTF-8")."\"/>\n"
            ."<meta name=\"generator\" content=\"".System::get_item_version('system_family')." "
            .System::get_item_version('codebase').".".$system_vars['db_version']."\"/>\n"
            .($page_vars['meta_description'] ?
                "<meta name=\"description\" content=\"".$page_vars['meta_description']."\"/>\n"
             :
                ""
             )
            .($page_vars['meta_keywords'] ?
                "<meta name=\"keywords\" content=\"".$page_vars['meta_keywords']."\"/>\n"
             :
                ""
             )
        );
        Output::push(
            'head_top',
            ($favicon ? "<link rel=\"shortcut icon\" href=\"".BASE_PATH."img/sysimg/".$favicon."\"/>\n" : "")
            ."<link rel=\"search\" type=\"application/opensearchdescription+xml\""
            ." title=\"".$system_vars['textEnglish']." Search\" href=\"".BASE_PATH."osd/\" />\n"
            .(System::has_feature('Articles') ?
                 "<link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Articles Feed\""
                ." href=\"".BASE_PATH."rss/articles\" />\n"
             :
                ""
             )
            .(System::has_feature('Events') ?
                 "<link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Events Feed\""
                ." href=\"".BASE_PATH."rss/events\" />\n"
             :
                ""
             )
            .(System::has_feature('Jobs') ?
                 "<link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Job Postings Feed\""
                ." href=\"".BASE_PATH."rss/jobs\" />\n"
             :
                ""
             )
            .(System::has_feature('News') ?
                 "<link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS News Feed\""
                ." href=\"".BASE_PATH."rss/news\" />\n"
             :
                ""
             )
            .(System::has_feature('Podcasting') ?
                 "<link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Podcasts Feed\""
                ." href=\"".BASE_PATH."rss/podcasts\" />\n"
            :
                ""
            )
        );
        Output::push(
            'style_include',
            Output::drawCssInclude()
            .($mode!='details' ?
                 "<link rel=\"stylesheet\" type=\"text/css\""
                ." href=\"".BASE_PATH."css/layout/".$page_vars['layoutID']."/".$cs_layout."\" />\n"
             :
                ""
             )
            .(isset($page_vars) && trim($page_vars['theme']['style'])!='' ?
                 "<link rel=\"stylesheet\" type=\"text/css\""
                ." href=\"".BASE_PATH."css/theme/".$page_vars['theme']['ID']."/"
                .dechex(crc32($page_vars['theme']['style']))."\" />"
             :
                ""
             )
        );
        Output::push(
            'style',
            (isset($report_name) && $report_name=='system' ?
                "@media screen { // Only appears for system report\n"
                ."  .scrollbox { height: 140px; media: screen; overflow: auto; border: none; }\n"
                ."}\n"
             :
                ""
            )
            .($isIE ?
                 ".css3, .form_box,"
                ." .shadow { behavior: url(".BASE_PATH."css/pie/".System::get_item_version('css_pie')."); }\n"
            :
                ""
            )
            .".zoom_text { font-size: "
            .(isset($_COOKIE['textsize']) && $_COOKIE['textsize']=='big' ? "120" : "80")
            ."%;}\r\n"
            // Page
            .(isset($page_vars) && trim($page_vars['style'])!='' ?
            "\r\n"
            ."/* [Page Style] */\r\n"
            .$page_vars['style']
            : "")
            // Theme
        );
        Output::push('javascript_top', Output::drawJsInclude(false, $CM_level));
        Output::push(
            'javascript',
            "var \$J, _gaq, _paq, _onload, _onunload, ap_instances, base_url, currency_symbol,\n"
            ."  currentLanguage, defaultDateFormat, defaultTimeFormat, fck_version, option_separator,\n"
            ."  pwd_len_min, rating_blocks, site_title, system_family, valid_prefix;\n"
            ."\$J =               jQuery;\n"
            ."ap_instances =      [];\n"
            ."base_url =          \"".BASE_PATH."\";\n"
            ."cke_posting_fonts = ".(System::has_feature('Postings-allow-fonts-and-sizes') ? 1 : 0).";\n"
            ."currency_symbol =   \"".$system_vars['defaultCurrencySymbol']."\";\n"
            ."currentLanguage =   \""
            .(isset($_SESSION['lang']) ? $_SESSION['lang'] : $system_vars['defaultLanguage'])."\"\n"
            ."defaultDateFormat = \"".addslashes($system_vars['defaultDateFormat'])."\";\n"
            ."defaultTimeFormat = \"".$system_vars['defaultTimeFormat']."\";\n"
            ."option_separator =  \"".OPTION_SEPARATOR."\";\n"
            ."pwd_len_min =       ".PWD_LEN_MIN.";\n"
            ."rating_blocks =     [];\n"
            ."site_title =        \"".$system_vars['textEnglish']."\";\n"
            ."system_family =     \"".System::get_item_version('system_family')."\";\n"
            ."valid_prefix =      \"vp_\"; // Used with controls in Custom_Form class\n"
            .(
                $system_vars['debug_no_internet']!=1 &&
                $system_vars['google_analytics_key']!='' &&
                $mode!='details' &&
                $mode!='report' ?
                    "(function (i,s,o,g,r,a,m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function () {\n"
                    ."(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n"
                    ."m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n"
                    ."})(window,document,'script','//www.google-analytics.com/analytics.js','ga');\n"
                    ."ga('create', '".$system_vars['google_analytics_key']."');\n"
                    ."ga('send', 'pageview');\n"
                : ""
             )
            .(
                $system_vars['debug_no_internet']!=1 &&
                $system_vars['piwik_id'] &&
                $mode!='details' &&
                $mode!='report' ?
                     "var _paq = _paq || [];\n"
                    ."(function () {\n"
                    ."  var u=document.location.protocol+\"//\"+document.location.hostname+\"/piwik/\";\n"
                    ."  _paq.push(['setSiteId', ".$system_vars['piwik_id']."]);\n"
                    ."  _paq.push(['setTrackerUrl', u+'piwik.php']);\n"
                    ."  _paq.push(['trackPageView']);\n"
                    ."  _paq.push(['enableLinkTracking']);\n"
                    ."  var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];\n"
                    ."  g.type='text/javascript'; g.defer=true; g.async=true; g.src=u+'piwik.js';\n"
                    ."  s.parentNode.insertBefore(g,s);\n"
                    ."})();\n"
                :
                    ""
            )
        );
        Output::push(
            'javascript_bottom',
            "addEvent(window,\"load\",_onload);\n"
            ."addEvent(window,\"unload\",_onunload);\n"
        );
        \Nav\Suite::drawJsPreload(false);
        $anchor_ID = System::get_item_version('system_family').'_main_content';
        $js_onload =
        "  externalLinks();\n"
        ."  initialise_tooltips();\n"
        .($isIE ? "  initialise_constraints();\n" : "")
        ."  ToolTips.attachBehavior();\n"
        .($CM_level>0 ? "  CM_load();\n" : "")
        .($showLoading ? "  if (popup_msg==='') { popup_hide_on_loaded(); }\n" : "");
        Output::push('javascript_onload', $js_onload);
        Output::push(
            'javascript_onunload',
            "  ToolTips.out();\n"
            ."  EventCache.flush();\n"
            ."  if (window.GUnload) {window.GUnload();}\n"
        );
        Output::push('head_bottom', "</head>\r\n");
        Output::push(
            'body_top',
            "<body class=\""
            .(isset($_COOKIE['textsize']) && $_COOKIE['textsize']=='big' ? "zoom_big" : "zoom_small")
            ."\">"
        );
        Output::push(
            'body',
            "<form id='form' enctype='multipart/form-data' method='post' action='./' style='padding:0;margin:0;'>\r\n"
            ."<div id='top' class='margin_none padding_none'>\r\n"
            ."<a href=\"#".$anchor_ID."\" title=\"Main content begins here\" class='fl' style=\"display:none\">"
            ."Skip to Main Content</a>\r\n"
            .draw_form_field('limit', $limit, 'hidden')."\r\n"
            .draw_form_field('offset', $offset, 'hidden')."\r\n"
            .draw_form_field('filterExact', $filterExact, 'hidden')."\r\n"
            .draw_form_field('filterField', $filterField, 'hidden')."\r\n"
            .draw_form_field('filterValue', $filterValue, 'hidden')."\r\n"
            .draw_form_field('anchor', $anchor, 'hidden')."\r\n"
            .draw_form_field('bulk_update', $bulk_update, 'hidden')."\r\n"
            .draw_form_field('command', '', 'hidden')."\r\n"
            .draw_form_field('component_help', $component_help, 'hidden')."\r\n"
            .draw_form_field('DD', $DD, 'hidden')."\r\n"
            .draw_form_field('goto', $page, 'hidden')."\r\n"
            .draw_form_field('mode', $mode, 'hidden')."\r\n"
            .draw_form_field('MM', $MM, 'hidden')."\r\n"
            .draw_form_field('preset_values', $preset_values, 'hidden')."\r\n"
            .draw_form_field('print', $print, 'hidden')."\r\n"
            .draw_form_field('report_name', $report_name, 'hidden')."\r\n"
            .draw_form_field('rnd', dechex(mt_rand(0, mt_getrandmax())), 'hidden')."\r\n"
            .draw_form_field('search_categories', $search_categories, 'hidden')."\r\n"
            .draw_form_field('search_date_end', $search_date_end, 'hidden')."\r\n"
            .draw_form_field('search_date_start', $search_date_start, 'hidden')."\r\n"
            .draw_form_field('search_keywords', $search_keywords, 'hidden')."\r\n"
            .draw_form_field('search_name', $search_name, 'hidden')."\r\n"
            .draw_form_field('search_offset', $search_offset, 'hidden')."\r\n"
            .draw_form_field('search_text', $search_text, 'hidden')."\r\n"
            .draw_form_field('search_type', $search_type, 'hidden')."\r\n"
            .draw_form_field('selectID', $selectID, 'hidden')."\r\n"
            .draw_form_field('selected_section', $selected_section, 'hidden')."\r\n"
            .draw_form_field('sortBy', $sortBy, 'hidden')."\r\n"
            .draw_form_field('source', '', 'hidden')."\r\n"
            .draw_form_field('submode', '', 'hidden')."\r\n"
            .draw_form_field('targetID', '', 'hidden')."\r\n"
            .draw_form_field('targetField', '', 'hidden')."\r\n"
            .draw_form_field('targetFieldID', '', 'hidden')."\r\n"
            .draw_form_field('targetReportID', '', 'hidden')."\r\n"
            .draw_form_field('targetValue', '', 'hidden')."\r\n"
            .draw_form_field('YYYY', $YYYY, 'hidden')."\r\n"
            ."</div>"
            ."\n<!-- Modal Popup mask -->\n"
            ."<div id=\"popupMask\" style=\"display:none;\"></div>\n"
            ."<div id=\"popupContainer\" style=\"display:none;\">\n"
            ."  <div id=\"popupInner\">\n"
            ."    <div id=\"popupTitleBar\">\n"
            ."      <div id=\"popupTitle\"></div>\n"
            ."      <div id=\"popupControls\">"
            ."<img src=\"".BASE_PATH."img/spacer\" class=\"icons\" height=\"10\" width=\"10\""
            ." style='background-position:-2590px 0px;' onclick=\"hidePopWin(null)\" alt='Close' /></div>"
            ."    </div>\n"
            ."    <div id=\"popupBody\"></div>\n"
            ."  </div>\n"
            ."</div>\n"
            .($showLoading ? "<script type='text/javascript'>show_popup_please_wait();</script>\n" : "")
        );
        if (
            Base::module_test('Church') &&
            $system_vars['debug_no_internet']!=1 &&
            $mode!='details' &&
            $mode!='report'
        ) {
            $Obj_CBL = new \Component\BibleLinks;
            Output::push('body', $Obj_CBL->draw());
        }
    }

    public function prepareResponsiveHead()
    {
        global $page_vars, $system_vars, $print, $report_name, $ID, $mode;
        global $anchor, $bulk_update, $component_help, $DD, $limit, $memberID, $offset, $page, $sortBy;
        global $MM, $YYYY, $selectID, $selected_section, $preset_values;
        global $search_categories, $search_date_end, $search_date_start, $search_keywords;
        global $search_name, $search_offset, $search_text, $search_type;
        global $filterExact,$filterField,$filterValue;
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        $isUSERADMIN =      get_person_permission("USERADMIN");
        $isSYSADMIN =       get_person_permission("SYSADMIN");
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER");
        $isSYSEDITOR =      get_person_permission("SYSEDITOR");
        $CM_level =
            $isMASTERADMIN ? 3 : ($isSYSADMIN ? 2 : ($isUSERADMIN || $isSYSAPPROVER || $isSYSEDITOR ? 1 : 0));
        $isIE =                strpos(getenv("HTTP_USER_AGENT"), "MSIE");
        $isAdmin =
            get_person_permission("GROUPEDITOR") ||
            get_person_permission("SYSEDITOR") ||
            get_person_permission("SYSADMIN") ||
            get_person_permission("SYSAPPROVER") ||
            get_person_permission("MASTERADMIN");
        if (isset($page_vars)) {
            $layoutID =       ($page_vars['layoutID']!='1' ? $page_vars['layoutID'] : $system_vars['defaultLayoutID']);
        } else {
            $layoutID =       $system_vars['defaultLayoutID'];
        }
        switch ($print) {
            case 1:
                $Obj_Layout = new layout();
                $layoutID = $Obj_Layout->get_ID_by_name("_print", SYS_ID.',1');
                break;
            case 2:
                $Obj_Layout = new layout();
                $layoutID = $Obj_Layout->get_ID_by_name("_popup", SYS_ID.',1');
                break;
        }
        $page_vars['layoutID'] = $layoutID;
        $favicon =          (isset($system_vars['favicon']) ? $system_vars['favicon'] : "");
        $Obj_System =       new System();
        $Obj_Layout =       new Layout();
        $cs_layout =        $Obj_Layout->getCssChecksum($page_vars['layoutID']);
        $showCM =           $isAdmin && (!isset($mode) || ($mode!='details' && $mode!='print_form'));
        $showLoading =      $isAdmin && ($page_vars['layoutID']!=2);

        Output::push(
            'html_top',
            "<!DOCTYPE html>\n"
            ."<html lang=\"".$system_vars['defaultLanguage']."\">\n"
            ."<head>\n"
            ."    <meta charset=\"utf-8\">\n"
            ."    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n"
            ."    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
            // The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags
            ."    <meta name=\"generator\" content=\""
            .System::get_item_version('system_family')." "
            .System::get_item_version('codebase')."."
            .$system_vars['db_version']
            ."\">\n"
            .($page_vars['meta_description'] ?
                "<meta name=\"description\" content=\"".$page_vars['meta_description']."\">\n"
             :
                ""
             )
            .($page_vars['meta_keywords'] ?
                "<meta name=\"keywords\" content=\"".$page_vars['meta_keywords']."\">\n"
             :
                ""
             )
            ."    <title>".strip_tags(convert_safe_to_php($page_vars['title']))."</title>\n"
            ."    <link rel=\"stylesheet\" href=\"//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css\">\n"
            ."    <link rel=\"stylesheet\" href=\"".BASE_PATH."css/responsive/"
            .System::get_item_version('codebase')
            ."\">\n"
            ."    <script src=\"/sysjs/device\"></script>\n"  // Needed for Animate to work properly
        );

        Output::push(
            'head_top',
            ($favicon ? "<link rel=\"shortcut icon\" href=\"".BASE_PATH."img/sysimg/".$favicon."\"/>\n" : "")
            ."    <link rel=\"search\" type=\"application/opensearchdescription+xml\""
            ." title=\"".$system_vars['textEnglish']." Search\" href=\"".BASE_PATH."osd/\" />\n"
            .(System::has_feature('Articles') ?
                 "    <link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Articles Feed\""
                ." href=\"".BASE_PATH."rss/articles\" />\n"
             :
                ""
             )
            .(System::has_feature('Events') ?
                 "    <link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Events Feed\""
                ." href=\"".BASE_PATH."rss/events\" />\n"
             :
                ""
             )
            .(System::has_feature('Jobs') ?
                 "    <link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Job Postings Feed\""
                ." href=\"".BASE_PATH."rss/jobs\" />\n"
             :
                ""
             )
            .(System::has_feature('News') ?
                 "    <link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS News Feed\""
                ." href=\"".BASE_PATH."rss/news\" />\n"
             :
                ""
             )
            .(System::has_feature('Podcasting') ?
                 "    <link rel=\"alternate\" type=\"application/rss+xml\""
                ." title=\"".$system_vars['textEnglish']." RSS Podcasts Feed\""
                ." href=\"".BASE_PATH."rss/podcasts\" />\n"
            :
                ""
            )
        );

        Output::push(
            'style_include',
            Output::drawCssInclude()
            .($mode!='details' ?
                 "<link rel=\"stylesheet\" type=\"text/css\""
                ." href=\"".BASE_PATH."css/layout/".$page_vars['layoutID']."/".$cs_layout."\" />\n"
             :
                ""
             )
            .(isset($page_vars) && trim($page_vars['theme']['style'])!='' ?
                 "<link rel=\"stylesheet\" type=\"text/css\""
                ." href=\"".BASE_PATH."css/theme/".$page_vars['theme']['ID']."/"
                .dechex(crc32($page_vars['theme']['style']))."\" />"
             :
                ""
             )
        );
        Output::push(
            'style',
            (isset($report_name) && $report_name=='system' ?
                "@media screen { // Only appears for system report\n"
                ."  .scrollbox { height: 140px; media: screen; overflow: auto; border: none; }\n"
                ."}\n"
             :
                ""
            )
            .($isIE ?
                 ".css3, .form_box,"
                ." .shadow { behavior: url(".BASE_PATH."css/pie/".System::get_item_version('css_pie')."); }\n"
            :
                ""
            )
            .".zoom_text { font-size: "
            .(isset($_COOKIE['textsize']) && $_COOKIE['textsize']=='big' ? "120" : "80")
            ."%;}\r\n"
            // Page
            .(isset($page_vars) && trim($page_vars['style'])!='' ?
            "\r\n"
            ."/* [Page Style] */\r\n"
            .$page_vars['style']
            : "")
            // Theme
        );
        Output::push('javascript_top', Output::drawJsInclude(false, $CM_level));
        Output::push(
            'javascript',
            "var \$J, _gaq, _paq, _onload, _onunload, ap_instances, base_url, currency_symbol,\n"
            ."  currentLanguage, defaultDateFormat, defaultTimeFormat, fck_version, option_separator,\n"
            ."  pwd_len_min, rating_blocks, site_title, system_family, valid_prefix;\n"
            ."\$J =               jQuery;\n"
            ."ap_instances =      [];\n"
            ."base_url =          \"".BASE_PATH."\";\n"
            ."cke_posting_fonts = ".(System::has_feature('Postings-allow-fonts-and-sizes') ? 1 : 0).";\n"
            ."currency_symbol =   \"".$system_vars['defaultCurrencySymbol']."\";\n"
            ."currentLanguage =   \""
            .(isset($_SESSION['lang']) ? $_SESSION['lang'] : $system_vars['defaultLanguage'])."\"\n"
            ."defaultDateFormat = \"".addslashes($system_vars['defaultDateFormat'])."\";\n"
            ."defaultTimeFormat = \"".$system_vars['defaultTimeFormat']."\";\n"
            ."option_separator =  \"".OPTION_SEPARATOR."\";\n"
            ."pwd_len_min =       ".PWD_LEN_MIN.";\n"
            ."rating_blocks =     [];\n"
            ."site_title =        \"".$system_vars['textEnglish']."\";\n"
            ."system_family =     \"".System::get_item_version('system_family')."\";\n"
            ."valid_prefix =      \"vp_\"; // Used with controls in Custom_Form class\n"
            .(
                $system_vars['debug_no_internet']!=1 &&
                $system_vars['google_analytics_key']!='' &&
                $mode!='details' &&
                $mode!='report' ?
                    "(function (i,s,o,g,r,a,m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function () {\n"
                    ."(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n"
                    ."m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n"
                    ."})(window,document,'script','//www.google-analytics.com/analytics.js','ga');\n"
                    ."ga('create', '".$system_vars['google_analytics_key']."');\n"
                    ."ga('send', 'pageview');\n"
                : ""
             )
            .(
                $system_vars['debug_no_internet']!=1 &&
                $system_vars['piwik_id'] &&
                $mode!='details' &&
                $mode!='report' ?
                     "var _paq = _paq || [];\n"
                    ."(function () {\n"
                    ."  var u=document.location.protocol+\"//\"+document.location.hostname+\"/piwik/\";\n"
                    ."  _paq.push(['setSiteId', ".$system_vars['piwik_id']."]);\n"
                    ."  _paq.push(['setTrackerUrl', u+'piwik.php']);\n"
                    ."  _paq.push(['trackPageView']);\n"
                    ."  _paq.push(['enableLinkTracking']);\n"
                    ."  var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];\n"
                    ."  g.type='text/javascript'; g.defer=true; g.async=true; g.src=u+'piwik.js';\n"
                    ."  s.parentNode.insertBefore(g,s);\n"
                    ."})();\n"
                :
                    ""
            )
        );
        Output::push(
            'javascript_bottom',
            "addEvent(window,\"load\",_onload);\n"
            ."addEvent(window,\"unload\",_onunload);\n"
        );
        \Nav\Suite::drawJsPreload(true);
        $anchor_ID = System::get_item_version('system_family').'_main_content';
        $js_onload =
        "  externalLinks();\n"
        ."  initialise_tooltips();\n"
        .($isIE ? "  initialise_constraints();\n" : "")
        ."  ToolTips.attachBehavior();\n"
        .($CM_level>0 ? "  CM_load();\n" : "")
        .($showLoading ? "  if (popup_msg==='') { popup_hide_on_loaded(); }\n" : "");
        Output::push('javascript_onload', $js_onload);
        Output::push(
            'javascript_onunload',
            "  ToolTips.out();\n"
            ."  EventCache.flush();\n"
            ."  if (window.GUnload) {window.GUnload();}\n"
        );

        Output::push("head_bottom", "</head>\n");

        Output::push(
            'body_top',
            "<body class=\""
            .(isset($_COOKIE['textsize']) && $_COOKIE['textsize']=='big' ? "zoom_big" : "zoom_small")
            ."\">"
        );
        Output::push(
            'body',
            "<form id='form' enctype='multipart/form-data' method='post' action='./' style='padding:0;margin:0;'>\r\n"
            ."<div id='top' class='margin_none padding_none'>\r\n"
            ."<a href=\"#".$anchor_ID."\" title=\"Main content begins here\" class='fl' style=\"display:none\">"
            ."Skip to Main Content</a>\r\n"

            .draw_form_field('limit', $limit, 'hidden')."\r\n"
            .draw_form_field('offset', $offset, 'hidden')."\r\n"
            .draw_form_field('filterExact', $filterExact, 'hidden')."\r\n"
            .draw_form_field('filterField', $filterField, 'hidden')."\r\n"
            .draw_form_field('filterValue', $filterValue, 'hidden')."\r\n"
            .draw_form_field('anchor', $anchor, 'hidden')."\r\n"
            .draw_form_field('bulk_update', $bulk_update, 'hidden')."\r\n"
            .draw_form_field('command', '', 'hidden')."\r\n"
            .draw_form_field('component_help', $component_help, 'hidden')."\r\n"
            .draw_form_field('DD', $DD, 'hidden')."\r\n"
            .draw_form_field('goto', $page, 'hidden')."\r\n"
            .draw_form_field('mode', $mode, 'hidden')."\r\n"
            .draw_form_field('MM', $MM, 'hidden')."\r\n"
            .draw_form_field('preset_values', $preset_values, 'hidden')."\r\n"
            .draw_form_field('print', $print, 'hidden')."\r\n"
            .draw_form_field('report_name', $report_name, 'hidden')."\r\n"
            .draw_form_field('rnd', dechex(mt_rand(0, mt_getrandmax())), 'hidden')."\r\n"
            .draw_form_field('search_categories', $search_categories, 'hidden')."\r\n"
            .draw_form_field('search_date_end', $search_date_end, 'hidden')."\r\n"
            .draw_form_field('search_date_start', $search_date_start, 'hidden')."\r\n"
            .draw_form_field('search_keywords', $search_keywords, 'hidden')."\r\n"
            .draw_form_field('search_name', $search_name, 'hidden')."\r\n"
            .draw_form_field('search_offset', $search_offset, 'hidden')."\r\n"
            .draw_form_field('search_text', $search_text, 'hidden')."\r\n"
            .draw_form_field('search_type', $search_type, 'hidden')."\r\n"
            .draw_form_field('selectID', $selectID, 'hidden')."\r\n"
            .draw_form_field('selected_section', $selected_section, 'hidden')."\r\n"
            .draw_form_field('sortBy', $sortBy, 'hidden')."\r\n"
            .draw_form_field('source', '', 'hidden')."\r\n"
            .draw_form_field('submode', '', 'hidden')."\r\n"
            .draw_form_field('targetID', '', 'hidden')."\r\n"
            .draw_form_field('targetField', '', 'hidden')."\r\n"
            .draw_form_field('targetFieldID', '', 'hidden')."\r\n"
            .draw_form_field('targetReportID', '', 'hidden')."\r\n"
            .draw_form_field('targetValue', '', 'hidden')."\r\n"
            .draw_form_field('YYYY', $YYYY, 'hidden')."\r\n"
            ."</div>"

            ."\n<!-- Modal Popup mask -->\n"
            ."<div id=\"popupMask\" style=\"display:none;\"></div>\n"
            ."<div id=\"popupContainer\" style=\"display:none;\">\n"
            ."  <div id=\"popupInner\">\n"
            ."    <div id=\"popupTitleBar\">\n"
            ."      <div id=\"popupTitle\"></div>\n"
            ."      <div id=\"popupControls\">"
            ."<img src=\"".BASE_PATH."img/spacer\" class=\"icons\" height=\"10\" width=\"10\""
            ." style='background-position:-2590px 0px;' onclick=\"hidePopWin(null)\" alt='Close' /></div>"
            ."    </div>\n"
            ."    <div id=\"popupBody\"></div>\n"
            ."  </div>\n"
            ."</div>\n"
            .($showLoading ? "<script type='text/javascript'>show_popup_please_wait();</script>\n" : "")

        );
        if (
            Base::module_test('Church') &&
            $system_vars['debug_no_internet']!=1 &&
            $mode!='details' &&
            $mode!='report'
        ) {
            $Obj_CBL = new \Component\BibleLinks;
            Output::push('body', $Obj_CBL->draw());
        }
    }

    public static function prepareResponsiveFoot()
    {
        Output::push(
            'body_bottom',
            "<div id='CM'></div>\n"
        );
        Output::push(
            'html_bottom',
            "<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js\"></script>\n"
            ."</form></body>\n"
            ."</html>"
        );
    }

    public static function render()
    {
        mem('Pre Render');
        $content =
             Output::pull('html_top')
            .Output::pull('head_top')
            .Output::pull('head_include')
            .Output::pull('style_include')
            .(Output::isPresent('style') ?
                "\n<style type=\"text/css\">\n"
               ."/*<![CDATA[*/\r\n"
               .Output::pull('style')
               ."/*]]>*/\n"
               ."</style>\n"
            :
                ""
            )
            .Output::pull('javascript_top')
            .((
                Output::isPresent('javascript') ||
                Output::isPresent('javascript_onload') ||
                Output::isPresent('javascript_onload_bottom') ||
                Output::isPresent('javascript_bottom')
            ) ?
               "\n<script type=\"text/javascript\">\n"
              ."//<![CDATA[\n"
              .Output::pull('javascript')
              .((
                    Output::isPresent('javascript_onload') ||
                    Output::isPresent('javascript_onload_bottom')
               ) ?
                  "\nfunction _onload() {\n"
                 .Output::pull('javascript_onload')
                 .Output::pull('javascript_onload_bottom')
                 ."}\n"
                : ""
               )
              .(Output::isPresent('javascript_onunload') ?
                  "\nfunction _onunload() {\n"
                 .Output::pull('javascript_onunload')
                 ."}\n"
                : ""
               )
              ."\n"
              .Output::pull('javascript_bottom')
              ."//]]>\n"
              ."</script>\n"
            :
                ""
            )
            .Output::pull('head_bottom')
            .Output::pull('body_top')
            .Output::pull('body')
            .Output::pull('body_bottom')
            .Output::pull('html_bottom')
        ;
        //    print $content;return;
        $content = str_replace(
            array(
                '<p><div',
                '</div></p>',
                'target="_blank"',
                "target='_blank'"
            ),
            array(
                '<div',
                '</div>',
                'rel="external"',
                'rel="external"'
            ),
            $content
        );
        if (substr($_SERVER["HTTP_HOST"], 0, 8)!='desktop.' && substr($_SERVER["HTTP_HOST"], 0, 7)!='laptop.') {
        //  @header("Cache-Control: private, no-cache, no-cache=\"Set-Cookie\", proxy-revalidate");
        }
        @header("Pragma: no-cache");
        print preg_replace(
            "#(action='|action=\"|href='|href=\"|src='|src=\"|background='|background=\"|url\()(\./)#",
            "$1".BASE_PATH,
            $content
        );
        mem('Post Render');
        if (DEBUG_MEMORY || get_var('mem')==1) {
            print
            mem()
            ."<script type='text/javascript'>\n"
            ."\$J('#memory_monitor').draggable({ handle:'#memory_monitor_handle',opacity:0.9});\n"
            ."</script>";
        }
    }

    public function usage()
    {
        $sql =
         "SELECT\n"
        ."  SUM(IF(`systemID`=1 AND `name`\n"
        ."    IN(\n"
        ."      '--- Use Default ---',\n"
        ."      '_content',\n"
        ."      '_help',\n"
        ."      '_popup',\n"
        ."      '_print',\n"
        ."      '_report'\n"
        ."    ),1,0)) AS `internal`,\n"
        ."  SUM((SELECT COUNT(*) FROM `system` WHERE `defaultLayoutID`=`layout`.`ID`)) AS `system`,\n"
        ."  SUM((SELECT COUNT(*) FROM `pages` WHERE `layoutID` =`layout`.`ID`)) AS `page`\n"
        ."FROM\n"
        ."  `layout`\n"
        ."WHERE\n"
        ."  `ID` IN(".$this->_get_ID().")";
        return $this->get_record_for_sql($sql);
    }

}
