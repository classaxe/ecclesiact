<?php
namespace Component;
/*
Version History:
  1.0.2 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class EditYourProfile extends Base
{
    const VERSION = '1.0.2';

    protected $_msg = '';
    protected $_submode;
    protected $_Obj_User;

    public function __construct()
    {
        $this->_ident =         'edit_your_profile';
        $this->_parameter_spec =    array(
            'help_page' =>      array(
                'match' =>      '',
                'default' =>    '_help_user_toolbar_profile',
                'hint' =>       'Help page to display if user click on help icon'
            ),
            'page_public' =>    array(
                'match' =>      '',
                'default' =>    '/signin',
                'hint' =>       'Page to redirect to if member is notr signed in'
            ),
            'report_name' =>    array(
                'match' =>      '',
                'default' =>    'user',
                'hint' =>       'Name of report to use for signup form fields'
            ),
            'shadow' =>         array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       'Whether or not to draw shadow round dialog'
            ),
            'text_button' =>    array(
                'match' =>      '',
                'default' =>    'Update',
                'hint' =>       'Your Text Here'
            ),
            'text_heading' =>   array(
                'match' =>      '',
                'default' =>    'Edit Your Profile',
                'hint' =>       'Your Text Here'
            ),
            'width' =>          array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'Blank to accept default, or number to specify form width'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawForm();
        return $this->_html;
    }

    public function drawForm()
    {
        global $ID;
        $old_ID = $ID;
        $ID =    $this->_Obj_User->_get_ID();
        $Obj_Report_Form =  new \Report_Form;
        $content =
            $Obj_Report_Form->draw($this->_cp['report_name'], false, false, false, $this->_cp['width']);
        $controls =
             "<input type=\"button\" value=\"".$this->_cp['text_button']."\""
            ." onclick=\"geid('submode').value='save';geid('form').submit();\" class='formbutton'/>";
        $this->_html.=
        \HTML::drawFormBox(
            $this->_cp['text_heading'],
            $content,
            $this->_cp['help_page'],
            $this->_cp['shadow'],
            false,
            $controls
        );
        $ID = $old_ID;
    }

    protected function setup($instance = '', $args = array(), $disable_params = false)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadUser();
        $this->setupLoadComponentResults();
    }

    protected function setupLoadComponentResults()
    {
        component_result_set('NFirst', $this->_Obj_User->record['NFirst']);
        component_result_set('NLast', $this->_Obj_User->record['NLast']);
        component_result_set('NMiddle', $this->_Obj_User->record['NMiddle']);
        component_result_set('PUsername', $this->_Obj_User->record['PUsername']);
    }

    protected function setupLoadUser()
    {
        if (!get_userID()) {
            $this->setupRedirectPublic();
        }
        $this->_Obj_User = new \User(get_userID());
        if (!$this->_Obj_User->load()) {
            $this->setupRedirectPublic();
        }
    }

    protected function setupRedirectPublic()
    {
        header("Location: ".BASE_PATH.trim($this->_cp['page_public'], '/'));
        print "&nbsp;";
        die();
    }
}
