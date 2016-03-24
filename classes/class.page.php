<?php
/*
Version History:
  1.0.128 (2016-03-24)
    1) Removed a number of methods out into PageDraw class
*/
class Page extends Displayable_Item
{
    const VERSION = '1.0.128';
    const FIELDS = 'ID, archive, archiveID, deleted, systemID, memberID, group_assign_csv, page, path, path_extender, comments_allow, comments_count, componentID_post, componentID_pre, component_parameters, content, content_text, keywords, include_title_heading, layoutID, locked, meta_description, meta_keywords, navsuite1ID, navsuite2ID, navsuite3ID, parentID, password, permPUBLIC, permSYSLOGON, permSYSMEMBER, ratings_allow, style, subtitle, themeID, title, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';

    public static $javascript =  array();
    public static $style = "";
    public static $css_colors =  array();
    public static $css_color_idx = 1;

    public function __construct($ID = "")
    {
        parent::__construct("pages", $ID);
        $this->_set_object_name('Page');
        $this->_set_name_field('page');
        $this->_set_assign_type('page');
        $this->_set_type('page');
        $this->_set_has_activity(true);
        $this->_set_has_groups(true);
        $this->_set_has_keywords(true);
        $this->_set_message_associated('and associated keyword and group assignment records have');
        $this->_set_path_prefix('page');  // Used to prefix items with IDs in path or to activate search
        $this->_set_search_type('page');  // Used to search for items in search system
        $this->set_edit_params(
            array(
                'report' =>                     'pages',
                'report_rename' =>              true,
                'report_rename_label' =>        'new page name',
                'icon_delete' =>                '[ICON]16 16 8216 Delete this Page[/ICON]',
                'icon_delete_disabled' =>       '[ICON]16 16 8232 (Delete this Page)[/ICON]',
                'icon_edit' =>                  '[ICON]18 18 279 Edit this Page[/ICON]',
                'icon_edit_disabled' =>         '[ICON]18 18 2347 (Edit this Page)[/ICON]',
                'icon_edit_popup' =>            '[ICON]18 18 297 Edit this Page in a popup window[/ICON]',
                'icon_edit_popup_disabled' =>   '[ICON]18 18 8198 (Edit this Page in a popup window)[/ICON]'
            )
        );
    }

    public function count_named($name, $systemID = "")
    {
        $sql =
             "SELECT\n"
            ."  count(*) AS `count`\n"
            ."FROM\n"
            ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
            ."WHERE\n"
            .($systemID!="" ? "  `systemID` = ".$systemID." AND\n" : "" )
            ."  `page`"
            .($name === html_entity_decode($name) ?
             "=\"".$name."\""
             :
             " IN (\"".$name."\",\"".html_entity_decode($name)."\")"
            );
  //    z($sql);
        return $this->get_field_for_sql($sql);
    }

    public function draw_search_results($result)
    {
        $out = "";
        $offset =       $result['offset'];
        $found =        $result['count'];
        $limit =        $result['limit'];
        $retrieved =    count($result['results']);
        $search_text =  $result['search_text'];
        if ($found) {
            $out.=
             $this->draw_search_results_paging_nav($result, $search_text)
            ."<table cellpadding='2' cellspacing='0' border='1' style='width:100%' class='table_border'>\n"
            ."  <tr class='table_header'>\n"
            .(isset($result['results'][0]['textEnglish']) ?
             "    <th class='table_border txt_l'>Site</th>\n"
             : "")
            ."    <th class='table_border txt_l'>Title</th>\n"
            ."    <th class='table_border txt_l'>Summary</th>\n"
            ."    <th class='table_border'>Date</th>\n"
            ."  </tr>\n";
            foreach ($result['results'] as $row) {
                $title =    context(Language::convert_tags($row['title']), $search_text, 30);
                $text =
                str_replace(
                    array('&amp;hellip;'),
                    array('&hellip;'),
                    context(
                        htmlentities(
                            html_entity_decode(
                                Language::convert_tags($row['content_text'])
                            )
                        ),
                        $search_text,
                        60
                    )
                );
                $date =     $row['date'];
                $local =    $row['systemID']==SYS_ID;
                $out.=
                "  <tr class='table_data'>\n"
                .(isset($row['textEnglish']) ?
                "    <td class='table_border va_t'>".$row['textEnglish']."</th>\n"
                : "")
                ."    <td class='table_border va_t'"
                .($row['title']!=strip_tags($title) ? " title=\"".$row['title']."\"" : "")
                .">\n"
                ."<a href=\"".($local ? BASE_PATH : trim($row['systemURL'], '/').'/').trim($row['path'], '/')."\""
                .($local ? "" : " rel='external'")
                .">"
                ."<b>".($title!="" ? $title : "(Untitled)")."</b></a></td>\n"
                ."    <td class='table_border va_t'>".$text."</td>\n"
                ."    <td class='table_border va_t txt_r nowrap'>".format_date($date)."</td>\n"
                ."  </tr>\n";
            }
            $out.=
            "</table>\n<br />";
        }

        return $out;
    }

