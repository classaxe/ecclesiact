<?php
define("VERSION_COMPONENT_PRAYER_REQUEST", "1.0.0");
/*
Version History:
  1.0.0 (2015-02-14)
    1) Initial release - Moved from Church Module

*/


class Component_Prayer_Request extends Component_Base
{
    public function __construct()
    {
        global $system_vars;
        $this->_ident =             "prayer_request";
        $this->_parameter_spec =    array(
            'email' =>    array(
                'match' =>      '',
                'default' =>    $system_vars['adminEmail'],
                'hint' =>       'Person to send email requests to'
            ),
            'thanks' =>   array(
                'match' =>      '',
                'default' =>    'prayer-request/thanks',
                'hint' =>       'Page to show after sending request'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        global $system_vars;
        $this->_setup($instance, $args, $disable_params);
        $this->_draw_control_panel(true);
        switch(get_var('submode')){
            case 'send_prayer_request':
                $Obj = new Prayer_Request;
                $data = array(
                    'systemID' =>       SYS_ID,
                    'content' =>        addslashes(get_var('description')),
                    'type' =>           'prayer-request',
                    'status' =>         'New',
                    'xml:AEmail' =>     addslashes(get_var('AEmail')),
                    'xml:ATelephone' => addslashes(get_var('ATelephone')),
                    'xml:NName' =>      addslashes(get_var('NName'))
                );
                $ID = $Obj->insert($data);
                $Obj = new Report();
                $Obj->_set_ID($Obj->get_ID_by_name('module.church.prayer-requests-report'));
                $Obj->email_form($ID, "Prayer Request", $this->_cp['email']);
                header('Location: '.BASE_PATH.trim($this->_cp['thanks'], '/'));
                print('Redirecting...');
                die;
            break;
        }
        $this->_html.=
             "<table width='400' cellpadding='0' border='0' cellspacing='0' align='center'>"
            ."  <tr>\n"
            ."    <td>".draw_auto_form('module.church.prayer-request-form', 0)."</td>\n"
            ."  </tr>"
            ."  <tr>\n"
            ."    <td align='center' colspan='2' class='table_admin_h'>\n"
            ."<input type='button' value='Submit' onclick=\""
            ."if (confirm('Send prayer request?')){ geid_set('submode','send_prayer_request');geid('form').submit(); }"
            ." else {alert('Cancelled');}"
            ."\" class='formbutton' style='width: 60px;'>\n"
            ."</td>\n"
            ."  </tr>\n"
            ."</table>";
        return $this->_html;
    }

    public function get_version()
    {
        return VERSION_COMPONENT_PRAYER_REQUEST;
    }
}
