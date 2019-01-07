<?php
/* Custom Fields used:
custom_1 = denomination (must be as used in other SQL-based controls)

Version History:
  1.0.126 (2019-01-06)
    1) Community::get_stats() now correctly handles merging of existing stats with newly obtained ones.
*/

class Community extends Displayable_Item
{
    const VERSION = '1.0.126';
    const FIELDS = 'ID, archive, archiveID, deleted, date_launched, dropbox_email, dropbox_password, dropbox_app_key, dropbox_app_secret, dropbox_access_token_key, dropbox_access_token_secret, dropbox_delta_cursor, dropbox_folder, dropbox_last_checked, email_domain, enabled, gallery_album_rootID, map_lat_max, map_lat_min, map_lon_max, map_lon_min, name, podcast_album_rootID, sponsorship, sponsorship_gallery_albumID, stats_cache, systemID, title, URL, URL_external, welcome, XML_data, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';

    protected $_community_record =        array();
    protected $_edit_form =               array();
    protected $_Obj_DropLib =             false;
    protected $_path_extension =          '';
    protected $_popup =                   array();
    protected $_selected_section =        '';
    protected $_section_tabs_arr =        array();
    protected $_track_outlinks =          array();
    protected $_track_views =             array();

    public function __construct($ID = "")
    {
        parent::__construct('community', $ID);
        $this->_set_assign_type('Community');
        $this->_set_object_name('Community');
        $this->_set_default_enclosure_base_folder('/UserFiles/Image/communities/');
        $this->_set_context_menu_ID('community');
        $this->set_edit_params(
            array(
                'report' =>                 'community',
                'report_rename' =>          true,
                'report_rename_label' =>    'new name'
            )
        );
    }

    public function _get_default_enclosure_base_folder()
    {
        return $this->_default_enclosure_base_folder;
    }
    public function _set_default_enclosure_base_folder($value)
    {
        $this->_default_enclosure_base_folder = $value;
    }

    public function delete()
    {
        $sql =
             "DELETE FROM\n"
            ."  `community_membership`\n"
            ."WHERE\n"
            ." `communityID` IN(".$this->_get_ID().")";
        $this->do_sql_query($sql);
        $sql =
             "DELETE FROM\n"
            ."  `postings`\n"
            ."WHERE\n"
            ." `communityID` IN(".$this->_get_ID().")";
        $this->do_sql_query($sql);
        parent::delete();
    }

    public function get_bounding_box()
    {
        $sql =
             "SELECT\n"
            ."  MAX(`community_member`.`service_map_lat`) `map_lat_max`,\n"
            ."  MIN(`community_member`.`service_map_lat`) `map_lat_min`,\n"
            ."  MAX(`community_member`.`service_map_lon`) `map_lon_max`,\n"
            ."  MIN(`community_member`.`service_map_lon`) `map_lon_min`\n"
            ."FROM\n"
            ."  `community`INNER JOIN `community_membership` ON\n"
            ."  `community_membership`.`communityID` = `community`.`ID`\n"
            ."INNER JOIN `community_member` ON\n"
            ."  `community_member`.`ID` = `community_membership`.`memberID`\n"
            ."WHERE\n"
            ."  (`community_member`.`service_map_lat`!=0 || `community_member`.`service_map_lon`!=0) AND\n"
            ."  `community`.`ID` = ".$this->_get_ID();
        return $this->get_record_for_sql($sql);
    }

    public function get_communities()
    {
        $sql =
             "SELECT\n"
            ."  (SELECT\n"
            ."      COUNT(*)\n"
            ."  FROM\n"
            ."     `community_membership`\n"
            ."  WHERE\n"
            ."      `community_membership`.`communityID`=`".$this->_get_table_name()."`.`ID`\n"
            ."  ) `members`,\n"
            ."  `map_lat_min`+((`map_lat_max`-`map_lat_min`)/2) `map_lat`,\n"
            ."  `map_lon_min`+((`map_lon_max`-`map_lon_min`)/2) `map_lon`,\n"
            ."  `".$this->_get_table_name()."`.*\n"
            ."FROM\n"
            ."  `".$this->_get_table_name()."`\n"
            ."WHERE\n"
            ."  `systemID` = ".SYS_ID
            .($this->_current_user_rights['canEdit'] ? "\n" : " AND\n  `enabled`=1\n")
            ."ORDER BY\n"
            ."  `title`";
        return $this->get_records_for_sql($sql);
    }