    public function export_sql($targetID, $show_fields)
    {
        return $this->sql_export($targetID, $show_fields);
    }

    public static function get_css_idx($color, $bgcolor)
    {
        foreach (static::$css_colors as $idx => $style) {
            if (strToUpper($color)==$style['color'] && strToUpper($bgcolor)==$style['bgcolor']) {
                return $idx;
            }
        }
        $idx = static::$css_color_idx++;
        static::$css_colors[$idx] = array(
            'color' =>      strToUpper($color),
            'bgcolor' =>    strToUpper($bgcolor)
        );
        Output::push(
            "style",
            ($idx==1 ? "\n/* [Option colours] */\n" : "")
            .".color_".$idx." {"
            .($color!==false ? " color: #".strToUpper($color).";" : "")
            .($bgcolor!==false ? " background-color: #".strToUpper($bgcolor).";" : "")
            ."}\n"
        );
        return $idx;
    }

    public function get_field($arg1, $arg2 = false)
    {
        if ($arg2===false) {
            return parent::get_field($arg1);
        }
        deprecated();
        $sql =
         "SELECT\n"
        ."  `".$arg2."`\n"
        ."FROM\n"
        ."  `pages`\n"
        ."WHERE\n"
        ."  (`path` = \"//".trim($arg1, '/')."/\" OR\n"
        ."   `page` = \"".$arg1."\") AND\n"
        ."  (`systemID` = 1 OR `systemID` = ".SYS_ID.")\n"
        ."ORDER BY\n"
        ."  `systemID` DESC,\n"
        ."  `path` = \"//".trim($arg1, '/')."/\"\n"
        ."LIMIT\n"
        ."  0,1";

        return $this->get_field_for_sql($sql);
    }

    public function get_unique_name($name, $systemID = SYS_ID)
    {
        $name=str_replace(array(' ','/'), array('-'), $name);
        $sql =
         "SELECT\n"
        ."  `page`\n"
        ."FROM\n"
        ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
        ."WHERE\n"
        ."  `systemID` = ".$systemID." AND\n"
        ."  (`page` = \"".$name."\" OR `page` REGEXP \"^".$name."-[0-9]+$\")\n"
        ."ORDER BY\n"
        ."  `page`";
        $records = $this->get_records_for_sql($sql);
        if (!count($records)) {
            return $name;
        }
        $max = 1;
        foreach ($records as $record) {
            $page = $record['page'];
            $page_arr = explode('-', $page);
            if (count($page_arr)>1 && is_numeric($page_arr[count($page_arr)-1])) {
                $idx = (int) $page_arr[count($page_arr)-1];
                if ($idx>$max) {
                    $max = $idx;
                }
            }
        }
  //    y($max);y($records);
        return $name."-".(1+$max);
    }

    public function get_ID_by_path($path, $systemID = false)
    {
        $sql =
         "SELECT\n"
        ."  `ID`\n"
        ."FROM\n"
        ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
        ."WHERE\n"
        .($systemID ?
          "  `systemID` IN(".$systemID.") AND\n"
        : "  `systemID` IN(1," . SYS_ID . ") AND\n"
        )
        ."  `path` = \"".$path."\"\n"
        ."ORDER BY\n"
        ."  `systemID` = ".SYS_ID." DESC\n"
        ."LIMIT 0,1";
  //    z($sql);
        return $this->get_field_for_sql($sql);
    }

    public function get_page_by_path($name, $systemID = SYS_ID)
    {
        $results = array();
        $sql =
             "SELECT\n"
            ."  *,\n"
            ."  '' AS `path_extension`,\n"
            ."  IF(`path`=\"//".$name."/\",1,0) AS `exact`\n"
            ."FROM\n"
            ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `systemID` IN(1,".$systemID.") AND\n"
            ."  (`path` = \"//".$name."/\" OR `page` = \"".$name."\")\n"
            ."ORDER BY\n"
            ."  `path` = \"//".$name."/\" DESC,\n"
            ."  `systemID` = ".$systemID." DESC";
        $records =  $this->get_records_for_sql($sql);
        foreach ($records as $record) {
            $results[] = $record;
        }
        if (count($results) && $results[0]['exact']) {
            return $results[0];
        }
        switch (count($results)) {
            case 0:
                return $this->get_page_by_extended_path($name, $systemID);
            break;
            case 1:
                return $results[0];
            break;
            default:
                return $this->get_page_disambiguation($name, $systemID);
            break;
        }
    }

