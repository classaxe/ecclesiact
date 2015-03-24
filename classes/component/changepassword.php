<?php
namespace Component;

define("VERSION_NS_COMPONENT_CHANGE_PASSWORD", "1.0.2");
/*
Version History:
  1.0.2 (2015-03-17)
    1) Moved from Component_Change_Password and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class ChangePassword extends Base
{
    protected $_command;
    protected $_done = false;
    protected $_msg = '';
    protected $_Obj_User;
    protected $_pwd;
    protected $_pwd2;


    public function __construct()
    {
        $this->_ident =         'password';
        $this->_parameter_spec = array(
            'help_page' =>            array(
                'match' =>      '',
                'default' =>    '_help_user_toolbar_password',
                'hint' =>       'Help page to display if user click on help icon'
            ),
            'page_public' =>          array(
                'match' =>      '',
                'default' =>    '/signin',
                'hint' =>       'Page to redirect to if member is notr signed in'
            ),
            'shadow'=>                array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint'=>'0|1'
            ),
            'text_confirm_password'=> array(
                'match' =>      '',
                'default' =>    'Confirm Password',
                'hint' =>       'Text to use for Confirm Password label'
            ),
            'text_heading' =>         array(
                'match' =>      '',
                'default' =>    'Change Your Password',
                'hint' =>       'Your Text Here'
            ),
            'text_initial' =>         array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Your Text Here'
            ),
            'text_new_password'=>     array(
                'match' =>      '',
                'default' =>    'New Password',
                'hint' =>       'Text to use for New Password label'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawStatus();
        $this->drawInitial();
        $this->drawForm();
        $this->drawDone();
        return $this->_html;
    }

    protected function drawDone()
    {
        if (!$this->_done) {
            return;
        }
        $this->_html.=
            "<p class='txt_c'><input type='submit' value='Done' onclick=\"geid_set('goto','')\" /></p>";
    }

    public function drawForm()
    {
        if ($this->_done) {
            return;
        }
        $content =
             "  <label for='change_password' style='width: 10em'>"
            .$this->_cp['text_new_password']
            ."</label>\n"
            ."  <input type='password' id='change_password' name='change_password' size='20'value=\"\""
            ." style='width: 180px;'/>\n"
            ."  <br class='clear' />\n"
            ."  <label for='change_password2' style='width: 10em'>"
            .$this->_cp['text_confirm_password']
            ."</label>\n"
            ."  <input type='password' id='change_password2' name='change_password2' size='20' value=\"\""
            ." style='width: 180px;'/>\n";
        $controls =
             "<input type='submit' value='Change Password' style='formButton'"
            ." onclick=\"geid('command').value='Change Password';\"/>";
        $this->_html.=  \HTML::drawFormBox(
            $this->_cp['text_heading'],
            $content,
            "_help_user_toolbar_password",
            $this->_cp['shadow'],
            false,
            $controls
        );
    }

    protected function drawInitial()
    {
        if ($this->_command) {
            return;
        }
        $this->_html.= $this->_cp['text_initial'];
    }

    protected function setup($instance = '', $args = array(), $disable_params = false)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadPerson();
        $this->setupDoCommand();
    }

    protected function setupDoCommand()
    {
        $this->_command =   get_var('command');
        $this->_pwd =       get_var('change_password');
        $this->_pwd2 =      get_var('change_password2');
        switch ($this->_command) {
            case "Change Password":
                if ($this->_pwd != $this->_pwd2) {
                    $this->_msg =    "<b>Error</b>: Both passwords should match";
                    return;
                }
                if (strlen($this->_pwd)<5) {
                    $this->_msg =    "<b>Error</b>: You must enter a password of at least 5 characters.";
                    return;
                }
                $Obj = new \User(get_userID());
                $Obj->set_field('PPassword', encrypt(strToLower($this->_pwd)));
                $_SESSION['person']['PPassword'] = $this->_pwd;
                $this->_done = true;
                $this->_msg =    "<b>Success</b>: Your password has been changed.";
                break;
        }

    }

    protected function setupLoadPerson()
    {
        if (!get_userID()) {
            $this->setupRedirectPublic();
        }
        $this->_Obj_User = new \User(get_userID());
        if (!$this->_Obj_User->exists()) {
            $this->setupRedirectPublic();
        }
    }

    protected function setupRedirectPublic()
    {
        if (!get_userID()) {
            header("Location: ".BASE_PATH.trim($this->_cp['page_public'], '/'));
            print "&nbsp;";
            die();
        }
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_CHANGE_PASSWORD;
    }
}