    public function get_events_upcoming($category_csv = false)
    {
        $sql =
             "SELECT\n"
            ."  *\n"
            ."FROM\n"
            ."  `postings`\n"
            ."WHERE\n"
            ."  `systemID`=".SYS_ID." AND\n"
            ."  `type`='event' AND\n"
            .($category_csv!="" ?
                "  `category` REGEXP \"".implode("|", explode(',', str_replace(" ", "", $category_csv)))."\" AND\n"
            :
                ""
            )
            ."  `effective_date_start` >= '".date('Y')."-".date('m')."-".date('d')."' AND\n"
            ."  `communityID` = ".$this->_get_ID();
        return $this->get_records_for_sql($sql);
    }

    public function get_members_ID_csv()
    {
        $sql =
             "SELECT\n"
            ."  GROUP_CONCAT(`community_member`.`ID`)\n"
            ."FROM\n"
            ."  `community`\n"
            ."INNER JOIN `community_membership` ON\n"
            ."  `community_membership`.`communityID` = `community`.`ID`\n"
            ."INNER JOIN `community_member` ON\n"
            ."  `community_member`.`ID` = `community_membership`.`memberID`\n"
            ."WHERE\n"
            ."  `community`.`ID` = ".$this->_get_ID()."\n";
        return $this->get_field_for_sql($sql);
    }

    public function get_members()
    {
        $sql =
             "SELECT\n"
            ."  (SELECT\n"
            ."      `system`.`textEnglish`\n"
            ."  FROM\n"
            ."      `system`\n"
            ."  WHERE\n"
            ."      `system`.`ID` = `community_member`.`systemID`\n"
            ."  ) `systemTitle`,\n"
            ."  CONCAT(\n"
            ."      TRIM(TRAILING '/' FROM `community`.`URL`),\n"
            ."      '/',\n"
            ."      `community_member`.`name`\n"
            ."  ) AS `member_URL`,\n"
            ."  (\n"
            ."      LENGTH(`partner_csv`) -\n"
            ."      LENGTH(replace(`partner_csv`, ',', '')) +\n"
            ."      if(LENGTH(`partner_csv`), 1, 0) >= `partner_min`\n"
            ."  ) as `compliance`,\n"
            ."  (\n"
            ."      LENGTH(`partner_csv`) -\n"
            ."      LENGTH(replace(`partner_csv`,',','')) +\n"
            ."      if(LENGTH(`partner_csv`),1,0)\n"
            ."  ) `partners`,\n"
            ."  COALESCE(\n"
            ."      (SELECT\n"
            ."          `shortform_name`\n"
            ."      FROM\n"
            ."          `community_member` `cm2`\n"
            ."      WHERE\n"
            ."          `cm2`.`ID` = `community_member`.`primary_ministerialID`\n"
            ."      ),\n"
            ."      ''\n"
            ."  ) `ministerial_title`,\n"
            ."  `community_member`.*\n"
            ."FROM\n"
            ."  `community`\n"
            ."INNER JOIN `community_membership` ON\n"
            ."  `community_membership`.`communityID` = `community`.`ID`\n"
            ."INNER JOIN `community_member` ON\n"
            ."  `community_member`.`ID` = `community_membership`.`memberID`\n"
            ."WHERE\n"
            ."  `community`.`ID` = ".$this->_get_ID()."\n"
            ."ORDER BY\n"
            ."  `name`,`service_addr_city`";
        // z($sql);
        return $this->get_records_for_sql($sql);
    }

