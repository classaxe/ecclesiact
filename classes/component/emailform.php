<?php
namespace Component;

/*
Version History:
  1.0.3 (2015-10-09)
    1) Bug fix in EmailForm::doSubmode() to prevent sending of message if submode is anything other than 'send'
    2) Now determines whether there is ANYTHING to send (whether fields were defined or not)
       and gives an error if there isn't

*/
class EmailForm extends Base
{
    const VERSION = '1.0.3';

    protected $_email_body_html;
    protected $_email_body_text;
    protected $_email_errors = "";
    protected $_email_subject;

    public function __construct()
    {
        global $system_vars;
        $this->_ident =             'email_form';
        $this->_parameter_spec = array(
            'address' =>      array(
                'match' =>      '',
                'default' =>    $system_vars['adminEmail'],
                'hint' =>       'user1@example.com, user2@example.com, ...'
            ),
            'success' =>      array(
                'match' =>      '',
                'default' =>    'contact-success',
                'hint' =>       'Page to display if succeeded'
            ),
            'title' =>        array(
                'match' =>      '',
                'default' =>    'Form submission from (system) via (page)',
                'hint' =>       'Title to give email message - automatically set if not specified'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawStatus();
        return $this->_html;
    }

    protected function doSubmode()
    {
        if (!isset($_POST['submode']) || $_POST['submode']!=='send') {
            return false;
        }
        $this->validate();
        if ($this->_email_errors==='') {
            $this->prepareMessage();
            $this->sendEmail();
        }
        if ($this->_email_errors!='') {
            $this->_msg = "<b>Error:</b><br />".str_replace("\n", '', trim(nl2br($this->_email_errors), "\n"));
            return false;
        }
        header("Location: ".BASE_PATH.$this->_cp['success']);
        die();
    }

    protected function prepareMessage()
    {
        global $system_vars, $page_vars;
        $this->_email_subject = ($this->_cp['title']!='Form submission from (system) via (page)' ?
            $this->_cp['title']
         :
            "Form submission from ".$system_vars['textEnglish']." via ".trim($page_vars['path'], '/')
        );
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $browser = get_browser($_SERVER['HTTP_USER_AGENT']);
        }
        $this->_email_body_html =
             "<h1>".$this->_email_subject."</h1>\n"
            .(isset($_SERVER['REMOTE_ADDR']) || isset($_SERVER["HTTP_REFERER"]) || isset($_SERVER['HTTP_USER_AGENT']) ?
                 "<ul>\n"
                .(isset($_SERVER['REMOTE_ADDR']) ?
                     "  <li>Email from IP Address ".$_SERVER['REMOTE_ADDR']
                    ." (Hostname: ".gethostbyaddr($_SERVER['REMOTE_ADDR']).")</li>\n"
                 :
                    ""
                 )
                .(isset($_SERVER['HTTP_USER_AGENT']) ?
                     "  <li>Browser used: ".$browser->parent
                    ." running on ".$browser->platform." (UA string is ".$_SERVER['HTTP_USER_AGENT'].")</li>\n"
                 :
                    ""
                 )
                .(isset($_SERVER["HTTP_REFERER"]) ?
                    "  <li>Referer: ".$_SERVER["HTTP_REFERER"]."</li>\n"
                 :
                    ""
                 )
                ."</ul>\n"
            :
                ""
            )
            ."<table cellpadding='2' cellspacing='0' border='1' bordercolor='#808080' bgcolor='#ffffff'>\n"
            ."  <tr>\n"
            ."    <th align='left' style='text-align:left;background-color:#e0e0e0;'>Field</th>\n"
            ."    <th align='left' style='text-align:left;background-color:#e0e0e0;'>Value</th>\n"
            ."  </tr>\n";
        $this->_email_body_text =
            $this->_email_subject."\n"
            .(isset($_SERVER['REMOTE_ADDR']) ?
                "Email from IP Address ".$_SERVER['REMOTE_ADDR']."\n"
             :
                ""
             )
            .(isset($_SERVER["HTTP_REFERER"]) ?
                "Referer: ".$_SERVER["HTTP_REFERER"]."\n"
             :
                ""
             )
            .pad("FIELD", 25)."VALUE\n"
            ."---------------------------------------------------------\n";
        $ignore_arr =   explode(",", SYS_STANDARD_FIELDS);
        foreach ($_POST as $field => $value) {
            if ($value!=="" && !in_array($field, $ignore_arr) && substr($field, 0, 19)!='poll_max_votes_for_') {
                $value = sanitize('html', $value);
                $this->_email_body_text.= pad($field, 25).$value."\n";
                $this->_email_body_html.=
                     "  <tr>\n"
                    ."    <th align='left' style='text-align:left'>".$field."</th>\n"
                    ."    <td>".($value=="" ? "&nbsp;" : $value)."</td>\n"
                    ."  </tr>\n";
            }
        }
        $this->_email_body_html.= "</table>";
    }

    protected function sendEmail()
    {
        global $system_vars;
        get_mailsender_to_component_results(); // Use system default mail sender details
        component_result_set('from_name', $system_vars['adminName']);
        component_result_set('from_email', $system_vars['adminEmail']);
        $data =             array();
        $data['subject'] =  $this->_email_subject;
        $data['html'] =     $this->_email_body_html;
        $data['text'] =     $this->_email_body_text;
        if (get_var('Email')) {
            $data['replyto_email'] = get_var('Email');
            if (get_var('Name')) {
                $data['replyto_name'] = get_var('Name');
            }
        }
        $PEmail_arr =       explode(',', $this->_cp['address']);
        foreach ($PEmail_arr as $PEmail) {
            $data['PEmail'] = trim($PEmail);
            $data['NName'] =  trim($PEmail);
            $mail_result = mailto($data);
            if (substr($mail_result, 0, 12)!="Message-ID: ") {
                $this->_email_errors.= $mail_result."\n";
            }
        }
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->doSubmode();
    }

    protected function validate()
    {
        $has_content = false;
        $ignore_arr =   explode(",", SYS_STANDARD_FIELDS);
        foreach ($_POST as $field => $value) {
            if ($value!=="" && !in_array($field, $ignore_arr) && substr($field, 0, 19)!='poll_max_votes_for_') {
                $has_content = true;
                break;
            }
        }
        if (!$has_content) {
            $this->_email_errors.= "There is nothing in your message for me to send.\n";
        }
        $email = (isset($_POST['Email']) ? $_POST['Email'] : false);
        if ($email !== false && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_email_errors.= "Invalid Email address ".$email."\n";
        }
        $name = (isset($_POST['Name']) ? $_POST['Name'] : false);
        if ($name !== false && strlen(trim($name)) < 3) {
            $this->_email_errors.= "Please provide your name.\n";
        }
        $message = (isset($_POST['Message']) ? $_POST['Message'] : false);
        if ($name !== false && strlen(trim($message)) < 3) {
            $this->_email_errors.= "Please enter a useful message in the space provided.\n";
        }
    }

    public static function getVersion()
    {
        return EmailForm::VERSION;
    }
}
