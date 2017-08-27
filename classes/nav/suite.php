<?php
namespace Nav;
/*
Version History:
  1.0.43 (2017-08-26)
    1) Gave Suite::copy() method fourth parameter 'data' to look like recently modified Record::copy()
*/
class Suite extends \Record
{
    const VERSION = '1.0.43';
    const FIELDS = 'ID, archive, archiveID, deleted, systemID, buttonStyleID, childID_csv, name, parentButtonID, width, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';

    public static $cache_buttons_array = array();
    public static $cache_childID_array = array();

    public function __construct($ID = "")
    {
        parent::__construct("navsuite", $ID);
        $this->_set_assign_type('navsuite');
        $this->_set_object_name('Button Suite');
        $this->_set_message_associated('and contained Buttons have');
        $this->set_edit_params(
            array(
                'report_rename' =>          true,
                'report_rename_label' =>    'new suite name'
            )
        );
    }

    public function ajaxSetSeq()
    {
        $this->_set_ID(get_var('targetID'));
        $this->set_field('childID_csv', get_var('targetValue'));
        $this->clearCache();
        $records = $this->getButtons(true, true, false);
        $out = array();
        foreach ($records as $r) {
            $out[]=array($r['ID'],$r['img_checksum']);
        }
        print json_encode($out);
        die;
    }

    public function clearCache()
    {
        $navsuite_width =   $this->get_field('width');
        if ($navsuite_width==0) {
            $max_width = $this->getMaxWidth();
            $this->set_field('width', $max_width, true);
        }
        $sql =
             "SELECT\n"
            ."  GROUP_CONCAT(`ID`)\n"
            ."FROM\n"
            ."  `navbuttons`\n"
            ."WHERE\n"
            ."  `suiteID` = ".$this->_get_ID();
        $ID_arr =   explode(',', $this->get_field_for_sql($sql));
        $Obj_Nav_Button = new \Nav\Button;
        foreach ($ID_arr as $ID) {
            $Obj_Nav_Button->_set_ID($ID);
            $Obj_Nav_Button->clearCache();
            $Obj_Nav_Button->makeImage();
        }
        if ($navsuite_width==0) {
            $this->set_field('width', 0, true);
        }
    }

    public function copy($new_name = false, $new_systemID = false, $new_date = true, $data = false)
    {
        $newID =    parent::copy($new_name, $new_systemID, $new_date, $data);
        $buttons =  $this->getButtons(true);
        $Obj =      new \Nav\button;
        $mapping = array();
        foreach ($buttons as $data) {
            $oldButtonID = $data['ID'];
            unset(
                $data['ID'],
                $data['archive'],
                $data['archiveID'],
                $data['childID']
            );
            if ($new_date) {
                unset(
                    $data['history_created_by'],
                    $data['history_created_date'],
                    $data['history_created_IP'],
                    $data['history_modified_by'],
                    $data['history_modified_date'],
                    $data['history_modified_IP']
                );
            }
            $data['suiteID'] = $newID;
            if ($new_systemID) {
                $data['systemID'] = $new_systemID;
            }
            $newButtonID = $Obj->insert($data);
            $mapping[$oldButtonID] = $newButtonID;
            $Obj->_set_ID($oldButtonID);
            $Obj->copy_group_assign($newButtonID);
        }
        $Obj = new \Nav\Suite($newID);
        $newIds =   array();
        $childIDs = explode(',', $Obj->get_field('childID_csv'));
        foreach ($childIDs as $childID) {
            if (isset($mapping[$childID])) {
                $newIDs[] = $mapping[$childID];
            }
        }
        $childIDs = implode(',', $newIDs);
        $Obj->set_field('childID_csv', $childIDs, true, false);
        return $newID;
    }

    public function delete()
    {
        $sql =
         "SELECT\n"
        ."  `ID`\n"
        ."FROM\n"
        ."  `navbuttons`\n"
        ."WHERE\n"
        ." `suiteID` IN (".$this->_get_ID().")";
        if ($records = $this->get_records_for_sql($sql)) {
            foreach ($records as $record) {
                $Obj = new \Nav\Button($record['ID']);
                $Obj->delete();
            }
        }
        return parent::delete();
    }