    public function get_stats()
    {
        set_time_limit(600);    // Extend maximum execution time to 10 mins
        $start =    STATS_START_DATE;
        $end =      date('Y-m-d', time());
        $step =     '+1 month';
        $format =   'Y-m';

        $r =        $this->load();

        $dates_to_check =       get_dates_in_range($start, $end, $step, $format);
        $this->_stats_dates =   $dates_to_check;
        $find =                 BASE_PATH.trim($r['URL'], '/');
        $this->_stats =         [];
        if ($r['stats_cache']) {
            $this->_stats = unserialize($r['stats_cache']);
            if (isset($this->_stats['cache_date']) && $this->_stats['cache_date']==$end) {
                // Got stats already today, quit early
                return;
            }
            // Got some stats, but not today. Try again starting from month we last parsed
            $start = substr($this->_stats['cache_date'], 0, 7).'-01';
        }
        $Obj_Piwik = new Piwik;
        if (!$Obj_Piwik->isOnline()) {
            return $this->_stats;
        }
        $visits = $Obj_Piwik->getVisitsForMonths($start, $end, $find);
        $this->_stats = array_merge($this->_stats, $visits[$find]);
        $this->_stats['cache_date'] = $end;
        $this->set_field('stats_cache', Record::escape_string(serialize($this->_stats), true, false));
    }

    public function export_sql($targetID, $show_fields)
    {
        $header =
            "Selected ".$this->object_name.$this->plural($targetID)."\n"
            ."(with members, membships, contacts and postings)";
        $extra_delete =
             "DELETE FROM\n"
            ."  `category_assign`\n"
            ."WHERE\n"
            ."  `assignID` IN(\n"
            ."      SELECT\n"
            ."          `ID`\n"
            ."      FROM\n"
            ."          `postings`\n"
            ."      WHERE\n"
            ."          `memberID` IN(\n"
            ."              SELECT\n"
            ."                  `ID`\n"
            ."              FROM\n"
            ."                  `community_member`\n"
            ."              WHERE\n"
            ."                  `primary_communityID` IN (".$targetID.") AND\n"
            ."                  `systemID` IN (1,".SYS_ID.")\n"
            ."          ) OR\n"
            ."          (\n"
            ."              `communityID` = ".$targetID." AND\n"
            ."              `systemID` IN (1,1450618986)\n"
            ."      )\n"
            ."  );\n"
            ."\n"
            ."DELETE FROM\n"
            ."  `postings`\n"
            ."WHERE\n"
            ."  (\n"
            ."      `memberID` IN(\n"
            ."          SELECT\n"
            ."              `ID`\n"
            ."          FROM\n"
            ."              `community_member`\n"
            ."          WHERE\n"
            ."              `primary_communityID` IN (".$targetID.")\n"
            ."      ) OR\n"
            ."          `communityID` IN (".$targetID.")\n"
            ."  ) AND\n"
            ."  `systemID` IN (1,".SYS_ID.");\n"
            ."\n"
            ."DELETE FROM\n"
            ."  `person`\n"
            ."WHERE\n"
            ."  `ID` IN (\n"
            ."      SELECT\n"
            ."          `contactID`\n"
            ."      FROM\n"
            ."          `community_member`\n"
            ."      WHERE\n"
            ."          `primary_communityID` IN (".$targetID.") AND\n"
            ."          `systemID` IN (1,".SYS_ID.")\n"
            ."  ) AND\n"
            ."  `systemID` IN (1,".SYS_ID.");\n"
            ."\n"
            ."DELETE FROM\n"
            ."  `community_member`\n"
            ."WHERE\n"
            ."  `primary_communityID` IN (".$targetID.") AND\n"
            ."  `systemID` IN (1,".SYS_ID.");\n"
            ."\n"
            ."DELETE FROM\n"
            ."  `community_membership`\n"
            ."WHERE\n"
            ."  `communityID` IN (".$targetID.") AND\n"
            ."  `systemID` IN (1,".SYS_ID.");\n"
            ."\n";

        $extra_select =
            Backup::db_export_sql_query(
                "`community_member`      ",
                "SELECT\n"
                ."  *\n"
                ."FROM\n"
                ."  `community_member`\n"
                ."WHERE\n"
                ."  `primary_communityID` IN (".$targetID.") AND\n"
                ."  `systemID` IN (1,".SYS_ID.")",
                $show_fields
            )
            .Backup::db_export_sql_query(
                "`community_membership`  ",
                "SELECT\n"
                ."  *\n"
                ."FROM\n"
                ."  `community_membership`\n"
                ."WHERE\n"
                ."  `communityID` IN (".$targetID.") AND\n"
                ."  `systemID` IN (1,".SYS_ID.")",
                $show_fields
            )
            .Backup::db_export_sql_query(
                "`category_assign`       ",
                "SELECT\n"
                ."  *\n"
                ."FROM\n"
                ."  `category_assign`\n"
                ."WHERE\n"
                ."  `assignID` IN(\n"
                ."      SELECT\n"
                ."          `ID`\n"
                ."      FROM\n"
                ."          `postings`\n"
                ."      WHERE\n"
                ."          `memberID` IN(\n"
                ."              SELECT\n"
                ."                  `ID`\n"
                ."              FROM\n"
                ."                  `community_member`\n"
                ."              WHERE\n"
                ."                  `primary_communityID` IN (".$targetID.") AND\n"
                ."                  `systemID` IN (1,".SYS_ID.")\n"
                ."          ) OR\n"
                ."          (\n"
                ."              `communityID` = ".$targetID." AND\n"
                ."              `systemID` IN (1,1450618986)\n"
                ."      )\n"
                ."  )",
                $show_fields
            )
            .Backup::db_export_sql_query(
                "`postings`              ",
                "SELECT\n"
                ."  *\n"
                ."FROM\n"
                ."  `postings`\n"
                ."WHERE\n"
                ."  (\n"
                ."      `memberID` IN(\n"
                ."          SELECT\n"
                ."              `ID`\n"
                ."          FROM\n"
                ."              `community_member`\n"
                ."          WHERE\n"
                ."              `primary_communityID` IN (".$targetID.")\n"
                ."      ) OR\n"
                ."          `communityID` IN (".$targetID.")\n"
                ."  ) AND\n"
                ."  `systemID` IN (1,".SYS_ID.");\n",
                $show_fields
            )
            .Backup::db_export_sql_query(
                "`person`                ",
                "SELECT\n"
                ."  *\n"
                ."FROM\n"
                ."  `person`\n"
                ."WHERE\n"
                ."  `ID` IN(\n"
                ."      SELECT\n"
                ."          `contactID`\n"
                ."      FROM\n"
                ."          `community_member`\n"
                ."      WHERE\n"
                ."          `primary_communityID` IN (".$targetID.") AND\n"
                ."          `systemID` IN (1,".SYS_ID.")\n"
                ."  ) AND\n"
                ."      `systemID` IN (1,".SYS_ID.")",
                $show_fields
            );
        return parent::sql_export($targetID, $show_fields, $header, '', $extra_delete, $extra_select);
    }

