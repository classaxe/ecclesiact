<?php
namespace Component;

define("VERSION_NS_COMPONENT_CONTENT_SIGNIN_MIRROR", "1.0.3");
/*
Version History:
  1.0.3 (2015-03-17)
    1) Moved from Component_Content_Signin_Mirror and MAJORLY reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class ContentSigninMirror extends \Component_Base
{
    protected $content;

    public function __construct()
    {
        $this->_ident =         'content_signin_mirror';
        $this->_parameter_spec = array(
            'public_page' =>    array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Page with content for public'
            ),
            'signed_in_page' => array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Page with content for people who have signed in'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel();
        return $this->_html.$this->content;
    }

    protected function setup($instance = '', $args = array(), $disable_params = false)
    {
        parent::setup($instance, $args, $disable_params);
        $page =         (isset($_SESSION['person']) ? $this->_cp['signed_in_page'] : $this->_cp['public_page']);
        $path =       '//'.trim($page, '/').'/';
        $Obj_Page =     new \Page;
        $Obj_Page->_set_ID($Obj_Page->get_ID_by_path($path));
        $content = $Obj_Page->get_field('content');
        if ($content===false) {
            $this->content = $this->_safe_ID.": Page not found - ".$path;
            return;
        }
        $this->content = $content;
    }

    public function getVersion()
    {
        return VERSION_NS_COMPONENT_CONTENT_SIGNIN_MIRROR;
    }
}