    public function exportSql($targetID, $show_fields)
    {
        $header =       "Selected ".$this->_get_object_name().$this->plural($targetID)." with Buttons";
        $extra_delete =
             "DELETE FROM `navbuttons`             WHERE `suiteID` IN (".$targetID.");\n"
            ."DELETE FROM `group_assign`           WHERE `assign_type` = 'navbuttons' AND"
            ." `assignID` IN (SELECT `ID` FROM `navbuttons` WHERE `suiteID` IN(".$targetID."));\n";
        $Obj = new \Backup;
        $extra_select =
            $Obj->db_export_sql_query(
                "`navbuttons`            ",
                "SELECT * FROM `navbuttons` WHERE `suiteID` IN(".$targetID.") ORDER BY `position`",
                $show_fields
            )
            .$Obj->db_export_sql_query(
                "`group_assign`          ",
                "SELECT * FROM `group_assign` WHERE `assign_type` = 'navbuttons' AND"
                ." `assignID` IN(SELECT `ID` FROM `navbuttons` WHERE `suiteID` IN(".$targetID."));",
                $show_fields
            )."\n";
        return parent::sqlExport($targetID, $show_fields, $header, '', $extra_delete, $extra_select);
    }

    public static function drawJsPreload($responsive=false)
    {
        global $print, $page_vars;
        if ($print==1) {
            return;
        }
        if (
            ($page_vars['navsuite1ID']!='' && $page_vars['navsuite1ID']!='1') ||
            ($page_vars['navsuite2ID']!='' && $page_vars['navsuite2ID']!='1') ||
            ($page_vars['navsuite3ID']!='' && $page_vars['navsuite3ID']!='1')
        ) {
            \Output::push('javascript_onload', static::getJsPreload($responsive));
        }

    }

    public function getButtons($all = false, $no_cache = false, $legacyMode = false)
    {
        $key = $this->_get_ID()."_".($all?1:0);
        if (isset(static::$cache_buttons_array[$key]) && !$no_cache) {
            return static::$cache_buttons_array[$key];
        }
      // Need to get all fields as this is used for \Nav\Suite::copy()
        $sql =
             "SELECT\n"
            ."  `nb`.*,\n"
            ."  (SELECT\n"
            ."    COALESCE(GROUP_CONCAT(`ns`.`ID`),0)\n"
            ."  FROM\n"
            ."    `navsuite` `ns`\n"
            ."  WHERE\n"
            ."    `nb`.`ID` = `ns`.`parentButtonID`\n"
            ."  ) `childID`\n"
            ."FROM\n"
            ."  `navbuttons` `nb`\n"
            ."WHERE\n"
            ."  `suiteID` IN(".$this->_get_ID().")"
            .($legacyMode ? "\nORDER BY `position`" : "");
        $records = $this->get_records_for_sql($sql);
        $buttons = array();
        $childID_csv =  $this->get_field('childID_csv');
        $csv_arr =  explode(',', $childID_csv);
        if ($legacyMode) {
            $buttons = $records;
        } else {
            foreach ($csv_arr as $ID) {
                foreach ($records as &$item) {
                    if ($item['ID']==$ID) {
                        $buttons[] = $item;
                    }
                }
            }
            foreach ($records as &$item) {
                if (!in_array($item['ID'], $csv_arr)) {
                    $buttons[] = $item;
                }
            }
        }
        static::$cache_buttons_array[$key] = $buttons;
        if (!$buttons) {
            return false;
        }
        if ($all) {
            return $buttons;
        }
        foreach ($buttons as &$button) {
            $button['visible'] = $this->is_visible($button);
        }
        static::$cache_buttons_array[$key] = $buttons;
        return $buttons;
    }

