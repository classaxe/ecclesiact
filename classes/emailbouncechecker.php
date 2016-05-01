<?php
/*
Version History:
  1.0.3 (2016-05-01)
    1) Changes to allow this to be used as a VCRON schedualable task, always checking all identities
*/
class EmailBounceChecker extends MailIdentity
{
    const VERSION = '1.0.3';
    const DEBUG =   true;   // writes debug messages to debug log
    const PRUNE =   true;   // Removes all delivery status notifications, even those not matched to broadcast message
    const LIMIT =   300;    // Maximum limit of messages to process in a single run

    protected $mailIdentity;

    public function check()
    {
        @set_time_limit(600);    // Extend maximum execution time to 10 mins
        // Get login credentials for mailqueues
        $mailIdentities = static::getMailIdentitesUsed();
        foreach ($mailIdentities as $mailIdentity) {
            $this->mailIdentity = $mailIdentity;
            $this->checkForMailIdentity();
        }
    }

    protected function checkForMailIdentity()
    {
        $Obj_POP3 = new phPOP3(
            $this->mailIdentity['bounce_pop3_host'],
            $this->mailIdentity['bounce_pop3_port'],
            $this->mailIdentity['bounce_pop3_username'],
            $this->mailIdentity['bounce_pop3_password']
        );
        $mailbox = $Obj_POP3->pop3_list();
        static::d();
        static::d('getting messages');
        if ($mailbox["messages"] === 0) {
            static::d('No Messages');
            $Obj_POP3->pop3_quit();
            return;
        }
        static::d($mailbox["messages"].' messages');
        $processed = 0;
        for ($i=1; $i<$mailbox["messages"]+1; $i++) {
            if ($processed > static::LIMIT) {
                static::d('Limit of messages per operaration is '.static::LIMIT.' - quitting for now');
                $Obj_POP3->pop3_quit();
                return;
            }
            static::d('Message #'.$i.' is being retrieved');
            $message =  $Obj_POP3->pop3_retrieve($i);
            $body =     implode('', $message->body);
            $start =    strpos($body, "Content-Type: message/delivery-status");
            if ($start===false) {
                static::d('Message #'.$i.' is not delivery-status');
                continue;
            }
            $text = substr($body, $start);
            preg_match("/Message-ID: <([^>]+)>/", $text, $messageID);
            $messageID =    (isset($messageID[1]) ? strip_tags($messageID[1]) : false);
            if ($messageID===false) {
                static::d('Message #'.$i.' has no Message-ID');
                continue;
            }
            preg_match("/Status: ([0-9.]+)/", $text, $status);
            preg_match("/Diagnostic-Code:(.*?)\r\n\r\n/s", $text, $diagnostic);
            $status =       (isset($status[1]) ? $status[1] : false);
            $diagnostic =   (isset($diagnostic[1]) ? str_replace(array('\r\n','  '), ' ', $diagnostic[1]) : "");
            $soft =         substr($status, 0, 1)!='5';  // Not permanent error
            $mqi =          static::getMailqueueItemForMessageID($messageID);
            if (!$mqi) {
                static::d('Message #'.$i.' '.$messageID.' is not associated with a broadcast');
                if (self::PRUNE) {
                    $Obj_POP3->pop3_delete($i);
                    static::d('Message #'.$i.' '.$messageID.' - PRUNE option set so removed it anyway');
                    $processed++;
                }
                continue;
            }
            static::d('Message #'.$i.' '.$messageID.' matches ID '.$mqi['ID'].' for mailqueue '.$mqi['mailqueueID']);
            $error =    $status." ".$diagnostic;
            if ($soft) {
                static::setSoftBounce($messageID, $error);
                static::d('Message #'.$i.' '.$messageID.' ID:'.$mqi['ID'].' is soft bounce, will retry');
            } else {
                static::d('Message #'.$i.' '.$messageID.' ID:'.$mqi['ID'].' is hard bounce');
                static::setHardBounce($messageID, $error);
            }
            $Obj_POP3->pop3_delete($i);
            static::d('Message #'.$i.' '.$messageID.' ID:'.$mqi['ID'].' was deleted from mailbox');
            $processed++;
        }
        static::d('Logging out');
        $Obj_POP3->pop3_quit();
    }

    protected function d($message = false)
    {
        if (self::DEBUG) {
            d($message ? $this->mailIdentity['bounce_pop3_username'].' | '.$message : str_repeat('*', 100));
        }
    }

    protected static function getMailIdentitesUsed()
    {
        $sql =
             "SELECT DISTINCT\n"
            ."  `mailidentity`.`bounce_pop3_host`,\n"
            ."  `mailidentity`.`bounce_pop3_password`,\n"
            ."  `mailidentity`.`bounce_pop3_port`,\n"
            ."  `mailidentity`.`bounce_pop3_username`\n"
            ."FROM\n"
            ."  `mailqueue`\n"
            ."LEFT JOIN `mailidentity` ON\n"
            ."  `mailidentity`.`ID` = `mailqueue`.`mailidentityID`\n";
        return static::getRecordsForSql($sql);
    }

    protected static function getMailqueueItemForMessageID($messageID)
    {
        $sql =
             "SELECT\n"
            ."  `ID`,\n"
            ."  `mailqueueID`\n"
            ."FROM\n"
            ."  `mailqueue_item`\n"
            ."WHERE\n"
            ."  `mail_messageID` = \"".$messageID."\"";
        return static::getRecordForSql($sql);
    }

    protected static function setHardBounce($messageID, $error)
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
    
    protected static function setSoftBounce($messageID, $error)
    {
        $sql =
             "UPDATE\n"
            ."  `mailqueue_item`\n"
            ."SET\n"
            ."  `date_completed` = '0000-00-00 00:00:00'\n"
            ."  `mail_error` = \"".$error."\",\n"
            ."  `mail_bounce_count` = `mail_bounce_count`+1,\n"
            ."  `mail_sent` = 0,\n"
            ."WHERE\n"
            ."  `mail_messageID` = \"".$messageID."\"";
        static::doSqlQuery($sql);
    }
}
