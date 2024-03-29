<?php
/*
Custom Fields used:
custom_1 = denomination (must be as used in other SQL-based controls)
*/
/*
Version History:
  1.0.64 (2022-08-15)
    1) Changes to Community_Member_Display functions for parameters to use in other places
*/
class Community_Member_Display extends Community_Member
{
    const VERSION = '1.0.64';

    protected $_events =                        [];
    protected $_events_christmas =              [];
    protected $_events_easter =                 [];
    protected $_events_special =                [];
    protected $_nav_prev =                      false;
    protected $_nav_next =                      false;
    protected $_Obj_Community;
    protected $_Obj_System;
    protected $_sponsors_national_records =     [];
    protected $_sponsors_local_records =        [];
    protected $_sponsors_national_container =   '';
    protected $_stats;

    public function draw($cp, $member_extension)
    {
        $this->setupInitial($cp, $member_extension);
        if ($this->_print) {
            $Obj = new Community_Member_Summary;
            return $Obj->draw($cp, $member_extension);
        }
        if ($this->_member_page) {
            $Obj = new Community_Member_Resource;
            return $Obj->draw($cp, $member_extension);
        }
        $this->setup($cp);
        $this->drawJs();
        $this->drawCss();
        $this->drawTitle();
        $this->drawCommunityNavigation();
        $this->drawSectionTabButtons();
        $this->drawFrameOpen();
        $this->drawSectionContainerOpen();
        $this->drawProfile();
        $this->drawMembers();
        $this->drawMap();
        $this->drawContact();
        $this->drawArticles();
        $this->drawEvents();
        $this->drawEventsChristmas();
        $this->drawEventsEaster();
        $this->drawEventsSpecial();
        $this->drawCalendar();
        $this->drawNews();
        $this->drawPodcasts();
        $this->drawStats();
        $this->drawAbout();
        $this->drawSectionContainerClose();
        $this->drawFrameClose();
        return $this->_html;
    }

    protected function drawCss()
    {
        Output::push(
            'style_include',
            "<link rel=\"stylesheet\" type=\"text/css\""
            ." href=\"/css/community/".System::get_item_version('css_community')."\" />"
        );
        $css =
             ".profile_frame .member_slideshow                { width: "
            .(($this->_cp['profile_photo_width'])+20)."px; }\n"
            .".profile_frame .photo_frame .member_photo_frame { width: "
            .(($this->_cp['profile_photo_width']))."px; height:".(($this->_cp['profile_photo_height']))."px; }\n";
        Output::push('style', $css);
    }

    protected function drawJs()
    {
        $selected_section = (get_var('selected_section') ?
            get_var('selected_section')
        :
            $this->_section_tabs_arr[0]['ID']
        );
        Output::push(
            'javascript_onload',
            "  show_section_onhashchange_setup(spans_".$this->_safe_ID.");\n"
            ."  window.setTimeout(\n"
            ."    \"var tab='".$selected_section."';"
            .(get_var('anchor') ? "" : "if(document.location.hash){tab=document.location.hash.substr(1);};")
            ."show_section_tab(spans_".$this->_safe_ID.",tab);\",\n"
            ."    500\n"
            ."  );\n"
        );
    }

    protected function drawAbout()
    {
        if (!$this->_cp['show_about']) {
            return;
        }
        $Obj_Page =         new Page;
        $this->_pageID =    $Obj_Page->get_ID_by_path('//'.trim($this->_cp['template_member_page'], '/').'/');
        $Obj_Page->_set_ID($this->_pageID);
        $content =          $Obj_Page->get_field('content');
        $this->_html.=
             HTML::drawSectionTabDiv('about', $this->_selected_section)
            ."<div class='inner'>"
            ."<h2>"
            .($this->_current_user_rights['canEdit'] && $this->_pageID ?
               "<a"
              ." href=\"".BASE_PATH.'details/'.$this->_edit_form['pages'].'/'.$this->_pageID.'"'
              ." onclick=\"details('".$this->_edit_form['pages']."','".$this->_pageID."',"
              ."'".$this->_popup['pages']['h']."','".$this->_popup['pages']['w']."');return false;\""
              .">".$this->_cp['label_about']."</a>"
            :
               $this->_cp['label_about']
            )
            ."</h2>\n"
            .($this->_cp['header_about'] ? "<div class='section_header'>".$this->_cp['header_about']."</div>\n" : "")
            .($this->_pageID ?
                $this->drawAboutItems($content)
            :
                 "<b>Error </b>: The 'About' section template page //".trim($this->_cp['template_member_page'], '/')."/"
                ." wasn't found."
            )
            ."<div class='clear'>&nbsp;</div>"
            ."<div class='section_footer'>".$this->_cp['footer_about']."</div>"
            ."</div>"
            ."</div>";
    }

    protected function drawAboutItems($content)
    {
        $replace = array(
            '[[COMMUNITY_NAME]]' =>     $this->_community_record['name'],
            '[[COMMUNITY_TITLE]]' =>    $this->_community_record['title'],
            '[[COMMUNITY_URL]]' =>      $this->_community_record['URL_external'],
            '[[MEMBER_TITLE]]' =>       $this->_record['title'],
            '[[MEMBER_URL]]' =>
                 BASE_PATH.trim($this->_community_record['URL'], '/').'/'
                .trim($this->_record['name'], '/'),
            '[[SPONSORS_LOCAL]]' =>     $this->drawSponsorsLocal(),
            '[[SPONSORS_NATIONAL]]' =>  $this->drawSponsorsNational()
        );
        return strtr($content, $replace);
    }

