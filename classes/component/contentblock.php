<?php
namespace Component;

define("VERSION_NS_COMPONENT_CONTENT_BLOCK", "1.0.1");
/*
Version History:
  1.0.1 (2015-03-17)
    1) Moved from Component_Content_Block and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class ContentBlock extends Base
{
    protected $_record;
    protected static $style="";

    public function __construct()
    {
        $this->_ident = "draw_content_block";
        $this->_parameter_spec =    array(
            'name' =>   array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Name of content block to insert'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawCss();
        $this->drawControlPanel();
        $this->drawContentBlock();
        return $this->render();
    }

    protected function drawCss()
    {
        if (
            $this->_record['style'] &&
            preg_replace('/\s+/', '', $this->_record['style'])!='.content_block_'.$this->_record['name'].'{}'
        ) {
            \Page::push_content(
                'style',
                "/* [Content Block: ".$this->_cp['name']."] */\r\n"
                .$this->_record['style']
                ."\r\n"
            );
        }
    }

    protected function drawContentBlock()
    {
        $canEdit =
            $this->_ID &&
            $this->_current_user_rights['canEdit'] &&
            ($this->_record['systemID']==SYS_ID || $this->_current_user_rights['isMASTERADMIN']);
        $this->_html.=
            "<div"
            .($canEdit ?
                 " title='".$this->_tooltip."' "
                ."onmouseover=\""
                ."if(!CM_visible('CM_content_block')) {"
                ."window.status='".$this->_tooltip."';"
                ."this.style.backgroundColor='"
                .($this->_record['systemID']==SYS_ID ? '#80ff80' : '#ffe0e0')
                ."';"
                ."_CM.type='content_block';"
                ."_CM.ID='".$this->_ID."';"
                ."_CM_text[0]='&quot;".$this->_cp['name']."&quot;';"
                ."};return false;\" "
                ."onmouseout=\""
                ."if(!CM_visible('CM_event')){"
                ."this.style.backgroundColor=''"
                ."};"
                ."window.status='';"
                ."_CM.type='';return false;\""
             :
                ""
            )
            .">"
            .($this->_ID ?
                ($this->_current_user_rights['canEdit'] && $this->_record['content']=='' ?
                     "<span style='background-color:#ffffd0;'>Content Block ".$this->_instance." -<br />"
                    ."<b>\"".$this->_cp['name']."\"</b> is empty...<br /><i>Right-click to edit</i></span>"
                 :
                    $this->_record['content']
                )
                :
                ($this->_current_user_rights['canEdit'] ?
                     "<a href=\"".BASE_PATH."details/".$this->_form."/\""
                    ." style='text-decoration:none;color:#0000ff;font-weight:bold;'"
                    ." onclick=\"details('".$this->_form."','','".$this->_popup['h']."','".$this->_popup['w']."'"
                    .",'','','','name=".$this->_instance."&amp;style=.content_block_home-contact {   }');"
                    ."return false;\""
                    .">"
                    ."[ICON]11 11 1188 Create new ".$this->_get_object_name()."[/ICON]\n"
                    ."&nbsp;Create new ".$this->_get_object_name()." \"".$this->_instance."\"</a>"
                 :
                    ""
                )
            )
            ."</div>\n";
    }

    protected function render()
    {
        return
             "<div style='zoom:100%' id=\"content_block_".$this->_instance."\""
            ." class=\"content_block_".$this->_record['name']."\">\n"
            .$this->_html
            ."</div>\n";
    }

    protected function setup($instance, $args, $disable_params)
    {
        $this->_parameter_spec['name']['default'] = $instance;
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadUserRights();
        $this->setupLoadRecord();
        $this->setupLoadEditParameters();
    }

    protected function setupLoadEditParameters()
    {
        if ($this->_current_user_rights['canEdit']) {
            $edit_params =        $this->_Obj->get_edit_params();
            $this->_form =        $edit_params['report'];
            $this->_popup =       get_popup_size($this->_form);
            $this->_tooltip =     "Right-click to edit &quot;".$this->_instance."&quot;";
        }
    }

    protected function setupLoadRecord()
    {
        $this->_Obj =       new \Content_Block;
        $this->_ID =        $this->_Obj->get_ID_by_name($this->_cp['name'], '1,'.SYS_ID);
        $this->_Obj->_set_ID($this->_ID);
        $this->_record =    $this->_Obj->load();
    }

    public function getVersion()
    {
        return VERSION_NS_COMPONENT_CONTENT_BLOCK;
    }
}
