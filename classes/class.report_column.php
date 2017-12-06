<?php
/*
Version History:
  1.0.148 (2017-12-01)
    1) Internals for Report_Column::draw_form_field() now moved into new class as Report_Column_Form_Field::draw()
       with the original method here now acting merely as a stub
*/
class Report_Column extends Record
{
    const FIELDS =  'ID, archive, archiveID, deleted, systemID, reportID, group_assign_csv, seq, tab, defaultValue, fieldType, formField, formFieldHeight, formFieldSpecial, formFieldTooltip, formFieldUnique, formFieldWidth, formLabel, formSelectorSQLMaster, formSelectorSQLMember, permCOMMUNITYADMIN, permGROUPVIEWER, permGROUPEDITOR, permMASTERADMIN, permPUBLIC, permSYSADMIN, permSYSAPPROVER, permSYSEDITOR, permSYSLOGON, permSYSMEMBER, permUSERADMIN, reportField, reportFieldSpecial, reportFilter, reportFilterLabel, reportLabel, reportSortBy_AZ, reportSortBy_a, reportSortBy_d, required_feature, required_feature_invert, history_created_by, history_created_date, history_created_IP, history_modified_by, history_modified_date, history_modified_IP';
    const VERSION = '1.0.148';

    public function __construct($ID = "")
    {
        parent::__construct("report_columns", $ID);
        $this->_set_assign_type('Report Column');
        $this->_set_has_groups(true);
        $this->_set_object_name('Report Column');
    }

    public static function attach_behaviour($field, $type, $args = "")
    {
        Output::push('javascript_onload', "  afb(\"".$field."\",\"".$type."\",\"".$args."\");\n");
    }

    public static function bulk_update(&$data, $bulk_update, $field, $value)
    {
        if (!$bulk_update) {
            $data[$field] = addslashes($value);
            return true;
        }
        if (isset($_POST[$field.'_apply'])) {
            $data[$field] = addslashes($value);
            return true;
        }
        return false;
    }

    public static function draw_combo_selector($field, $value, $selectorSQL, $width, $reportID, $jsCode)
    {
        $out = array();
        $field_alt =    $field."_alt";
        $field_sel =    $field."_selector";
        $records =      static::get_records_for_sql(get_sql_constants($selectorSQL));
        $value_alt =    $value;
        $value_sel =    "--";
        foreach ($records as $record) {
            if (strToLower($record['value'])==strToLower(trim($value))) {
                $value_alt = "";
                $value_sel = $record['value'];
                break;
            }
        }
        Output::push('javascript_onload', "  combo_selector_set('$field','$width');\n");
        return
             draw_form_field($field, '', 'hidden')
            ."<table class='minimal' style='width:".$width."'>\n"
            ."  <tr>\n"
            ."    <td>"

            ."<select id=\"".$field_sel."\"style=\"width: ".(((int)$width)+4)."px;\" class=\"formField\""
            .($jsCode ? $jsCode : " onchange=\"combo_selector_set('".$field."','".$width."')\"")
            .">"
            .static::draw_select_options($value_sel, $selectorSQL)
            ."</select>"
            ."</td>\n"
            ."    <td>&nbsp;</td>\n"
            ."    <td align='right'><span id=\"".$field."_alt_span\" style=\"display:none;\">"
            ."<input id=\"".$field_alt."\" type=\"text\" value=\"".$value_alt."\" class='formField'"
            ." style=\"width: ".(((int)$width/2)-5)."px;\" "
            .($jsCode ? $jsCode : " onchange=\"combo_selector_set('".$field."','".$width."')\"")
            ."/>"
            ."</span></td>\n"
            ."  </tr>\n"
            ."</table>\n";
    }

    public static function draw_form_field(
        $row,
        $field,
        $value,
        $type,
        $width = "",
        $selectorSQL = "",
        $reportID = 0,
        $jsCode = "",
        $readOnly = 0,
        $bulk_update = 0,
        $label = "",
        $formFieldSpecial = '',
        $height = ''
    )
    {
        $Obj = new Report_Column_Form_Field;
        return $Obj->draw(
            $row,
            $field,
            $value,
            $type,
            $width,
            $selectorSQL,
            $reportID,
            $jsCode,
            $readOnly,
            $bulk_update,
            $label,
            $formFieldSpecial,
            $height);
    }

