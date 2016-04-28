<?php
/*
Version History:
  1.0.0 (2016-04-28)
    1) Initial Release - extracted from mail_queue class
*/
class EmailBounceChecker extends MailIdentity
{
    const VERSION = '1.0.0';

    public function check()
    {
        @set_time_limit(600);    // Extend maximum execution time to 10 mins
        // Get login credentials for mailqueues
        $mailidentities = static::getIdentitesUsed();
        foreach ($mailidentities as $mi) {
            $Obj_POP3 = new phPOP3(
                $mi['bounce_pop3_host'],
                $mi['bounce_pop3_port'],
                $mi['bounce_pop3_username'],
                $mi['bounce_pop3_password']
            );
            $mailbox = $Obj_POP3->pop3_list();
            if ($mailbox["messages"] > 0) {
                for ($i=1; $i<$mailbox["messages"]+1; $i++) {
                    $message = $Obj_POP3->pop3_retrieve($i);
                    $body = implode('', $message->body);
                    if ($start = strpos($body, "Content-Type: message/delivery-status")) {
                        $text = substr($body, $start);
                        preg_match("/Status: ([0-9.]+)/", $text, $status);
                        preg_match("/Message-ID: <([^>]+)>/", $text, $messageID);
                        preg_match("/Diagnostic-Code:(.*?)\r\n\r\n/s", $text, $diagnostic);
                        $status =
                            (isset($status[1]) ? $status[1] : false);
                        $messageID =
                            (isset($messageID[1]) ? $messageID[1] : "");
                        $diagnostic =
                            (isset($diagnostic[1]) ? str_replace(array('\r\n','  '), ' ', $diagnostic[1]) : "");
                        if ($messageID) {
                            $soft = substr($status, 0, 1)!='5';  // Not permanent error
                            $mailqueueID = $this->get_mailqueueID_for_messageID($messageID);
                            if ($mailqueueID) {
                                $error =    $status." ".$diagnostic;
                                if ($soft) {
                                    static::setSoftBounce($messageID, $error);
                                    $this->set_field('date_completed', '0000-00-00 00:00:00');
                                } else {
                                    static::setHardBounce($messageID, $error);
                                }
                                $Obj_POP3->pop3_delete($i);
                            }
                        }
                    }
                }
            }
        }
        $Obj_POP3->pop3_quit();
    }

    public static function getIdentitesUsed()
    {
        $isMASTERADMIN = get_person_permission("MASTERADMIN");
        $sql =
             "SELECT DISTINCT\n"
            ."  `mailidentity`.`bounce_pop3_host`,\n"
            ."  `mailidentity`.`bounce_pop3_password`,\n"
            ."  `mailidentity`.`bounce_pop3_port`,\n"
            ."  `mailidentity`.`bounce_pop3_username`\n"
            ."FROM\n"
            ."  `mailqueue`\n"
            ."LEFT JOIN `mailidentity` ON\n"
            ."  `mailidentity`.`ID` = `mailqueue`.`mailidentityID`\n"
            .($isMASTERADMIN ? "" : "WHERE\n  `mailqueue`.`systemID` = ".SYS_ID);
        return static::getRecordsForSql($sql);
    }

    private static function setHardBounce($messageID, $error)
    {
        $sql =
             "UPDATE\n"
            ."  `mailqueue_item`\n"
            ."SET\n"
            ."  `mail_error` = \"".$error."\",\n"
            ."  `mail_failed` = 1\n"
            ."WHERE\n"
            ."  `mail_messageID` = \"".$messageID."\"";
        static::doSqlQuery($sql);
    }
    
    private static function setSoftBounce($messageID, $error)
    {
        $sql =
             "UPDATE\n"
            ."  `mailqueue_item`\n"
            ."SET\n"
            ."  `mail_error` = \"".$error."\",\n"
            ."  `mail_bounce_count` = `mail_bounce_count`+1,\n"
            ."  `mail_sent` = 0\n"
            ."WHERE\n"
            ."  `mail_messageID` = \"".$messageID."\"";
        static::doSqlQuery($sql);
    }
}
