<?php
/*
Version History:
  1.0.127 (2016-01-16)
    1) Page::get_css_idx() - formatting tweaks for clearer HTML output
*/
class Page extends Displayable_Item
{
    const VERSION = '1.0.127';
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
            'report' =>                 'pages',
            'report_rename' =>          true,
            'report_rename_label' =>    'new page name',
            'icon_edit' =>              '[ICON]18 18 279 Edit this Page[/ICON]',
            'icon_edit_disabled' =>     '[ICON]18 18 2347 (Edit this Page)[/ICON]',
            'icon_edit_popup' =>        '[ICON]18 18 297 Edit this Page in a popup window[/ICON]'
            )
        );
    }

    private static function _isLanguageCode($page)
    {
        if (strlen($page)!=2) {
            return false;
        }
        $languages = ListType::getListData('lst_iso-639-1');
        $prohibited_names_arr = array_keys($languages);

        return (in_array($page, $prohibited_names_arr));
    }

    private static function _isReservedPageName($page)
    {
        $prohibited_names_arr = explode(", ", SYS_RESERVED_URL_PARTS);

        return (in_array($page, $prohibited_names_arr));
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

    private function do_clone($page_heading_title)
    {
        global $page_vars, $system_vars;
        $isMASTERADMIN =    get_person_permission("MASTERADMIN", $page_vars['group_assign_csv']);
        $isSYSADMIN =       get_person_permission("SYSADMIN", $page_vars['group_assign_csv']);
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER", $page_vars['group_assign_csv']);
        $isSYSEDITOR =      get_person_permission("SYSEDITOR", $page_vars['group_assign_csv']);
        $isSYSMEMBER =      get_person_permission("SYSMEMBER", $page_vars['group_assign_csv']);
        $isSYSLOGON =       get_person_permission("SYSLOGON", $page_vars['group_assign_csv']);
        $isPUBLIC =         get_person_permission("PUBLIC", $page_vars['group_assign_csv']);
        $isVIEWER =         get_person_permission("VIEWER", $page_vars['group_assign_csv']);
        $canEdit =          (
            $page_vars['layoutID']!=2 &&
            ($isMASTERADMIN || ($page_vars['locked']==0 && ($isSYSADMIN || $isSYSEDITOR)))
        );
        $canPublish =       ($page_vars['layoutID']!=2 && ($isMASTERADMIN || $isSYSADMIN || $isSYSAPPROVER));
        $new_page =         get_var('new_page');
        $new_title =        get_var('new_title');
        $parentID =         $page_vars['parentID'];
        $path =             $page_vars['path'];
        $parent_path =      $this->get_parent_path_for_page_vars($page_vars);
        if ($parentID==0 && Page::_isReservedPageName($new_page)) {
            $this->do_tracking("403");
            $reserved_arr = explode(', ', SYS_RESERVED_URL_PARTS);
            sort($reserved_arr);

            return
             $this->draw_toolbar_page_edit(1, 1, 0)
            ."<div class='status_error'><b>Error copying page:</b>\n"
            ."<p>Sorry, \"<b>".$new_page."</b>\" is one of <b>Reserved Names</b>"
            ." that can't be used for the naming of root-level (non-nested) pages.</p>"
            ."<p>The full list is:</p>\n"
            ."<ul style='columns:3; -webkit-columns:3; -moz-columns:3; list-style-type:none; margin:0; padding:0;'>\n"
            ."<li>".implode("</li>\n<li>", $reserved_arr)."</li>\n"
            ."</ul>\n"
            ."<p class='clr_b'>Please choose a different name and try again.</p></div>\n"
            .$this->draw_detail_content($page_heading_title, $page_vars);
        }
        if ($parentID==0 && Page::_isLanguageCode($new_page)) {
            $this->do_tracking("403");

            return
             $this->draw_toolbar_page_edit(1, 1, 0)
            ."<div class='status_error'><b>Error copying page:</b>\n"
            ."<p>Sorry, \"<b>".$new_page."</b>\" is one of the 2-letter codes used for switching "
            ."between different languages.<br />\n"
            ."You can't use these names for root-level (non-nested) pages.</b></p>"
            ."<p>Please choose a different name and try again.</p></div>\n"
            .$this->draw_detail_content($page_heading_title, $page_vars);
        }
        $new_systemID = ($isMASTERADMIN ? get_var('new_systemID') : $page_vars['systemID']);
  //    y($parent_path.$new_page);
        if ($this->get_ID_by_path($parent_path.$new_page.'/', $new_systemID, true)) {
            $this->do_tracking("403");

            return
             $this->draw_toolbar_page_edit(1, 1, 0)
            ."<div class='status_error'><b>Error copying page:</b>\n"
            ."<p>Sorry, a page named \"<b>".$new_page."</b>\" already exists in "
            .($new_systemID==SYS_ID ? "this" : "the specified")." site"
            .($parentID!=0 ? ", and is nested under the same parent page as this one" : "").".</p>"
            ."<p>Please choose a different name and try again.</p></div>\n"
            .$this->draw_detail_content($page_heading_title, $page_vars);
        }
        $layoutID =    $page_vars['layoutID'] ==    $system_vars['defaultLayoutID'] ?     0 : $page_vars['layoutID'];
        $navsuite1ID = $page_vars['navsuite1ID'] == $page_vars['layout']['navsuite1ID'] ? 0 : $page_vars['navsuite1ID'];
        $navsuite2ID = $page_vars['navsuite2ID'] == $page_vars['layout']['navsuite2ID'] ? 0 : $page_vars['navsuite2ID'];
        $navsuite3ID = $page_vars['navsuite3ID'] == $page_vars['layout']['navsuite3ID'] ? 0 : $page_vars['navsuite3ID'];
        $themeID =     $page_vars['themeID'] ==     $system_vars['defaultThemeID'] ?      0 : $page_vars['themeID'];
        $data = array(
            'systemID' =>               $new_systemID,
            'memberID' =>               ($canPublish ? addslashes($page_vars['memberID']) : 0),
            'group_assign_csv' =>       ($canPublish ? addslashes($page_vars['group_assign_csv']) : ''),
            'page' =>                   addslashes($new_page),
            'path_extender' =>          addslashes($page_vars['path_extender']),
            'componentID_pre' =>        addslashes($page_vars['componentID_pre']),
            'componentID_post' =>       addslashes($page_vars['componentID_post']),
            'component_parameters' =>   addslashes($page_vars['component_parameters']),
            'content' =>                addslashes($page_vars['content']),
            'content_text' =>           addslashes($page_vars['content_text']),
            'keywords' =>               addslashes($page_vars['keywords']),
            'include_title_heading' =>  addslashes($page_vars['include_title_heading']),
            'layoutID' =>               addslashes($layoutID),
            'locked' =>                 ($isMASTERADMIN ? $page_vars['locked'] : 0),
            'meta_description' =>       addslashes($page_vars['meta_description']),
            'meta_keywords' =>          addslashes($page_vars['meta_keywords']),
            'navsuite1ID' =>            addslashes($navsuite1ID),
            'navsuite2ID' =>            addslashes($navsuite2ID),
            'navsuite3ID' =>            addslashes($navsuite3ID),
            'parentID' =>               addslashes($page_vars['parentID']),
            'permPUBLIC' =>             ($canPublish ? $page_vars['permPUBLIC'] : 0),
            'permSYSLOGON' =>           ($canPublish ? $page_vars['permSYSLOGON'] : 0),
            'permSYSMEMBER' =>          ($canPublish ? $page_vars['permSYSMEMBER'] : 0),
            'style' =>                  addslashes($page_vars['style']),
            'subtitle' =>               addslashes($page_vars['subtitle']),
            'themeID' =>                addslashes($themeID),
            'title' =>                  addslashes($new_title)
        );
        $newPageID =  $this->insert($data);
        $new_path =     "//".$this->get_path($newPageID);
        $this->_set_ID($newPageID);
        $this->set_field('path', $new_path);
        $submode="";
        if ($newPageID) {
            $this->do_tracking("200");

            return
             $this->draw_toolbar_page_edit(1, 1, 1, $new_page, $newPageID)
            ."<div class='status_okay'><b>Success:</b><br />Copied this page to <b>"
            ."<a href=\"".BASE_PATH.trim($parent_path, '/').'/'.$new_page."\">".$new_page."</a></b></div>\n"
            .$this->draw_detail_content($page_heading_title, $page_vars);
        } else {
            $this->do_tracking("403");

            return
            $this->draw_toolbar_page_edit(1, 1, 0)
            ."<div class='status_error'><b>Error copying page:</b><br />"
            ."A page named <b>".$new_page."</b> already exists in the system.</div>\n"
            .$this->draw_detail_content($page_heading_title, $page_vars);
        }
    }

    public function draw_detail($page)
    {
        global $page_vars, $submode, $system_vars, $print, $new_page, $new_title, $new_systemID, $content, $msg;
        $this->_set_ID($page_vars['ID']);
        $isMASTERADMIN =    get_person_permission("MASTERADMIN", $page_vars['group_assign_csv']);
        $isSYSADMIN =        get_person_permission("SYSADMIN", $page_vars['group_assign_csv']);
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER", $page_vars['group_assign_csv']);
        $isSYSEDITOR =      get_person_permission("SYSEDITOR", $page_vars['group_assign_csv']);
        $isSYSMEMBER =      get_person_permission("SYSMEMBER", $page_vars['group_assign_csv']);
        $isSYSLOGON =        get_person_permission("SYSLOGON", $page_vars['group_assign_csv']);
        $isPUBLIC =            get_person_permission("PUBLIC", $page_vars['group_assign_csv']);
        $isVIEWER =         get_person_permission("VIEWER", $page_vars['group_assign_csv']);
        $isMember = (
        $isMASTERADMIN ||
        $isSYSADMIN ||
        $isSYSAPPROVER ||
        $isSYSEDITOR ||
        $isSYSMEMBER);
        $visible =
        ($page_vars['permPUBLIC'] && $isPUBLIC) ||
        ($page_vars['permSYSLOGON'] && $isSYSLOGON) ||
        ($page_vars['permSYSMEMBER'] && $isMember) ||
        ($isVIEWER);
        $page_heading_title = (isset($page_vars['include_title_heading']) && $page_vars['include_title_heading'] ?
            "<h1 class='title'><a href=\"/".trim($page_vars['path'], '/')."\">"
            .$page_vars['title']
            ."</a></h1>"
        :
            ""
        );

        // layoutID of 2 is for popups
        $canEdit = (
            $page_vars['layoutID']!=2 &&
            ($isMASTERADMIN || ($page_vars['locked']==0 && ($isSYSADMIN || $isSYSEDITOR)))
        );
        $canPublish =   ($page_vars['layoutID']!=2 && ($isMASTERADMIN || $isSYSADMIN || $isSYSAPPROVER));
        if ($page_vars['ID']) {
            if ($canEdit) {
                switch ($submode) {
                    case 'save_page':
                        $Obj_Lang = new Language();
                        $content =  $Obj_Lang->prepare_field('content');
                        $data =
                        array(
                        'content' =>      addslashes($content),
                        'content_text' => addslashes(strip_tags($content))
                        );
                        $Obj = new Page($page_vars['ID']);
                        $Obj->update($data);
                        header("Location: ".BASE_PATH.$page);

                        return;
                    break;
                    case "delete_page":
                        $Obj = new Page($page_vars['ID']);
                        $Obj->delete();
                        header("Location: ".BASE_PATH.$page."?msg=page_deleted");

                        return;
                    break;
                }
            }
            if ($canEdit && $submode=='save_as' && $new_page!="") {
                return $this->do_clone($page_heading_title, $canEdit);
            }
            if ($canEdit && $submode!='save_page' && $submode!='save_as' && $submode!='edit') {
                $this->do_tracking("200");
                return
                ($print!=1 ?
                 $this->draw_toolbar_page_edit(1, 1, 0, '', '')
                : "")
                .$this->draw_detail_content($page_heading_title, $page_vars);
            }
            if ($canEdit && $submode=='edit' && $print!="1") {
                $Obj_RC = new Report_Column();
                $column = $Obj_RC->get_column_for_report('pages', 'content');
                $toolbarSet = $column['formFieldSpecial'];
                $this->do_tracking("200");
                $Obj_FCK =  new FCK();
                $Obj_RC =   new Report_Column();
                return
                 ""
                .$Obj_RC->draw_form_field(
                    array(),
                    'content',
                    $page_vars['content'],
                    'html_multi_language',
                    '100%',
                    '',
                    '',
                    '',
                    0,
                    0,
                    '',
                    $toolbarSet,
                    300
                )
                ."<input type='button' name='save_page' value='Save' class='formButton' style='width: 100px;'"
                ." onclick=\"if (confirm('SAVE CHANGES\\n\\nAre you sure you wish to save changes to this page?\\n"
                ."This change cannot be undone.')) { geid('submode').value='save_page';geid('form').submit();} "
                ."else { alert('SAVE CHANGES\\n\\nNo changes have been saved.'); }\"/>"
                ."<input type='button' class='formButton' value='Cancel'  style='width: 100px;'"
                ." onclick=\"geid('submode').value='';geid('form').submit();\"/><br />";
            }
            if (($canEdit || $canPublish) && $submode=='save_as') {
                $this->do_tracking("200");
                return
                 "<div class='dialog'>"
                .($isMASTERADMIN ?
                     "<div class='clr_b'>\n"
                    ."  <div class='fl' style='width:100px;'>Save ".($page_vars['systemID']=='1' ? "Global page " : "")
                    ."to:&nbsp;</div>\n"
                    ."  <div class='fl'>\n"
                    ."<select name=\"new_systemID\" style=\"width: 255px;\" class='formField'>\n"
                    .draw_select_options(
                        "SELECT `ID` AS `value`,`textEnglish` AS `text` FROM `system` ORDER BY `text`",
                        SYS_ID
                    )
                    ."</select></div>\n"
                    ."</div>\n"
                 :
                    ""
                 )
                ."<div class='clr_b'>\n"
                ."  <div class='fl' style='width:100px;'>New Title:</div>\n"
                ."  <div>".draw_form_field('new_title', $page_vars['title'], 'text', 250)."</div>\n"
                ."</div>"
                ."<div class='clr_b'>\n"
                ."  <div class='fl' style='width:100px;'>New Name:</div>\n"
                ."  <div class='fl'>"
                .draw_form_field('new_page', $page_vars['page'], 'posting_name_unprefixed', 250)
                ."</div>\n"
                ."</div>\n"
                ."<div class='clr_b' style='margin: 0 auto; text-align:center;'>"
                ."<input type='button' name='save_as' value='Save' class='formButton' style='width: 50px;'"
                ." onclick=\"geid('submode').value='save_as';geid('form').submit();\"/>\n"
                ."<input type='button' class='formButton' value='Cancel'  style='width: 50px;'"
                ." onclick=\"geid('submode').value='';geid('form').submit();\"/>\n"
                ."</div>\n"
                ."</div>\n"
                .$this->draw_detail_content($page_heading_title, $page_vars);
            }
            if ($page_vars['ID']) {
                if ($visible) {
                    $this->do_tracking("200");

                    return $this->draw_detail_content($page_heading_title, $page_vars);
                }
                $this->do_tracking("403");

                return draw_html_error_403();
            }
        }
        $page_arr = explode('/', $page);
        switch ($page_arr[0]) {
            case 'checkout':
                if ($system_vars['gatewayID']==1) {
                    $this->do_tracking("403");
                    break;
                }
                $this->do_tracking("200");
                $Obj_HTML =     new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]component_checkout[/ECL]";
            break;
            case "email-opt-in":
                $this->do_tracking("200");
                $ID = sanitize('ID', (isset($page_arr[1]) ? $page_arr[1] : ''));
                $Obj_Mail_Queue_Item = new Mail_Queue_Item($ID);
                $Obj_HTML =     new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]component_email_opt_in[/ECL]";
            break;
            case "email-opt-out":
                $this->do_tracking("200");
                $ID = sanitize('ID', (isset($page_arr[1]) ? $page_arr[1] : ''));
                $Obj_Mail_Queue_Item = new Mail_Queue_Item($ID);
                $Obj_HTML =     new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]component_email_opt_out[/ECL]";
            break;
            case "email-unsubscribe":
                $this->do_tracking("200");
                $ID = sanitize('ID', (isset($page_arr[1]) ? $page_arr[1] : ''));
                $Obj_Mail_Queue_Item = new Mail_Queue_Item($ID);
                $Obj_HTML =     new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]component_email_unsubscribe[/ECL]";
            break;
            case 'emergency_signin':
                $this->do_tracking("200");
                $Obj_CSignin = new Component_Signin();

                return     $Obj_CSignin->draw();
            break;
            case 'forgotten_password':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]component_forgotten_password[/ECL]";
            break;
            case 'manage_profile':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]edit_your_profile[/ECL]";
            break;
            case 'password':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]draw_change_password[/ECL]";
            break;
            case 'paypal_cancel':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]paypal_cancel_repopulate_cart[/ECL]";
            break;
            case 'paypal_return':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]paypal_return_check_payment[/ECL]";
            break;
            case 'signin':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]draw_signin()[/ECL]";
            break;
            case 'signed_in':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
             ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."<h3>Welcome ".get_userFullName()."</h3>\n"
            ."<p>You have signed in with the following rights:\n"
            ."[ECL]draw_rights()[/ECL]"
            ."<p>Click <a href='".BASE_PATH."signin?command=signout'><b>here</b></a> to sign out.</p>\n";

            break;
            case 'sitemap':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]draw_html_sitemap(1)[/ECL]";
            break;
            case 'your_order_history':
                if ($system_vars['gatewayID']==1) {
                    $this->do_tracking("403");
                    break;
                }
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]your_order_history[/ECL]";
            break;
            case 'your_registered_events':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();

                return
            ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>1)) : "")
            ."[ECL]component_your_registered_events[/ECL]";
            break;
        }
        $this->do_tracking("404");
        $Obj_HTML = new HTML();
        switch ($msg) {
            case "page_deleted":
                $status_msg = "<b>Success</b>: the Page ".$page." has been deleted.";
                break;
            default:
                $status_msg = "";
                break;
        }

        return
        ($canPublish ?
        ($status_msg ? HTML::draw_status('form_edit_inpage', $status_msg) : "")
         .$Obj_HTML->draw_toolbar('page_create', array('wasSubstituted'=>0))
        : draw_html_error_404().$page_vars['content']
        );
    }

    public function draw_detail_content($page_heading_title, $page_vars)
    {
        global $msg;
        $isPUBLIC =      get_person_permission("PUBLIC");
        $ratings_allow = $page_vars['ratings_allow']=='all'||($page_vars['ratings_allow']=='registered' && !$isPUBLIC);
        switch ($msg) {
            case "posting_deleted":
                $status_msg = "<b>Success</b>: The requested Posting has been deleted.";
                break;
            default:
                $status_msg = "";
                break;
        }
        $anchor_ID = System::get_item_version('system_family').'_main_content';
        $responsive = $page_vars['layout']['responsive'];
        return
            ($responsive ?
                 ""
              :
                 "<div style=\"visibility:hidden\"><a name=\"".$anchor_ID."\" id=\"".$anchor_ID."\">"
                ."Main content begins here"
                ."</a></div>\r\n"
                .$page_heading_title
                ."<div class='content'>"
             )
            .($status_msg ? HTML::draw_status('form_edit_inpage', $status_msg) : "")
            .($page_vars['componentID_post']!=1 ?
                draw_component($page_vars['componentID_post'])
             :
                $page_vars['content_zones'][0]
             )
            .($responsive ?
                 ""
              :
                 "</div>"
             )
            .(!$responsive && $page_vars['comments_count']>0 ?
                "<a href=\"#anchor_comments_list\">View Comments</a>"
             :
                ""
             )
            .($ratings_allow ? Rating::draw_block() : "")
            .$this->draw_related_block()
            .$this->draw_comments_block($page_vars['comments_allow'])
            ;
    }

    public static function draw_http_error($status)
    {
        global $page,$mode, $report_name, $selectID, $command, $targetID, $print;
        if ($page=='home') {
            $page='';
        }
        $uri_arr = array();
        if ($command!='') {
            $uri_arr[] = "command=$command";
        }
        if ($mode!='') {
            $uri_arr[] = "mode=$mode";
        }
        if ($report_name!='') {
            $uri_arr[] = "report_name=$report_name";
        }
        if ($selectID!='') {
            $uri_arr[] = "selectID=$selectID";
        }
        if ($print!='') {
            $uri_arr[] = "print=$print";
        }
        if ($targetID!='') {
            $uri_arr[] = "targetID=$targetID";
        }
        $uri =  implode('&', $uri_arr);
        $uri =  BASE_PATH.$page.($uri ? "?".$uri : "");
        switch ($status) {
            case "403":
                $title =    "403 Permission Denied";
                $problem =  "Sorry, you are not authorised to access this resource.";
                break;
            case "404":
                $title =    "404 Page not found";
                $problem =  "Sorry, we can't find the page you requested.";
                break;
        }

        return
         "<div style='background-color:#f0f0f0;margin:auto; border:2px solid #000; padding:10px;'>\n"
        ."<h1>".$title."</h1>"
        ."<p><a href=\"".$uri."\">".$uri."</a><br />\n<br />\n"
        .$problem."<br />\n"
        ."<a href='#' onclick=\"history.back();return false;\"><b>Click here</b></a> to go back.</p>\n"
        ."<p>Please contact us if you believe this to be an error.</p>\n"
        ."</div>&nbsp;<br class='clr_b' />";
    }

    public static function draw_html_content($zone = 1)
    {
        $zone =     sanitize('range', $zone, 1, 'n', 1);
        global $mode, $page, $page_vars, $report_name, $ID;
        switch ($mode) {
            case "details":
                return Page::_draw_html_content_draw_details($report_name);
            break;
            case "report":
                return Page::_draw_html_content_draw_report($report_name);
            break;
            case "print_form":
                return Page::_draw_html_content_draw_print_form($report_name);
            break;
            default:
                $posting_prefix_types = Portal::portal_param_get('path_type_prefixed_types');
                foreach ($posting_prefix_types as $_type) {
                    $Obj = new $_type();
                    if ($mode==$Obj->_get_path_prefix()) {
                        $Obj->_set_ID($ID);

                        return Page::_draw_html_content_render(
                            $Obj->draw_detail()
                        );
                    }
                }
                break;
        }
        if ($zone==1) {
            $Obj = new Page();

            return Page::_draw_html_content_render(
                $Obj->draw_detail($page)
            );
        }
        if (isset($page_vars['content_zones'][$zone-1])) {
            return Page::_draw_html_content_render(
                $page_vars['content_zones'][$zone-1]
            );
        }

        return Page::_draw_html_content_render(
            "<!-- Zone ".$zone." is empty -->"
        );
    }

    protected static function _draw_html_content_draw_details($report_name)
    {
        $Obj = new Report_Form();
        $Obj->_set_ID($Obj->get_ID_by_name($report_name));
        $record = $Obj->get_record();
        if ($record===false) {
            $Obj->do_tracking("404");
            header("Status: 404 Not Found", true, 404);

            return Page::_draw_html_content_render(
                Page::draw_http_error('404')
            );
        }
        if (!$Obj->is_visible($record)) {
            $Obj->do_tracking("403");
            if (get_userID()) {
                header("Status: 403 Unauthorised", true, 403);

                return Page::_draw_html_content_render(
                    Page::draw_http_error('403')
                );
            }
            header("Location: ".BASE_PATH."signin");

            return;
        }
        $Obj->do_tracking("200");
        $componentID = $record['formComponentID'];
        if ($componentID!= "1") {
            return Page::_draw_html_content_render(
                draw_component($componentID)
            );
        }

        return Page::_draw_html_content_render(
            draw_auto_form($report_name)
        );
    }

    protected static function _draw_html_content_draw_print_form($report_name)
    {
        global $page_vars;
        $ID = $page_vars['ID'];
        $Obj = new Report();
        return Page::_draw_html_content_render(
            $Obj->draw_print_form($report_name, $ID)
        );
    }

    protected static function _draw_html_content_draw_report($report_name)
    {
        $Obj = new Report_Report();

        return Page::_draw_html_content_render(
            $Obj->draw_by_name($report_name)
        );
    }

    protected static function _draw_html_content_render($html)
    {
        return
        "\n"
        ."<!-- html_content_start -->\n"
        ."<span id='html_content_start'></span>\n"
        .$html."\n"
        ."<!-- html_content_end -->\n";
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

    private function draw_toolbar_page_edit(
        $allowPopupEdit = 0,
        $allowSaveAs = 0,
        $withCopy = 0,
        $newPage = '',
        $newPageID = ''
    ) {
        global $page_vars;
        $Obj = new Page();
        $args =
        array(
        'allowInpageEdit' =>  true,
        'allowPopupEdit' =>   $allowPopupEdit,
        'allowSaveAs' =>      $allowSaveAs,
        'edit_params' =>      $Obj->get_edit_params(),
        'ID' =>               $page_vars['ID'],
        'object_name' =>      $Obj->_get_object_name(),
        'withCopy' =>         $withCopy,
        'newPage' =>          $newPage,
        'newPageID' =>        $newPageID
        );
        $Obj = new HTML();

        return "<div class='context_toolbar noprint'>".$Obj->draw_toolbar('page_edit', $args)."</div>";
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