    public static function draw_form_field_lookup(
        $field,
        $value,
        $control_num,
        $report_name,
        $report_field,
        $report_matchmode,
        $linked_field = '',
        $displayed_field = '',
        $autocomplete = '',
        $row_js = '',
        $onematch_js = '',
        $nomatch_js = '',
        $lookup_info_initial = '',
        $lookup_result_initial = '',
        $results_height = 100
    ) {
        $args = array(
            'field' =>                    $field,
            'value' =>                    $value,
            'control_num' =>              $control_num,
            'report_name' =>              $report_name,
            'report_field' =>             $report_field,
            'report_matchmode' =>         $report_matchmode,
            'linked_field' =>             $linked_field,
            'displayed_field' =>          $displayed_field,
            'autocomplete' =>             $autocomplete,
            'row_js' =>                   $row_js,
            'onematch_js' =>              $onematch_js,
            'nomatch_js' =>               $nomatch_js,
            'lookup_info_initial' =>      $lookup_info_initial,
            'lookup_result_initial' =>    $lookup_result_initial,
            'results_height' =>           $results_height
        );
        $Obj_RFFL = new Report_Form_Field_Lookup;
        $Obj_RFFL->init($args);
        return $Obj_RFFL->draw();
    }

    public static function draw_label($label, $tooltip = '', $field = '', $standalone = false, $width = false)
    {
        if ($label=='') {
            return;
        }
        return
             "<div class='fl' style='width:17px'>"
            .($tooltip!='' ?
                 "<img src='".BASE_PATH."img/spacer' class='icons'"." title=\""
                .str_replace(array("\\n","<br>","<br/>","<br />"), "\n", $tooltip)
                ."\" style='padding:0;margin:2px;height:11px;width:11px;background-position:-2600px 0px;' alt='' />\n"
             :
                "<img class='border_none fl' src=\"".BASE_PATH."img/spacer\" alt='' height='1' width='17'/>"
            )
            ."</div>"
            ."<div class='admin_formLabel fl'"
            .($width ? " style='width:".((int)$width-17)."px'" : "")
            .">"
            ."<label".($field && !$standalone ? " for=\"".$field."\"" : "").">"
            .convert_safe_to_php($label)
            ."</label>"
            ."</div>";
    }