    public function get_page_disambiguation($name, $systemID = SYS_ID)
    {
        $results = array();
        $sql =
             "SELECT\n"
            ."  *,\n"
            ."  '' as `path_extension`\n"
            ."FROM\n"
            ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `systemID`=".$systemID." AND\n"
            ."  `page` = \"".$name."\"\n"
            ."ORDER BY\n"
            ."  `path`";
      //    z($sql);
        $records =  $this->get_records_for_sql($sql);
        foreach ($records as $record) {
            if ($this->is_visible($record)) {
                $results[] = $record;
            }
        }
        $count =    count($results);
        switch ($count) {
            case 0:
                return false;
            break;
            case 1:
                return $results[0];
            break;
            default:
                $disambiguation =
                     "<div class='disambiguation css3'>\n"
                    ."<h1>".title_case_string($name)." (disambiguation)</h1>\n"
                    ."<h2>Please choose one of the  "
                    .($count==2 ? "two" : "")
                    .($count==3 ? "three" : "")
                    .($count>3 ? "several" : "")
                    ." possible matches for the requested resource:</h2>\n"
                    ."<table summary='Page Choices' class='css3'>\n";
                $n = 1;
                foreach ($results as $r) {
                    $disambiguation.=
                         "  <tr>\n"
                        ."    <td>".$n++.".&nbsp;</td>\n"
                        ."    <th>".$r['title']."</th>\n"
                        ."    <td>"
                        ."<a title=\"Click to choose this option...\""
                        ." href=\"".BASE_PATH
                        .trim($r['path'], '/')
                        ."\">"
                        .BASE_PATH.trim($r['path'], '/').""
                        ."</a></td>\n"
                        ."  </tr>\n";
                }
                $disambiguation.=
                     "</table>\n"
                    ."</div>";
                $result =                           $results[0];
                $result['title'] =                  title_case_string($name)." (disambiguation)";
                $result['content'] =                $disambiguation;
                $result['content'] =                $disambiguation;
                $result['content_text'] =           '';
                $result['path'] =                   "//".$name;
                $result['layoutID'] =               1;
                $result['include_title_heading'] =  0;
      //        y($result);
                return $result;
            break;
        }
    }

    public function get_page_by_extended_path($name, $systemID = SYS_ID)
    {
        $path_arr = explode('/', $name);
        $slashes = count($path_arr)-1;
        for ($i=$slashes; $i--; $i>0) {
            $name_arr = array();
            for ($j=0; $j<=$i; $j++) {
                $name_arr[] = $path_arr[$j];
            }
            $test_path = implode('/', $name_arr);
            $sql =
            "SELECT\n"
            ."  *,\n"
            ."  \"".substr($name, strlen($test_path)+1)."\" as `path_extension`\n"
            ."FROM\n"
            ."  `".$this->_get_db_name()."`.`".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `systemID` IN(1,".$systemID.") AND\n"
            ."  `path_extender` = 1 AND\n"
            ."  `path` = \"//".$test_path."/\"\n"
            ."ORDER BY\n"
            ."  `systemID` = ".$systemID." DESC\n"
            ."LIMIT 0,1";
    //      z($sql);
            if ($result = $this->get_record_for_sql($sql)) {
                $result['path'] = '//'.$test_path.'/';

                return $result;
            }
        }

        return false;
    }

    public function get_resolved_path($path = '')
    {
        $path = trim($path, "/");
        if ($path=='') {
            return '0|'.$path;
        }
        $ID = 0;
        $path_test = "";
        $path_bits = explode('/', $path);
        for ($i=count($path_bits); $i>0; $i--) {
            $path_arr = array();
            for ($j=0; $j<$i; $j++) {
                $path_arr[] = $path_bits[$j];
            }
            $path_test = "//".implode('/', $path_arr).'/';

            $sql =
            "SELECT\n"
            ."  `ID`\n"
            ."FROM\n"
            ."  `".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `systemID` = ".SYS_ID." AND\n"
            ."  `path` = \"".$path_test."\"";
    //      z($sql);
            if ($record =   $this->get_record_for_sql($sql)) {
                $ID =  $record['ID'];
                break;
            }
        }
        if ($ID==0) {
            return '0|'.$path;
        }
        $page = substr($path, strlen($path_test)-2);

        return $ID."|".$page;
    }

    public function get_parent_path_for_page_vars($page_vars)
    {
        if ($page_vars['parentID']==0) {
            return '//';
        }
        $parent_path_bits = explode('/', trim($page_vars['path'], '/'));
        array_pop($parent_path_bits);

        return '//'.implode('/', $parent_path_bits).'/';
    }

    public function get_path($ID, $path = '')
    {
        if ($ID==0) {
          // 'No Parent' specified - get out now
            return $path;
        }
        $sql =
         "SELECT\n"
        ."  `page`,\n"
        ."  `parentID`\n"
        ."FROM\n"
        ."  `".$this->_get_table_name()."`\n"
        ."WHERE\n"
        ."  `ID` = ".$ID;
        if (!$record =   $this->get_record_for_sql($sql)) {
          // Parent doesn't exist - move up tree
            return $path;
        }
      // Okay, continue...
        $page =     $record['page'];
        $parentID = $record['parentID'];
        $path =  $page."/".$path;  // Add new piece to front of path

        return $this->get_path($parentID, $path);
    }

