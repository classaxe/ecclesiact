<?php
/*
Version History:
  1.0.6 (2015-11-29)
    1) Added support for Ajax HTML5 file uploader that replaces Java version as previously
*/
class Component_Jumploader extends Component_Base
{
    const VERSION = '1.0.6';

    protected $_cp;
    protected $_args;
    protected $_cp_are_fixed;
    protected $_html;
    protected $_ident;
    protected $_instance;
    protected $_safe_ID;
    protected $_parameter_spec;

    public function __construct()
    {
        $this->_ident = "jumploader_uploader";
        $this->_parameter_spec = array(
            'ext' =>                array(
                'match' =>      '',
                'default' =>    'csv|xls|xlsx',
                'hint' =>       'CSV list of acceptable file extensions'
            ),
            'folder' =>             array(
                'match' =>      '',
                'default' =>    '/UserFiles/File',
                'hint' =>       'Folder in which to place uploaded items'
            ),
            'height' =>             array(
                'match' =>      '',
                'default' =>    480,
                'hint' =>       'Height in px'
            ),
            'input' =>              array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list of Excel input fields to map'
            ),
            'mode' =>               array(
                'match' =>      'enum|embedded,framed',
                'default' =>    'embedded',
                'hint' =>       'embedded|framed'
            ),
            'output' =>             array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list of Database fields to output to'
            ),
            'show_summary' =>       array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'type' =>               array(
                'match' =>      'enum|file,flash,image,media,video',
                'default' =>    'file',
                'hint' =>       'file|flash|image|media|video'
            ),
            'width' =>              array(
                'match' =>      '',
                'default' =>    800,
                'hint' =>       'Width in px'
            )
        );

    }

    function draw($instance = '', $args = array(), $cp_are_fixed = false)
    {
        $this->_draw_setup($instance, $args, $cp_are_fixed);
        $this->_draw_jumploader();
        return $this->_html;
    }

    protected function _draw_jumploader()
    {
        $this->_html.=
             HTML::draw_status('form_edit_inpage', $this->_msg)
            .$this->_Obj_JL->draw();
    }

    protected function _draw_uploaded_files_table()
    {
        $this->_html.=
             "<table class='form_view' style='width:100%'>\n"
            ."  <tr class='head'>\n"
            ."    <th>Filename</th>\n"
            ."    <th>Type</th>\n"
            ."    <th>Size (bytes)</th>\n"
            ."    <th>Status</th>\n"
            ."  </tr>\n";
        foreach ($this->_upload_status as $file) {
            $this->_html.=
                 "  <tr>\n"
                ."    <td>"
                ."<a rel='external' href=\"".$file['path']."\">"
                ."<img src=\"".get_icon_for_extension($file['extension'])."\""
                ." class=\"file_icon\" alt=\".".$file['extension']." file\" /></a>"
                ."<a rel='external' href=\"".$file['path']."\">"
                .$file['filename']."</a></td>\n"
                ."    <td class='txt_r'>".$file['extension']."</td>\n"
                ."    <td class='txt_r'>".number_format($file['size'])."</td>\n"
                ."    <td>".$file['message']."</td>\n"
                ."  </tr>\n";
        }
        $this->_html.=
            "</table>\n";
    }

    protected function _draw_setup($instance, $args, $cp_are_fixed)
    {
        parent::setup($instance, $args, $cp_are_fixed);
        $this->_draw_control_panel(true);
        $this->_draw_setup_jumploader_init();
        $this->_draw_setup_jumploader_upload();
    }

    protected function _draw_setup_jumploader_init()
    {
        $this->_Obj_JL = new Jumploader;
        $this->_Obj_JL->init(
            $this->_safe_ID,
            $this->_cp['width'],
            $this->_cp['height'],
            $this->_cp['mode'],
            $this->_cp['type'],
            $this->_cp['ext'],
            $this->_cp['show_summary']
        );
    }

    protected function _draw_setup_jumploader_upload()
    {
        $this->_Obj_JL->files_uploader(BASE_PATH.trim($this->_cp['folder'], '/'));
        $this->_msg =   "";
        $this->_upload_status =    $this->_Obj_JL->get_status();
        $this->_upload_count =     $this->_Obj_JL->get_uploaded_count();
        $this->_Obj_JL->clear_status();
        if ($this->_upload_count) {
            $this->_msg = "<b>Success:</b> Uploaded ".$this->_upload_count." file".($this->_upload_count==1? '' : 's');
        }
    }
}