    public static function draw_list_selector($field, $value, $selectorSQL, $order = false, $width = 100, $height = 110)
    {
        $c2 = ($order ? 80 : 42);
        $c1 = ((int)$width/2)-$c2;
        $out =        '';
        $options =    array();
        $chosen =     array();
        if ($selectorSQL!="") {
            $records = static::get_records_for_sql($selectorSQL);
            foreach ($records as $record) {
                $bgcol =    (isset($record['color_background']) ? $record['color_background'] : 'ffffff');
                $textcol =  (isset($record['color_text']) ? $record['color_text'] : '000000');
                $options[] = array(
                    'value' =>              $record['value'],
                    'text' =>               get_image_alt($record['text']),
                    'color_background' =>   $bgcol,
                    'color_text' =>         $textcol,
                    'available' =>          true
                );
            }
        }
        $disabled = (isset($options[0]['text']) && $options[0]['text']=='Please save this record first');
        $state = ($disabled ? " disabled='disabled'" : "");
        $value_arr = explode(",", $value);
        for ($i=0; $i<count($value_arr); $i++) {
            for ($j=0; $j<count($options); $j++) {
                if ($options[$j]['value']==$value_arr[$i] && ($options[$j]['text']!='Please save this record first')) {
                    $chosen[] =
                    array(
                        'value'=>$options[$j]['value'],
                        'text'=>$options[$j]['text'],
                        'color_background'=>$options[$j]['color_background'],
                        'color_text'=>$options[$j]['color_text'],
                    );
                    $options[$j]['available']=false;
                }
            }
        }
        $out=
             "<div>\n"
            ."  <div class='fl txt_c admin_formLabel'"
            .($disabled ? " style='color: #808080;font-style:italic;'" : "")
            .">--- Available Options ---<br />\n"
            ."<select ".$state.($disabled ? "style='background-color:#f0f0f0;'" : '')
            ." name=\"".$field."_list1\" multiple='multiple' style=\"width:".$c1."px;height:".$height."px;\""
            ." ondblclick=\"".$field."_opt.transferRight()\">\n"
            ."<option value=\"dummy-value-for-xhtml-strict\" style=\"display:none;\">&nbsp;</option>\n";
        foreach ($options as $option) {
            if ($option['available']) {
                if (isset($option['color_text']) || isset($option['color_background'])) {
                    $_t = (isset($option['color_text']) ?         $option['color_text'] : false);
                    $_b = (isset($option['color_background']) ?   $option['color_background'] : false);
                    $idx = Page::get_css_idx($_t, $_b);
                }
                $out.=
                "<option value=\"".$option['value']."\""
                .($idx ? " class=\"color_".$idx."\"" : "")
                ." title=\"".$option['text']."\">"
                .$option['text']
                ."</option>\n";
            }
        }
        $out.=
             "</select></div>\n"
            ."	  <div class='fl va_m' style='padding-top:35px;padding-left:10px;padding-right:10px'>\n"
            ."<input type=\"button\"".$state." id=\"".$field."_right\"     name=\"".$field."_right\"    "
            ." class=\"formButton\" value=\"&gt;\""
            ." onclick=\"".$field."_opt.transferRight()\" style=\"width:20px;\" /> "
            ."<input type=\"button\"".$state." id=\"".$field."_right_all\" name=\"".$field."_right_all\""
            ." class=\"formButton\" value=\"All &gt;\""
            ." onclick=\"".$field."_opt.transferAllRight()\" style=\"width:40px;\" /><br /><br />\n"
            ."<input type=\"button\"".$state." id=\"".$field."_left\"      name=\"".$field."_left\"     "
            ." class=\"formButton\" value=\"&lt;\""
            ." onclick=\"".$field."_opt.transferLeft()\" style=\"width:20px;\" /> "
            ."<input type=\"button\"".$state." id=\"".$field."_left_all\"  name=\"".$field."_left_all\" "
            ." class=\"formButton\" value=\"All &lt;\""
            ." onclick=\"".$field."_opt.transferAllLeft()\" style=\"width:40px;\" />\n"
            ."</div>\n"
            ."    <div class='fl txt_c admin_formLabel'"
            .($disabled ? " style='color: #808080;font-style:italic;'" : "")
            ."><b>--- Chosen Options ---</b><br />\n"
            ."<select ".$state.($disabled ? "style='background-color:#f0f0f0;'" : '')
            ." name=\"".$field."_list2\" multiple='multiple' style=\"width:".$c1."px;height:".$height."px;\""
            ." ondblclick=\"".$field."_opt.transferLeft()\">\n"
            ."<option value=\"dummy-value-for-xhtml-strict\" style=\"display:none;\">&nbsp;</option>\n";
        foreach ($chosen as $choose) {
            if (isset($choose['color_text']) || isset($choose['color_background'])) {
                $_t = (isset($choose['color_text']) ?         $choose['color_text'] : false);
                $_b = (isset($choose['color_background']) ?   $choose['color_background'] : false);
                $idx = Page::get_css_idx($_t, $_b);
            }
            $out.=
                 "<option value=\"".$choose['value']."\""
                .($idx ? " class=\"color_".$idx."\"" : "")
                .">"
                .$choose['text']
                ."</option>\n";
        }
        $out.=
             "</select></div>\n"
            ."<input type=\"hidden\" id=\"$field\" name=\"$field\" value=\"\"/>\n"
            .($order ?
                 "	  <div class='fl va_m' style='padding-top:45px;padding-left:10px;'>\n"
                ."<input type=\"button\"".$state." id=\"".$field."_right_up\" name=\"".$field."_right_up\""
                ." class=\"formButton\" value=\"UP\" style=\"width: 50px;\""
                ." onclick=\"moveOptionUp(geid('".$field."_list2'));".$field."_opt.update();\"><br />\n"
                ."<input type=\"button\"".$state." id=\"".$field."_right_down\" name=\"".$field."_right_down\""
                ." class=\"formButton\" value=\"DOWN\" style=\"width: 50px;\""
                ." onclick=\"moveOptionDown(geid('".$field."_list2'));".$field."_opt.update();\">"
                ."</div>\n"
             :
                ""
             )
            ."</div>";
        Output::push(
            'javascript_onload',
            "\n  // Set up list_selector for ".$field.":\n"
            ."  ".$field."_opt = new OptionTransfer(\"".$field."_list1\",\"".$field."_list2\");\n"
            ."  ".$field."_opt.setAutoSort(".($order ? "false" : "true").");\n"
            ."  ".$field."_opt.setDelimiter(\",\");\n"
            ."  ".$field."_opt.saveNewRightOptions(\"$field\");\n"
            ."  ".$field."_opt.init(geid('form'));\n"
        );
        Output::push('javascript', "var ".$field."_opt; // for list selector");
        return $out;
    }

