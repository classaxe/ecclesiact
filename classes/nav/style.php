<?php
namespace Nav;

/*
Version History:
  1.0.15 (2017-01-22)
    1) Bug fix for \Nav\Style::sample() to have it once again show actual button states on each button
*/
class Style extends \Record
{
    const VERSION = '1.0.15';
    const FIELDS = 'ID, archive, archiveID, deleted, systemID, button_spacing, css, dropdownArrow, img_checksum, img_height, img_width, name, orientation, overlay_ba_img, overlay_ba_img_align, overlay_bm_img, overlay_bm_img_align, overlay_bz_img, overlay_bz_img_align, sdmenu_exclusive, sdmenu_speed, subnavOffsetX, subnavOffsetY, subnavStyleID, templateFile, text1_effect_color_active, text1_effect_color_down, text1_effect_color_normal, text1_effect_color_over, text1_effect_level_active, text1_effect_level_down, text1_effect_level_normal, text1_effect_level_over, text1_effect_type_active, text1_effect_type_down, text1_effect_type_normal, text1_effect_type_over, text1_font_color_active, text1_font_color_down, text1_font_color_normal, text1_font_color_over, text1_font_face, text1_font_size, text1_h_align, text1_h_offset, text1_uppercase, text1_v_offset, text2_effect_color_active, text2_effect_color_down, text2_effect_color_normal, text2_effect_color_over, text2_effect_level_active, text2_effect_level_down, text2_effect_level_normal, text2_effect_level_over, text2_effect_type_active, text2_effect_type_down, text2_effect_type_normal, text2_effect_type_over, text2_font_color_active, text2_font_color_down, text2_font_color_normal, text2_font_color_over, text2_font_face, text2_font_size, text2_h_align, text2_h_offset, text2_uppercase, text2_v_offset, type, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';

    public $file_prefix = "btn_style_";

    public function __construct($ID = "")
    {
        parent::__construct('navstyle', $ID);
        $this->_set_object_name('Navbutton Style');
        $this->set_edit_params(
            array(
                'report_rename' =>          true,
                'report_rename_label' =>    'new name'
            )
        );
    }

    public function clearCache()
    {
        $navstyle_name = $this->get_field('name');
        $single_buttons = \FileSystem::dir_wildcard_search(SYS_BUTTONS, 'custom_button_*'.$navstyle_name.'.png');
        foreach ($single_buttons as $single_button) {
            unlink($single_button);
        }
        $sql =
             "SELECT\n"
            ."  `ID`\n"
            ."FROM\n"
            ."  `navsuite`\n"
            ."WHERE\n"
            ."  `buttonStyleID` IN(".$this->_get_ID().")";
        $records = $this->get_records_for_sql($sql);
        if ($records) {
            foreach ($records as $record) {
                $Obj = new \Nav\Suite($record['ID']);
                $Obj->clearCache();
            }
        }
    }

    public function exportSql($targetID, $show_fields)
    {
        return $this->sqlExport($targetID, $show_fields);
    }

    public function handleReportCopy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::tryCopy($newID, $msg, $msg_tooltip, $name);
    }

    public function makeImages()
    {
        $width=100;
        $this->sample($width, true);
    }

    public function onDelete()
    {
        global $action_parameters;
        $ID_arr = explode(",", $action_parameters['triggerID']);
        foreach ($ID_arr as $ID) {
            $this->_set_ID($ID);
            $this->clearCache();
        }
    }

    public function onUpdate()
    {
        global $action_parameters;
        $ID_arr = explode(",", $action_parameters['triggerID']);
        foreach ($ID_arr as $ID) {
            $this->_set_ID($ID);
            $this->clearCache();
            $this->makeImages();
        }
    }

    public function sample($width = 100, $no_show = false, $text1 = 'Sample', $filename = '', $text2 = '')
    {
        if (!$this->_get_ID()) {
            return false;
        }
        $ID =                       $this->_get_ID();
        $data =                     $this->get_record();
        $filename =                 ($filename ? $filename : SYS_BUTTONS.$this->file_prefix.$ID.".png");
        if ($data['type']!=="Image") {
            if (file_exists($filename)) {
                unlink($filename);
            }
            return;
        }
        $data['navsuite_width'] =   0;
        $data['width'] =            $width;
        $data['text1'] =            $text1;
        $data['text2'] =            $text2;
        $data['childID_csv'] =      "";
        $Obj_Navbutton_Image =      new \Nav\ButtonImage;
        return $Obj_Navbutton_Image->draw($data, $filename, $no_show, $this->_get_ID());
    }
}