    public static function getJsPreload($responsive=false)
    {
        global $page_vars, $print;
        if ($print=='1' || $print=='2') {
            return "";
        }
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        $isSYSADMIN =       get_person_permission("SYSADMIN");
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER");
        $isSYSEDITOR =      get_person_permission("SYSEDITOR");
        $isAdmin =          ($isSYSEDITOR||$isSYSAPPROVER||$isSYSADMIN||$isMASTERADMIN ? 1 : 0);
        $out = "";
        for ($i=1; $i<=3; $i++) {
            $suiteID =  $page_vars['navsuite'.$i.'ID'];
            if ($suiteID!=1 && $suiteID!="") {
                $Obj = new \Nav\Suite($suiteID);
                if ($Obj->exists()) {
                    $out.=
                        "  nav_setup(".$i.",".($isAdmin ? 1 : 0).",'".BASE_PATH.trim($page_vars['path'], '/')."',".($responsive ? '1' : '0').");\n";
                }
            }
        }
        return $out;
    }

    public function getMaxWidth()
    {
        $sql =
             "SELECT\n"
            ."    `navsuite`.`systemID`,\n"
            ."    `navstyle`.`orientation`,\n"
            ."    `navstyle`.`templateFile`,\n"
            ."    `navstyle`.`text1_font_face`,\n"
            ."    `navstyle`.`text1_font_size`,\n"
            ."    `navstyle`.`text2_font_face`,\n"
            ."    `navstyle`.`text2_font_size`,\n"
            ."    `navsuite`.`width` `suite_width`,\n"
            ."    (SELECT\n"
            ."        MAX(`width`)\n"
            ."    FROM\n"
            ."        `navbuttons`\n"
            ."    WHERE\n"
            ."        `navbuttons`.`suiteID`=`navsuite`.`ID`\n"
            ."    ) `max_fixed_width`,\n"
            ."    (SELECT\n"
            ."        GROUP_CONCAT(\n"
            ."            IF(\n"
            ."                `navstyle`.`text1_uppercase`,\n"
            ."                UCASE(`navbuttons`.`text1`),\n"
            ."                `navbuttons`.`text1`\n"
            ."            )\n"
            ."            SEPARATOR '\\n'\n"
            ."        )\n"
            ."    FROM\n"
            ."        `navbuttons`\n"
            ."    WHERE\n"
            ."        `navbuttons`.`suiteID` = `navsuite`.`ID` AND\n"
            ."        `navbuttons`.`width` = 0\n"
            ."    ) `text1`,\n"
            ."    (SELECT\n"
            ."        GROUP_CONCAT(\n"
            ."            IF(\n"
            ."                `navstyle`.`text2_uppercase`,\n"
            ."                UCASE(`navbuttons`.`text2`),\n"
            ."                `navbuttons`.`text2`\n"
            ."            )\n"
            ."            SEPARATOR '\\n'\n"
            ."        )\n"
            ."    FROM\n"
            ."        `navbuttons`\n"
            ."    WHERE\n"
            ."        `navbuttons`.`suiteID` = `navsuite`.`ID` AND\n"
            ."        `navbuttons`.`width` = 0\n"
            ."    ) `text2`\n"
            ."FROM\n"
            ."  `navsuite`\n"
            ."INNER JOIN `navstyle` ON\n"
            ."  `navstyle`.`ID` = `navsuite`.`buttonStyleID`\n"
            ."WHERE\n"
            ."  `navsuite`.`ID` IN(".$this->_get_ID().")";
  //    z($sql);
        $record = $this->get_record_for_sql($sql);
        if ($record['orientation']=='---') {
            return $record['suite_width'];
        }
        $Obj_NBI =    new \Nav\ButtonImage;
        $Obj_NBI->getTextSize(
            $record['text1_font_face'],
            $record['text1_font_size'],
            $record['text1'],
            $text1_width,
            $text1_height
        );
        $Obj_NBI->getTextSize(
            $record['text2_font_face'],
            $record['text2_font_size'],
            $record['text2'],
            $text2_width,
            $text2_height
        );
        $text_width = max($text1_width, $text2_width);
        $Obj_NBI->getButtonBaseSize($record, $template_width, $template_height);
        $auto_width =       $text_width+$template_width;
        return ($auto_width > $record['max_fixed_width'] ? $auto_width : $record['max_fixed_width']);
    }