    public static function draw_radio_selector(
        $field,
        $value,
        $entries_arr,
        $width,
        $jsCode,
        $ajax_mode = 0,
        $stacked = 0
    ) {
        $out = '';
        if ((int)$width && !$stacked) {
            $out.="<div style='width:".((int)$width)."px'>";
        }
        for ($i=0; $i<count($entries_arr); $i++) {
            $record = $entries_arr[$i];
            $l = $record['text'];
            $v = $record['value'];
            if (!$ajax_mode) {
                $idx = Page::get_css_idx(
                    (isset($record['color_text']) ?       $record['color_text'] : false),
                    (isset($record['color_background']) ? $record['color_background'] : false)
                );
            }
            $ID = $field.($i==0? '' : '_'.get_web_safe_ID($v));
            $out.=
                 "<label"
                .($stacked && (int)$width || $ajax_mode ?
                 " style='"
                .($stacked ? "" : "float:left;")
                .($stacked || (int)$width ? "display:block;margin:0 1px 1px 0;width:".(int)$width."px;" : "")
                ."'"
                 : ""
                )
                ." class='"
                ."xformOptionValue"
                .(!$ajax_mode ? " color_".$idx : "")
                ."'>"
                ."<input type=\"radio\" name=\"".$field."\" value=\"".$v."\" "
                ."id=\"".$ID."\"".(strToLower($value)==strToLower($v) ? " checked='checked'" : "")
                .($jsCode ? " ".$jsCode : "")
                ." />"
                .$l
                ."&nbsp;</label>\n";
        }
        if ((int)$width && !$stacked) {
            $out.="</div>";
        }
        return $out;
    }

    public static function draw_radio_selector_for_sql(
        $field,
        $value,
        $sql,
        $width,
        $jsCode,
        $ajax_mode = 0,
        $stacked = 0
    ) {
        $out =    array();
        $sql =    get_sql_constants($sql);
        $records = static::get_records_for_sql($sql);
        if ($records===false) {
            return '';
        }
        return static::draw_radio_selector($field, $value, $records, $width, $jsCode, $ajax_mode, $stacked);
    }

    public static function draw_report_field(
        $column,
        $row,
        $popupFormHeight,
        $popupFormWidth,
        $isEDITOR,
        $this_report_name,
        &$targetID,
        &$mayCancel,
        $reportMembersGlobalEditors,
        $ajax_popup_url,
        $primaryObject
    ) {
        $Obj = new Report_Column_Report_Field;
        return $Obj->draw(
            $column,
            $row,
            $popupFormHeight,
            $popupFormWidth,
            $isEDITOR,
            $this_report_name,
            $targetID,
            $mayCancel,
            $reportMembersGlobalEditors,
            $ajax_popup_url,
            $primaryObject
        );
    }

    public static function drawNavStyleSample($field, $value, $row)
    {
        if ($value=="") {
            return "(Save this Button Style first)";
        }
        return static::drawNavSample($value, "btn_style", $row);
    }

    public static function drawNavSuiteSample($field, $value, $row)
    {
        if ($value=="") {
            return "(Save this Button Suite first)";
        }
        return static::drawNavSample($value, "btn_style", $row);
    }

    public static function drawNavSample($value, $submode, $row)
    {
        $orientation =  $row['orientation'];
        $height =       $row['img_height'];
        $width =        $row['img_width'];
        $type =         $row['type'];
        $systemID =     $row['systemID'];
        $states =       array('Active', 'Down', 'Normal', 'Over');
        switch ($type) {
            case "SD Menu":
                return "SD Menu - no images generated";
                break;
            case "Responsive":
                return "Responsive Menu - no images generated";
                break;
        }
        $url =
             "url(".BASE_PATH."img/sample/".$submode."/".$systemID."/".$value."/"
            .(isset($row['img_checksum']) ? $row['img_checksum'] : '')
            .")";
        $out = "<div>\n";
        for ($i=0; $i<=3; $i++) {
            $state = $states[$i];
            $out.=
                 "  <img class ='fl' src='".BASE_PATH."img/spacer' style='margin:1px;background: "
                .$url." no-repeat 100% -".($i*$height)."px' width='".$width."' height='".$height."'"
                ." alt='Button as seen in &apos;$state&apos; state'"
                ." title='Button as seen in &apos;$state&apos; state'/>"
                .($orientation == '|' ? "<br class='clr_b' />\n" : "");
        }
        $out.=
            "</div>\n";
        return $out;
    }

