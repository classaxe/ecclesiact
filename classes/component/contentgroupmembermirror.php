<?php
namespace Component;
/*
Version History:
  1.0.5 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class ContentGroupMemberMirror extends Base
{
    const VERSION = '1.0.5';

    protected $content;

    public function __construct()
    {
        $this->_ident =         'content_group_member_mirror';
        $this->_parameter_spec = array(
            'page_choices_csv' => array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list format: page|group,page|group...'
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
        $ObjGroup =   new \Group;
        $personID =         get_userID();
        $page_choice_arr =  explode(",", $this->_cp['page_choices_csv']);
        foreach ($page_choice_arr as $page_choice) {
            $matched =    true;
            $choice =     explode("|", $page_choice);
            $path =       '//'.trim($choice[0], '/').'/';
            $group =      (isset($choice[1]) ? $choice[1] : false);
            if ($group) {
                $matched = false;
                if ($groupID = $ObjGroup->get_ID_by_name($group)) {
                    $ObjGroup->_set_ID($groupID);
                    if ($perms = $ObjGroup->member_perms($personID)) {
                        if ($perms['permVIEWER']==1 || $perms['permEDITOR']==1) {
                            $matched = true;
                        }
                    }
                }
            }
            if ($matched) {
                $Obj_Page = new \Page;
                $Obj_Page->_set_ID($Obj_Page->get_ID_by_path($path));
                $content = $Obj_Page->get_field('content');
                if ($content!==false) {
                    $this->content = $content;
                    return;
                }
                $this->content = $this->_safe_ID.": Page not found - ".$path;
                return;
            }
        }
    }
}
