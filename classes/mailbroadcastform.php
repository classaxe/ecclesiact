<?php
/*
Version History:
  1.0.0 (2016-12-03)
    1) Created this class from Mail_Queue:draw_broadcast_form()
    2) Added additional help for use of multiple BCC recipients and required fields
*/

class MailBroadcastForm extends Mail_Queue
{
    const VERSION = '1.0.0';
    
    public function draw()
    {
        global $submode, $groupID, $maillBccRecipients, $mailtemplateID, $mailidentityID;
        global $selectID, $targetID, $targetReportID;
        global $offset, $limit;
        global $filterField,$filterValue,$filterExact;
        $msg = "";
        $out = "";
        switch ($submode) {
            case "delete":
                $Obj_Report =   new Report($targetReportID);
                $report_name =  $Obj_Report->get_name();
                switch ($report_name){
                    case 'mail_queue':
                        $count = count(explode(',', $targetID));
                        $this->_set_ID($targetID);
                        $this->delete();
                        $msg =
                             "<b>Success:</b> The "
                            .($count==1 ? "Mail Job" : $count." Mail Jobs")
                            ." and associated recipients have been deleted.";
                        $targetID = "";
                        $submode = "";
                        break;
                }
                break;
            case "queue":
                if ($groupID==0) {
                    $msg =
                    "<b>Error:</b> Please create a Group containing members who are set to receive email"
                        ." then try this operation again.";
                        break;
                }
                $selectID = $this->create_queue($mailidentityID, $mailtemplateID, $groupID, $maillBccRecipients);
                $msg = "<b>Success:</b> the Mail Job has been created but has not yet been started.";
                break;
            case "queue_again":
                $targetIDs = explode(',', $targetID);
                $new_targetIDs = array();
                foreach ($targetIDs as $t) {
                    $this->_set_ID($t);
                    if ($this->queueAgain()) {
                        $new_targetIDs[] = $this->_get_ID();
                    }
                }
                $targetID = implode(',', $new_targetIDs);
                $msg =
                     "<b>Success:</b> The "
                    .(count($new_targetIDs)===1 ? '' : count($new_targetIDs))
                    ." Mail Job"
                    .(count($new_targetIDs)===1 ? " has" : "s have")
                    ." been recreated but delivery has not yet been started.<br />"
                    ."To start queued jobs, click the job number then press the 'Begin Delivery' button that will "
                    ."appear below the Mail Jobs list.";
                break;
            case "send":
                $this->_set_ID($selectID);
                $msg = $this->send();
                break;
            case "send_again":
                $targetIDs = explode(',', $targetID);
                $new_targetIDs = array();
                foreach ($targetIDs as $t) {
                    $this->_set_ID($t);
                    if ($this->sendAgain()) {
                        $new_targetIDs[] = $this->_get_ID();
                    }
                }
                $targetID = implode(',', $new_targetIDs);
                $msg =
                     "<b>Success:</b> The "
                    .(count($new_targetIDs)===1 ? '' : count($new_targetIDs))
                    ." Mail Job"
                    .(count($new_targetIDs)===1 ? " has" : "s have")
                    ." been recreated and delivery will commence shortly.";
                break;
            case "send_now":
                if ($groupID==0) {
                    $msg =
                    "<b>Error:</b> Please create a Group containing members who are set to receive email"
                        ." then try this operation again.";
                        break;
                }
                $selectID = $this->create_queue($mailidentityID, $mailtemplateID, $groupID, $maillBccRecipients);
                $this->_set_ID($selectID);
                $msg = $this->send();
                break;
        }
        $out.=
             "<h3 class='admin_heading' style='display: inline;'>Create Mail Job</h3><br />\n"
            .HTML::draw_status('mail_broadcast', $msg);
        if (!$this->get_emailable_group_count()) {
            $out.=
                 "<p>There are no Groups with Email Recipients.<br />\n"
                ."You cannot create a new Mail Broadcast Job until you have one.</p>";
        } else {
            $out.=
                 "<table class='minimal'>\n"
                ."  <tr>\n"
                ."    <td>".draw_form_header("Mail Broadcast", "", 0)."</td>\n"
                ."  </tr>\n"
                ."  <tr>\n"
                ."    <td>\n"
                ."    <table cellpadding='0' cellspacing='0' class='table_border' width='100%'>\n"
                ."      <tr class='table_header'>\n"
                ."        <td>&nbsp;<b>*</b>&nbsp;</td>\n"
                ."        <td>From:&nbsp;</td>\n"
                ."        <td><select name=\"mailidentityID\" class='admin_formFixed' style='width: 500px;'>"
                .draw_select_options(MailIdentity::getSelectorSQL(), $mailidentityID)
                ."</select></td>\n"
                ."      </tr>\n"
                ."      <tr class='table_header'>\n"
                ."        <td>&nbsp;<b>*</b>&nbsp;</td>\n"
                ."        <td>To:&nbsp;</td>\n"
                ."        <td><select name=\"groupID\" class='admin_formFixed' style='width: 500px;'>"
                .draw_select_options(Group::get_selector_email_groups_sql(), $groupID)
                ."</select></td>\n"
                ."      </tr>\n"
                ."      <tr class='table_header'>\n"
                ."        <td>&nbsp;<b>*</b>&nbsp;</td>\n"
                ."        <td>Template:&nbsp;</td>\n"
                ."        <td><select name=\"mailtemplateID\" class='admin_formFixed' style='width: 500px;'>"
                .draw_select_options(Mail_Template::get_selector_SQL(), $mailtemplateID)
                ."</select></td>\n"
                ."      </tr>\n"
                ."      <tr class='table_header'>\n"
                ."        <td>&nbsp;</td>\n"
                ."        <td>BCC to:&nbsp;</td>\n"
                ."        <td>".draw_form_field('maillBccRecipients', $maillBccRecipients, 'text_fixed', 495)
                ."</td>\n"
                ."      </tr>\n"
                ."      <tr class='table_header'>\n"
                ."        <td>&nbsp</td>\n"
                ."        <td>&nbsp</td>\n"
                ."        <td><small><i><b>*</b> Means that the field is required.<br />"
                ."Separate BCC recipients like this:</i> &nbsp; <b>a@example.com; b@example.com</b></small></td>\n"
                ."      </tr>\n"
                ."      <tr class='table_header'>\n"
                ."        <td colspan='3' align='center' style='padding: 0.5em 0 0.25em 0'>"
                ."<input type='reset' value='Clear Form' style='formButton'/>"
                ."<input type='button' onclick=\"if("
                ."geid_val('mailidentityID') && geid_val('mailtemplateID') && geid_val('groupID')){"
                ."geid_set('submode','send_now');geid('form').submit();"
                ."}else{"
                ."alert('All three fields are required');"
                ."}\" value='Send Now' style='formButton'/>"
                ."<input type='button' onclick=\""
                ."if(geid_val('mailidentityID') && geid_val('mailtemplateID') && geid_val('groupID')){"
                ."geid_set('submode','queue');geid('form').submit();"
                ."}else{"
                ."alert('All three fields are required');"
                ."}\" value='Send Later' style='formButton'/>"
                ."</td>\n"
                ."      </tr>\n"
                ."    </table></td>"
                ."  </tr>\n"
                ."</table>\n"
                ."<br /><br />\n";
        }
        $mailqueue_count = $this->get_mailqueue_count();
        if (!$mailqueue_count) {
            return $out;
        }
        $this->_set_ID($selectID);
        $record = $this->get_mailqueue_status();
        if ($record===false) {
            $selectID = '';
        }
        $out.=
             "<h3 class='admin_heading' style='margin:0'>Mail Jobs</h3>\n"
            .($selectID == '' ?
                "<p>Click a Mail <span style='color:#808000'><b>Job Number</b></span> in the report below to "
                ."<span style='background-color: #ffffa0;border:1px solid #888'>&nbsp;select&nbsp;</span> it.<br />\n"
                ."This lets you view details of the Recipients, and allows you to start the Job"
                ." if it hasn't already been started."
                ."</p>\n"
             :
                "<p>Mail Job <a href=\"#mail_queue_items\" style='color:#000;text-decoration:none;'"
                ." title=\"Click to View Recipients\"><b>#".$selectID."</b></a> has been selected"
                .($record['date_started'] != "0000-00-00 00:00:00" ?
                    ".<br />"
                    :
                    " but hasn't yet been started.<br />"
                    ."Click <a href=\"".BASE_PATH."report/mail_broadcast?submode=send&amp;selectID=".$selectID."\">"
                    ."<b>here</b></a> to start it now."
                )
                ."</p>\n"
            )
            .draw_auto_report('mail_queue', 1);
        if ($selectID) {
            if ($record['date_started'] == "0000-00-00 00:00:00") {
                $out.=
                     "<input type='button' onclick=\"this.value='Please Wait...';this.disabled=1;"
                    ."geid_set('selectID','".$selectID."');geid_set('submode','send');geid('form').submit()\" "
                    ."value='Begin Delivery' style='formButton'/>";
            }
            $out.=
                 "<br />\n"
                ."<a name='mail_queue_items'></a>\n"
                ."<h3 class='admin_heading' style='display: inline;'>"
                ."Recipients for Mail Job #".$selectID."</h3>\n"
                .draw_auto_report('mail_queue_items', 1);
        }
        return $out;
    }
}