    public static function getSelectorSql()
    {
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        if ($isMASTERADMIN) {
            return
                 "SELECT\n"
                ."  0 `seq`,\n"
                ."  '' `value`,\n"
                ."  '(None)' `text`,\n"
                ."  'd0d0d0' `color_background`,\n"
                ."  '404040' `color_text`\n"
                ."UNION SELECT\n"
                ."  1,\n"
                ."  `community`.`ID`,\n"
                ."  CONCAT(\n"
                ."    `system`.`textEnglish`,': ',\n"
                ."    `community`.`title`\n"
                ."  ),\n"
                ."  IF(`system`.`ID`=SYS_ID,'c0ffc0','ffe0e0'),\n"
                ."  '000000'\n"
                ."FROM\n"
                ."  `community`\n"
                ."INNER JOIN `system` ON\n"
                ."  `system`.`ID` = `community`.`systemID`\n"
                ."ORDER BY\n"
                ."  `seq`,`text`";
        }
        return
             "SELECT\n"
            ."  0 `seq`,\n"
            ."  '' `value`,\n"
            ."  '(None)' `text`,\n"
            ."  'd0d0d0' `color_background`,\n"
            ."  '404040' `color_text`\n"
            ."UNION SELECT\n"
            ."  1,\n"
            ."  `community`.`ID`,\n"
            ."  `community`.`title`,\n"
            ."  'c0ffc0',\n"
            ."  '000000'\n"
            ."FROM\n"
            ."  `community`\n"
            ."WHERE\n"
            ."  `community`.`systemID` IN(1,".SYS_ID.")\n"
            ."ORDER BY\n"
            ."  `seq`,`text`";
    }