    public function get_search_results($args)
    {
        /* This routine reduces load to server by only returning content_text for visible records
        The first 'pass' just gets the count and sets the start and end points for the real retrieval
        The second gets the actual content data with start and end points modified according to the
        result of the first 'pass' to determine visibility for the current user
        */
        $search_categories =    isset($args['search_categories']) ? $args['search_categories'] : "";
        $search_date_end =      isset($args['search_date_end']) ? $args['search_date_end'] : "";
        $search_date_start =    isset($args['search_date_start']) ? $args['search_date_start'] : "";
        $search_keywordIDs =    isset($args['search_keywordIDs']) ? $args['search_keywordIDs'] : "";
        $search_memberID =      isset($args['search_memberID']) ? $args['search_memberID'] : 0;
        $search_name =          isset($args['search_name']) ? $args['search_name'] : "";
        $search_offset =        isset($args['search_offset']) ? $args['search_offset'] : 0;
        $search_sites =         isset($args['search_sites']) ? $args['search_sites'] : "";
        $search_text =          isset($args['search_text']) ? $args['search_text'] : "";
        $search_type =          isset($args['search_type']) ? $args['search_type'] : "*";
        $systems_csv =          isset($args['systems_csv']) ? $args['systems_csv'] : "";
        $systemIDs_csv =        isset($args['systemIDs_csv']) ? $args['systemIDs_csv'] : SYS_ID;
        $limit =                isset($args['search_results_page_limit']) ? $args['search_results_page_limit'] : false;
        $sortBy =               isset($args['search_results_sortBy']) ? $args['search_results_sortBy'] : 'relevance';
        if (strlen($search_date_end)==4) {
            $search_date_end = $search_date_end."-12-31";
        }
        if (strlen($search_date_end)==7) {
            $search_date_end = $search_date_end."-31";
        }
        $out = array(
            'count' =>          0,
            'limit' =>          $limit,
            'offset' =>         $search_offset,
            'results' =>        array(),
            'search_name' =>    $search_name,
            'search_text' =>    $search_text
        );
        if ($search_categories!="") {
            // Since pages don't have categories, we won't have a match
            return $out;
        }
        switch ($sortBy) {
            case 'date':
                $order = "  `date` DESC\n";
                break;
            case 'relevance':
                $order = ($search_text ?
                     "  `p`.`page` LIKE \"".$search_text."%\" DESC,\n"
                    ."  `p`.`title` LIKE \" %".$search_text." %\" DESC,\n"
                    ."  `p`.`content` LIKE \" %".$search_text." %\" DESC,\n"
                    ."  `date` DESC,\n"
                    ."  `p`.`content` LIKE \"%".$search_text." %\" DESC,\n"
                    ."  `p`.`title` LIKE \"%".$search_text." %\" DESC\n"
                :
                    "  `date` DESC, `p`.`title`\n"
                );
                break;
            case 'title':
                $order = "  `p`.`title`\n";
                break;
        }
        $search_offset = (int) $search_offset;
        // Paging query:
        $sql =
             "SELECT\n"
            ."  `p`.`systemID`,\n"
            ."  `p`.`permPUBLIC`,\n"
            ."  `p`.`permSYSLOGON`,\n"
            ."  `p`.`permSYSMEMBER`,\n"
            ."  `p`.`group_assign_csv`,\n"
            ."  DATE(`p`.`history_created_date`) `date`\n"
            ."FROM\n"
            ."  `pages` `p`\n"
            .($search_keywordIDs!="" ?
                 "INNER JOIN `keyword_assign` `k` ON\n"
                ."  `k`.`assign_type` = 'page' AND\n"
                ."  `k`.`assignID` = `p`.`ID`\n"
            :
                ""
            )
            ."WHERE\n"
            ."  `p`.`systemID` IN (".$systemIDs_csv.") AND\n"
            .($search_memberID!=0 ? "  `p`.`memberID` IN(".$search_memberID.") AND\n" : "")
            .($search_date_start!="" ? "  `p`.`history_created_date` >= '".$search_date_start."' AND\n" : "")
            .($search_date_end!="" ?
                "  `p`.`history_created_date` < DATE_ADD('".$search_date_end."',INTERVAL 1 DAY) AND\n"
             :
                ""
             )
            ."  (`p`.`systemID`=".SYS_ID." OR `p`.`permPUBLIC` = 1) AND\n"
            .($search_keywordIDs!="" ? "  `k`.`keywordID` IN(".$search_keywordIDs.") AND\n" : "")
            .($search_text ?
            "(\n"
            .($search_name=='' ? "" : "  `p`.`page` LIKE \"".$search_name."%\" OR\n")
            ."  `p`.`content_text` LIKE \"".$search_text."%\" OR\n"
            ."  `p`.`content_text` LIKE \"%".$search_text."%\" OR\n"
            ."  `p`.`content_text` LIKE \"".$search_text."%\" OR\n"
            ."  `p`.`meta_description` LIKE \"%".$search_text."\" OR\n"
            ."  `p`.`meta_description` LIKE \"%".$search_text."%\" OR\n"
            ."  `p`.`meta_description` LIKE \"".$search_text."%\" OR\n"
            ."  `p`.`meta_keywords` LIKE \"%".$search_text."\" OR\n"
            ."  `p`.`meta_keywords` LIKE \"%".$search_text."%\" OR\n"
            ."  `p`.`meta_keywords` LIKE \"".$search_text."%\" OR\n"
            ."  `p`.`title` LIKE \"".$search_text."%\" OR\n"
            ."  `p`.`title` LIKE \"%".$search_text."%\" OR\n"
            ."  `p`.`title` LIKE \"".$search_text."%\"\n"
            .") AND\n"
            :  ($search_name=='' ? "" : "  `p`.`page` LIKE '".$search_name."%' AND\n")
            )
            ."  1\n"
            ."GROUP BY `p`.`ID`\n"
            ."ORDER BY\n"
            .$order;
        $records =      $this->get_records_for_sql($sql);
        $valid_start =  $search_offset;
        $valid_end =    ($limit===false ? false : $search_offset+$limit);
        if ($records) {
            foreach ($records as $row) {
                if ($row['systemID']==SYS_ID) {
                    $visible = $this->is_visible($row);
                } else {
                    $visible = $row['permPUBLIC'];
                }
                if ($visible) {
                // Visible record, increment count
                    $out['count']++;
                } else {
                    // Skipped record - not visible to user
                    if ($out['count']<$search_offset) {
                        // Haven't reached paged range yet, increment start and end points by one
                        $valid_start++;
                        $valid_end++;
                    } elseif ($out['count']<$search_offset+$limit) {
                        // In paged range but not enough matches yet, increment end point by one
                        $valid_end++;
                    }
                }
            }
            // Data results query:
            $sql =
                 "SELECT\n"
                ."  `p`.`systemID`,\n"
                .((string) $systemIDs_csv!=(string) SYS_ID ?
                     "  `s`.`textEnglish`,\n"
                    ."  `s`.`URL` `systemURL`,\n"
                :
                    ""
                )
                ."  `p`.`page`,\n"
                ."  `p`.`path`,\n"
                ."  `p`.`permPUBLIC`,\n"
                ."  `p`.`permSYSLOGON`,\n"
                ."  `p`.`permSYSMEMBER`,\n"
                ."  `p`.`group_assign_csv`,\n"
                ."  `p`.`title`,\n"
                ."  `p`.`content_text`,\n"
                ."  DATE(`p`.`history_created_date`) `date`\n"
                ."FROM\n"
                ."  `pages` `p`\n"
                .($search_keywordIDs!="" ?
                     "INNER JOIN `keyword_assign` `k` ON\n"
                    ."  `k`.`assign_type` = 'page' AND\n"
                    ."  `k`.`assignID` = `p`.`ID`\n"
                :
                    ""
                )
                .((string) $systemIDs_csv!=(string) SYS_ID ?
                     "INNER JOIN `system` `s` ON\n"
                    ."  `p`.`systemID` = `s`.`ID`\n"
                :
                    ""
                )
                ."WHERE\n"
                ."  `p`.`systemID` IN (".$systemIDs_csv.") AND\n"
                .($search_date_start!="" ?
                    "  `p`.`history_created_date` >= '".$search_date_start."' AND\n"
                 :
                    ""
                 )
                .($search_date_end!="" ?
                    "  `p`.`history_created_date` < DATE_ADD('".$search_date_end."',INTERVAL 1 DAY) AND\n"
                 :
                    ""
                 )
                ."  (`p`.`systemID`=".SYS_ID." OR `p`.`permPUBLIC` = 1) AND\n"
                .($search_keywordIDs!="" ? "  `k`.`keywordID` IN(".$search_keywordIDs.") AND\n" : "")
                .($search_text ?
                     "(\n"
                    .($search_name=='' ? "" : "  `p`.`page` LIKE \"".$search_name."%\" OR\n")
                    ."  `p`.`content_text` LIKE \"".$search_text."%\" OR\n"
                    ."  `p`.`content_text` LIKE \"%".$search_text."%\" OR\n"
                    ."  `p`.`content_text` LIKE \"".$search_text."%\" OR\n"
                    ."  `p`.`meta_description` LIKE \"%".$search_text."\" OR\n"
                    ."  `p`.`meta_description` LIKE \"%".$search_text."%\" OR\n"
                    ."  `p`.`meta_description` LIKE \"".$search_text."%\" OR\n"
                    ."  `p`.`meta_keywords` LIKE \"%".$search_text."\" OR\n"
                    ."  `p`.`meta_keywords` LIKE \"%".$search_text."%\" OR\n"
                    ."  `p`.`meta_keywords` LIKE \"".$search_text."%\" OR\n"
                    ."  `p`.`title` LIKE \"".$search_text."%\" OR\n"
                    ."  `p`.`title` LIKE \"%".$search_text."%\" OR\n"
                    ."  `p`.`title` LIKE \"".$search_text."%\"\n"
                    .") AND\n"
                :
                    ($search_name=='' ? "" : "  `p`.`page` LIKE '".$search_name."%' AND\n")
                )
                ."  1\n"
                ."GROUP BY `p`.`ID`\n"
                ."ORDER BY\n"
                .$order
                .($limit>0 ? "LIMIT ".$valid_start.",".($valid_end-$valid_start) : "");
            $records = $this->get_records_for_sql($sql);
            foreach ($records as $row) {
                if ($row['systemID']==SYS_ID) {
                    $visible = $this->is_visible($row);
                } else {
                    $visible = $row['permPUBLIC'];
                }
                if ($visible) {
                    $out['results'][] = $row;
                }
            }
        }
        return $out;
    }