    protected function drawArticles()
    {
        if (!$this->_cp['show_articles'] || !$this->_record['full_member']) {
            return;
        }
        $Obj = new Community_Member_Article;
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $Obj->community_URL =   $this->_community_record['URL'];
        $args = array(
            'author_show' =>          $this->_cp['listing_show_author'],
            'category_show' =>        $this->_cp['listing_show_category'],
            'content_char_limit' =>   $this->_cp['listing_content_char_limit'],
            'content_plaintext' =>    $this->_cp['listing_content_plaintext'],
            'content_show' =>         $this->_cp['listing_show_content'],
            'results_limit' =>        $this->_cp['listing_results_limit'],
            'results_paging' =>       $this->_cp['listing_results_paging'],
            'thumbnail_height' =>     $this->_cp['listing_thumbnail_height'],
            'thumbnail_show' =>       $this->_cp['listing_show_thumbnails'],
            'thumbnail_width' =>      $this->_cp['listing_thumbnail_width']
        );
        $this->_html.=
            HTML::drawSectionTabDiv('articles', $this->_selected_section)
            ."<div class='inner'>"
            .$this->drawWebShare('articles', 'articles')
            ."<h2>Articles for ".$this->_record['title']."</h2>"
            .$Obj->draw_listings('member_articles', $args, false)
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawCalendar()
    {
        if (!$this->_cp['show_calendar'] ||
            !($this->_record['full_member'] ||
            $this->_record['primary_ministerialID'])
        ) {
            return;
        }
        $Obj = new \Component\CommunityMemberCalendar;
        $Obj->communityID = $this->_record['communityID'];
        $Obj->community_record = $this->_community_record;
        $Obj->memberID = $this->_record['ID'];
        $Obj->partner_csv = $this->_record['partner_csv'];
        $args = array(
            'show_controls' => 0,
            'show_heading' => 0
        );
        $this->_html.=
             HTML::drawSectionTabDiv('calendar', $this->_selected_section)
            ."<div class='inner'>"
            .$this->drawWebShare('events', 'calendar')
            ."<h2>Monthly Calendar for ".$this->_record['title']."</h2>"
            .$Obj->draw('community_member', $args, false)
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawCommunityNavigation()
    {
        global $page_vars;
        $this->_html.=
             "<div class='profile_nav_outer'>"
            ."<img src='".BASE_PATH."img/icon/2600/11' alt='Member Profile'"
            ." style='vertical-align:middle;margin-right: 2px;'/>"
            ."Use these controls to navigate around community."
            ."<div class='profile_nav'>\n"
            ."<input type=\"button\" class=\"form_button\" style=\"width:5em;text-align:left\""
            ." title=\"View ".$this->_nav_prev['title']."\" value=\"&lt; Prev\""
            ." onclick=\"document.location='".BASE_PATH.trim($page_vars['relative_URL'], '/')."/"
            .$this->_nav_prev['name']."'+document.location.hash\" />\n"
            ."<input type=\"button\" class=\"form_button\" style=\"width:5em;\""
            ." title=\"View entire ".$this->_Obj_Community->record['title']." community\" value=\"Up\""
            ." onclick=\"document.location='".BASE_PATH.trim($page_vars['relative_URL'], '/')."'\" />\n"
            ."<input type=\"button\" class=\"form_button\" style=\"width:5em;text-align:right\""
            ." title=\"View ".$this->_nav_next['title']."\" value=\"Next &gt;\""
            ." onclick=\"document.location='".BASE_PATH.trim($page_vars['relative_URL'], '/')."/"
            .$this->_nav_next['name']."'+document.location.hash\" />\n"
            ."</div>"
            ."</div>\n";
    }

    protected function drawContact()
    {
        if (!$this->_cp['show_contact']) {
            return;
        }
        $this->drawContactFormSetup();
        $office =   $this->drawAddress('office_addr_');
        $mailing =  $this->drawAddress('mailing_addr_');
        $phone =    $this->drawOfficePhone($this->_record);
        $notes =    $this->drawOfficeNotes($this->_record);
        $hours =    $this->drawOfficeHours($this->_record);
        $this->_html.=
             HTML::drawSectionTabDiv('contact', $this->_selected_section)
            ."<div class='inner'>"
            ."<h2>Contact ".htmlentities($this->_record['title'])."</h2><br />\n"
            ."<div class='addresses noref'>\n"
            .($phone || $notes || $hours ? "<div>" : "")
            .($phone ? "<h3>Telephone</h3>".$phone."\n" : "")
            .($notes ? "<h3>Notes</h3>".$notes."\n" : "")
            .($hours ? "<h3>Office Hours</h3>".$hours."\n" : "")
            .($phone || $notes || $hours ? "</div>" : "")
            .($office ? "<div><h3>Office Address</h3>".$office."</div>\n" : "")
            .($mailing && $mailing!=$office ? "<div class='addr'><h3>Mailing Address</h3>".$mailing."</div>\n" : "")
            ."</div>\n";
        if (count($this->_contacts)) {
            $this->_html.=
                 "<hr />\n"
                .HTML::draw_status('contact_form_status', $this->_msg);
        }
        $this->drawContactForm();
        $this->_html.=
             "</div>\n"
            ."</div>\n";
    }

    protected function drawContactForm()
    {
        global $page_vars;
        if (!count($this->_contacts)) {
            return;
        }
        if ($this->submode == "community_member_contact_sent") {
            $this->drawContactFormResult();
            return;
        }
        $this->drawContactFormJs();
        $this->drawContactFormHtml();
    }

    protected function drawContactFormHtml()
    {
        $width = 400;
        $this->_html.=
             "<div class='contact_form'>\n"
            ."<h2>Send us a Message:</h2>\n"
            .Report_Column::draw_label('Message to:', '', 'contact_send_to', 200)
            .draw_form_field(
                'contact_send_to',
                $this->contact_send_to,
                'selector_csvlist',
                $width,
                '',
                '',
                '',
                '',
                '',
                '',
                $this->_contacts_csv
            )
            ."<br class='clr_b' />"
            .Report_Column::draw_label('Your Name:', '', 'contact_sender_name', 200)
            .draw_form_field(
                'contact_sender_name',
                $this->contact_sender_name,
                'text',
                $width,
                '',
                '',
                " onkeypress=\"return keytest_enter_execute(event,function(){geid('contact_sender_email').focus();})\""
            )
            ."<br class='clr_b' />"
            .Report_Column::draw_label('Your Email:', '', 'contact_sender_email', 200)
            .draw_form_field(
                'contact_sender_email',
                $this->contact_sender_email,
                'text',
                $width,
                '',
                '',
                " onkeypress=\"return keytest_enter_execute(event,function(){geid('contact_message').focus();})\""
            )
            ."<br class='clr_b' />"
            .Report_Column::draw_label('Your Message:', '', 'contact_message', 200)
            .draw_form_field(
                'contact_message',
                $this->contact_message,
                'textarea',
                $width,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                150
            )
            ."<br class='clr_b' />"
            .Report_Column::draw_label('Verification Image:', '', 'captcha_key', 200)
            ."<img class='formField std_control' style='border:1px solid #7F9DB9;padding:2px;'"
            ." src='".BASE_PATH."?command=captcha_img' alt='Verification Image' />"
            ."<br class='clr_b' />"
            .Report_Column::draw_label('Verification Code:', '', 'captcha_key', 200)
            .draw_form_field(
                'captcha_key',
                '',
                'text',
                182,
                '',
                '',
                " onkeypress=\"keytest_enter_execute(event,function(){geid('contact_form_send').focus();})\""
            )
            ."<br class='clr_b' />"
            ."<p class='txt_c'>\n"
            ."  <input type='button' id='contact_form_send' value='Send Message'"
            ." onclick='return contact_form_verify();'/>\n"
            ."</p>\n"
            ."</div>";
    }

    protected function drawContactFormJs()
    {
        $js =
             "function email_check(val){\n"
            ."  return !(val.length<6 || val.indexOf('@')<1 || val.lastIndexOf('.')-2<val.lastIndexOf('@'));\n"
            ."}\n"
            ."function contact_form_verify(){\n"
            ."  var err_arr = [];\n"
            ."  var n = 1;\n"
            ."  if (geid_val(\"contact_send_to\")=='0') {\n"
            ."    err_arr.push((n++)+\") Message To\");\n"
            ."  }\n"
            ."  if (geid_val(\"contact_sender_name\")=='') {\n"
            ."    err_arr.push((n++)+\") Your Name\");\n"
            ."  }\n"
            ."  if (!email_check(geid_val(\"contact_sender_email\"))) {\n"
            ."    err_arr.push((n++)+\") Your Email\");\n"
            ."  }\n"
            ."  if (geid_val(\"contact_message\")=='') {\n"
            ."    err_arr.push((n++)+\") Your Message\");\n"
            ."  }\n"
            ."  if (geid_val(\"captcha_key\").length!=6) {\n"
            ."    err_arr.push((n++)+\") Characters shown in the image (slows down spammers!)\");\n"
            ."  }\n"
            ."  var err = err_arr.join('\\n');\n"
            ."  if (err==''){\n"
            ."    geid_set('submode','community_member_contact');\n"
            ."    geid('form').submit();\n"
            ."    return;\n"
            ."  }\n"
            ."  alert(\n"
            ."    '-----------------------\\n'+\n"
            ."    'Attention Required\\n'+\n"
            ."    '-----------------------\\n'+\n"
            ."    'The following required fields were not provided:\\n'+\n"
            ."    err +\n"
            ."    '\\n\\nPress [OK] to continue.'\n"
            ."  );\n"
            ."}\n";
        Output::push('javascript', $js);
    }

    protected function drawContactFormResult()
    {
        $this->_html.=
             "<div class='contact_form'>\n"
            ."<h1 style='margin: 0.25em 0em'>You Sent us a Message:</h1>\n"
            .Report_Column::draw_label('Message to:', '', 'contact_send_to', 200)
            ."<div class='fl'>".$this->contact_recipient_name."</div>\n"
            ."<div class='clr_b'></div>"
            .Report_Column::draw_label('Your Name:', '', 'contact_sender_name', 200)
            ."<div class='fl'>".$this->contact_sender_name."</div>\n"
            ."<div class='clr_b'></div>"
            .Report_Column::draw_label('Your Email:', '', 'contact_sender_email', 200)
            ."<div class='fl'>".$this->contact_sender_email."</div>"
            ."<div class='clr_b'></div>"
            .Report_Column::draw_label('Your Message:', '', 'contact_message', 200)
            ."<div class='fl' style='width:400px'>".nl2br($this->contact_message)."</div>"
            ."<div class='clr_b'></div>"
            ."<p class='txt_c'>"
            ."<input type='button' value='Done'"
            ." onclick=\"document.location='".BASE_PATH.trim($this->_community_record['URL'], '/').'/'
            .trim($this->_record['name'], '/')."#contact'\" />"
            ."</p>\n"
            ."</div>";
    }

    protected function drawContactFormProcess()
    {
        switch ($this->submode) {
            case "community_member_contact":
                $Obj_Captcha = new Captcha;
                if (!$Obj_Captcha->isKeyRight(isset($_POST['captcha_key']) ? $_POST['captcha_key'] : "NOWAY")) {
                    $this->_msg =
                         "<span style='color:red'><b>Error</b>:"
                        ." You must enter the same characters shown in the image.</span>";
                    break;
                }
                $this->contact_recipient_email = "";
                foreach ($this->_contacts as $contact) {
                    if ($contact['idx']==$this->contact_send_to) {
                        $this->contact_recipient_email = $contact['email'];
                        $this->contact_recipient_name =  $contact['name'];
                        break;
                    }
                }
                $subject =
                     "Website contact via ".$this->_record['title']." church profile at "
                    .$this->_community_record['URL_external']."/".$this->_record['name'].'#contact';
                $message_html =
                     "<table cellspacing='0' cellpadding='2' border='1'>"
                    ."<tr>"
                    ."<td valign='top'><b>Subject:</b></td>"
                    ."<td valign='top'>"
                    ."Website contact via ".$this->_record['title']." church profile at "
                    ."Churches in ".$this->_community_record['title']
                    ."</td>"
                    ."</tr>"
                    ."<tr>"
                    ."<td valign='top'><b>Source:</b></td>"
                    ."<td valign='top'>"
                    .$this->_community_record['URL_external']."/".$this->_record['name']."#contact"
                    ."</td>"
                    ."</tr>"
                    ."<tr>"
                    ."<td valign='top'><b>Sender:</b></td>"
                    ."<td valign='top'>".$this->contact_sender_name." &lt;".$this->contact_sender_email."&gt;</td>"
                    ."</tr>\n"
                    ."<tr>"
                    ."<td valign='top'><b>Message:</b></td>"
                    ."<td valign='top'>".strip_tags($this->contact_message)."</td>"
                    ."</tr>"
                    ."</table>";
                $message_text =
                     "Subject: Website contact via ".$this->_record['title']." church profile at "
                    ."Churches in ".$this->_community_record['title']."\n"
                    ."Source: "
                    .$this->_community_record['URL_external']."/".$this->_record['name']."#contact\n"
                    ."Sender: ".$this->contact_sender_name." <".$this->contact_sender_email.">\n"
                    ."Message: ".strip_tags($this->contact_message)."\n";
                get_mailsender_to_component_results();      // Use system default mail sender details
                $data = array(
                    'NName' =>            $this->contact_recipient_name.' <'.$this->contact_recipient_email.'>',
                    'PEmail' =>           $this->contact_recipient_email,
                    'bcc_email' =>        'info@churchesinyourtown.ca',
                    'replyto_email' =>    $this->contact_sender_email,
                    'replyto_name' =>     $this->contact_sender_name,
                    'subject' =>          strip_tags($subject),
                    'html' =>             nl2br($message_html),
                    'text' =>             wordwrap(html_entity_decode(strip_tags($message_text)))
                );
                if ($this->_current_user_rights['isEditor']) {
                    $data['cc_email'] = $this->contact_sender_email;
                    $data['cc_name'] =  $this->contact_sender_name.' <'.$this->contact_sender_email.'>';
                }
                $mail_result =              mailto($data);
                if (substr($mail_result, 0, 12)=="Message-ID: ") {
                    $this->_msg = "<b>Success:</b> Your message has been sent.";
                    $this->submode = "community_member_contact_sent";
                } else {
                    $this->_msg = "<b>Error:</b> ".$mail_result;
                }
                break;
        }
    }

    protected function drawContactFormSetup()
    {
        if (!count($this->_contacts)) {
            return;
        }
        $this->submode =                get_var('submode');
        $this->contact_send_to =        get_var('contact_send_to');
        $this->contact_sender_email =   get_var('contact_sender_email');
        $this->contact_sender_name =    get_var('contact_sender_name');
        $this->contact_message =        get_var('contact_message');
        $this->msg = "";
        $this->drawContactFormProcess();
        $this->drawContactFormGetContactsCsv();
        if ($personID = get_userID()) {
            $Obj_User =     new User($personID);
            $Obj_User->load();
            $this->contact_sender_email = ($this->contact_sender_email ?
                $this->contact_sender_email
             :
                $Obj_User->record['PEmail']
            );
            $this->contact_sender_name =  ($this->contact_sender_name ?
                $this->contact_sender_name
             :
                 $Obj_User->record['NFirst']
                .($Obj_User->record['NMiddle'] ? " ".$Obj_User->record['NMiddle'] : "")
                .($Obj_User->record['NLast'] ? " ".$Obj_User->record['NLast'] : "")
            );
        }
    }

    protected function drawContactFormGetContactsCsv()
    {
        if (!count($this->_contacts)) {
            return "";
        }
        $out = array();
        if (count($this->_contacts)>1) {
            array_unshift(
                $this->_contacts,
                array('idx' => 0, 'name' => '(Please choose a name from the list)', 'bgcolor' => 'd0d0d0')
            );
        }
        foreach ($this->_contacts as $contact) {
            $out[] = $contact['idx']."|".str_replace(',', '&comma;', $contact['name'])."|a0ffa0";
        }
        $this->_contacts_csv = implode(',', $out);
    }

    protected function drawContextMenuMember($record)
    {
        if (!$this->_current_user_rights['canEdit']) {
            return;
        }
        return
             " onmouseover=\""
            ."if(!CM_visible('CM_community_member')) {"
            ."this.style.backgroundColor='"
            .($record['systemID']==SYS_ID ? '#ffff80' : '#ffe0e0')
            ."';"
            ."_CM.type='community_member';"
            ."_CM.ID='".$record['ID']."';"
            ."_CM.full_member=".($record['full_member']=='1' ? '1' : '0').";"
            ."_CM.ministerial_member=".($record['primary_ministerialID'] ? '1' : '0').";"
            ."_CM_text[0]='&quot;".str_replace(array("'","\""), '', htmlentities($record['title']))."&quot;';"
            ."_CM.path='".$record['member_URL']."';"
            ."}\""
            ." onmouseout=\"this.style.backgroundColor='';_CM.type=''\"";
    }

    protected function drawFrameOpen()
    {
        $this->_html.="<div class='profile_frame'>";
    }

    protected function drawFrameClose()
    {
        $this->_html.="</div>";
    }

    protected function drawAddress($prefix)
    {
        $r = $this->_record;
        if ($r[$prefix.'line1']=='' &&
            $r[$prefix.'line2']=='' &&
            $r[$prefix.'city']=='' &&
            $r[$prefix.'sp']=='' &&
            $r[$prefix.'postal']==''
        ) {
            $prefix = 'service_addr_';
        }
        if (!trim($r[$prefix.'line1'].$r[$prefix.'line2'].$r[$prefix.'city'].$r[$prefix.'sp'].$r[$prefix.'postal'])) {
            return;
        }
        return
             "<p style='margin:0 0 2em 1em;'>\n"
            .$r[$prefix.'line1']."<br />"
            .($r[$prefix.'line2'] ? $r[$prefix.'line2']."<br />" : "")
            .$this->_record[$prefix.'city']."<br />"
            .($r[$prefix.'sp'] ? $r[$prefix.'sp']."<br />" : "")
            .($r[$prefix.'postal'] ? $r[$prefix.'postal'] : "")
            ."</p>";
    }


    protected function drawMap()
    {
        if (!$this->_cp['show_map']) {
            return;
        }
        if ($this->_record['service_map_lat']==0 && $this->_record['service_map_lon']==0) {
            return;
        }
        $this->_html.=
             HTML::drawSectionTabDiv('map', $this->_selected_section)
            ."<div class='inner noref'>"
            ."<h2>Map for ".htmlentities($this->_record['title'])."</h2>\n";
        $Obj_Map =      new Google_Map('community_member', SYS_ID);
        $Obj_Map->map_centre(
            $this->_record['service_map_lat'],
            $this->_record['service_map_lon'],
            $this->_cp['profile_map_zoom']
        );
        $img =
        ($this->_record['featured_image'] && file_exists('.'.$this->_record['featured_image']) ?
            $this->_record['featured_image']
        :
            '/640x480-photo-unavailable.png'
        );
        $featured_image =
             BASE_PATH
            ."img/width/"
            .$this->_cp['profile_map_photo_width'].$img;
            $Obj_Map->add_icon("/UserFiles/Image/map_icons/".$this->_record['type']."/", $this->_record['type']);
            $marker_html =
            "<img style='float:left;margin:0 4px 4px 0;border:1px solid #888'"
            ." width='".$this->_cp['profile_map_photo_width']."'"
            ." src='".$featured_image."' alt='".$this->_record['name']."'>"
            ."<div>"
            ."<strong>".htmlentities($this->_record['title'])."</strong><br />"
            .$this->_record['service_addr_line1']."<br />"
            .($this->_record['service_addr_line2'] ? $this->_record['service_addr_line2']."<br />" : '')
            ."<br />"
            .$this->_record['service_addr_city'].' &bull; '
            .$this->_record['service_addr_sp'].' &bull; '
            .$this->_record['service_addr_postal'];
        $Obj_Map->add_marker_with_html(
            $this->_record['service_map_lat'],
            $this->_record['service_map_lon'],
            $marker_html,
            $this->_record['ID'],
            $this->_current_user_rights['canEdit'],
            true,
            $this->_record['type'],
            htmlentities($this->_record['title']),
            'Click for Info'
        );
        $Obj_Map->add_control_type();
        $Obj_Map->add_control_large();
        $args =     array(
            'map_width'=>$this->_cp['width']-10,
            'map_height'=>$this->_cp['profile_map_height']
        );
        $this->_html.=
            $Obj_Map->draw($args)
            ."</div>\n"
            ."<div class='clear'>&nbsp;</div>"
            ."</div>\n";
    }

    protected function drawMembers()
    {
        if (!$this->_record['type']=='ministerium') {
            return;
        }
        $entries = array();
        foreach ($this->_members as $r) {
            if ($r['primary_ministerialID']==$this->_record['ID']) {
                $entries[] = $r;
            }
        }
        if (!count($entries)) {
            return;
        }
        $this->_html.=
             HTML::drawSectionTabDiv('members', $this->_selected_section)
            ."<div class='inner noref'>"
            ."<h2>Members of ".$this->_record['title']."</h2>"
            ."<ul class=\"cross churches_spaced\">\n";
        foreach ($entries as $r) {
            $img = ($r['featured_image'] && file_exists('.'.$r['featured_image']) ?
                $r['featured_image']
             :
                '/640x480-photo-unavailable.png'
            );
            $featured_image =
                BASE_PATH."img/sysimg?img=".$img."&amp;resize=1&amp;maintain=0&amp;width=50&amp;height=40";
            $this->_html.=
                "  <li"
                .$this->drawContextMenuMember($r)
                .">\n"
                ."    <a href=\"".$r['member_URL']."\">"
                ."<img alt=\"".str_replace('& ', '&amp; ', $r['title'])."\" src=\"".$featured_image."\""
                ." style=\"border:1px solid #888; float:left;margin:0 1em 0 0\"/></a>"
                ."<h3><a href=\"".$r['member_URL']."\">".$r['title']."</a></h3>\n"
                ."<address>"
                .$r['service_addr_line1'].","
                .($r['service_addr_line2'] ? $r['service_addr_line2'].', ' : '')
                .$r['service_addr_city'].' '
                .$r['service_addr_sp'].' '.$r['service_addr_postal']
                ."</address>\n"
                .str_replace(
                    '##LINKED_TITLE##',
                    "<a href=\"".$r['member_URL']."\">".str_replace('& ', '&amp; ', $r['title'])."</a>",
                    nl2br($r['summary'])
                )
                ."<br class='clear' />\n"
                ."  </li>\n";
        }
        $this->_html.=
             "</ul>\n"
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawEvents()
    {
        if (!$this->_cp['show_events'] ||
            !($this->_record['full_member'] || $this->_record['primary_ministerialID'])
        ) {
            return;
        }
        $Obj = new Community_Member_Event;
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $Obj->community_URL =   $this->_community_record['URL'];
        $args = array(
            'author_show' =>          $this->_cp['listing_show_author'],
            'category_show' =>        $this->_cp['listing_show_category'],
            'content_char_limit' =>   $this->_cp['listing_content_char_limit'],
            'content_plaintext' =>    $this->_cp['listing_content_plaintext'],
            'content_show' =>         $this->_cp['listing_show_content'],
            'filter_what' =>          'future',
            'results_limit' =>        $this->_cp['listing_results_limit'],
            'results_paging' =>       $this->_cp['listing_results_paging'],
            'thumbnail_height' =>     $this->_cp['listing_thumbnail_height'],
            'thumbnail_show' =>       $this->_cp['listing_show_thumbnails'],
            'thumbnail_width' =>      $this->_cp['listing_thumbnail_width']
        );
        $this->_html.=
            HTML::drawSectionTabDiv('events', $this->_selected_section)
            ."<div class='inner'>"
            .$this->drawWebShare('events', 'events')
            ."<h2>Upcoming Events for ".htmlentities($this->_record['title'])."</h2>\n"
            .$Obj->draw_listings('member_events', $args, false)
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawEventsChristmas()
    {
        if (!$this->_cp['show_events_special']) {
            return;
        }
        if (!$this->_events_christmas) {
            return;
        }
        $Obj = new Community_Member_Event;
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $Obj->community_URL =   $this->_community_record['URL'];
        $args = array(
            'author_show' =>          $this->_cp['listing_show_author'],
            'category_show' =>        false,
            'content_char_limit' =>   $this->_cp['listing_content_char_limit'],
            'content_plaintext' =>    $this->_cp['listing_content_plaintext'],
            'content_show' =>         $this->_cp['listing_show_content'],
            'filter_category_list' => 'Christmas',
            'filter_what' =>          'future',
            'results_limit' =>        $this->_cp['listing_results_limit'],
            'results_paging' =>       $this->_cp['listing_results_paging'],
            'thumbnail_height' =>     $this->_cp['listing_thumbnail_height'],
            'thumbnail_show' =>       $this->_cp['listing_show_thumbnails'],
            'thumbnail_width' =>      $this->_cp['listing_thumbnail_width']
        );
        $this->_html.=
            HTML::drawSectionTabDiv('christmas', $this->_selected_section)
            ."<div class='inner'>"
            ."<h2>".$this->_cp['label_events_christmas']." for ".htmlentities($this->_record['title'])."</h2>\n"
            .$Obj->draw_listings('member_events_christmas', $args, false)
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawEventsEaster()
    {
        if (!$this->_cp['show_events_special']) {
            return;
        }
        if (!$this->_events_easter) {
            return;
        }
        $Obj = new Community_Member_Event;
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $Obj->community_URL =   $this->_community_record['URL'];
        $args = array(
            'author_show' =>          $this->_cp['listing_show_author'],
            'category_show' =>        false,
            'content_char_limit' =>   $this->_cp['listing_content_char_limit'],
            'content_plaintext' =>    $this->_cp['listing_content_plaintext'],
            'content_show' =>         $this->_cp['listing_show_content'],
            'filter_category_list' => 'Easter',
            'filter_what' =>          'future',
            'results_limit' =>        $this->_cp['listing_results_limit'],
            'results_paging' =>       $this->_cp['listing_results_paging'],
            'thumbnail_height' =>     $this->_cp['listing_thumbnail_height'],
            'thumbnail_show' =>       $this->_cp['listing_show_thumbnails'],
            'thumbnail_width' =>      $this->_cp['listing_thumbnail_width']
        );
        $this->_html.=
            HTML::drawSectionTabDiv('easter', $this->_selected_section)
            ."<div class='inner'>"
            ."<h2>".$this->_cp['label_events_easter']." for ".htmlentities($this->_record['title'])."</h2>\n"
            .$Obj->draw_listings('member_events_easter', $args, false)
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawEventsSpecial()
    {
        if (!$this->_cp['show_events_special']) {
            return;
        }
        if (!$this->_events_special) {
            return;
        }
        $Obj = new Community_Member_Event;
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $Obj->community_URL =   $this->_community_record['URL'];
        $args = array(
            'author_show' =>          $this->_cp['listing_show_author'],
            'category_show' =>        false,
            'content_char_limit' =>   $this->_cp['listing_content_char_limit'],
            'content_plaintext' =>    $this->_cp['listing_content_plaintext'],
            'content_show' =>         $this->_cp['listing_show_content'],
            'filter_category_list' => 'Special-Days',
            'filter_what' =>          'future',
            'results_limit' =>        $this->_cp['listing_results_limit'],
            'results_paging' =>       $this->_cp['listing_results_paging'],
            'thumbnail_height' =>     $this->_cp['listing_thumbnail_height'],
            'thumbnail_show' =>       $this->_cp['listing_show_thumbnails'],
            'thumbnail_width' =>      $this->_cp['listing_thumbnail_width']
        );
        $this->_html.=
            HTML::drawSectionTabDiv('special', $this->_selected_section)
            ."<div class='inner'>"
            ."<h2>".$this->_cp['label_events_special']." for ".htmlentities($this->_record['title'])."</h2>\n"
            .$Obj->draw_listings('member_events_special', $args, false)
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawNews()
    {
        if (!$this->_cp['show_news'] ||
            !($this->_record['full_member'] || $this->_record['primary_ministerialID'])
        ) {
            return;
        }
        $Obj = new Community_Member_News_Item;
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $Obj->community_URL =   $this->_community_record['URL'];
        $args = array(
            'author_show' =>          $this->_cp['listing_show_author'],
            'category_show' =>        $this->_cp['listing_show_category'],
            'content_char_limit' =>   $this->_cp['listing_content_char_limit'],
            'content_plaintext' =>    $this->_cp['listing_content_plaintext'],
            'content_show' =>         $this->_cp['listing_show_content'],
            'results_limit' =>        $this->_cp['listing_results_limit'],
            'results_paging' =>       $this->_cp['listing_results_paging'],
            'thumbnail_height' =>     $this->_cp['listing_thumbnail_height'],
            'thumbnail_show' =>       $this->_cp['listing_show_thumbnails'],
            'thumbnail_width' =>      $this->_cp['listing_thumbnail_width']
        );
        $this->_html.=
            HTML::drawSectionTabDiv('news', $this->_selected_section)
            ."<div class='inner'>"
            .$this->drawWebShare('news', 'news')
            ."<h2>Latest News for ".htmlentities($this->_record['title'])."</h2>\n"
            .$Obj->draw_listings('member_news', $args, false)
            ."</div>\n"
            ."</div>\n";
    }

    protected function drawPodcasts()
    {
        if (!$this->_cp['show_podcasts'] || !$this->_record['full_member']) {
            return;
        }
        $Obj = new Community_Member_Podcast;
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $Obj->community_URL =   $this->_community_record['URL'];
        $args = array(
            'audioplayer_width' =>    $this->_cp['listing_audioplayer_width'],
            'author_show' =>          $this->_cp['listing_show_author'],
            'category_show' =>        $this->_cp['listing_show_category'],
            'content_char_limit' =>   $this->_cp['listing_content_char_limit'],
            'content_plaintext' =>    $this->_cp['listing_content_plaintext'],
            'content_show' =>         $this->_cp['listing_show_content'],
            'results_limit' =>        $this->_cp['listing_results_limit'],
            'results_paging' =>       $this->_cp['listing_results_paging'],
            'thumbnail_height' =>     $this->_cp['listing_thumbnail_height'],
            'thumbnail_show' =>       $this->_cp['listing_show_thumbnails'],
            'thumbnail_width' =>      $this->_cp['listing_thumbnail_width']
        );
        $this->_html.=
            HTML::drawSectionTabDiv('podcasts', $this->_selected_section)
            ."<div class='inner'>"
            .$this->drawWebShare('podcasts', 'podcasts')
            ."<h2>Latest "
            .($this->_record['type']=='ministerium' ? 'Audio' : 'Sermons')." from "
            .htmlentities($this->_record['title'])."</h2>\n"
            .$Obj->draw_listings('member_podcasts', $args, false)
            ."</div>\n"
            ."</div>\n";
    }


    protected function drawProfile()
    {
        global $page_vars;
        $r =            $this->_record;
        $servicetimes = $this->drawServiceTimes($r);
        $service_addr = $this->drawAddress('service_addr_');
        $verified =     ($r['date_survey_returned']!='0000-00-00' ? $r['date_survey_returned'] : false);
        $ministerial =  ($r['ministerial_title']!='' ? $r['ministerial_title'] : false);
        $Obj_LA = new Language_Assign;
        $languages = $Obj_LA->get_text_csv_for_assignment($this->_get_assign_type(), $r['ID']);
        $this->_html.=
             HTML::drawSectionTabDiv('profile', $this->_selected_section)
            ."<div class='inner'>"
            ."<h2>"
            .($this->_current_user_rights['isEditor'] ?
                 "<a href=\"".BASE_PATH."details/".$this->_edit_form['member']."/".$r['ID']."\""
                ." onclick=\"details('".$this->_edit_form['member']."',".$r['ID'].","
                .$this->_popup['member']['h'].",".$this->_popup['member']['w'].",'','');return false;\">"
             :
                ""
            )
            ."Profile for ".$r['title']
            .($this->_current_user_rights['isEditor'] ? "</a>" : "")
            ."</h2>"
            .($this->get_member_profile_images() ?
                 "<a href=\""
                .BASE_PATH."communities/".$this->_community_record['name'].'/'.$this->_record['name'].'/photos'."\">"
                ."<b>Click here</b></a> to see these images enlarged</a>"
             :
                ""
            )
            ."<div class='photo_frame"
            .($this->get_member_profile_images() ? "" : " photo_frame_single")
            ."'>"
            .$this->drawProfileImage()
            .($r['full_member'] || $verified || $ministerial ?
                "<div class='member_icons'>"
               .($r['full_member']?
                     "<img src='".BASE_PATH."img/spacer' width='32' height='32' class='icons-big'"
                    ." style='background-position: -15692px 0px;' alt='Premium Listing - all features available'"
                    ." title='Premium Listing - all features available' />"
                  :
                    ""
               )
               .($verified ?
                     "<img src='".BASE_PATH."img/spacer' width='32' height='32' class='icons-big'"
                    ." style='background-position: -15996px 0px;' alt=\"Member Verified\""
                    ." title=\"Verified by ".$r['type']." on ".format_date($verified, 'l j F Y')."\" />"
                :
                    ""
               )
               .($ministerial ?
                     "<img src='".BASE_PATH."img/spacer' width='32' height='32' class='icons-big'"
                    ." style='background-position: -16030px 0px;'"
                    ." title=\"Member of ".$ministerial."\" alt=\"Member of ".$ministerial."\" />"
                :
                    ""
               )
               ."</div>"
            :
               ""
            )
            ."</div>"
            ."<div class='details noref'>\n"
            ."<div class='details_c1'>\n"
            .($r['date_survey_returned']!='0000-00-00' ?
                 "<h3>Information Verified by Member:</h3>"
                ."<p style='margin:0 0 2em 1em;'>".format_date($r['date_survey_returned'])."</p>"
             :
                ""
            )
            .($r['ministerial_title'] ?
                 "<h3>Member of:</h3>\n"
                ."<p style='margin:0 0 2em 1em;'>"
                ."<a href=\"".BASE_PATH.trim($this->_community_record['URL'], '/').'/'
                .trim($r['ministerial_name'], '/')."\" rel=\"external\">"
                .$r['ministerial_title']
                ."</a></p>"
             :
                ""
            )
            .($r['custom_1'] ? "<h3>Denomination:</h3><p style='margin:0 0 2em 1em;'>".$r['custom_1']."</p>" : "")
            ."</div>\n"
            ."<div class='details_c2'>\n"
            .($service_addr ?
                 "<h3>".($r['type']=='church' ? "Address for Services:" : "Our Address")."</h3>\n"
                .$service_addr
             :
                ""
            )
            .($r['languages'] ?
                "<h3>Languages for Services:</h3><p style='margin:0 0 2em 1em;'>"
                .$languages
                ."</p>"
             :
                ""
            )
            .($r['service_notes'] ?
                "<h3>Notes:</h3><p style='margin:0 0 2em 1em;'>\n".$r['service_notes']."</p>"
             :
                ""
            )
            ."</div>\n"
            ."<div class='clear'>&nbsp;</div>\n"
            .($servicetimes ?
                "<h3>Regular Meeting Times:</h3><div style='margin:0 0 2em 1em;'>".$servicetimes."</div>"
             :
                ""
            );
        $link_types = explode(', ',Community_Member::LINK_TYPES);
        foreach ($link_types as $idx => $type) {
            $attr = $this->getLinkAttributes($type);
            if ($attr['url']) {
                $this->_html .=
                     "<div class='label'>".$attr['icon'].' '.$attr['long'].":</div>"
                    ."<div class='value'><a rel=\"external\" href=\"".$attr['url']."\">".$attr['url_s']."</a></div>";
            }
        }
        $this->_html.=
             ($r['full_member'] || $r['primary_ministerialID'] ?
             "<div class='label'>"
            ."<img class='icon' src='/img/spacer' alt='' title=''"
            ." style='width:16px;height:16px;margin:0px 5px 2px 0px;background-position:-8047px 0px;float:left;' />"
            ."RSS:</div>"
            ."<div class='value'>["
            .($r['full_member'] ?
                " <a rel=\"external\" href=\"".$this->_base_path."/rss/articles\">Articles</a> |\n"
             :
                ""
            )
            ." <a rel=\"external\" href=\"".$this->_base_path."/rss/events\">Events</a> |\n"
            ." <a rel=\"external\" href=\"".$this->_base_path."/rss/news\">News</a>\n"
            .($r['full_member'] ?
                "| <a rel=\"external\" href=\"".$this->_base_path."/rss/podcasts\">Sermons</a>\n"
             :
                ""
            )
            ."]"
            ."</div>"
            :
             ""
            )
            .($r['full_member'] ?
                 "<div class='label'><img class='icon' src='/img/spacer' alt='' title=''"
                ." style='width:16px;height:16px;margin:0px 5px 4px 0px;background-position:-6170px 0px;float:left;' />"
                ."Share:</div> "
                ."<div class='value'><a href=\"#\""
                ." onclick=\"return community_embed('".addslashes(htmlentities($r['title']))."',"
                ."'".$this->_base_path."')\">Show live updates on your website...</a></div>\n"
            :
             ""
            )
            ."</div>\n"
            ."<div class='clear'>&nbsp;</div>\n"
            ."[ECL]component_share_this[/ECL]"
            ."</div>\n"
            ."</div>\n"
            ;
    }

    protected function drawProfileImage()
    {
        if ($this->get_member_profile_images()) {
            return $this->drawProfileImageSlideshow();
        }
        return $this->drawProfileImageSingle();
    }

    protected function drawProfileImageSingle()
    {
        $img = ($this->_record['featured_image'] && file_exists('.'.$this->_record['featured_image']) ?
            $this->_record['featured_image']
         :
            '/640x480-photo-unavailable.png'
        );
        $featured_image =   BASE_PATH."img/width/".$this->_cp['profile_photo_width'].$img;
        return
            "<div class=\"member_photo_frame\">\n"
            ."<img src=\"".$featured_image."\" class=\"member_photo\""
            ." width=\"".$this->_cp['profile_photo_width']."\""
            ." alt=\"".$this->_record['name']."\" />"
            ."</div>\n";
    }

    protected function drawProfileImageSlideshow()
    {
        $Obj_WS =   new Component\WOWSlider;
        $path =     '//communities/'.$this->_community_record['name'].'/members/'.$this->_record['name'].'/profile';
        $args = array(
            'bullets_margin_top' =>       $this->_cp['profile_photo_height']-40,
            'caption_show' =>             0,
            'effect' =>                   'basic_linear',
            'filter_container_path' =>    $path,
            'max_height' =>               $this->_cp['profile_photo_height'],
            'max_width' =>                $this->_cp['profile_photo_width'],
            'results_limit' =>            0,
            'show_watermark' =>           1,
            'thumbnail_height' =>         (int)($this->_cp['profile_photo_height']/4),
            'thumbnail_width' =>          (int)($this->_cp['profile_photo_width']/4),
            'title_show' =>               0
        );
        return "<div class='member_slideshow'>".$Obj_WS->draw('profile', $args, true)."</div>";
    }

    protected function drawSectionTabButtons()
    {
        $this->_html.=
            HTML::drawSectionTabButtons(
                $this->_section_tabs_arr,
                $this->_safe_ID,
                $this->_selected_section,
                "document.location.hash='#'+this.id.substr(8).split('_')[0]"
            );
    }

    protected function drawSectionContainerClose()
    {
        $this->_html.= "</div>\n";
    }

    protected function drawSectionContainerOpen()
    {
        Output::push(
            'javascript_onload',
            "  show_section_tab('spans_".$this->_safe_ID."','".$this->_selected_section."');\n"
        );
        $this->_html.= "<div id='".$this->_safe_ID."_container' style='position:relative;'>\n";
    }

    protected function drawSponsorsNational()
    {
        if ($this->_cp['show_sponsors']!=1) {
            return;
        }
        if (!count($this->_sponsors_national_records)) {
            return;
        }
        $Obj_CGT = new Component_Gallery_Thumbnails;
        $args = array(
            'color_background' =>           'f8f8ff',
            'color_border' =>               '695137',
            'filter_category_master' =>     '',
            'filter_container_path' =>      $this->_sponsors_national_container,
            'image_padding_horizontal' =>   10,
            'image_padding_vertical' =>     5,
            'max_height' =>                 210,
            'max_width' =>                  165,
            'results_limit' =>              0,
            'show_background' =>            1,
            'show_border' =>                1,
            'show_image_border' =>          1,
            'show_caption' =>               0,
            'show_image' =>                 1,
            'show_title' =>                 1,
            'show_uploader' =>              0,
            'title_height' =>               50
        );
        return
             "<hr />\n"
            ."<h3>".$this->_cp['label_sponsors_national']."</h3>"
            ."<div class='section_header'>".$this->_cp['header_sponsors_national']."</div>"
            .$Obj_CGT->draw('national_sponsors', $args, false);
    }

    protected function drawSponsorsLocal()
    {
        if ($this->_cp['show_sponsors']!=1) {
            return;
        }
        if (!$this->_community_record['sponsorship_gallery_albumID']) {
            return;
        }
        $Obj_GA = new Gallery_Album;
        $Obj_GA->_set_ID($this->_community_record['sponsorship_gallery_albumID']);
        $path = $Obj_GA->get_field('path');
        $Obj_SP = new Sponsorship_Plan;
        $result = $Obj_SP->getFilteredSortedAndPagedRecords(
            array(
                'filter_container_path' =>  $path
            )
        );
        $Obj_CGT = new Component_Gallery_Thumbnails;
        $out = "";
        foreach ($result['data'] as $plan) {
            $Obj_SP->xmlfields_decode($plan);
            $Obj_SP->_set_ID($plan['ID']);
            if ($this->_current_user_rights['canEdit'] || $Obj_SP->get_children()) {
                $args = array(
                    'color_background' =>       'ffffff',
                    'filter_category_master' => '',
                    'filter_container_path' =>  $plan['path'],
            //          'max_height' =>             $plan['xml:width'],
                    'max_width' =>              $plan['xml:width'],
                    'results_limit' =>          0,
                    'show_background' =>        1,
                    'show_caption' =>           $plan['xml:show_description'],
                    'show_image' =>             $plan['xml:show_logo'],
                    'show_title' =>             $plan['xml:show_name'],
                    'show_uploader' =>          1,
                );
                $result = $Obj_CGT->draw($plan['name'], $args, false);
                if ($result) {
                    $out.=
                        ($out ? "<hr />\n" : "")
                        .($this->_current_user_rights['canEdit'] ?
                         "<h4>"
                        ."<a href='#' onclick=\"details('".$this->_edit_form['sponsor_plan']."','".$plan['ID']."',"
                        ."'".$this->_popup['sponsor_plan']['h']."','".$this->_popup['sponsor_plan']['w']."');"
                        ."return false;\">"
                        .$plan['title']." ($".$plan['xml:cost'].")"
                        ."</a>"
                        ."</h4>"
                        :
                        "<h4>".$plan['title']." ($".$plan['xml:cost'].")"."</h4>\n"
                        )
                        .$result;
                }
            }
        }
        if ($out) {
            return
                 "<hr />\n"
                ."<h3>".$this->_cp['label_sponsors_local']."</h3>"
                ."<div class='section_header'>".$this->_cp['header_sponsors_local']."</div>"
                .$this->_community_record['sponsorship']
                .$out;
        }
    }

    protected function drawStats()
    {
        global $system_vars;
        if (!$this->_current_user_rights['canViewStats']) {
            return;
        }
        if (DEV_STATUS && !PIWIK_DEV) {
            return;
        }
        $r =    $this->_record;
        $this->_html.=
             HTML::drawSectionTabDiv('stats', $this->_selected_section)
            ."<h2>".$this->_cp['label_stats']."</h2>"
            ."<table cellpadding='2' cellspacing='0' border='1' class='member_stats'"
            ." summary='Table showing statistics for this member'>\n"
            ."  <thead>\n"
            ."    <tr>\n"
            ."      <th rowspan='3' class='st_date st_line st_bord_l st_bord_t'>Month</th>\n"
            ."      <th colspan='4' class='st_site st_line st_bord_t txt_c'>"
            .$system_vars['textEnglish']
            ."</th>\n"
            ."      <th colspan='4' class='st_comm st_line st_bord_t txt_c'>"
            ."Community of ".$this->_community_record['title']
            ."</th>\n"
            ."      <th colspan='8' class='st_prof st_bord_r st_bord_t txt_c'>".$r['title']." Profile</th>\n"
            ."    </tr>\n"
            ."    <tr>\n"
            ."      <th rowspan='2' class='st_site st_bord_b'>Actions</th>\n"
            ."      <th rowspan='2' class='st_site st_bord_b'>Visits</th>\n"
            ."      <th colspan='2' class='st_site st_line'>Visit Time</th>\n"
            ."      <th rowspan='2' class='st_comm st_bord_b'>Hits</th>\n"
            ."      <th rowspan='2' class='st_comm st_bord_b'>Visits</th>\n"
            ."      <th colspan='2' class='st_comm st_line'>Visit Time</th>\n"
            ."      <th rowspan='2' class='st_prof st_bord_b'>Hits</th>\n"
            ."      <th rowspan='2' class='st_prof st_bord_b'>Visits</th>\n"
            ."      <th colspan='2' class='st_prof st_line'>Visit Time</th>\n";
        $link_types = explode(', ',Community_Member::LINK_TYPES);
        foreach ($link_types as $idx => $type) {
            $attr = $this->getLinkAttributes($type);
            $this->_html .=
                "      <th rowspan='2' class='st_prof"
                .($attr['url']   ? '' : ' st_void')
                .($idx+1 === count($link_types) ? ' st_bord_r' : '')
                ."'"
                ." title='".$attr['long']." Referrals'>"
                .($attr['url'] ? "<a rel=\"external\" href=\"".$attr['url']."\">" : "").$attr['icon']."<br />"
                .$attr['short']
                .($attr['url'] ? "</a>" : "")
                ."</th>\n";
        }
        $this->_html.=
             "    </tr>\n"
            ."    <tr>\n"
            ."      <th class='st_site st_bord_b'>Avg</th>\n"
            ."      <th class='st_site st_bord_b st_line'>Tot</th>\n"
            ."      <th class='st_comm st_bord_b'>Avg</th>\n"
            ."      <th class='st_comm st_bord_b st_line'>Tot</th>\n"
            ."      <th class='st_prof st_bord_b'>Avg</th>\n"
            ."      <th class='st_prof st_bord_b st_line'>Tot</th>\n"
            ."    </tr>\n"
            ."  </thead>\n"
            ."  <tbody>\n";
        $community_url =    BASE_PATH.trim($this->_community_record['URL'], '/');
        $member_url_arr =   array($community_url.'/'.trim($r['name'], '/'));
        if (trim($r['name_aliases'])) {
            $name_aliases = explode(',', trim($r['name_aliases']));
            foreach ($name_aliases as $name_alias) {
                $member_url_arr[] = $community_url.'/'.trim($name_alias, '/');
            }
        }
        for ($i = count($this->_stats_dates) -1; $i >= 0; $i--) {
            $YYYYMM = $this->_stats_dates[$i];

            if (!isset($this->_stats[$YYYYMM]['visits'])) {
                continue;
            }
            $site = [
                'hits' =>       0,
                'visits' =>     0,
                'time_a' =>     0,
                'time_t' =>     0
            ];
            $comm = [
                'hits' =>       0,
                'visits' =>     0,
                'time_a' =>     0,
                'time_t' =>     0
            ];
            $prof = [
                'hits' =>       0,
                'visits' =>     0,
                'time_a' =>     0,
                'time_t' =>     0
            ];
            if (isset($this->_Obj_System->_stats[$YYYYMM])) {
                $site =   $this->_Obj_System->_stats[$YYYYMM];
            }
            if (isset($this->_Obj_Community->_stats[$YYYYMM])) {
                $comm =   $this->_Obj_Community->_stats[$YYYYMM];
            }
            if (isset($this->_stats[$YYYYMM]['visits'])) {
                $prof = $this->_stats[$YYYYMM]['visits'];
            }
            $bord_b = ($i==0 ? " st_bord_b" : "");
            $this->_html.=
                 "    <tr>\n"
                ."      <td class='st_date st_bord_l st_line".$bord_b."'>".$YYYYMM."</td>\n"
                ."      <td class='st_site".$bord_b."'>"
                .(isset($site['actions']) && $site['actions'] ? $site['actions'] : '')
                ."</td>\n"
                ."      <td class='st_site".$bord_b."'>"
                .($site['visits'] ? $site['visits'] : '&nbsp;')
                ."</td>\n"
                ."      <td class='st_site".$bord_b."'>"
                .($site['time_a'] ? format_seconds($site['time_a']) : "&nbsp;")
                ."</td>\n"
                ."      <td class='st_site st_line".$bord_b."'>"
                .($site['time_t'] ? format_seconds($site['time_t']) : "&nbsp;")
                ."</td>\n"
                ."      <td class='st_comm".$bord_b."'>"
                .($comm['hits'] ? $comm['hits'] : '&nbsp;')
                ."</td>\n"
                ."      <td class='st_comm".$bord_b."'>"
                .($comm['visits'] ? $comm['visits'] : '&nbsp;')
                ."</td>\n"
                ."      <td class='st_comm".$bord_b."'>"
                .($comm['time_a'] ? format_seconds($comm['time_a']) : "&nbsp;")
                ."</td>\n"
                ."      <td class='st_comm st_line".$bord_b."'>"
                .($comm['time_t'] ? format_seconds($comm['time_t']) : "&nbsp;")
                ."</td>\n"
                ."      <td class='st_prof".$bord_b."'>"
                .($prof['hits'] ? $prof['hits'] : '&nbsp;')
                ."</td>\n"
                ."      <td class='st_prof".$bord_b."'>"
                .($prof['visits'] ? $prof['visits'] : '&nbsp;')
                ."</td>\n"
                ."      <td class='st_prof".$bord_b."'>"
                .($prof['time_a'] ? format_seconds($prof['time_a']) : "&nbsp;")
                ."</td>\n"
                ."      <td class='st_prof st_line".$bord_b."'>"
                .($prof['time_t'] ? format_seconds($prof['time_t']) : "&nbsp;")
                ."</td>\n";

            $link_types = explode(', ',Community_Member::LINK_TYPES);
            foreach ($link_types as $type) {
                $hits = 0;
                if ($r['link_' . $type]) {
                    $link_arr = explode('|', $r['link_' . $type]);
                    foreach ($link_arr as $la) {
                        if (isset($this->_stats[$YYYYMM]['links'][$la]['hits'])) {
                            $hits += $this->_stats[$YYYYMM]['links'][$la]['hits'];
                        }
                    }
                }
                $this->_html .=
                    "      <td class='st_link" . $bord_b . "'>"
                    . ($hits ? $hits : '&nbsp;')
                    . "</td>\n";
            }
            $this->_html.=
                 "    </tr>\n";
            if (substr($YYYYMM, 5, 2)=='01') {
                $this->_html.=
                     "<tr>\n"
                    ."<td colspan='17' class='st_bord_l st_bord_r' style='background:#808080'></td>\n"
                    ."</tr>";
            }
        }
        $this->_html.=
             "  </tbody>\n"
            ."</table>\n"
            ."<div class='section_footer'>".$this->_cp['footer_stats']."</div>"
            ."</div>\n";
    }

    protected function drawTitle()
    {
        $this->_html.=
             "<h1 class='title'>"
            ."<a href=\"".BASE_PATH.trim($this->_community_record['URL'], '/')."\">"
            .$this->_community_record['title']."</a>"
            .": "
            ."<a href=\"".BASE_PATH.trim($this->_community_record['URL'], '/').'/'
            .trim($this->_record['name'], '/')."\">"
            .$this->_record['title']."</a>"
            ."</h1>";
    }

    protected function drawWebShare($rss = '', $embed = '')
    {
        return
             "<div class='web_share'>"
            .($rss ?
                "<img class='icon rss' src='/img/spacer' alt='' title='RSS Feed'  />"
                ."<a rel=\"external\" title=\"Click to subscribe to this RSS Feed\""
                ." href=\"".$this->_base_path."/rss/".$rss."\">RSS</a>\n"
             :
                ""
            )
            .($embed ?
                 "<img class='icon share' src='/img/spacer' alt='' title='Embed on your website'  />"
                ."<a title=\"Embed this information on your website\" href=\"#\""
                ." onclick=\"return community_embed('".addslashes(htmlentities($this->_record['title']))."',"
                ."'".$this->_base_path."','".$embed."')\">Web</a>\n"
            :
                ""
            )
            ."</div>";
    }

    protected function setupInitial($cp, $member_extension)
    {
        $this->_cp =    $cp;
        $this->setupInitialLoadMember($member_extension);
        $this->_print = get_var('print')=='1';
    }

    protected function setupInitialLoadMember($member_extension)
    {
        global $page_vars;
        $this->_ident =             "community_member_display";
        $this->_safe_ID =           Component_Base::get_safe_ID($this->_ident, $this->_instance);
        $this->_base_path =         BASE_PATH.trim($page_vars['path'], '/');
        $this->_member_extension =  $member_extension;
        $member_page_arr =      explode('/', $this->_member_extension);
        $this->_member_name =   array_shift($member_page_arr);
        $this->_member_page =   implode('/', $member_page_arr);
        if (!$this->get_member_profile($this->_cp['community_name'], $this->_member_name)) {
            header("Status: 404 Not Found", true, 404);
            throw new Exception("Member \"".$this->_member_name."\" not found.");
        }
    }

    protected function setup($cp)
    {
        global $page_vars;
        $this->_cp =                $cp;
        $this->setupLoadEmailContacts();
        $this->setupLoadUserRights();
        $this->setupLoadEditParameters();
        $this->setupLoadCommunityRecord();
        $this->setupLoadSystemRecord();
        $this->setupLoadEventsSpecial();
        $this->setupLoadCommunityMembers();
        $this->setupLoadSponsors();
        $this->setupLoadStats();
        $this->setupLoadNavigationPosition();
        $this->setupTabs();
    }

    protected function setupLoadCommunityRecord()
    {
        $community_name =   $this->_cp['community_name'];
        $this->_Obj_Community = new Community;
        $this->_Obj_Community->set_ID_by_name($community_name);
        if (!$this->_community_record = $this->_Obj_Community->load()) {
            header("Status: 404 Not Found", true, 404);
            throw new Exception("Community \"".$community_name."\" not found.");
        }
    }

    protected function setupLoadCommunityMembers()
    {
        $this->_members =   $this->_Obj_Community->get_members();
    }

    protected function setupLoadEditParameters()
    {
        if (!$this->_current_user_rights['isEditor']) {
            return;
        }
        $this->_edit_form['pages'] =          'pages';
        $this->_edit_form['community'] =      'community';
        $this->_edit_form['member'] =         'community_member';
        $this->_edit_form['sponsor_plan'] =   'community.sponsorship-plans';
        $this->_popup['pages'] =              get_popup_size($this->_edit_form['pages']);
        $this->_popup['community'] =          get_popup_size($this->_edit_form['community']);
        $this->_popup['member'] =             get_popup_size($this->_edit_form['member']);
        $this->_popup['sponsor_plan'] =       get_popup_size($this->_edit_form['sponsor_plan']);
    }

    protected function setupLoadEmailContacts()
    {
        $this->_contacts =          $this->get_email_contacts();
    }

    protected function setupLoadEventsSpecial()
    {
        $this->_events_christmas =        $this->get_events_upcoming('Christmas');
        $this->_events_easter =           $this->get_events_upcoming('Easter');
        $this->_events_special =          $this->get_events_upcoming('Special-Days');
    }

    protected function setupLoadNavigationPosition()
    {
        for ($i=0; $i<count($this->_members); $i++) {
            $m = $this->_members[$i];
            if ($m['ID'] == $this->_get_ID()) {
                $this->_nav_prev = ($i-1<0 ? $this->_members[count($this->_members)-1] : $this->_members[$i-1]);
                $this->_nav_next = ($i+1>count($this->_members)-1 ? $this->_members[0] : $this->_members[$i+1]);
                return;
            }
        }
    }

    protected function setupLoadSponsors()
    {
        $this->_sponsors_national_container = '//sponsors/national';
        $Obj_GA = new Gallery_Album;
        if (!$ID = $Obj_GA->get_ID_by_path($this->_sponsors_national_container)) {
            return;
        }
        $Obj_GA->_set_ID($ID);
        $this->_sponsors_national_records = $Obj_GA->get_children();
    }

    protected function setupLoadStats()
    {
        global $system_vars;
        if (DEV_STATUS && !PIWIK_DEV) {
            return;
        }
        if (!$this->_current_user_rights['canViewStats']) {
            return;
        }
        $this->_Obj_System->get_stats();
        $this->_Obj_Community->get_stats();
        $this->get_stats();
    }

    protected function setupLoadSystemRecord()
    {
        $this->_Obj_System = new System;
        $this->_Obj_System->_set_ID(SYS_ID);
    }

    protected function setupLoadUserRights()
    {
        $this->_current_user_rights['isEditor'] =
            get_person_permission("SYSEDITOR") ||
            get_person_permission("SYSAPPROVER") ||
            get_person_permission("SYSADMIN") ||
            get_person_permission("MASTERADMIN");
        $this->_current_user_rights['canEdit'] =
            $this->_current_user_rights['isEditor'] ||
            (get_person_permission("COMMUNITYADMIN") && $_SESSION['person']['memberID']==$this->_record['ID']);
        $this->_current_user_rights['canViewStats'] =
            $this->_current_user_rights['canEdit'];
    }

    protected function setupTabs()
    {
        $this->_section_tabs_arr[] =    array('ID'=>'profile','label'=>'Profile');
        if ($this->_record['type'] == 'ministerium') {
            $this->_section_tabs_arr[] =    array('ID'=>'members', 'label'=>$this->_cp['tab_members']);
        }
        if ($this->_cp['show_events_special']==1 &&
            $this->_events_christmas
        ) {
            $this->_section_tabs_arr[] =    array('ID'=>'christmas', 'label'=>$this->_cp['tab_events_christmas']);
        }
        if ($this->_cp['show_events_special']==1 &&
            $this->_events_easter
        ) {
            $this->_section_tabs_arr[] =    array('ID'=>'easter', 'label'=>$this->_cp['tab_events_easter']);
        }
        if ($this->_cp['show_events_special']==1 &&
            $this->_events_special
        ) {
            $this->_section_tabs_arr[] =    array('ID'=>'special', 'label'=>$this->_cp['tab_events_special']);
        }
        if ($this->_cp['show_map']==1 &&
            ($this->_record['service_map_lat']!=0 || $this->_record['service_map_lon']!=0)
        ) {
            $this->_section_tabs_arr[] =    array('ID'=>'map','label'=>'Map');
        }
        if ($this->_cp['show_contact']==1) {
            $this->_section_tabs_arr[] =     array('ID'=>'contact', 'label'=>$this->_cp['tab_contact']);
        }
        if ($this->_cp['show_articles']==1 &&
            $this->_record['full_member']
        ) {
            $this->_section_tabs_arr[] =   array('ID'=>'articles', 'label'=>$this->_cp['tab_articles']);
        }
        if ($this->_cp['show_events']==1 &&
            ($this->_record['full_member'] || $this->_record['primary_ministerialID'])
        ) {
            $this->_section_tabs_arr[] =   array('ID'=>'events', 'label'=>$this->_cp['tab_events']);
        }
        if (
            $this->_cp['show_calendar']==1 &&
            ($this->_record['full_member'] || $this->_record['primary_ministerialID'])
        ) {
            $this->_section_tabs_arr[] =   array('ID'=>'calendar', 'label'=>$this->_cp['tab_calendar']);
        }
        if ($this->_cp['show_news']==1 &&
            ($this->_record['full_member'] || $this->_record['primary_ministerialID'])
        ) {
            $this->_section_tabs_arr[] =   array('ID'=>'news', 'label'=>$this->_cp['tab_news']);
        }
        if ($this->_cp['show_podcasts']==1 &&
            ($this->_record['full_member'])
        ) {
            $this->_section_tabs_arr[] =   array(
                'ID'=>'podcasts',
                'label'=>($this->_record['type'] == 'church' ?
                    $this->_cp['tab_podcasts']
                 :
                    $this->_cp['tab_audio']
                )
            );
        }
        if ($this->_cp['show_stats']==1 && $this->_current_user_rights['canViewStats'] && (PIWIK_DEV || !DEV_STATUS)) {
            $this->_section_tabs_arr[] =   array('ID'=>'stats', 'label'=>$this->_cp['tab_stats']);
        }
        if ($this->_cp['show_about']==1) {
            $this->_section_tabs_arr[] =   array('ID'=>'about', 'label'=>$this->_cp['tab_about']);
        }
        $extra_space = 11;
        $width = floor($this->_cp['width']/count($this->_section_tabs_arr))-$extra_space;
        $total = 0;
        for ($i=0; $i<count($this->_section_tabs_arr); $i++) {
            $w =  floor($width);
            $total+=$w+$extra_space;
            if ($i==count($this->_section_tabs_arr)-1) {
                $w+=5+$extra_space+$this->_cp['width']-$total;
            }
            $this->_section_tabs_arr[$i]['width'] = $w;
        }
    }
}
