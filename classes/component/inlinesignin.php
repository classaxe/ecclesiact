<?php
namespace Component;

/*
Version History:
  1.0.4 (2016-01-16)
    1) Now a PSR-2 namespaced component
    2) draw() method is now declared as static

*/
class InlineSignin extends Base
{
    const VERSION = '1.0.4';

    public function __construct()
    {
        $this->_ident =            "inline_signin";
        $this->_parameter_spec =   array(
            'autocomplete_disabled' =>    array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       'Prevents browsers from \'remembering\' username and password'
            ),
            'css_background_disabled' =>  array(
                'match' => 'enum|0,1',
                'default'=>'0',
                'hint'=>'Prevents browsers from \'remembering\' username and password'
            ),
            'footer_public' =>            array(
                'match' => '',
                'default'=>'',
                'hint'=>'Text to show after if person has not signed in'
            ),
            'footer_signedin' =>          array(
                'match' => '',
                'default'=>'',
                'hint'=>'Text to show before if person has signed in'
            ),
            'header_public' =>            array(
                'match' => '',
                'default'=>'',
                'hint'=>'Text to show after if person has not signed in'
            ),
            'header_signedin' =>          array(
                'match' => '',
                'default'=>'',
                'hint'=>'Text to show before if person has signed in'
            ),
            'label_password' =>           array(
                'match' => '',
                'default'=>'',
                'hint'=>'Label to show before password field (if used)'
            ),
            'label_username' =>           array(
                'match' => '',
                'default'=>'',
                'hint'=>'Label to show before username field (if used)'
            ),
            'message_inactive' =>         array(
                'match' => '',
                'default'=>'<b>Sorry:</b> Your account is presently inactive',
                'hint'=>'Message top show if login is inactive'
            ),
            'message_invalid' =>          array(
                'match' => '',
                'default'=>'<b>Sorry:</b> Your credentials are not recognised',
                'hint'=>'Message top show if login is not recognised'
            ),
            'message_missing' =>          array(
                'match' => '',
                'default'=>'Please provide <b>username</b> and <b>password</b>',
                'hint'=>'Message top show if login details are incomplete'
            ),
            'signin_button_alt' =>        array(
                'match' => '',
                'default'=>'Sign In',
                'hint'=>'Text to use for signin button image title  / alt text'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        if (get_userID()) {
            $this->_html.= $this->_cp['header_signedin'].$this->_cp['footer_signedin'];
            return $this->_html;
        }
        switch (get_var('msg')){
            case "invalid":
                $msg_html = "<div id=\"topbar_signin_msg\">".$this->_cp['message_invalid']."</div>";
                $username = "";
                $password = "";
                break;
            case "inactive":
                $msg_html = "<div id=\"topbar_signin_msg\">".$this->_cp['message_inactive']."</div>";
                $username = "";
                $password = "";
                break;
            case "missing":
                $msg_html = "<div id=\"topbar_signin_msg\">".$this->_cp['message_missing']."</div>";
                $username = "";
                $password = "";
                break;
            default:
                $msg_html = "";
                $username = sanitize('html', get_var('topbar_username'));
                $password = sanitize('html', get_var('topbar_password'));
                break;
        }
        if ($this->_cp['autocomplete_disabled']==1) {
            \Output::push('javascript_onload', "  autocomplete_off();\n");
        }
        $this->_html.=
             "<div id=\"topbar_signin\">\n"
            .$msg_html
            .$this->_cp['header_public']
            .($this->_cp['label_username'] ?
                "<label for=\"topbar_username\">".$this->_cp['label_username']."</label>"
             :
                ""
            )
            ."<input type=\"text\" id=\"topbar_username\""
            .($this->_cp['autocomplete_disabled']==1 ?
                " class=\"autocomplete_off\""
             :
                ""
            )
            ." name=\"topbar_username\""
            ." value=\"".$username."\" size=\"10\" maxlength=\"50\""
            ." onkeypress=\"return keytest_enter_execute(event,function(){geid('topbar_password').focus();})\""
            .($this->_cp['css_background_disabled'] ?
                ""
             :
                " onblur=\"this.style.backgroundPosition=(this.value ? '100% 0%' : '0% 0%')\""
            )
            ." onfocus=\"inline_signin_hide_msg();"
            .($this->_cp['css_background_disabled'] ?
                ""
             :
                "this.style.backgroundPosition='100% 0%'"
            )
            ."\" />\n"
            .($this->_cp['label_password'] ?
                "<label for=\"topbar_password\">".$this->_cp['label_password']."</label>"
             :
                ""
            )
            ."<div id=\"topbar_password_container\">\n"
            ."<input type=\"password\" id=\"topbar_password\""
            .($this->_cp['autocomplete_disabled']==1 ?
                " class=\"autocomplete_off\""
             :
                ""
            )
            ." name=\"topbar_password\""
            ." value=\"".$password."\" size=\"10\" maxlength=\"25\""
            ." onkeypress=\"keytest_enter_execute(event,function(){"
            ."geid_set('command','topbar_signin');geid('form').submit()"
            ."})\""
            .($this->_cp['css_background_disabled'] ?
                ""
             :
                " onblur=\"this.style.backgroundPosition=(this.value ? '100% 100%' : '0% 100%')\""
            )
            ." onfocus=\"inline_signin_hide_msg();"
            .($this->_cp['css_background_disabled'] ? "" : "this.style.backgroundPosition='100% 100%'")
            ."\" />\n"
            ."</div>\n"
            ."<input type=\"image\" id=\"topbar_signin_btn\""
            ."alt=\"".$this->_cp['signin_button_alt']."\" title=\"".$this->_cp['signin_button_alt']."\""
            ." onclick=\"geid_set('command','topbar_signin');geid('form').submit();return false;\""
            ." src=\"".BASE_PATH."img/spacer\" />\n"
            .$this->_cp['footer_public']
            ."</div>";
        return $this->_html;
    }
}