    public function get_selector_sql_parents($thisPageID = false)
    {
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        $UnionOrder = 0;
        $sql_out =
             "SELECT\n"
            ."  ".$UnionOrder++." `UnionOrder`,\n"
            ."  0 `value`,\n"
            ."  '' `path`,\n"
            ."  \"Parent Page".($thisPageID ? " (Do NOT choose entries shown in red)" : "")
            ."\" `text`,\n"
            ."  'e0e0e0' `color_background`,\n"
            ."  1 `isHeader`\n"
            ."UNION SELECT\n"
            ."  ".$UnionOrder++.",\n"
            ."  0,\n"
            ."  '',\n"
            ."  '(None)',\n"
            ."  'e0e0e0',\n"
            ."  0\n";
        if (!$isMASTERADMIN) {
            $sql_out.=
                 "UNION SELECT\n"
                ."  ".$UnionOrder++.",\n"
                ."  `ID`,\n"
                ."  `path`,\n"
                ."  CONCAT(\n"
                ."    REPEAT(\n"
                ."      '&nbsp;',\n"
                ."      3*(length(`path`)-length(replace(`path`,'/',''))-3)\n"
                ."    ),\n"
                ."    `page`\n"
                ."  ),\n"
                .($thisPageID ?
                     "  IF(\n"
                    ."      `pages`.`ID` IN(".$thisPageID.") OR\n"
                    ."      `pages`.`parentID` IN(".$thisPageID."),\n"
                    ."      'ffe0e0',\n"
                    ."      'e8ffe8'\n"
                    ."  ),\n"
                :
                    "  'e8ffe8',\n"
                )
                ."  0\n"
                ."FROM\n"
                ."  `pages`\n"
                ."WHERE\n"
                ."  `systemID`=".SYS_ID."\n"
                ."ORDER BY\n"
                ."  `UnionOrder`,`path`";
      //    z($sql_out);die;
            return $sql_out;
        }
        $sql =
             "SELECT\n"
            ."  `system`.`ID`,\n"
            ."  `system`.`textEnglish`\n"
            ."FROM\n"
            ."  `system`\n"
            ."INNER JOIN `pages` ON\n"
            ."  `system`.`ID` = `pages`.`systemID`\n"
            ."GROUP BY\n"
            ."  `system`.`ID`\n"
            ."ORDER BY\n"
            ."  `system`.`textEnglish`";
        $records = $this->get_records_for_sql($sql);
        $bgcolor = '';
        foreach ($records as $record) {
            $bgcolor = ($bgcolor =='e8ffe8' ? 'f8fff8' : 'e8ffe8');
            $sql_out.=
                 "UNION SELECT\n"
                ."  ".$UnionOrder++.",\n"
                ."  0,\n"
                ."  '',\n"
                ."  \"Pages from '".$record['textEnglish']."'\",\n"
                ."  '$bgcolor',\n"
                ."  1\n"
                ."UNION SELECT\n"
                ."  ".$UnionOrder++.",\n"
                ."  `pages`.`ID`,\n"
                ."  `path`,\n"
                ."  CONCAT(\n"
                ."    REPEAT(\n"
                ."      '&nbsp;',\n"
                ."      3*(length(path)-length(replace(path,'/',''))-3)\n"
                ."    ),\n"
                ."    `page`\n"
                ."  ),\n"
                .($thisPageID ?
                 "  IF(\n"
                ."    `pages`.`ID` IN(".$thisPageID.") OR\n"
                ."    `pages`.`parentID` IN(".$thisPageID."),\n"
                ."    'ffe0e0',\n"
                ."    '".$bgcolor."'),\n"
                : "  '".$bgcolor."',\n"
                )
                ."  0\n"
                ."FROM\n"
                ."  `pages`\n"
                ."WHERE\n"
                ."  `pages`.`systemID`=".$record['ID']."\n";
        }
        $sql_out.=
             "ORDER BY\n"
            ."  `UnionOrder`,`path`";
        // z($sql_out);die;
        return $sql_out;
    }