    public static function draw_select_options($value, $sql)
    {
        global $report_name;
        $records = static::get_records_for_sql(get_sql_constants($sql));
        if ($records===false) {
            return '';
        }
        return static::draw_select_options_from_records($value, $records);
    }

    public static function draw_select_options_from_records($value, $records)
    {
        $out =    "";
        $headerLevel=0;
        $selected_applied = false;
        foreach ($records as $record) {
            $special = ($record['value']=='' || $record['value']=='--');
            $idx = false;
            if (isset($record['color_text']) || isset($record['color_background'])) {
                $_t = (isset($record['color_text']) ?         $record['color_text'] : false);
                $_b = (isset($record['color_background']) ?   $record['color_background'] : false);
                $idx = Page::get_css_idx($_t, $_b);
            }
            $text = str_replace(
                array('& ','&comma'),
                array('&amp; ',','),
                $record['text']
            );
            if (isset($record['isHeader']) && $record['isHeader']=='1') {
                if ($headerLevel>0) {
                    $out.= "  </optgroup>\n";
                    $headerLevel--;
                }
                $out.=
                    "  <optgroup"
                    .($idx ? " class=\"color_".$idx."\"" : "")
                    ." label=\""
                    .$text
                    ."\">\r\n";
                    $headerLevel++;
            } else {
                $out.=
                     "    <option value=\"".$record['value']."\""
                    .($idx ? " class=\"color_".$idx."\"" : "")
                    .($record['value']==$value && !$selected_applied ? " selected=\"selected\"" : "")
                    .(@$record['description']!='' ? " title=\"".$record['description']."\"" : "")
                    .">"
                    .$text
                    ."</option>\r\n";
                if ($record['value']==$value) {
                    $selected_applied = true;
                }
                if ($headerLevel>0 && $special) {
                    $out.= "  </optgroup>\n";
                    $headerLevel--;
                }
            }
        }
        if ($headerLevel>0) {
            $out.="  </optgroup>\n";
            $headerLevel--;
        }
  //    y(Page::$css_colors);
        return $out;
    }

    public static function draw_selector($field, $value, $sql, $width, $jsCode)
    {
        return
             "<select id=\"$field\" name=\"$field\" style=\"width: ".(((int)$width)+4)."px;\""
            ." class=\"formField\"".($jsCode ? " ".$jsCode : "").">\n"
            .static::draw_select_options($value, $sql)
            ."</select>";
    }

    public static function draw_selector_csv($field, $value, $sql, $width, $height, $hasWeight = 0)
    {
        $records =      static::get_records_for_sql($sql);
        $value_arr =    explode(",", $value);
        $list_arr =     array();
        Output::push(
            'javascript_onload',
            "  selector_csv_show(\"".$field."\",".($hasWeight ? "1" : "0").");\n"
        );
        return
             "<input type='hidden' id=\"".$field."\" name=\"".$field."\" value=\"".$value."\" />"
            ."<select id=\"selector_csv_".$field."\" style=\"width:".(((int)$width)*0.45)."px;font-size:8pt;\""
            ." class=\"formField fl\" "
            ."onchange=\"selector_csv_add('".$field."',this.options[this.selectedIndex].value,"
            .($hasWeight ? "1" : "0")
            .");\">\n"
            .static::draw_select_options('', $sql)
            ."</select>"
            ."<div id=\"selector_csv_div_".$field."\" class='formField fl txt_l'"
            ." style='width:".(((int)$width)*0.55)."px;height:".$height."px;"
            ."overflow:auto;background-color:#ffffff;font-size:8pt;'>"
            ."</div>";
    }

