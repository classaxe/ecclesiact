<?php
/*
Version History:
  1.0.2 (2016-12-31)
    1) Renamed PageDraw::draw_detail() to PageDraw::drawDetailForPage()
*/
class PageDraw extends Page
{
    const VERSION = '1.0.2';

    private static function isLanguageCode($page)
    {
        if (strlen($page)!=2) {
            return false;
        }
        return in_array(
            $page,
            array_keys(ListType::getListData('lst_iso-639-1'))
        );
    }

    private static function isReservedPageName($page)
    {
        return in_array(
            $page,
            explode(", ", SYS_RESERVED_URL_PARTS)
        );
    }

    private function makeClone($page_heading_title)
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
        if ($parentID==0 && static::isReservedPageName($new_page)) {
            $this->do_tracking("403");
            $reserved_arr = explode(', ', SYS_RESERVED_URL_PARTS);
            sort($reserved_arr);
            return
                 $this->toolbar(1, 1, 0)
                ."<div class='status_error'><b>Error copying page:</b>\n"
                ."<p>Sorry, \"<b>".$new_page."</b>\" is one of <b>Reserved Names</b>"
                ." that can't be used for the naming of root-level (non-nested) pages.</p>"
                ."<p>The full list is:</p>\n"
                ."<ul style='columns:3;-webkit-columns:3;-moz-columns:3;list-style-type:none; margin:0; padding:0;'>\n"
                ."  <li>".implode("</li>\n  <li>", $reserved_arr)."</li>\n"
                ."</ul>\n"
                ."<p class='clr_b'>Please choose a different name and try again.</p></div>\n"
                .$this->draw_detail_content($page_heading_title, $page_vars);
        }
        if ($parentID==0 && static::isLanguageCode($new_page)) {
            $this->do_tracking("403");
            return
                 $this->toolbar(1, 1, 0)
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
             $this->toolbar(1, 1, 0)
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
             $this->toolbar(1, 1, 1, $new_page, $newPageID)
            ."<div class='status_okay'><b>Success:</b><br />Copied this page to <b>"
            ."<a href=\"".BASE_PATH.trim($parent_path, '/').'/'.$new_page."\">".$new_page."</a></b></div>\n"
            .$this->draw_detail_content($page_heading_title, $page_vars);
        } else {
            $this->do_tracking("403");

            return
            $this->toolbar(1, 1, 0)
            ."<div class='status_error'><b>Error copying page:</b><br />"
            ."A page named <b>".$new_page."</b> already exists in the system.</div>\n"
            .$this->draw_detail_content($page_heading_title, $page_vars);
        }
    }

    public function drawDetailForPage($page)
    {
        global $page_vars, $submode, $system_vars, $print, $new_page, $new_title, $new_systemID, $content, $msg;
        $this->_set_ID($page_vars['ID']);
        $isMASTERADMIN =    get_person_permission("MASTERADMIN", $page_vars['group_assign_csv']);
        $isSYSADMIN =       get_person_permission("SYSADMIN", $page_vars['group_assign_csv']);
        $isSYSAPPROVER =    get_person_permission("SYSAPPROVER", $page_vars['group_assign_csv']);
        $isSYSEDITOR =      get_person_permission("SYSEDITOR", $page_vars['group_assign_csv']);
        $isSYSMEMBER =      get_person_permission("SYSMEMBER", $page_vars['group_assign_csv']);
        $isSYSLOGON =       get_person_permission("SYSLOGON", $page_vars['group_assign_csv']);
        $isPUBLIC =         get_person_permission("PUBLIC", $page_vars['group_assign_csv']);
        $isVIEWER =         get_person_permission("VIEWER", $page_vars['group_assign_csv']);
        $isMember =         ($isMASTERADMIN || $isSYSADMIN || $isSYSAPPROVER || $isSYSEDITOR || $isSYSMEMBER);
        $visible =
            ($page_vars['permPUBLIC'] && $isPUBLIC) ||
            ($page_vars['permSYSLOGON'] && $isSYSLOGON) ||
            ($page_vars['permSYSMEMBER'] && $isMember) ||
            ($isVIEWER);
        // layoutID of 2 is for popups
        $canEdit = (
            $page_vars['layoutID']!=2 &&
            ($isMASTERADMIN ||
            ($page_vars['locked']==0 && $page_vars['systemID']==SYS_ID && ($isSYSADMIN || $isSYSEDITOR))
            )
        );
        $canPublish =   ($page_vars['layoutID']!=2 && ($isMASTERADMIN || $isSYSADMIN || $isSYSAPPROVER));
        $page_heading_title = (isset($page_vars['include_title_heading']) && $page_vars['include_title_heading'] ?
            "<h1 class='title'><a href=\"/".trim($page_vars['path'], '/')."\">"
            .$page_vars['title']
            ."</a></h1>"
        :
            ""
        );
        if ($page_vars['ID']) {
            if ($canEdit) {
                switch ($submode) {
                    case 'save_page':
                        $Obj_Lang = new Language();
                        $content =  $Obj_Lang->prepare_field('content');
                        $data = array(
                            'content' =>      addslashes($content),
                            'content_text' => addslashes(strip_tags($content))
                        );
                        $Obj = new Page($page_vars['ID']);
                        $Obj->update($data);
                        header("Location: ".BASE_PATH.$page);
                        return;
                        break;
                    case "delete_page":
                        if ($isMASTERADMIN || ($isSYSAPPROVER && $page_vars['systemID']==SYS_ID)) {
                            $Obj = new Page($page_vars['ID']);
                            $Obj->delete();
                            header("Location: ".BASE_PATH.$page."?msg=page_deleted");
                            return;
                        }
                        break;
                }
            }
            if ($canEdit && $submode=='save_as' && $new_page!="") {
                return $this->makeClone($page_heading_title, $canEdit);
            }
            if ($canEdit && $submode!='save_page' && $submode!='save_as' && $submode!='edit') {
                $this->do_tracking("200");
                return
                    ($print!=1 ?
                     $this->toolbar(1, 1, 0, '', '')
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
                        ."  <div class='fl' style='width:100px;'>Save "
                        .($page_vars['systemID']=='1' ? "Global page " : "")
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
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]component_checkout[/ECL]";
            break;
            case "email-opt-in":
                $this->do_tracking("200");
                $ID = sanitize('ID', (isset($page_arr[1]) ? $page_arr[1] : ''));
                $Obj_Mail_Queue_Item = new Mail_Queue_Item($ID);
                $Obj_HTML =     new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]component_email_opt_in[/ECL]";
            break;
            case "email-opt-out":
                $this->do_tracking("200");
                $ID = sanitize('ID', (isset($page_arr[1]) ? $page_arr[1] : ''));
                $Obj_Mail_Queue_Item = new Mail_Queue_Item($ID);
                $Obj_HTML =     new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]component_email_opt_out[/ECL]";
            break;
            case "email-unsubscribe":
                $this->do_tracking("200");
                $ID = sanitize('ID', (isset($page_arr[1]) ? $page_arr[1] : ''));
                $Obj_Mail_Queue_Item = new Mail_Queue_Item($ID);
                $Obj_HTML =     new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
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
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]component_forgotten_password[/ECL]";
            break;
            case 'manage_profile':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]edit_your_profile[/ECL]";
            break;
            case 'password':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]draw_change_password[/ECL]";
            break;
            case 'paypal_cancel':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]paypal_cancel_repopulate_cart[/ECL]";
            break;
            case 'paypal_return':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]paypal_return_check_payment[/ECL]";
            break;
            case 'signin':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]draw_signin()[/ECL]";
            break;
            case 'signed_in':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."<h3>Welcome ".get_userFullName()."</h3>\n"
                    ."<p>You have signed in with the following rights:\n"
                    ."[ECL]draw_rights()[/ECL]"
                    ."<p>Click <a href='".BASE_PATH."signin?command=signout'><b>here</b></a> to sign out.</p>\n";
            break;
            case 'sitemap':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
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
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
                    ."[ECL]your_order_history[/ECL]";
            break;
            case 'your_registered_events':
                $this->do_tracking("200");
                $Obj_HTML = new HTML();
                return
                     ($canPublish ? $Obj_HTML->draw_toolbar('page_create', array('wasSubstituted' => 1)) : "")
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
            .($ratings_allow ? $this->draw_ratings_block() : "")
            .$this->draw_related_block()
            .$this->draw_comments_block($page_vars['comments_allow'])
            ;
    }

    public static function draw_html_content($zone = 1)
    {
        $zone =     sanitize('range', $zone, 1, 'n', 1);
        global $mode, $page, $page_vars, $report_name, $ID;
        switch ($mode) {
            case "details":
                return static::drawContent($report_name);
            break;
            case "report":
                $Obj = new Report_Report();
                return static::render(
                    $Obj->draw_by_name($report_name)
                );
            break;
            case "print_form":
                $Obj = new Report();
                return static::render(
                    $Obj->draw_form_view($report_name, $page_vars['ID'], true, true, false)
                );
            break;
            default:
                $posting_prefix_types = Portal::portal_param_get('path_type_prefixed_types');
                foreach ($posting_prefix_types as $_type) {
                    $Obj = new $_type();
                    if ($mode==$Obj->_get_path_prefix()) {
                        $Obj->_set_ID($ID);

                        return static::render(
                            $Obj->draw_detail()
                        );
                    }
                }
                break;
        }
        if ($zone==1) {
            $Obj = new static();

            return static::render(
                $Obj->drawDetailForPage($page)
            );
        }
        if (isset($page_vars['content_zones'][$zone-1])) {
            return static::render(
                $page_vars['content_zones'][$zone-1]
            );
        }

        return static::render(
            "<!-- Zone ".$zone." is empty -->"
        );
    }

    protected static function drawContent($report_name)
    {
        $Obj = new Report_Form();
        $Obj->_set_ID($Obj->get_ID_by_name($report_name));
        $record = $Obj->get_record();
        if ($record===false) {
            $Obj->do_tracking("404");
            header("Status: 404 Not Found", true, 404);

            return static::render(
                HttpError::draw('404')
            );
        }
        if (!$Obj->is_visible($record)) {
            $Obj->do_tracking("403");
            if (get_userID()) {
                header("Status: 403 Unauthorised", true, 403);

                return static::render(
                    HttpError::draw('403')
                );
            }
            header("Location: ".BASE_PATH."signin");

            return;
        }
        $Obj->do_tracking("200");
        $componentID = $record['formComponentID'];
        if ($componentID!= "1") {
            return static::render(
                draw_component($componentID)
            );
        }

        return static::render(
            draw_auto_form($report_name)
        );
    }

    protected static function render($html)
    {
        return
             "\n"
            ."<!-- html_content_start -->\n"
            ."<span id='html_content_start'></span>\n"
            .$html."\n"
            ."<!-- html_content_end -->\n";
    }

    private function toolbar(
        $allowPopupEdit = 0,
        $allowSaveAs = 0,
        $withCopy = 0,
        $newPage = '',
        $newPageID = ''
    ) {
        $args = array(
            'allowInpageEdit' =>  true,
            'allowPopupEdit' =>   $allowPopupEdit,
            'allowSaveAs' =>      $allowSaveAs,
            'edit_params' =>      $this->get_edit_params(),
            'ID' =>               $this->_get_ID(),
            'object_name' =>      $this->_get_object_name(),
            'withCopy' =>         $withCopy,
            'newPage' =>          $newPage,
            'newPageID' =>        $newPageID
        );
        $Obj = new HTML();
        return
             "<div class='context_toolbar noprint'>"
            .$Obj->drawToolbar('page_edit', $args)
            ."</div>";
    }
}