    public function handle_report_copy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::try_copy($newID, $msg, $msg_tooltip, $name);
    }

    public function on_action_community_setup()
    {
        global $msg;
        $ID_arr = explode(',', str_replace(' ', '', $this->_get_ID()));
        foreach ($ID_arr as $ID) {
            $this->_set_ID($ID);
            $this->load();
            $this->_on_action_community_setup_dropbox();
            $this->_on_action_community_setup_gallery_album();
            $this->_on_action_community_setup_podcast_album();
            $this->_on_action_community_setup_bounding_box();
            $this->_on_action_community_setup_home_page();
            $this->_on_action_community_setup_website_button();
        }
        $this->_set_ID(implode(',', $ID_arr));
    }

    protected function _on_action_community_setup_bounding_box()
    {
      // still needed because members may belong to multiple communities
        $data = $this->get_bounding_box();
        $this->update($data, true, false);
    }

    protected function _on_action_community_setup_dropbox()
    {
        global $msg;
        $r = $this->record;
        if (!$r['dropbox_email']) {
            return;
        }
        if (!$r['dropbox_app_key']) {
            $data = array(
                'dropbox_access_token_key' =>      '',
                'dropbox_access_token_secret' =>   ''
            );
            $this->update($data, true, false);
            unset($_SESSION['DropLib_RequestToken']);
            $msg.=
                 "No App Key or Secret were provided.<br />"
                ."Please provide these or "
                ."<a target='new_window' href='"
                ."https://www.dropbox.com/login?cont=https%3A//www.dropbox.com/developers/apps"
                ."'>click here</a> to create them.";
            return;
        }
        if ($r['dropbox_access_token_key']) {
            return;
        }
        if (isset($_SESSION['DropLib_RequestToken'])) {
            $params = array(
                'consumerKey' =>      $r['dropbox_app_key'],
                'consumerSecret' =>   $r['dropbox_app_secret'],
                'tokenKey' =>         $_SESSION['DropLib_RequestToken']['key'],
                'tokenSecret' =>      $_SESSION['DropLib_RequestToken']['secret'],
                'sslCheck' =>         false
            );
            $Obj_DropLib = new DropLib($params);
            $token = $Obj_DropLib->accessToken();
            $data = array(
                'dropbox_access_token_key' =>      $token['key'],
                'dropbox_access_token_secret' =>   $token['secret']
            );
            $this->update($data, true, false);
        } else {
            $params = array(
                'consumerKey' =>      $r['dropbox_app_key'],
                'consumerSecret' =>   $r['dropbox_app_secret'],
                'sslCheck' =>         false
            );
            $Obj_DropLib = new DropLib($params);
            $_SESSION['DropLib_RequestToken'] = $Obj_DropLib->requestToken();
            $url = $Obj_DropLib->authorizeUrl();
            $msg.=
                 "Please <a target='new_window' href='".$url."'>click here</a>"
                ." to sign in to dropbox and confirm the request to connect,"
                ." then save this community member one more time.";
        }
    }

    protected function _on_action_community_setup_gallery_album()
    {
        if ($this->record['gallery_album_rootID']) {
            return;
        }
        $Obj_GA = new Gallery_Album;
        $parentID = $Obj_GA->get_ID_by_path('//communities');
        if ($albumID = $Obj_GA->get_ID_by_path('//communities/'.$this->record['name'])) {
            $this->set_field('gallery_album_rootID', $albumID, true, false);
        } else {
            $data = array(
                'communityID' =>    $this->record['ID'],
                'container_path' => '//communities',
                'content' =>        'Root Album for '.$this->record['title'].' Community',
                'date' =>           date('Y-m-d', time()),
                'enabled' =>        1,
                'enclosure_url' =>  '/UserFiles/Image/gallery-images/communities/'.$this->record['name'].'/',
                'layoutID' =>       1,
                'name' =>           $this->record['name'],
                'parentID' =>       $parentID,
                'path' =>           '//communities/'.$this->record['name'],
                'permPUBLIC' =>     1,
                'permSYSMEMBER' =>  1,
                'permSYSLOGON' =>   1,
                'systemID' =>       SYS_ID,
                'themeID' =>        1,
                'title' =>          $this->record['title'],
            );
            $albumID = $Obj_GA->insert($data);
            $this->set_field('gallery_album_rootID', $albumID, true, false);
        }
        if (!$Obj_GA->get_ID_by_path('//communities/'.$this->record['name'].'/members')) {
            $data = array(
                'communityID' =>    $this->record['ID'],
                'container_path' => '//communities/'.$this->record['name'],
                'content' =>        'Sub Album for '.$this->record['title'].' Community Members',
                'date' =>           date('Y-m-d', time()),
                'enabled' =>        1,
                'enclosure_url' =>  '/UserFiles/Image/gallery-images/communities/'.$this->record['name'].'/members/',
                'layoutID' =>       1,
                'name' =>           'members',
                'parentID' =>       $albumID,
                'path' =>           '//communities/'.$this->record['name'].'/members',
                'permPUBLIC' =>     1,
                'permSYSMEMBER' =>  1,
                'permSYSLOGON' =>   1,
                'systemID' =>       SYS_ID,
                'themeID' =>        1,
                'title' =>          'Members',
            );
            $Obj_GA->insert($data);
        }
        if (!$Obj_GA->get_ID_by_path('//communities/'.$this->record['name'].'/sponsors')) {
            $data = array(
                'communityID' =>    $this->record['ID'],
                'container_path' => '//communities/'.$this->record['name'],
                'content' =>        'Sub Album for '.$this->record['title'].' Community Sponsors',
                'date' =>           date('Y-m-d', time()),
                'enabled' =>        1,
                'enclosure_url' =>  '/UserFiles/Image/gallery-images/communities/'.$this->record['name'].'/sponsors/',
                'layoutID' =>       1,
                'name' =>           'sponsors',
                'parentID' =>       $albumID,
                'path' =>           '//communities/'.$this->record['name'].'/sponsors',
                'permPUBLIC' =>     1,
                'permSYSMEMBER' =>  1,
                'permSYSLOGON' =>   1,
                'systemID' =>       SYS_ID,
                'themeID' =>        1,
                'title' =>          'Sponsors',
            );
            $Obj_GA->insert($data);
        }
    }

    protected function _on_action_community_setup_home_page()
    {
        if ($this->record['URL']=='') {
            $this->record['URL'] = '/communities/'.$this->record['name'];
            $this->set_field('URL', $this->record['URL'], true, false);
        }
        $url = trim($this->record['URL'], '/');
        $Obj_Page = new Page;
        if ($rec = $Obj_Page->get_page_by_path($url)) {
            return;
        }
        $parent = substr($url, 0, strrpos($url, '/'));
        if (!$parent_r = $Obj_Page->get_page_by_path($parent)) {
            return;
        }
        $data = array(
            'systemID' =>             SYS_ID,
            'page' =>                 $this->record['name'],
            'path' =>                 '//'.trim($this->record['URL'], '/').'/',
            'path_extender' =>        1,
            'componentID_post' =>     1,
            'componentID_pre' =>      1,
            'component_parameters' =>
                 "calendar_large.show_controls=0".OPTION_SEPARATOR
                ."calendar_large.show_heading=0".OPTION_SEPARATOR
                ."community:module_community.community_name=".$this->record['name']."".OPTION_SEPARATOR
                ."community:module_community.gallery_member_height=210".OPTION_SEPARATOR
                ."community:module_community.gallery_member_padding=3".OPTION_SEPARATOR
                ."community:module_community.gallery_member_photo_height=135".OPTION_SEPARATOR
                ."community:module_community.gallery_member_photo_width=180".OPTION_SEPARATOR
                ."community:module_community.gallery_member_spacing=4".OPTION_SEPARATOR
                ."community:module_community.gallery_member_width=190".OPTION_SEPARATOR
                ."list_articles.content_char_limit=300".OPTION_SEPARATOR
                ."list_articles.content_plaintext=1".OPTION_SEPARATOR
                ."list_articles.content_show=1".OPTION_SEPARATOR
                ."list_articles.results_limit=20".OPTION_SEPARATOR
                ."list_articles.results_paging=2".OPTION_SEPARATOR
                ."list_articles.title_linked=1".OPTION_SEPARATOR
                ."list_articles.title_show=1".OPTION_SEPARATOR
                ."list_events.box=0".OPTION_SEPARATOR
                ."list_events.box_rss_link=0".OPTION_SEPARATOR
                ."list_events.box_title_link=0".OPTION_SEPARATOR
                ."list_news.box=0".OPTION_SEPARATOR
                ."list_news.box_rss_link=0".OPTION_SEPARATOR
                ."list_news.box_title_link=0".OPTION_SEPARATOR
                ."list_podcasts.results_limit=10".OPTION_SEPARATOR
                ."list_podcasts.results_paging=2",
            'content' =>              "[ECL]community_panel:community[/ECL]",
            'layoutID' =>             1,
            'meta_description' =>
                $this->record['URL_external']." - Churches and Christian organisations in ".$this->record['title'],
            'meta_keywords' =>        $this->record['URL_external'].", Churches in ".$this->record['title'],
            'parentID' =>             $parent_r['ID'],
            'permPUBLIC' =>           1,
            'permSYSLOGON' =>         1,
            'permSYSMEMBER' =>        1,
            'themeID' =>              1,
            'title' =>                'Community of '.$this->record['title']
        );
        $Obj_Page->insert($data);
    }

    protected function _on_action_community_setup_podcast_album()
    {
        if ($this->record['podcast_album_rootID']) {
            return;
        }
        $Obj_PA = new Podcast_Album;
        $rootID = $Obj_PA->get_ID_by_path('//communities');
        if ($parentID = $Obj_PA->get_ID_by_path('//communities/'.$this->record['name'])) {
            $this->set_field('podcast_album_rootID', $parentID, true, false);
        } else {
            $data = array(
                'communityID' =>    $this->record['ID'],
                'container_path' => '//communities',
                'content' =>        'Root Album for '.$this->record['title'].' Community',
                'date' =>           date('Y-m-d', time()),
                'enabled' =>        1,
                'enclosure_url' =>  '/UserFiles/Media/communities/'.$this->record['name'].'/',
                'layoutID' =>       1,
                'name' =>           $this->record['name'],
                'parentID' =>       $rootID,
                'path' =>           '//communities/'.$this->record['name'],
                'permPUBLIC' =>     1,
                'permSYSMEMBER' =>  1,
                'permSYSLOGON' =>   1,
                'systemID' =>       SYS_ID,
                'themeID' =>        1,
                'title' =>          $this->record['title'],
            );
            $parentID = $Obj_PA->insert($data);
            $this->set_field('podcast_album_rootID', $parentID, true, false);
        }
        if (!$Obj_PA->get_ID_by_path('//communities/'.$this->record['name'].'/members')) {
            $data = array(
                'communityID' =>    $this->record['ID'],
                'container_path' => '//communities/'.$this->record['name'],
                'content' =>        'Sub Album for '.$this->record['title'].' Community Members',
                'date' =>           date('Y-m-d', time()),
                'enabled' =>        1,
                'enclosure_url' =>  '/UserFiles/Media/communities/'.$this->record['name'].'/members/',
                'layoutID' =>       1,
                'name' =>           'members',
                'parentID' =>       $parentID,
                'path' =>           '//communities/'.$this->record['name'].'/members',
                'permPUBLIC' =>     1,
                'permSYSMEMBER' =>  1,
                'permSYSLOGON' =>   1,
                'systemID' =>       SYS_ID,
                'themeID' =>        1,
                'title' =>          'Members',
            );
            $Obj_PA->insert($data);
        }
    }

    public function _on_action_community_setup_website_button()
    {
        if (!$this->record['URL_external']) {
            return;
        }
        if (!function_exists('imagettfbbox')) {
            return;
        }
        $path =         '/UserFiles/Image/gallery-images/communities/'.$this->record['name'].'/button.png';
        $template =     '/UserFiles/Image/churches-in-template.png';
        $text_arr =     explode('.', $this->record['URL_external']);
        $text =         $text_arr[1].'.'.$text_arr[2];
        $font =         SYS_FONTS.'arialnb.ttf';
        $color =        "ADDEFF";
        $bgcolor =      "29318C";
        $transp =       "FF0000";
        $size =         64;
        $arr_bbox =        imagettfbbox($size, 0, $font, $text);
        $text_width =   ($arr_bbox[2]-$arr_bbox[0])+4;
        if ($text_width<715) {
            $text_width=715;
        }
        $this->_img_base =  imageCreateFromPng('.'.$template);
        $img_base_w =        36+148+$text_width;
        $img_base_h =        imagesy($this->_img_base);
        $this->_img =       ImageCreate(36+148+$text_width, $img_base_h);
        $RGB =
            ImageColorAllocate(
                $this->_img,
                HexDec(substr($color, 0, 2)),
                HexDec(substr($color, 2, 2)),
                HexDec(substr($color, 4, 2))
            );
        $this->_img_l =        ImageCreate(573, $img_base_h);
        $this->_img_m =        ImageCreate(1, $img_base_h);
        $this->_img_r =        ImageCreate(326, $img_base_h);
        ImageCopy($this->_img_l, $this->_img_base, 0, 0, 0, 0, 573, $img_base_h);
        ImageCopy($this->_img_m, $this->_img_base, 0, 0, 574, 0, 1, $img_base_h);
        ImageCopy($this->_img_r, $this->_img_base, 0, 0, 575, 0, 326, $img_base_h);
        ImageCopyMerge($this->_img, $this->_img_l, 0, 0, 0, 0, 573, $img_base_h, 100);
        ImageCopyResized($this->_img, $this->_img_m, 573, 0, 0, 0, ($text_width-573), $img_base_h, 1, $img_base_h);
        ImageCopyMerge($this->_img, $this->_img_r, $text_width-141, 0, 0, 0, 326, $img_base_h, 100);
        ImageTTFText($this->_img, $size, 0, 35, 235, $RGB, $font, $text);
        ImageColorTransparent($this->_img, 1);
        if (file_exists('.'.$path)) {
            unlink('.'.$path);
        }
  //    header("Content-type: image/png"); ImagePNG($this->_img); die;
        ImagePNG($this->_img, '.'.$path);
    }

    public function updateStats($debug = false)
    {
        $start = microtime(true);
        $this->_record = $this->load();
        $this->get_stats();
        $end = microtime(true);
        $result =
            "| "
            .lead(two_dp($end-$start), 6)
            ." | "
            .pad($this->_record['title'] ? $this->_record['title'] : "(None)", 14);
        if ($debug) {
            d($result);
        }
        return $result;
    }

    public function updateAllVisitorStats()
    {
        set_time_limit(3600);
        $start = microtime(true);
        $debug = 0;
        $communities = $this->get_records(SYS_ID, 'title');
        $header =
            "<b>Updating Community Stats:</b>\n"
            ."<pre>"
            .str_repeat("-", 80)
            ."\n"
            ."|   TIME | COMMUNITY\n"
            .str_repeat("-", 80);
        $result = array($header);
        if ($debug) {
            d($header);
        }
        foreach ($communities as $community) {
            $this->_set_ID($community['ID']);
            $result[] = $this->updateStats($debug);
        }
        $end = microtime(true);
        $result[] =
        str_repeat("-", 80)
            ."\n"
            ."Total time taken: ".seconds_to_hhmmss($end-$start).".".substr(($end-$start)-(floor($end-$start)), 2, 3)
            ."</pre>";
        return implode("\n", $result);
    }

}
