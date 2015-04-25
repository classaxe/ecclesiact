<?php
namespace Component;

define("VERSION_COMPONENT_EMAIL_OPT_IN", "1.0.1");
/*
Version History:
  1.0.1 (2015-04-24)
    1) Moved from class.component_email_opt_in.php and reworked to use namespaces
    2) Now Fully PSR-2 compliant
*/
class EmailOptIn extends Base
{
    public function __construct()
    {
        $this->_ident =         'email_opt_in';
        $this->_parameter_spec = array(
            'show_subscription_details' =>    array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'text_title' =>                   array(
                'match' =>      '',
                'default' =>    "Confirm Email Subscription",
                'hint' =>       'Text to place above component'
            ),
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        try {
            $this->setup($instance, $args, $disable_params);
            $this->drawControlPanel(true);
            $this->drawHeading();
            $this->drawStatus();
            $this->drawSubscriptionDetails();
            $this->drawControls();
            return $this->_html;
        } catch (\Exception $e) {
            $this->_html.= "<p>".$e->getMessage()."</p>";
            return $this->_html;
        }
    }

    protected function drawControls()
    {
        if (get_var('submode')==='all') {
            return;
        }
        $this->_html .=
             "<p><b>Please confirm that you wish to receive occasional emails from us:</b></p>\n"
            ."<p>\n"
            ."<input type=\"hidden\" id=\"ID\" name=\"ID\" value=\"".$this->_get_ID()."\"/>\n"
            ."<input type=\"button\" value=\"Confirm\" style=\"width:8em\" onclick=\""
            ."if(confirm('Do you wish to receive messages from us?')){"
            ." geid_set('submode','all');geid('form').submit();"
            ."}\"/>\n"
            ."<input type=\"button\" value=\"Cancel\" style=\"width:8em\" onclick=\""
            ."window.location.assign('".BASE_PATH."')"
            ."\" />\n"
            ."</p>";
    }

    protected function drawHeading()
    {
        $this->_html.=  "<h2 style='margin:0.25em 0'>".$this->_cp['text_title']."</h2>";
    }

    protected function drawSubscriptionDetails()
    {
        if (!$this->_cp['show_subscription_details']) {
            return;
        }
        $this->_html.=
             "<p><b>Your subscription details:</b></p>"
            ."<table border='1' cellpadding='5' cellspacing='0' style='border-collapse:collapse'>\n"
            ."  <thead style='background:#d8d8d8'>\n"
            ."    <tr>\n"
            ."      <th>Your Name</th>\n"
            ."      <th>Your Email</th>\n"
            ."    </tr>\n"
            ."  </thead>\n"
            ."  <tbody>\n"
            ."    <tr>\n"
            ."      <td>".$this->_record['recipient_name']."</td>\n"
            ."      <td>".$this->_record['recipient_email']."</td>\n"
            ."    </tr>\n"
            ."  </tbody>\n"
            ."</table><br />\n";
    }


    protected function setup($instance = '', $args = array(), $disable_params = false)
    {
        global $page_vars;
        parent::setup($instance, $args, $disable_params);
        $this->_Obj_MQI =   new \Mail_Queue_Item(sanitize('ID', $page_vars['path_extension']));
        if (!$this->_record = $this->_Obj_MQI->get_message_details()) {
            throw new \Exception('Sorry - that message is no longer on our server.');
        }
        $this->doSubmode();
    }

    protected function doSubmode()
    {
        if (get_var('submode')!=='all') {
            return;
        }
        $Obj_Person = new \Person($this->_record['personID']);
        $records = $Obj_Person->get_group_membership();
        foreach ($records as $r) {
            if ($r['systemID']==SYS_ID) {
                $Obj_Group = new \Group($r['groupID']);
                $current = $Obj_Group->member_perms($this->_record['personID']);
                if ($current['permEMAILRECIPIENT']==1) {
                    $permArr = array(
                        'permEMAILOPTIN' =>     1,
                        'permEMAILOPTOUT' =>    0
                    );
                    $Obj_Group->member_assign($this->_record['personID'], $permArr, 'User Opt-In via link');
                }
            }
        }
        $this->_msg= "<b>Success:</b> You have confirmed your email subscription with us.";
    }

    public static function getVersion()
    {
        return VERSION_COMPONENT_EMAIL_OPT_IN;
    }
}