    public function getNextSeq()
    {
        $sql =
         "SELECT\n"
        ."  MAX(`position`)+1\n"
        ."FROM\n"
        ."  `navbuttons`\n"
        ."WHERE\n"
        ."  `suiteID` = ".$this->_get_ID();
        $seq = $this->get_field_for_sql($sql);
        return (!$seq ? 1 : $seq);
    }

    public static function getSelectorSql($include_default = false)
    {
        if (get_person_permission("MASTERADMIN")) {
            return
                 "SELECT\n"
                ."  1 `value`,\n"
                ."  '(None)' `text`,\n"
                ."  'd0d0d0' `color_background`,\n"
                ."  1 `seq`\n"
                .($include_default ?
                 "UNION SELECT\n"
                ."  0,\n"
                ."  '--- Use Default ---',\n"
                ."  'f0f0f0',\n"
                ."  0\n"
                 : "")
                ."UNION SELECT\n"
                ."  `navsuite`.`ID`,\n"
                ."  CONCAT(\n"
                ."    IF(`navsuite`.`ID`=1,\n"
                ."      '  ',\n"
                ."      IF(`navsuite`.`systemID` = 1,\n"
                ."        '* ',\n"
                ."        CONCAT(UPPER(`system`.`textEnglish`),' | ')\n"
                ."      )\n"
                ."    ),\n"
                ."    `navsuite`.`name`\n"
                ."  ),\n"
                ."  IF(`navsuite`.`ID`=1,\n"
                ."    'd0d0d0',\n"
                ."    IF(`navsuite`.`systemID`=1,\n"
                ."      'e0e0ff',\n"
                ."      IF(`navsuite`.`systemID`=SYS_ID,\n"
                ."        'c0ffc0',\n"
                ."        'ffe0e0'\n"
                ."      )\n"
                ."    )\n"
                ."  ),\n"
                ."  IF(`navsuite`.`systemID`=1,2,3)\n"
                ."FROM\n"
                ."  `navsuite`\n"
                ."INNER JOIN `system` ON\n"
                ."  `navsuite`.`systemID` = `system`.`ID`\n"
                ."WHERE\n"
                ."  `navsuite`.`parentButtonID` = 1\n"
                ."ORDER BY\n"
                ."  `seq`,`text`";
        }
        return
             "SELECT\n"
            ."  1 `value`,\n"
            ."  '(None)' `text`,\n"
            ."  'd0d0d0' `color_background`,\n"
            ."  1 `seq`\n"
            .($include_default ?
               "UNION SELECT\n"
              ."  0,\n"
              ."  '--- Use Default ---',\n"
              ."  'f0f0f0',\n"
              ."  0\n"
             : "")
            ."UNION SELECT\n"
            ."  `navsuite`.`ID`,\n"
            ."  CONCAT(\n"
            ."    IF(`navsuite`.`ID`=1,\n"
            ."      '',\n"
            ."      IF(`navsuite`.`systemID` = 1,\n"
            ."        '* ',\n"
            ."        ''\n"
            ."      )\n"
            ."    ),\n"
            ."    `navsuite`.`name`\n"
            ."  ),\n"
            ."  IF(`navsuite`.`ID`=1,\n"
            ."    'd0d0d0',\n"
            ."    IF(`navsuite`.`systemID`=1,\n"
            ."      'e0e0ff',\n"
            ."      'c0ffc0'\n"
            ."    )\n"
            ."  ),\n"
            ."  IF(`navsuite`.`systemID`=1,2,3)\n"
            ."FROM\n"
            ."  `navsuite`\n"
            ."INNER JOIN `system` ON\n"
            ."  `navsuite`.`systemID` = `system`.`ID`\n"
            ."WHERE\n"
            ."  `systemID` IN(1,SYS_ID) AND\n"
            ."  `navsuite`.`parentButtonID` = 1\n"
            ."ORDER BY\n"
            ."  `seq`,`text`";
    }

