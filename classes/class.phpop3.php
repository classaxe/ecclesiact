<?php
define("VERSION_PHPOP3", "1.0.4");
/*
Version History:
  1.0.4 (2016-12-26)
    1) Constructor renamed to __construct for PHP 7.0
  1.0.3 (2016-05-01)
    1) Made more PSR-2 compliant and constructor now returns true for success, false for fail
    2) Now uses an stdClass as a message container
*/

  /**
  *    phPOP3 - A PHP implementation of the Post Office Protocol 3 (POP3)
  *    See readme.htm for detailed instructions on installation and usage.
  *
  *    This software and all associated files are released
  *    under the GNU Lesser Public License (LGPL), see license.txt for details.
  *
  * @version 1.0.2
  * @author  Sebastian Bergmann <sebastian.bergmann@web.de>, Mathias Meyer <pom@beatsteaks.de>
  */

class phPOP3
{
    public $socket = -1;
    public $status;
    public $connect_error = false;

    public function __construct($server = '', $port = '', $username = '', $password = '')
    {
        
        if ($server) {
            if ($this->pop3_connect($server, $port)) {
                if ($this->pop3_user($username)) {
                    if ($this->pop3_pass($password)) {
                        $this->connect_error = false;
                        return;
                    }
                }
            }
        }
        $this->connect_error = true;
    }

    public function pop3_command($command)
    {
        if ($this->socket!==-1) {
            $command    =    $command . "\r\n";
            fputs($this->socket, $command);
            $line            = fgets($this->socket, 1024);
//            d('Command: '.$command.' Result: '.$line);
            $this->status[ "lastresult" ] = substr($line, 0, 1);
            $this->status[ "lastresultmessage" ]    = $line;
            if ($this->status[ "lastresult" ] != "+") {
                return false;
            }
            return true;
        }
        return false;
    }

    public function pop3_connect($server, $port)
    {
        if (!$this->socket = @fsockopen($server, $port)) {
            $this->error = 'Unknown server for mail identity: '.$server.", port:".$port;
            return false;
        }
        if (!$this->socket) {
            $this->error = 'No socket';
            return false;
        }
        $line    = fgets($this->socket, 1024);
        $this->status[ "lastresult" ] = substr($line, 0, 1);
        $this->status[ "lastresultmessage" ] = $line;
        if ($this->status[ "lastresult" ] != "+") {
            return false;
        }
        return true;
    }

    public function pop3_user($username)
    {
        $command =  "USER " . $username;
        $result =   $this->pop3_command($command);
        if (!$result) {
            fclose($this->socket);
            $this->socket = -1;
        }
        return $result;
    }

    public function pop3_pass($password)
    {
        $command =  "PASS " . $password;
        $result =   $this->pop3_command($command);
        if (!$result) {
            fclose($this->socket);
            $this->socket = -1;
        }
        return $result;
    }

    public function pop3_stat()
    {
        $this->pop3_command("STAT");
        if (!preg_match("/+OK (.*) (.*)/i", $this->status[ "lastresultmessage" ], $result)) {
            return false;
        }
        return $result[1];
    }

    public function pop3_list()
    {
        $this->pop3_command("LIST");
        if ($this->status[ "lastresult" ] != "+") {
            return false;
        }
        $i = 0;
        while (substr($line = fgets($this->socket, 1024), 0, 1) != ".") {
            $mailbox[ $i ] = $line;
            $i++;
        }
        $mailbox[ "messages" ] = $i;
        return $mailbox;
    }

    public function pop3_retrieve($message_id)
    {
        $command =  "RETR " . $message_id;
        $this->pop3_command($command);
        if ($this->status[ "lastresult" ] != "+") {
            return false;
        }
        $i =        0;
        $header =   1;
        while (!(substr($line = fgets($this->socket, 1024), 0, 1) == "." and substr($line, 1, 1) != ".")) {
            if (!$header) {
                $body[ $i ] = preg_replace("/^\.\./", ".", $line);
                $i++;
            } else {
                if (substr($line, 0, 6) == "Date: ") {
                    $date = substr($line, 6);
                } elseif (substr($line, 0, 6) == "From: ") {
                    $from = substr($line, 6);
                } elseif (substr($line, 0, 10) == "Reply-To: ") {
                    $reply_to = substr($line, 10);
                } elseif (substr($line, 0, 9) == "Subject: ") {
                    $subject = substr($line, 9);
                } elseif (substr($line, 0, 4) == "To: ") {
                    $to = substr($line, 4);
                }
            }
            if (( $header == 1 ) && ( strlen($line) == 2 )) {
                $header = 0;
            }
        }
        $body[ "lines" ] = $i;
        $message = new stdClass();
        $message->body =        @$body;
        $message->date =        @$date;
        $message->from =        @$from;
        $message->reply_to =    @$reply_to;
        $message->subject =     @$subject;
        $message->to =          @$to;
        return $message;
    }

    public function pop3_delete($message_id)
    {
        $command =      "DELE " . $message_id;
        return $this->pop3_command($command);
    }

    public function pop3_quit()
    {
        $result = $this->pop3_command("QUIT");
        fclose($this->socket);
        $this->socket = -1;
        return $result;
    }

    public function pop3_show_error()
    {
        echo $this->status[ "lastresultmessage" ] . "<br>";
    }

    public static function getVersion()
    {
        return VERSION_PHPOP3;
    }
}