    public static function draw_selector_with_selected($report_name, $reportID, $ajax_popup_url = false, $toolbar = 0)
    {
        $features = explode(',', Report::REPORT_FEATURES);
        $s = array();
        $Obj_Report = new Report($reportID);
        foreach ($features as $f) {
            $key = trim($f);
            $s[$key] = $Obj_Report->test_feature($key);
        }
        $popup_size =  get_popup_size($report_name);
        $js_submit =
        ($ajax_popup_url ?
        "popup_layer_submit('".$ajax_popup_url."')"
        : "geid('form').submit()"
        );
        $js =
             "\n"
            ."// ************************\n"
            ."// * 'With Selected' code *\n"
            ."// ************************\n"
            ."window.selected_operation_enable_".$reportID." = function(form,ID) {\n"
            ."  var control = 'selected_op_'+ID;\n"
            ."  if (form[control]) {\n"
            ."    form[control].disabled=(row_select_count(ID)==0);\n"
            ."  }\n"
            ."}\n"
            ."window.selected_operation_".$reportID." = function(form,report_name,ID){\n"
            ."  var args = {\n"
            .($s['selected_add_to_group']!==false ?
                "    selected_add_to_group: ".$s['selected_add_to_group'].",\n"
             :
                ""
            )
            .($s['selected_delete']!==false ?
                "    selected_delete: ".$s['selected_delete'].",\n"
              :
                ""
             )
            .($s['selected_empty']!==false ?
                "    selected_empty: ".$s['selected_empty'].",\n"
             :
                ""
            )
            .($s['selected_export_excel']!==false ?
                "    selected_export_excel: ".$s['selected_export_excel'].",\n"
             :
                ""
            )
            .($s['selected_export_sql']!==false ?
                "    selected_export_sql: ".$s['selected_export_sql'].",\n"
             :
                ""
            )
            .($s['selected_merge_profiles']!==false ?
                "    selected_merge_profiles: ".$s['selected_merge_profiles'].",\n"
             :
                ""
            )
            .($s['selected_process_maps']!==false ?
                "    selected_process_maps: ".$s['selected_process_maps'].",\n"
             :
                ""
            )
            .($s['selected_process_order']!==false ?
                "    selected_process_order: ".$s['selected_process_order'].",\n"
             :
                ""
            )
            .($s['selected_scrub_pii_data']!==false ?
                "    selected_scrub_pii_data: ".$s['selected_scrub_pii_data'].",\n"
              :
                ""
             )
            .($s['selected_send_email']!==false ?
                "    selected_send_email: ".$s['selected_send_email'].",\n"
             :
                ""
            )
            .($s['selected_queue_again']!==false ?
                "    selected_queue_again: ".$s['selected_queue_again'].",\n"
             :
                ""
            )
            .($s['selected_send_again']!==false ?
                "    selected_send_again: ".$s['selected_send_again'].",\n"
             :
                ""
            )
            .($s['selected_view_email_addresses']!==false ?
                "    selected_view_email_addresses: ".$s['selected_view_email_addresses'].",\n"
             :
                ""
            )
            .($s['selected_set_as_approved']!==false ?
                "    selected_set_as_approved: ".$s['selected_set_as_approved'].",\n"
             :
                ""
            )
            .($s['selected_set_as_attended']!==false ?
                "    selected_set_as_attended: ".$s['selected_set_as_attended'].",\n"
             :
                ""
            )
            .($s['selected_set_as_disabled']!==false ?
                "    selected_set_as_disabled: ".$s['selected_set_as_disabled'].",\n"
              :
                ""
            )
            .($s['selected_set_as_enabled']!==false ?
                "    selected_set_as_enabled: ".$s['selected_set_as_enabled'].",\n"
              :
                ""
            )
            .($s['selected_set_as_hidden']!==false ?
                "    selected_set_as_hidden: ".$s['selected_set_as_hidden'].",\n"
             :
                ""
            )
            .($s['selected_set_as_member']!==false ?
                "    selected_set_as_member: ".$s['selected_set_as_member'].",\n"
             :
                ""
            )
            .($s['selected_set_as_spam']!==false ?
                "    selected_set_as_spam: ".$s['selected_set_as_spam'].",\n"
             :
                ""
            )
            .($s['selected_set_as_unapproved']!==false ?
                "    selected_set_as_unapproved: ".$s['selected_set_as_unapproved'].",\n"
             :
                ""
            )
            .($s['selected_set_email_opt_in']!==false ?
                "    selected_set_email_opt_in: ".$s['selected_set_email_opt_in'].",\n"
             :
                ""
            )
            .($s['selected_set_email_opt_out']!==false ?
                "    selected_set_email_opt_out: ".$s['selected_set_email_opt_out'].",\n"
             :
                ""
            )
            .($s['selected_set_importance']!==false ?
                "    selected_set_important_on: ".$s['selected_set_importance'].",\n"
             :
                ""
            )
            .($s['selected_set_importance']!==false ?
                "    selected_set_important_off: ".$s['selected_set_importance'].",\n"
             :
                ""
            )
            .($s['selected_set_random_password']!==false ?
                "    selected_set_random_password: ".$s['selected_set_random_password'].",\n"
             :
                ""
            )
            .($s['selected_show_on_map']!==false ?
                "    selected_show_on_map: ".$s['selected_show_on_map'].",\n"
             :
                ""
            )
            .($s['selected_update']!==false ?
                "    selected_update: ".$s['selected_update'].",\n"
             :
                ""
            )
            ."    popup_size: { w:".$popup_size['w'].",h:".$popup_size['h']."},\n"
            ."    toolbar: ".$toolbar.",\n"
            ."    submit_action: function(){"
            .$js_submit
            ."}\n"
            ."  };\n"
            ."  return selected_operation(form,report_name,ID,args);\n"
            ."}\n";
        $html =
             "\n<select name=\"selected_op_".$reportID."\" disabled='disabled'"
            ." onchange=\"selected_operation_".$reportID."(geid('form'),'".$report_name."',".$reportID.")\""
            ." class='formField'>\n"
            ."  <option value='' style='color: RGB(0,0,255);'>With selected...</option>\n"
            .($s['selected_set_as_disabled']!==false ?
                 "  <option value='selected_set_as_disabled' style='background-color: RGB(255,220,220);'>"
                ."Disable</option>\n"
             :
                ""
            )
            .($s['selected_set_as_enabled']!==false ?
                 "  <option value='selected_set_as_enabled' style='background-color: RGB(220,255,220);'>"
                ."Enable</option>\n"
             :
                ""
            )
            .($s['selected_set_as_attended']!==false ?
                 "  <option value='selected_set_as_attended' style='background-color: RGB(200,255,200);'>"
                ."Set registrants as having attended</option>\n"
             :
                ""
            )
            .($s['selected_empty']!==false ?
                "  <option value='selected_empty' style='background-color: RGB(255,240,240);'>Empty</option>\n"
             :
                ""
            )
            .($s['selected_process_order']!==false ?
                 "  <option value='selected_process_order' style='background-color: RGB(220,255,200);'>"
                ."Process Order Actions</option>\n"
             :
                ""
            )
            .($s['selected_set_as_approved']!==false ?
                 "  <option value='selected_set_as_approved' style='background-color: RGB(200,255,200);'>"
                ."Set as Approved</option>\n"
             :
                ""
            )
            .($s['selected_set_as_member']!==false ?
                 "  <option value='selected_set_as_member' style='background-color: RGB(200,255,200);'>"
                ."Set as Member</option>\n"
             :
                ""
            )
/*
            .($s['selected_send_email']!==false ?
                 "  <option value='selected_send_email' style='background-color: RGB(200,255,255);'>"
                ."Send Email</option>\n"
             :
                ""
            )
*/
            .($s['selected_queue_again']!==false ?
                 "  <option value='selected_queue_again' style='background-color: RGB(200,255,255);'>"
                ."Requeue Email for delivery later</option>\n"
             :
                ""
            )
            .($s['selected_send_again']!==false ?
                 "  <option value='selected_send_again' style='background-color: RGB(200,255,255);'>"
                ."Resend Email now</option>\n"
             :
                ""
            )
            .($s['selected_view_email_addresses']!==false ?
                 "  <option value='selected_view_email_addresses' style='background-color: RGB(200,255,255);'>"
                ."View Email Addresses</option>\n"
             :
                ""
            )
            .($s['selected_add_to_group']!==false ?
                 "  <option value='selected_add_to_group' style='background-color: RGB(200,255,200);'>"
                ."Add to Group</option>\n"
             :
                ""
            )
            .($s['selected_set_as_hidden']!==false ?
                 "  <option value='selected_set_as_hidden' style='background-color: RGB(200,200,200);'>"
                ."Set as Hidden</option>\n"
             :
                ""
            )
            .($s['selected_set_as_spam']!==false ?
                 "  <option value='selected_set_as_spam' style='background-color: RGB(255,200,200);'>"
                ."Set as Spam</option>\n"
             :
                ""
            )
            .($s['selected_set_as_unapproved']!==false ?
                 "  <option value='selected_set_as_unapproved' style='background-color: RGB(200,200,200);'>"
                ."Set as Unapproved</option>\n"
             :
                ""
            )
            .($s['selected_set_email_opt_in']!==false ?
                 "  <option value='selected_set_email_opt_in' style='background-color: RGB(255,200,220);'>"
                ."Set Email Opt-In</option>\n"
             :
                ""
            )
            .($s['selected_set_email_opt_out']!==false ?
                 "  <option value='selected_set_email_opt_out' style='background-color: RGB(255,200,220);'>"
                ."Set Email Opt-Out</option>\n"
             :
                ""
            )
            .($s['selected_set_importance']!==false ?
                 "  <option value='selected_set_important_on' style='background-color: RGB(255,255,220);'>"
                ."Set Importance to High</option>\n"
             :
                ""
            )
            .($s['selected_set_importance']!==false ?
                 "  <option value='selected_set_important_off' style='background-color: RGB(220,220,220);'>"
                ."Set Importance to Normal</option>\n"
             :
                ""
            )
            .($s['selected_set_random_password']!==false ?
                 "  <option value='selected_set_random_password' style='background-color: RGB(255,220,220);'>"
                ."Set Random Password - no email is sent</option>\n"
             :
                ""
            )
            .($s['selected_show_on_map']!==false ?
                 "  <option value='selected_show_on_map' style='background-color: RGB(255,255,100);'>"
                ."Show on Map</option>\n"
             :
                ""
            )
            .($s['selected_process_maps']!==false ?
                 "  <option value='selected_process_maps' style='background-color: RGB(255,255,100);'>"
                ."Reprocess Map Locations</option>\n"
             :
                ""
            )
            .($s['selected_update']!==false ?
                 "  <option value='selected_update' style='background-color: RGB(220,220,255);'>"
                ."Perform bulk update</option>\n"
             :
                ""
            )
            .($s['selected_export_excel']!==false ?
                 "  <option value='selected_export_excel' style='background-color: RGB(220,255,220);'>"
                ."Export to Excel</option>\n"
             :
                ""
            )
            .($s['selected_export_sql']!==false ?
                 "  <option value='selected_export_sql' style='background-color: RGB(220,255,220);'>"
                ."Export as SQL</option>\n"
             :
                ""
            )
            .($s['selected_merge_profiles']!==false ?
                 "  <option value='selected_merge_profiles' style='background-color: RGB(255,220,180);'>"
                ."Merge Profiles</option>\n"
             :
                ""
            )
            .($s['selected_scrub_pii_data']!==false ?
                 "  <option value='selected_scrub_pii_data' style='background-color: RGB(255,220,180);'>"
                ."Scrub PII Data</option>\n"
             :
                ""
            )
            .($s['selected_delete']!==false ?
                 "  <option value='selected_delete' style='background-color: RGB(255,220,220);'>"
                ."Delete</option>\n"
             :
                ""
            )
            ."</select>\n";
        if ($ajax_popup_url) {
            $out =
            array(
            'html'=>$html,
            'js'=>$js
            );
    //      y($out);die;
            return $out;
        }
        Output::push('javascript', $js);
        return $html;
    }