    public function handleReportCopy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::tryCopy($newID, $msg, $msg_tooltip, $name);
    }

    public function hasLoop()
    {
        return in_array('loop', $this->parents());
    }

    public function onDelete()
    {
        global $action_parameters;
        $ID_arr = explode(",", $action_parameters['triggerID']);
        foreach ($ID_arr as $ID) {
            $this->_set_ID($ID);
            $this->clearCache();
        }
    }

    public function onPreUpdate()
    {
        global $action_parameters, $msg;
        if (
            $action_parameters['data']['bulk_update'] &&
            !isset($action_parameters['data']['parentButtonID_apply'])
        ) {
            return; // The loop check only runs when parentButtonID changes occur
        }
        $ID_arr = explode(",", $action_parameters['triggerID']);
        foreach ($ID_arr as $ID) {
            $this->_set_ID($ID);
            $OldParentButtonID = $this->get_field('parentButtonID');
            $this->set_field('parentButtonID', $_POST['parentButtonID'], true, false);
            if ($this->hasLoop()) {
                $msg =
                     "The Parent Button you tried to choose would result in an infinate loop.\n"
                    ."The old mapping has been restored.";
                $this->set_field('parentButtonID', $OldParentButtonID, true, false);
                $_POST['parentButtonID'] = $OldParentButtonID;
            }
        }
    }

    public function onUpdate()
    {
        global $action_parameters;
        $ID_arr = explode(",", $action_parameters['triggerID']);
        foreach ($ID_arr as $ID) {
            $this->_set_ID($ID);
            $this->clearCache();
        }
    }

    public function parent()
    {
        $sql =
             "SELECT\n"
            ."  `parentButtonID`\n"
            ."FROM\n"
            ."  `navsuite`\n"
            ."WHERE\n"
            ."  `ID` = \"".$this->_get_ID()."\"";
        return $this->get_field_for_sql($sql);
    }

    public function parents()
    {
        $out = array();
        $Obj = new \Nav\Suite($this->_get_ID());
        $parent = $Obj->parent();
        while ($parent!=1) {
            $out[] = $parent;
            $Obj = new \Nav\Button($parent);
            $parent = $Obj->parent();
            if (in_array($parent, $out)) {
                $out[] = 'loop';
                break;
            }
        }
        return $out;
    }

    public function getTree($full = false, $navsuiteID = '', $depth = 0, $SDMenu_mode = false)
    {
        global $page,$page_vars;
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        $isSYSADMIN =        get_person_permission("SYSADMIN");
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER");
        $isSYSEDITOR =        get_person_permission("SYSEDITOR");
        $isAdmin = ($isSYSEDITOR||$isSYSAPPROVER||$isSYSADMIN);
        $depth++;
        if ($navsuiteID=='') {
            $navsuiteID = $this->_get_ID();
        }
        $out =                array();
        $links =            array();
        $Obj_Nav_Suite =    new \Nav\Suite($navsuiteID);
        $buttons =          $Obj_Nav_Suite->getButtons(false, false, true);
        $visible =  false;
        $pos_min =  false;
        $pos_max =  false;
        foreach ($buttons as $button) {
            if ($button['visible']) {
                $visible=true;
                if ($pos_min===false || $pos_min > $button['position']) {
                    $pos_min=$button['position'];
                }
                if ($pos_max===false || $pos_max < $button['position']) {
                    $pos_max=$button['position'];
                }
            }
        }
        if (!$visible) {
            return "";  // All buttons are invisible so stop drawing
        }
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
            ."  `p_nst`.`subnavOffsetX`,\n"
            ."  `p_nst`.`subnavOffsetY`,\n"
            ."  `p_nst`.`orientation` AS `p_orientation`,\n"
            ."  `p_nb`.`img_width` AS `p_width`,\n"
            ."  `p_nb`.`img_height` AS `p_height`\n"
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
            ."  `ns`.`ID` = ".$navsuiteID;
  //    z($sql);
        $navsuite_record = $this->get_record_for_sql($sql);
        if ($navsuite_record===false) {
            return "";
        }
        if ($SDMenu_mode && $depth>2) {
            if ($isAdmin) {
                return
                    "  <li>\n<a title=\"ERROR - the previous entry has a submenu attached."
                   ." This type of menu doesn't support that.\">[Error above ^]</a>\n</li>";
            }
            return "";
        }
        $bStyleID =     $navsuite_record['buttonStyleID'];
        $bStyleName =   $navsuite_record['navstyle_name'];
        if (!$buttons===false) {
    //      y($buttons);die;
            foreach ($buttons as $button) {
                $Obj = new \Nav\Button($button['ID']);
                if ($button['visible']) {
                    $bID =          $button['ID'];
                    $bSystemID =    $button['systemID'];
                    $bText =        $button['text1'];
                    $bTextSafe =    str_replace(array("'","\\n"), array("&rsquo;","\n"), sanitize('html', $bText));
                    $bError =       '';
                    $bPos =         $button['position'];
                    $bSuiteID =     $button['suiteID'];
                    $sNameSafe =    str_replace("'", "&rsquo;", sanitize('html', $navsuite_record['name']));
                    $bSubnavStyleID =   $navsuite_record['subnavStyleID'];
                    $bPopup =       $button['popup'];
                    $childID =      $button['childID'];
                    $bURL =         htmlentities(html_entity_decode($button['URL']));
                    if (substr($bURL, 0, 8)=='./?page=') {
                        $bURL = BASE_PATH.substr($bURL, 8);
                    }
                    if ($SDMenu_mode && $bURL && $depth==1) {
                        $bURL = "";
                        if (($isAdmin && $bSystemID==SYS_ID) || $isMASTERADMIN) {
                            $bText = "[Error] ".$bText;
                            $bError =
                                "ERROR - This item is not linkable.\n"
                               ."Please right click to edit this button and remove the URL.";
                        }
                    }
                    if ($SDMenu_mode && !$bURL && $depth>1) {
                        $bURL = "./";
                        if (($isAdmin && $bSystemID==SYS_ID) || $isMASTERADMIN) {
                            $bText = "[Error] ".$bText;
                            $bError =
                                "ERROR - This item MUST have a URL.\n"
                               ."Please right click to edit this button and provide a URL.";
                        }
                    }
                    $CM = (($isAdmin && $bSystemID==SYS_ID) || $isMASTERADMIN ?
                         " onmouseout=\"_CM.type=''\""
                        ." onmouseover=\""
                        ."CM_SDMenu_Over("
                        .$bID.","
                        ."'".str_replace("\n", " ", $bTextSafe)."',"
                        .($childID ? 1 : 0).","
                        .($childID ? '-1' : ($bSubnavStyleID==1 ? 0 : $bSubnavStyleID)).","
                        .$bSuiteID.","
                        ."'".$sNameSafe."',"
                        .$bStyleID.","
                        ."'".sanitize('html', $bStyleName)."'"
                        .");\""
                     :
                         ""
                    );
                    $links[] =
                         str_repeat("  ", $depth)
                        ."<li>"
                        .($bURL ?
                             "<a id=\"btn_".$bID."\""
                            ." href=\"".$bURL."\""
                            .($bPopup ? " rel='external'" : "")
                        :
                            "<span id=\"btn_".$bID."\""
                        )
                        .$CM
                        ." title=\"".($bError ? $bError : $bTextSafe)."\""
                        .">"
                        .$bText
                        .($bPopup ? \HTML::draw_icon('external') : "")
                        .($bURL ?  "</a>" : "</span>")
                        .($childID ?
                             "\n".$this->getTree(false, $childID, $depth, $SDMenu_mode).str_repeat("  ", $depth)
                            ."</li>\n"
                         :
                            "</li>\n"
                        );
                }
            }
        }
        return
             str_repeat("  ", $depth-1)."<ul>\n"
            .implode("", $links)
            .str_repeat("  ", $depth-1)."</ul>\n";
    }
}