    public function handleReportCopy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        if (trim($name) === "") {
            $msg = "<b>Error:</b> New page must have a name.";
            return false;
        }
        $targetSystemID = $this->get_field('systemID');
        if ($this->exists_named($name, $targetSystemID)) {
            $msg = "<b>Error:</b> a Page named ".$name." already exists for the site.";
            return false;
        }
        $newID = $this->copy($name);
        if ($newID) {
            $msg =         status_message(0, true, 'Page', '', "been copied to ".$name.".", $this->_get_ID());
            $msg_tooltip = status_message(0, false, 'Page', '', "been copied to ".$name.".", $this->_get_ID());
            return true;
        }
        return false;
    }

    public static function hasDynamicTags($content)
    {
        $hasAudioClips =    (strpos(' '.$content, "[audio:"));
        $hasECLTags =       (strpos(' '.$content, "[ECL]"));
        $hasTransformers =  (strpos(' '.$content, "[TRANSFORM]"));
        $hasYoutubeClips =  (strpos(' '.$content, "[youtube"));

        return ($hasAudioClips || $hasECLTags || $hasTransformers || $hasYoutubeClips ? 1 : 0);
    }

    public function on_delete_pre_set_nested_paths()
    {
        global $action_parameters;
        $sql =
         "SELECT\n"
        ."  `ID`,\n"
        ."  `systemID`,\n"
        ."  `page`,\n"
        ."  `path`,\n"
        ."  `parentID`\n"
        ."FROM\n"
        ."  `".$this->_get_table_name()."`\n"
        ."WHERE\n"
        ."  `ID` IN(".$action_parameters['triggerID'].")";
        $records = $this->get_records_for_sql($sql);
        foreach ($records as $record) {
    //      y($record);
            $ID =             $record['ID'];
            $systemID =       $record['systemID'];
            $page =           $record['page'];
            $parentID =       $record['parentID'];

          // track down any nested pages using this as their parent and
          // set their parents to the parent this one specified -
          // i.e. move them up the tree (possibly to root position)
            $sql =
             "UPDATE\n"
            ."  `".$this->_get_table_name()."`\n"
            ."SET\n"
            ."  `parentID` = ".$parentID."\n"
            ."WHERE\n"
            ."  `systemID`=".$systemID." AND\n"
            ."  `parentID` = ".$ID;
    //      z($sql);
            $this->do_sql_query($sql);

          // Now remap any pages with this page in their path to use the shortened path
          // unless this page was already at root
            $old_path =       $record['path'];
            $new_path =       "//".$this->get_path($parentID);
            $sql =
             "UPDATE\n"
            ."  `".$this->_get_table_name()."`\n"
            ."SET\n"
            ."  `path` =\n"
            ."   REPLACE(`path`,'".$old_path."','".$new_path."')\n"
            ."WHERE\n"
            ."  `systemID`=".$systemID;
    //        z($sql);
            $this->do_sql_query($sql);
        }
  //    die;
    }

    public function on_copy_post_set_nested_paths()
    {
        global $action_parameters;
        $ID =           $action_parameters['triggerID'];
        $new_path =     "//".$this->get_path($ID);
        $this->_set_ID($ID);
        $this->set_field('path', $new_path);
    }

    public function on_insert_post_set_nested_paths()
    {
        global $action_parameters;
        $new_page =         $action_parameters['data']['page_name'];
        $new_parentID =     0;
        $systemID = (get_person_permission("MASTERADMIN") && isset($action_parameters['data']['systemID']) ?
        $action_parameters['data']['systemID']
        :
        SYS_ID
        );
        if (isset($action_parameters['data']['parentID'])) {
            $new_parentID =     $action_parameters['data']['parentID'];
        }
        $new_path =
        "//"
        .$this->get_path($new_parentID)
        .$new_page
        ."/";
  //    print $new_path;die;
        $sql =
        "UPDATE\n"
        ."  `".$this->_get_table_name()."`\n"
        ."SET\n"
        ."  `path` = '".$new_path."'\n"
        ."WHERE\n"
        ."  `systemID`=".$systemID." AND\n"
        ."  `ID` = ".$action_parameters['triggerID'];
  //        z($sql);die;
        $this->do_sql_query($sql);
    }

    public function on_update_pre_set_nested_paths()
    {
        // Only needed for form updates -
        // We detect this by looking for parentID - exit if not set, must be report mode
        global $action_parameters;
        //    y($action_parameters);die;
        if (!isset($action_parameters['data']['parentID'])) {
            return;
        }
        $bulk_update = (
            isset($action_parameters['data']['bulk_update']) &&
            $action_parameters['data']['bulk_update']==1 ?
                true
            :
                false
            );
        $sql =
             "SELECT\n"
            ."  `systemID`,\n"
            ."  `parentID`,\n"
            ."  `page`,\n"
            ."  `path`\n"
            ."FROM\n"
            ."  `".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `ID` IN(".$action_parameters['triggerID'].")";
        $records = $this->get_records_for_sql($sql);
        //    y($records);die;
        foreach ($records as $record) {
            $systemID =       $record['systemID'];
            $old_page =       $record['page'];
            $old_path =       $record['path'];
            $old_parentID =   $record['parentID'];
            $new_page =       false;
            $new_parentID =   false;
            switch ($bulk_update) {
                case false:
                    if ($old_page !==     $action_parameters['data']['page_name']) {
                        $new_page =         $action_parameters['data']['page_name'];
                    }
                    if (isset($action_parameters['data']['parentID'])) {
                        $new_parentID =     $action_parameters['data']['parentID'];
                    }
                    break;
                case true:
                    if (isset($action_parameters['data']['parentID_apply'])) {
                        if ($old_parentID != $action_parameters['data']['parentID']) {
                            $new_parentID =    $action_parameters['data']['parentID'];
                        }
                    }
                    break;
            }
            if ($new_parentID===false) {
                $new_parentID=$old_parentID;
            }
            $new_path =
            "//"
            .$this->get_path($new_parentID)
            .($new_page!==false ? $new_page : $old_page)
            ."/";
    //        print $new_path;die;
            $sql =
            "UPDATE\n"
            ."  `".$this->_get_table_name()."`\n"
            ."SET\n"
            ."  `path` =\n"
            ."   REPLACE(`path`,'".$old_path."','".$new_path."')\n"
            ."WHERE\n"
            ."  `systemID`=".$systemID;
            $this->do_sql_query($sql);
    //        z($sql);die;
        }
    }

    public static function pop_content($part)
    {
        return Output::pull($part);
    }

    public function print_form_data()
    {
        global $system_vars;
        $skip_csv =
             "anchor,bulk_update,command,component_help,eventID,filterExact,filterField,filterValue,"
            ."goto,limit,MM,mode,offset,print,report_name,search_categories,search_date_end,search_date_start,"
            ."search_keywords,search_offset,search_text,search_type,selected_section,selectID,sortBy,submode,"
            ."targetField,targetFieldID,targetID,targetReportID,targetValue,topbar_search,YYYY,undefined";
        $skip_arr = explode(",", $skip_csv);
        $out =
             DOCTYPE."\n"
            ."<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"".$system_vars['defaultLanguage']."\">\n"
            ."<head>\n"
            ."<title>Posted Form Printable View</title>\n"
            ."<style type='text/css'>\n"
            ."table {border-collapse: collapse}\n"
            ."table td, table th { border: 1px solid #888; padding: 2px; text-align: left}\n"
            ."table th { background-color: #eef; }\n"
            ."</style>"
            ."</head>\n"
            ."<body>\n"
            ."<div style='text-align: center; margin: auto; width: 80%;'>\n"
            ."<div><h1>Form Contents</h1>\n"
            ."<table>"
            ."  <tr>\n"
            ."    <th>Question</th>\n"
            ."    <th>Answer</th>\n"
            ."  </tr>\n";
        $fields = array();
        foreach ($_POST as $name => $value) {
            if (!in_array($name, $skip_arr) && $value!='' && substr($name, 0, 12)!='poll_answer_') {
                $fields[$name] = $value;
            }
        }
        foreach ($_GET as $name => $value) {
            if (!in_array($name, $skip_arr) && $value!='' && substr($name, 0, 12)!='poll_answer_') {
                $fields[$name] = $value;
            }
        }
        foreach ($fields as $name => $value) {
            $out.=
                 "  <tr>\n"
                ."    <th>".str_replace("_", " ", $name)."</th>\n"
                ."    <td>".$value."</th>\n"
                ."  </tr>\n";
        }
        $out.=
             "</table>"
            ."<p><input type='button' value='Print Form' onlick='window.print()' /></p>"
            ."</div></div></body></html>";
        print $out;
    }

    public static function push_content($part, $code)
    {
        Output::push($part, $code);
    }

    public function serve_content()
    {
        $request = Portal::get_request_path();
        $request = ($request ? $request : 'home');
        $record = $this->get_page_by_path($request);
        if (!$record) {
            header("Status: 404 Not Found", true, 404);
            print "404 - page not found - ".$request;
            die;
        }
        if ($record['permPUBLIC'] && $record['password']=='') {
            print
            ($record['style'] ? "<style type='text/css'>".$record['style']."</style>" : "")
            .$record['content'];
            die;
        }
        header("Status: 403 Unauthorised", true, 403);
        print "403 - only public content can be obtained this way";
        die;
    }
}