    public function exportSql($targetID, $show_fields)
    {
        return $this->sqlExport($targetID, $show_fields);
    }

    public static function get_column_for_report($reportName, $formField)
    {
        $sql =
             "SELECT\n"
            ."  `report_columns`.*\n"
            ."FROM\n"
            ."  `report`\n"
            ."LEFT JOIN `report_columns` ON\n"
            ."  `report`.`ID` = `report_columns`.`reportID`\n"
            ."WHERE\n"
            ."  `report`.`name`='".$reportName."' AND\n"
            ."  `report_columns`.`formField` = '".$formField."' AND\n"
            ."  `report_columns`.`systemID` IN(1,".SYS_ID.")\n"
            ."ORDER BY\n"
            ."  `report_columns`.`systemID` = 1\n"
            ."LIMIT 0,1";
        return static::get_record_for_sql($sql);
    }

    public static function get_selector_sql($type, $isMASTERADMIN, $formSelectorSQLMaster, $formSelectorSQLMember)
    {
        switch ($type) {
            case "checkbox_sql_csv":
            case "combo_selector":
            case "list (a-z)":
            case "list (sequenced)":
            case "selector":
            case "selector_url":
                return get_sql_constants($isMASTERADMIN ? $formSelectorSQLMaster : $formSelectorSQLMember);
            break;
        }
        return "";
    }

    public function handle_report_copy(&$newID, &$msg, &$msg_tooltip, $name)
    {
        return parent::try_copy($newID, $msg, $msg_tooltip, false);
    }

    public static function note_prepend($text)
    {
        $timestamp =    get_timestamp();
        $PUsername =    get_userPUsername();
        return
             $timestamp." (".$PUsername.")\n"
            .$text."\n"
            .str_repeat('-', 53)."\n";
    }
}
