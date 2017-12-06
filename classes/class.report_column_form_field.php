<?php
/*
Version History:
  1.0.0 (2017-12-01)
    1) Made this class to contain code used in Report_Column::draw_form_field() and previously part of that class
    2) Added 'year_month' field type
*/

class Report_Column_Form_Field extends Report_Column
{
    const VERSION = '1.0.0';

    public static function draw(
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
    ) {
        global $report_name, $system_vars;
        if ($bulk_update) {
            // These types cannot be bulk updated so don't even show them
            switch ($type) {
                case "file_upload":
                case "file_upload_to_userfile_folder":
                case "groups_assign_person":
                case "iframe":
                case "list (a-z)":
                case "list (sequenced)":
                case "media_file_upload":
                case "notes":
                case "sample_buttonstyle":
                case "sample_navsuite":
                case "seq":
                    return "";
                    break;
            }
        }
        if ($bulk_update) {
            $width = (int)$width-20;
        }
        if (!strpos($width, 'px') &&
            !strpos($width, '%') &&
            !strpos($width, 'em') &&
            !strpos($width, 'en')
        ) {
            $width.="px";
        }
        switch ($type) {
            case "categories_assign":
            case "checkbox_listdata_csv":
            case "combo_listdata":
            case "radio_listdata":
            case "selector_listdata":
            case "selector_listdata_csv":
                $params_arr =   explode("|", $formFieldSpecial);
                $listtype = $params_arr[0];
                $valueField = (isset($params_arr[2]) ? $params_arr[2] : 'value');
                break;
            case "selector_billing_address":
                $listtype =     'lst_billing_address';
                $valueField =   'value';
                break;
            case "radio_csvlist":
            case "selector_csvlist":
                $entries_csv =
                    str_replace(
                        array(
                            "\r\n",
                            "\n"
                        ),
                        ",",
                        $formFieldSpecial
                    );
                $_entries_arr =   explode(",", $entries_csv);
                $entries_arr = array();
                foreach ($_entries_arr as $_entry) {
                    $_entry_arr =   explode("|", $_entry);
                    $entries_arr[] =
                        array(
                            'value' =>            (ctype_space($_entry_arr[0]) ?
                                $_entry_arr[0]
                                :
                                trim($_entry_arr[0])
                            ),
                            'text' =>             (str_replace(
                                '&comma;',
                                ',',
                                (isset($_entry_arr[1]) ?
                                    $_entry_arr[1]
                                    :
                                    $_entry_arr[0]
                                )
                            )),
                            'color_background' => (isset($_entry_arr[2]) ?
                                $_entry_arr[2]
                                :
                                "ffffff"
                            ),
                            'color_text' => (isset($_entry_arr[3]) ?
                                $_entry_arr[3]
                                :
                                "000000"
                            ),
                        );
                }
                break;
        }
        switch ($type) {
            case "categories_assign":
            case "checkbox_listdata_csv":
            case "combo_listdata":
            case "radio_listdata":
            case "selector_billing_address":
            case "selector_listdata":
            case "selector_listdata_csv":
                $selectorSQL =
                    "SELECT\n"
                    ."  `color_text`,\n"
                    ."  `color_background`,\n"
                    ."  `isHeader`,\n"
                    ."  `seq`,\n"
                    ."  `textEnglish` AS `text`,\n"
                    ."  `".$valueField."` AS `value`\n"
                    ."FROM\n"
                    ."  `listdata`\n"
                    ."INNER JOIN `listtype` ON\n"
                    ."  `listdata`.`listTypeID` = `listtype`.`ID`\n"
                    ."WHERE\n"
                    ."  `listtype`.`name` = \"".$listtype."\" AND\n"
                    ."  `listdata`.`systemID` IN(1,".(isset($row['systemID']) ? $row['systemID'] : SYS_ID).")\n"
                    ."ORDER BY\n"
                    ."  `seq`,`text`";
                break;
            case "groups_assign":
                $selectorSQL = Group_Assign::get_selector_sql();
                break;
            case "keywords_assign":
                $selectorSQL = Keyword::get_selector_sql();
                break;
            case "languages_assign":
                $selectorSQL =
                    "SELECT\n"
                    ."  `color_text`,\n"
                    ."  `color_background`,\n"
                    ."  `isHeader`,\n"
                    ."  `seq`,\n"
                    ."  `textEnglish` AS `text`,\n"
                    ."  `value` AS `value`\n"
                    ."FROM\n"
                    ."  `listdata`\n"
                    ."INNER JOIN `listtype` ON\n"
                    ."  `listdata`.`listTypeID` = `listtype`.`ID`\n"
                    ."WHERE\n"
                    ."  `listtype`.`name` = \"lst_language\" AND\n"
                    ."  `listdata`.`systemID` IN(1,".(isset($row['systemID']) ? $row['systemID'] : SYS_ID).")\n"
                    ."ORDER BY\n"
                    ."  `seq`,`text`";
                break;
            case "push_products_assign":
                $selectorSQL = Push_Product::get_selector_sql(true);
                break;
            case "selector_contact":
                $Obj = new Contact;
                $selectorSQL = $Obj->get_selector_sql();
                break;
            case "selector_gallery_album":
                $Obj = new Gallery_Album;
                $selectorSQL = $Obj->get_selector_sql();
                break;
            case "selector_link":
                $Obj = new Link;
                $selectorSQL = $Obj->get_selector_sql();
                break;
            case "selector_podcast_album":
                $Obj = new Podcast_Album;
                $selectorSQL = $Obj->get_selector_sql();
                break;
        }

        // ###############################
        // # Deal with Read Only fields: #
        // ###############################
        if ($readOnly==1) {
            $bgColor =        '#e8e8e8';
            $borderColor =    '#c0c0c0';
            switch ($type) {
                case "bool":
                    $checksum = System::get_item_version('icons');
                    $out =
                        ($value=="1" ?
                            "<img src=\"".BASE_PATH."img/spacer\" alt=\"Yes\" title=\"Yes\" class='icons'"
                            ." style='margin:1px;height:13px;width:13px;background-position: -2222px 0px;' />"
                            :
                            "<img src=\"".BASE_PATH."img/spacer\" alt=\"No\" title=\"No\" class='icons'"
                            ." style='margin:1px;height:13px;width:13px;background-position: -2235px 0px;' />"
                        );
                    break;
                case "currency":
                    $out =
                        "<div class='fl'>".$system_vars['defaultCurrencySymbol']."</div><"
                        ."div class='formField txt_r fl' style='width:".$width.";background-color:".$bgColor.";'>"
                        .($value ? $value : "&nbsp;")
                        ."</div><div class='clr_b'></div>";
                    break;
                case "date":
                    $value = ($value=='0000-00-00' || $value=='0000-00-00 00:00:00' ? '' : $value);
                    $out =
                        "<input id=\"".$field."\" name=\"".$field."\" type=\"text\" value=\"".$value."\""
                        ." size=\"11\" maxlength=\"10\" class='admin_formFixed'"
                        ." style='background:#e0e0e0;color:#808080;' disabled='disabled' />\n";
                    break;
                case "file_upload":
                    if ($value!='') {
                        $file_params = static::get_embedded_file_properties($value);
                        $out =
                            "<a href=\"".BASE_PATH."?command=download_data"
                            ."&amp;reportID=".$reportID
                            ."&amp;targetID=".$row['ID']
                            ."&amp;targetValue=".$field
                            ."\" rel=\"external\""
                            ." title=\"Download ".$file_params['name']
                            ." (".$file_params['type'].", ".$file_params['size']." bytes)\">Download</a>";
                    } else {
                        $out = "(No file)";
                    }
                    break;
                case "groups_assign":
                    $ObjGroup =   new Group($value);
                    $value =      implode('<br />', $ObjGroup->get_names_for_IDs());
                    $out =
                        "<div style='width:".$width.";height:".($height ? $height : 110)."px;overflow:auto;"
                        ."border:1px solid ".$borderColor.";font-family:courier;font-size:8pt;"
                        ."background-color:".$bgColor.";'>".$value."</div>";
                    break;
                case "groups_assign_person":
                    $Obj_Person =   new Person($value);
                    $_records = $Obj_Person->get_group_membership();
                    $out =
                        "<div style='width:".((int)$width-14)."px;height:".($height ? $height : 240)."px;"
                        ."overflow:auto;border:1px solid ".$borderColor.";font-family:courier;font-size:8pt;"
                        ."background-color:".$bgColor.";'>"
                        ."<div style='padding:3px;'>\n"
                        ."<table class='report' cellpadding='0' cellspacing='0'>\n"
                        ."  <thead>\n"
                        ."    <tr>\n"
                        ."      <th class='grid_head_nosort'>Group</th>\n"
                        ."      <th class='grid_head_nosort'>Description</th>\n"
                        ."      <th class='grid_head_nosort'>"
                        ."[LBL]GREEN-group-viewer|100|Group Viewer Access[/LBL]"
                        ."</th>\n"
                        ."      <th class='grid_head_nosort'>"
                        ."[LBL]GREEN-group-editor|100|Group Editor Access[/LBL]"
                        ."</th>\n"
                        ."      <th class='grid_head_nosort'>"
                        ."[LBL]GREEN-email-recipient|100|Group Email Recipient[/LBL]"
                        ."</th>\n"
                        ."      <th class='grid_head_nosort'>"
                        ."[LBL]GREEN-email-opt-out|100|Group Email Opt Out[/LBL]"
                        ."</th>\n"
                        ."    </tr>\n"
                        ."  </thead>\n"
                        ."  <tbody>\n";
                    if (!count($_records)) {
                        $out.=
                            "    <tr>\n"
                            ."      <td colspan='8'><i>(There are no <b>Group Membership</b> records)</i></td>\n";
                    } else {
                        foreach ($_records as $_record) {
                            $out.=
                                "    <tr>\n"
                                ."      <td><b>".$_record['name']."</b></td>\n"
                                ."      <td>".$_record['description']."</td>\n"
                                ."      <td>"
                                .draw_form_field('', $_record['permVIEWER'], 'bool', 0, 0, 0, 0, 1)
                                ."</td>\n"
                                ."      <td>"
                                .draw_form_field('', $_record['permEDITOR'], 'bool', 0, 0, 0, 0, 1)
                                ."</td>\n"
                                ."      <td>"
                                .draw_form_field('', $_record['permEMAILRECIPIENT'], 'bool', 0, 0, 0, 0, 1)
                                ."</td>\n"
                                ."      <td>"
                                .draw_form_field('', $_record['permEMAILOPTOUT'], 'bool', 0, 0, 0, 0, 1)
                                ."</td>\n"
                                ."    </tr>\n";
                        }
                    }
                    $out.=
                        "</tbody>\n"
                        ."</table>\n"
                        ."</div>"
                        ."</div>";
                    $out = convert_labels($out);
                    break;
                case "importance":
                    $checksum = System::get_item_version('icons');
                    $out =
                        ($value=="1" ?
                            "<img src=\"".BASE_PATH."img/spacer\" alt=\"Yes\" title=\"Yes\" class='icons'"
                            ." style='margin:1px;height:13px;width:13px;background-position: -2222px 0px;' />"
                            :
                            "<img src=\"".BASE_PATH."img/spacer\" alt=\"No\" title=\"No\" class='icons'"
                            ." style='margin:1px;height:13px;width:13px;background-position: -2235px 0px;' />"
                        );
                    break;
                case "int":
                    $out =
                        "<div class='formField txt_r' style='width:".$width.";background-color:".$bgColor.";'>"
                        .($value ? $value : "&nbsp;")
                        ."</div>";
                    break;
                case "percent":
                    $out =
                        "<div class='formField txt_r fl' style='width:".$width.";background-color:".$bgColor.";'>"
                        .($value ? $value : "&nbsp;")
                        ."</div><div class='fl'>%</div><div class='clr_b'></div>";
                    break;
                case "checkbox_listdata_csv":
                case "checkbox_sql_csv":
                case "radio_listdata":
                case "selector":
                case "selector_url":
                case "selector_listdata":
                    $out_arr = array();
                    $sql =   get_sql_constants($selectorSQL);
                    $records = static::get_records_for_sql($sql);
                    if ($records===false) {
                        $out_arr[]=z($sql);
                    } else {
                        foreach ($records as $record) {
                            if (count($records)==1 || $record['value']==$value) {
                                $out_arr[] = $record['text'];
                                $out_arr[] = draw_form_field($field, $record['value'], 'hidden');
                            }
                        }
                    }
                    $out =
                        "<div class='formField'"
                        ." style='width:".$width.";height:1.4em;background-color:".$bgColor.";font-size:80%'>"
                        .implode("", $out_arr)
                        ."</div>";
                    break;
                case "textarea":
                    $out =
                        "<div class='formField' style='width:".$width.";height:".($height ? (int)$height : 70)."px;"
                        ."overflow:scroll;font-family:courier;font-size:8pt;"
                        ."background-color:".$bgColor.";white-space:pre;'>".$value."</div>";
                    break;
                case "textarea_big":
                case "textarea_readonly":
                    $out =
                        "<div class='formField' style='width:".$width.";height:".($height ? (int)$height : 140)."px;"
                        ."overflow:scroll;font-family:courier;font-size:8pt;"
                        ."background-color:".$bgColor.";white-space:pre;'>".$value."</div>";
                    break;
                case "text":
                    $out =
                        "<div id=\"div_".$field."\" class='formField'"
                        ." style='width:".$width.";height:1.4em;background-color:".$bgColor.";"
                        .((int)$width>100 ? "overflow:auto;" : "")
                        ."'>"
                        .$value
                        ."<input type='hidden' id=\"".$field."\" value=\"".$value."\" /></div>";
                    break;
                case "text_fixed":
                    $out =
                        "<div id=\"div_".$field."\" class='admin_formFixed'"
                        ." style='width:".$width.";height:1.4em;background-color:".$bgColor.";"
                        .((int)$width>100 ? "overflow:auto;" : "")
                        ."'>"
                        .$value
                        ."<input type='hidden' id=\"".$field."\" value=\"".$value."\" /></div>";
                    break;
                case "toggle_shared":
                    $checksum = System::get_item_version('icons');
                    $out = ($value=="1" ?
                        "<img src=\"".BASE_PATH."img/spacer\" alt=\"Yes\" title=\"Yes\" class='icons'"
                        ." style='margin:1px;height:13px;width:13px;background-position: -2222px 0px;' />"
                        :
                        "<img src=\"".BASE_PATH."img/spacer\" alt=\"No\" title=\"No\" class='icons'"
                        ." style='margin:1px;height:13px;width:13px;background-position: -2235px 0px;' />"
                    );
                    break;
                case "tristate":
                    switch ($value) {
                        case "0":
                            $out =
                                "<img src='".BASE_PATH."img/spacer' class='icons'"
                                ." style='height:12px;width:12px;background-position:-2837px 0px;' alt='None' />";
                            break;
                        case "1":
                            $out =
                                "<img src='".BASE_PATH."img/spacer' class='icons'"
                                ." style='height:12px;width:12px;background-position:-2849px 0px;' alt='Read Only' />";
                            break;
                        case "2":
                            $out =
                                "<img src='".BASE_PATH."img/spacer' class='icons'"
                                ." style='height:12px;width:12px;background-position:-2861px 0px;' alt='Read+Write' />";
                            break;
                    }
                    break;
                default:
                    $out =
                        "<div id=\"div_".$field."\" style='width:".$width.";height:1.4em;"
                        ."border:1px solid ".$borderColor.";font-family:courier;font-size:8pt;"
                        ."background-color:".$bgColor.";'>".$value."</div>";
                    break;
            }
        } else {
            switch ($type) {
                case "field_processor":
                    $Obj =  new FieldProcessor;
                    $field_bits = array(
                        'name'=> $field,
                        'params'=>$formFieldSpecial,
                        'width'=>$width
                    );
                    $out = $Obj->draw($field_bits, $field, 'custom_form', $formFieldSpecial);
                    break;
                case "ajax_report_lookup":
                    //          print $formFieldSpecial;die;
                    $pa =   explode("|", $formFieldSpecial);
                    $Obj_RFFL = new Report_Form_Field_Lookup;
                    $args = array(
                        'field' =>          $field,
                        'value' =>          $value,
                        'control_num' =>    Ajax::generate_control_num(),
                    );
                    if (isset($pa[0])) {
                        $args['report_name'] =           $pa[0];
                    }
                    if (isset($pa[1])) {
                        $args['report_field'] =          $pa[1];
                    }
                    if (isset($pa[2])) {
                        $args['report_matchmode'] =      $pa[2];
                    }
                    if (isset($pa[3])) {
                        $args['linked_field'] =          $pa[3];
                    }
                    if (isset($pa[4])) {
                        $args['displayed_field'] =       $pa[4];
                    }
                    if (isset($pa[5])) {
                        $args['autocomplete'] =          $pa[5];
                    }
                    if (isset($pa[6])) {
                        $args['row_js'] =                $pa[6];
                    }
                    if (isset($pa[7])) {
                        $args['onematch_js'] =           $pa[7];
                    }
                    if (isset($pa[8])) {
                        $args['nomatch_js'] =            $pa[8];
                    }
                    if (isset($pa[9])) {
                        $args['lookup_info_initial'] =   $pa[9];
                    }
                    if (isset($pa[10])) {
                        $args['lookup_result_initial'] = $pa[10];
                    }
                    if (isset($pa[11])) {
                        $args['results_height'] =        $pa[11];
                    }
                    $Obj_RFFL->init($args);
                    $out = $Obj_RFFL->draw();
                    break;
                case "bool":
                    $out =
                        "<input id=\"$field\" name=\"$field\" type=\"checkbox\" value=\"1\" class='formField'"
                        ." style=\"border: 1px solid transparent;background-color: transparent;\""
                        .($value=="1" ? " checked='checked'" : "")
                        ." $jsCode/>";
                    break;
                case "button_generic":
                    $out =
                        "<input id=\"$field\" type=\"button\" value=\"$value\" onclick=\"$jsCode\""
                        ." class='formButton'/>";
                    break;
                case "button_state_effect_levels":
                case "button_state_effect_types":
                    switch ($type) {
                        case "button_state_effect_levels":
                            $listtype =   "lst_text_effect_levels";
                            break;
                        case "button_state_effect_types":
                            $listtype =   "lst_text_effect_types";
                            break;
                    }
                    $_width =         ((int)$width/4)-($bulk_update ? 21 : 0);
                    $_padding_top =   ((int)$height/2);
                    $_div_open =
                        "<div class='admin_formLabel' style='width:".$_width."px;"
                        ."padding-top:".$_padding_top."px;float:left'>";
                    $field_active =       $field."_active";
                    $value_active =       (isset($row[$field_active]) ? $row[$field_active] : "");
                    $field_down =         $field."_down";
                    $value_down =         (isset($row[$field_down]) ?   $row[$field_down] : "");
                    $field_normal =       $field."_normal";
                    $value_normal =       (isset($row[$field_normal]) ? $row[$field_normal] : "");
                    $field_over =         $field."_over";
                    $value_over =         (isset($row[$field_over]) ?   $row[$field_over] : "");
                    return
                        ($bulk_update ?
                            "<input id=\"".$field_active."_apply\" name=\"".$field_active."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060; margin: 0 2px 0 0;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_active,
                            $value_active,
                            "selector_listdata",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0,
                            "",
                            $listtype."|0",
                            0
                        )
                        ."</div>"
                        .($bulk_update ?
                            "<input id=\"".$field_down."_apply\" name=\"".$field_down."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_down,
                            $value_down,
                            "selector_listdata",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0,
                            "",
                            $listtype."|0",
                            0
                        )
                        ."</div>"
                        .($bulk_update ?
                            "<input id=\"".$field_normal."_apply\" name=\"".$field_normal."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_normal,
                            $value_normal,
                            "selector_listdata",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0,
                            "",
                            $listtype."|0",
                            0
                        )
                        ."</div>"
                        .($bulk_update ?
                            "<input id=\"".$field_over."_apply\" name=\"".$field_over."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060; margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_over,
                            $value_over,
                            "selector_listdata",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0,
                            "",
                            $listtype."|0",
                            0
                        )."</div>"
                        ."<div class='clear'>&nbsp;</div>"
                        ;
                    break;
                case "button_state_swatches":
                    $_width =         ((int)$width/4)-($bulk_update ? 21 : 0);
                    $_padding_top =   ((int)$height/2);
                    $_div_open =
                        "<div class='admin_formLabel' style='width:".$_width."px;"
                        ."padding-top:".$_padding_top."px;text-align:center;float:left'>";
                    $field_active =       $field."_active";
                    $value_active =       (isset($row[$field_active]) ? $row[$field_active] : "000000");
                    $field_down =         $field."_down";
                    $value_down =         (isset($row[$field_down]) ?   $row[$field_down] : "000000");
                    $field_normal =       $field."_normal";
                    $value_normal =       (isset($row[$field_normal]) ? $row[$field_normal] : "000000");
                    $field_over =         $field."_over";
                    $value_over =         (isset($row[$field_over]) ?   $row[$field_over] : "000000");
                    return
                        ($bulk_update ?
                            "<input id=\"".$field_active."_apply\" name=\"".$field_active."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 0px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_active,
                            $value_active,
                            "swatch",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0
                        )
                        ."</div>"
                        .($bulk_update ?
                            "<input id=\"".$field_down."_apply\" name=\"".$field_down."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_down,
                            $value_down,
                            "swatch",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0
                        )
                        ."</div>"
                        .($bulk_update ?
                            "<input id=\"".$field_normal."_apply\" name=\"".$field_normal."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_normal,
                            $value_normal,
                            "swatch",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0
                        )
                        ."</div>"
                        .($bulk_update ?
                            "<input id=\"".$field_over."_apply\" name=\"".$field_over."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field(
                            $field_over,
                            $value_over,
                            "swatch",
                            $_width,
                            "",
                            "",
                            0,
                            0,
                            0
                        )
                        ."</div>"
                        ."<div class='clear'>&nbsp;</div>";
                    break;
                case "captcha":
                    $out =
                        "<input id=\"$field\" type=\"text\" name=\"$field\" value=\"$value\""
                        ." class='formField' size=\"1\" maxlength=\"1\" style=\"width: 20px;\" $jsCode/>";
                    break;
                case "char":
                    $out =
                        "<input id=\"$field\" type=\"text\" name=\"$field\" value=\"$value\""
                        ." class='formField' size=\"1\" maxlength=\"1\" style=\"width: 20px;\" $jsCode/>";
                    break;
                case "checkbox_csvlist":
                    $options =        explode(",", str_replace(" ", "", $formFieldSpecial));
                    $value_arr =      array();
                    $value_arr_old =  explode(",", str_replace(" ", "", $value));
                    foreach ($value_arr_old as $v) {
                        if (in_array($v, $options)) {
                            $value_arr[] = $v;
                        }
                    }
                    $value = implode(', ', $value_arr);
                    $out =
                        "<input type='hidden' name='".$field."' id='".$field."' value='".$value."' />\n";
                    foreach ($options as $option) {
                        $idx = Page::get_css_idx($option['color_text'], $option['color_background']);
                        $out.=
                            "<label style=\"width:".((int)$width)."px\">"
                            ."<input id=\"".$field."_".$option."\" type='checkbox' "
                            .(in_array($option, $value_arr) ? "checked='checked' " : "")
                            ."onclick=\"field_csv_toggle('".$field."','".$option."',this.checked)\" />"
                            .title_case_string($option)
                            ."</label>\n";
                    }
                    break;
                case "checkbox_csvlist_scrollbox":
                    $options =        explode(",", str_replace(" ", "", $formFieldSpecial));
                    $value_arr =      array();
                    $value_arr_old =  explode(",", str_replace(" ", "", $value));
                    foreach ($value_arr_old as $v) {
                        if (in_array($v, $options)) {
                            $value_arr[] = $v;
                        }
                    }
                    $value = implode(', ', $value_arr);
                    $out =
                        "<div style=\"border:1px solid #808080;background-color:#ffffff;"
                        ."font-size:80%;font-weight:bold;text-align:left;"
                        ."height:".$height."px;width:".((int)$width-17)."px;overflow:auto;\">\n"
                        ."<input type='hidden' name='".$field."' id='".$field."' value='".$value."' />\n";
                    foreach ($options as $option) {
                        $color_text =   (isset($option['color_text']) ? $option['color_text'] : '000000');
                        $color_bg =     (isset($option['color_background']) ? $option['color_background'] : 'ffffff');
                        $idx = Page::get_css_idx($color_text, $color_bg);
                        $out.=
                            "<div class=\"color_$idx\">"
                            ."<label style=\"padding-left:10px;padding-right:5px;\">"
                            ."<input id=\"".$field."_".$option."\" type='checkbox' "
                            .(in_array($option, $value_arr) ? "checked='checked' " : "")
                            ."onclick=\"field_csv_toggle('".$field."','".$option."',this.checked)\" />"
                            .title_case_string($option)
                            ."</label></div>\n";
                    }
                    $out.= "</div>";
                    break;
                case "checkbox_listdata_csv":
                case "checkbox_sql_csv":
                    $options = static::get_records_for_sql($selectorSQL);
                    $options_values_arr = array();
                    foreach ($options as $option) {
                        $options_values_arr[] = $option['value'];
                    }
                    $value_arr =      array();

                    $value_arr_old =  explode(",", str_replace(" ", "", $value));
                    foreach ($value_arr_old as $v) {
                        if (in_array($v, $options_values_arr)) {
                            $value_arr[] = $v;
                        }
                    }
                    $value = implode(', ', $value_arr);
                    $out=
                        "<div id='checkbox_csv_".$field."' class='checkbox_csv'"
                        ." style='height:".$height."px;width:".((int)$width)."px'>\n"
                        ."<input type='hidden' name='".$field."' id='".$field."' value='".$value."' />\n";
                    $value_arr = explode(",", str_replace(" ", "", $value));
                    for ($i=0; $i<count($options); $i++) {
                        $option=$options[$i];
                        switch ($option['isHeader']) {
                            case "0":
                                if ($option['value']!='') {
                                    $idx = Page::get_css_idx($option['color_text'], $option['color_background']);
                                    $out.=
                                        "<label class=\"color_".$idx." header\">"
                                        ."<input id=\"".$field."_".$i."\" type='checkbox' "
                                        .(in_array($option['value'], $value_arr) ? "checked='checked' " : "")
                                        ."onclick=\"field_csv_toggle('".$field."','".$option['value']."',this.checked)"
                                        ."\" />"
                                        .$option['text']
                                        ."</label>\n"
                                    ;
                                }
                                break;
                            case "1":
                                $idx = Page::get_css_idx($option['color_text'], $option['color_background']);
                                $out.=
                                    "<div class=\"color_".$idx."\">"
                                    .$option['text']
                                    ."</div>\n";
                                break;
                        }
                    }
                    $out.= "</div>";
                    break;
                case "checkbox_multi_horizontal":
                    break;
                case "combo_listdata":
                    $params_arr =     explode("|", $formFieldSpecial);
                    $isMASTERADMIN =    get_person_permission("MASTERADMIN");
                    $isSYSADMIN =        get_person_permission("SYSADMIN");
                    $can_add = (isset($params_arr[1]) ? ($isMASTERADMIN || $isSYSADMIN) &&$params_arr[1] : 0);
                    if ($can_add) {
                        $Obj = new ListType;
                        $ID =             $Obj->get_ID_by_name($params_arr[0]);
                        $_report_name =   "listtype";
                        $_popup_size =    get_popup_size($_report_name);
                        $out =
                            "<span class=\"fl\">"
                            .static::draw_combo_selector(
                                $field,
                                $value,
                                $selectorSQL,
                                ((int)$width-22)."px",
                                $reportID,
                                $jsCode
                            )
                            ."&nbsp; </span>"
                            .($ID!="" ?
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Edit list entries';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details('".$_report_name."','".$ID."',"
                                ."'".$_popup_size['h']."','".$_popup_size['w']."','','');return false;\">"
                                .convert_icons("[ICON]17 17 1390 Edit List entries[/ICON]")."</a>"
                                :
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Add a new list type';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details('".$_report_name."','',"
                                ."'".$_popup_size['h']."','".$_popup_size['w']."','','',false,"
                                ."'name=".$params_arr[0]."&systemID=".SYS_ID."');return false;\">"
                                .convert_icons("[ICON]14 14 715 Add new ListType[/ICON]")."</a>"
                            );
                    } else {
                        $out =
                            static::draw_combo_selector($field, $value, $selectorSQL, $width, $reportID, $jsCode);
                    }
                    break;
                case "combo_selector":
                    $out = static::draw_combo_selector($field, $value, $selectorSQL, $width, $reportID, $jsCode);
                    break;
                case "csv":
                    $value = explode(",", str_replace(", ", ",", $value));
                    asort($value);
                    $value =      implode("\n", $value);
                    $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
                    Output::push(
                        'javascript_onload',
                        "  \$J('#".$jq_field."')[0].value="
                        .json_encode(
                            str_replace(
                                array("<<br>>","</textarea>"),
                                array("\r\n","&lt;/textarea&gt;"),
                                $value
                            )
                        )
                        .";"
                    );
                    $out =
                        "<textarea id=\"".$field."\" name=\"".$field."\""
                        ." style=\"width: ".$width.";height:".($height ? $height : 150)."px;\""
                        ." rows=\"4\" cols=\"80\" ".$jsCode.">"
                        ."</textarea>\n";
                    break;
                case "currency":
                    $out =
                        $system_vars['defaultCurrencySymbol']
                        ."<input id=\"".$field."\" name=\"".$field."\" type=\"text\" value=\"".$value."\""
                        ." class='formField txt_r' style=\"width: ".$width.";\" $jsCode/>"
                        .static::attach_behaviour($field, $type);
                    break;
                case "date":
                    $value = ($value=='0000-00-00' || $value=='0000-00-00 00:00:00' ? '' : $value);
                    $out =
                        "<input id=\"".$field."\" name=\"".$field."\" type=\"text\" value=\"".$value."\""
                        ." size=\"12\" maxlength=\"10\" class='admin_formFixed' $jsCode />\n"
                        .static::attach_behaviour($field, $type);
                    break;
                case "datetime":
                    $value = ($value=='0000-00-00' || $value=='0000-00-00 00:00:00' ? '' : $value);
                    $out =
                        "<input id=\"".$field."\" name=\"".$field."\" type=\"text\" value=\"".$value."\""
                        ." size=\"20\" maxlength=\"19\" class='admin_formFixed' $jsCode />\n"
                        ." <span style='font-size: 80%'>(YYYY-MM-DD hh:mm:ss)</span>"
                        .static::attach_behaviour($field, $type);
                    break;
                case "year_month":
                    $value = ($value=='0000-00' ? '' : $value);
                    $out =
                        "<select id=\"$field\" name=\"$field\" style=\"width: ".(((int)$width)+4)."px;\""
                        ." class=\"formField\"".($jsCode ? " ".$jsCode : "").">\n";
                    if ($formFieldSpecial) {
                        $formFieldSpecial = explode('|', $formFieldSpecial);
                    }
                    $rangeStart =   (isset($formFieldSpecial[0]) ? $formFieldSpecial[0] : '2010-01-01');
                    $rangeEnd =     (isset($formFieldSpecial[1]) ? $formFieldSpecial[1] : date('Y-m-d', time()));
                    $entries =      array_reverse(
                        get_dates_in_range($rangeStart, $rangeEnd,'+1 month', 'Y-m')
                    );
                    foreach ($entries as $entry) {
                        $out.=
                            "  <option value=\"".$entry."\""
                            .($entry==$value ? " selected='selected'" : "")
                            .">".$entry."</option>\n";
                    }
                    $out.= "</select>\n";
                    break;
                case "email":
                    $out =
                        "<input id=\"".$field."\" name=\"".$field."\" type=\"text\" value=\"".$value."\""
                        ." class='formField'  style=\"width: ".$width.";\" $jsCode/>";
                    break;
                case "event_end_date_and_time":
                    return Event::form_field_end_date_and_time($width, $bulk_update, $row);
                    break;
                case "event_recurrence_settings":
                    return Event_Recurrence::form_field_recurrence_settings($width, $bulk_update, $row);
                    break;
                case "event_start_date_and_time":
                    return Event::form_field_start_date_and_time($width, $bulk_update, $row);
                    break;
                case "fieldset_map_loc_lat_lon":
                    $params = explode('|', $formFieldSpecial);
                    $_type =    urlencode($params[0]);
                    $_info =    $params[1];
                    $_lat =     $params[2];
                    $_lon =     $params[3];
                    $_area =    $params[4];
                    $field_names = explode(',', $field);
                    $_width =         (int)$width-($bulk_update ? 21 : 0);
                    $_div_open =      "<div style='float:left;'>";
                    $field_loc =      $field_names[0];
                    $value_loc =      (isset($row[$field_loc]) ? $row[$field_loc]  : '');
                    $field_lat =      $field_names[1];
                    $value_lat =      (isset($row[$field_lat]) ? $row[$field_lat] : '');
                    $field_lon =      $field_names[2];
                    $value_lon =      (isset($row[$field_lon]) ? $row[$field_lon] : '');
                    $field_qual =     $field_names[3];
                    $value_qual =     (isset($row[$field_qual]) ? $row[$field_qual] : '');
                    $loc_width =      (int)$width-330-($bulk_update ? 21 : 0);
                    $link = ($value_lat!=0 || $value_lon!=0 ?
                        "<a href=\"#\" onclick=\"return popup_map_general("
                        ."'".$_type."'"
                        .",".$row['ID']
                        .",'".$_info."'"
                        .",'".$_lat."'"
                        .",'".$_lon."'"
                        .",'".$_area."'"
                        .")\" title=\"Map link\">"
                        :
                        false
                    );
                    return
                        $_div_open
                        .draw_form_field(
                            $field_loc,
                            $value_loc,
                            "text",
                            $loc_width,
                            '',
                            0,
                            '',
                            1
                        )
                        ."</div>"
                        ."<div style='float:left;width:32px;text-align:right'>"
                        .($link ? $link."Lat</a>" : "Lat")
                        ."</div>"
                        .$_div_open.draw_form_field(
                            $field_lat,
                            ($value_lat ? number_format($value_lat, 4) : ''),
                            "text",
                            70,
                            '',
                            0,
                            '',
                            1
                        )
                        ."</div>"
                        ."<div style='float:left;width:34px;text-align:right'>"
                        .($link ? $link."Lon</a>" : "Lon")
                        ."</div>"
                        .$_div_open.draw_form_field(
                            $field_lon,
                            ($value_lon ? number_format($value_lon, 4) : ''),
                            "text",
                            70,
                            '',
                            0,
                            '',
                            1
                        )
                        ."</div>"
                        ."<div style='float:left;width:42px;text-align:right'>"
                        .($link ? $link."Qual</a>" : "Qual")
                        ."</div>"
                        .$_div_open.draw_form_field(
                            $field_qual,
                            $value_qual,
                            "text",
                            48,
                            '',
                            0,
                            '',
                            1
                        )
                        ."</div>"
                        ."<div style='float:left;'>%</div><br class='clear' />";
                    break;
                case "fieldset_name_email":
                    $field_names = explode(',', $field);
                    $_width =         ((int)$width/2)-($bulk_update ? 21 : 0);
                    $_div_open =      "<div style='float:left;height:21px;'>";
                    $field_n =        $field_names[0];
                    $value_n =        (isset($row[$field_n]) ?
                        $row[$field_n]
                        :
                        (isset($_REQUEST[$field_n]) ? $_REQUEST[$field_n] : '')
                    );
                    $field_e =        $field_names[1];
                    $value_e =        (isset($row[$field_e]) ? $row[$field_e] : '');
                    return
                        ($bulk_update ?
                            "<input id=\"".$field_n."_apply\" name=\"".$field_n."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 0px;\">"
                            :
                            ""
                        )
                        .$_div_open
                        .draw_form_field($field_n, $value_n, "text", $_width)
                        ."&nbsp; "
                        ."</div>"
                        .($bulk_update ?
                            "<input id=\"".$field_e."_apply\" name=\"".$field_e."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field($field_e, $value_e, "text", $_width-8)."</div>"
                        ."<div class='clear'>&nbsp;</div>";
                    break;
                case "fieldset_text_text_date":
                    $field_names = explode(',', $field);
                    $_width =         (((int)$width-121)/2)-($bulk_update ? 21 : 0);
                    $_div_open =      "<div style='float:left;height:21px;'>";
                    $field_1 =        $field_names[0];
                    $value_1 =        (isset($row[$field_1]) ?
                        $row[$field_1]
                        :
                        (isset($_REQUEST[$field_1]) ? $_REQUEST[$field_1] : '')
                    );
                    $field_2 =        $field_names[1];
                    $value_2 =        (isset($row[$field_2]) ? $row[$field_2] : '');
                    $field_3 =        $field_names[2];
                    $value_3 =        (isset($row[$field_3]) ? $row[$field_3] : '');
                    return
                        ($bulk_update ?
                            "<input id=\"".$field_1."_apply\" name=\"".$field_1."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 0px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field($field_1, $value_1, "text", $_width)."&nbsp; </div>"
                        .($bulk_update ?
                            "<input id=\"".$field_2."_apply\" name=\"".$field_2."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field($field_2, $value_2, "text", $_width-8)."&nbsp; </div>"
                        .($bulk_update ?
                            "<input id=\"".$field_3."_apply\" name=\"".$field_3."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field($field_3, $value_3, "date", $_width-8)."</div>"
                        ."<div class='clear'>&nbsp;</div>";
                    break;
                case "fieldset_name_phone":
                    $field_names = explode(',', $field);
                    $_width =         ((int)$width/2)-($bulk_update ? 21 : 0);
                    $_div_open =      "<div style='float:left;height:21px;'>";
                    $field_n =        $field_names[0];
                    $value_n =        (isset($row[$field_n]) ?
                        $row[$field_n]
                        :
                        (isset($_REQUEST[$field_n]) ? $_REQUEST[$field_n] : '')
                    );
                    $field_p =        $field_names[1];
                    $value_p =        (isset($row[$field_p]) ? $row[$field_p] : '');
                    return
                        ($bulk_update ?
                            "<input id=\"".$field_n."_apply\" name=\"".$field_n."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 0px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field($field_n, $value_n, "text", $_width)."&nbsp; </div>"
                        .($bulk_update ?
                            "<input id=\"".$field_p."_apply\" name=\"".$field_p."_apply\""
                            ." title=\"Apply changes to this field\" type='checkbox' value=\"1\""
                            ." class=\"fl formField\" style=\"background-color: #60a060;  margin: 0 2px 0 2px;\">"
                            :
                            ""
                        )
                        .$_div_open.draw_form_field($field_p, $value_p, "text", $_width-8)."</div>"
                        ."<div class='clear'>&nbsp;</div>";
                    break;
                case "file_upload":
                    if ($value!='') {
                        $file_params = static::get_embedded_file_properties($value);
                        $out =
                            "<a href=\"".BASE_PATH."?command=download_data"
                            ."&amp;reportID=".$reportID
                            ."&amp;targetID=".$row['ID']
                            ."&amp;targetValue=".$field
                            ."\" rel=\"external\""
                            ." title=\"Download ".$file_params['name']." ("
                            .$file_params['type'].", ".$file_params['size']." bytes)\">Download</a>"
                            ." or replace: <input id=\"".$field."\" type=\"file\" name=\"".$field."\" value=\"\""
                            ." class='formField'  style=\"width: ".((int)$width-150)."px;\" $jsCode/>";
                    } else {
                        $out =
                            "<input id=\"".$field."\" type=\"file\" name=\"".$field."\" value=\"\" class='formField'"
                            ." style=\"width: ".(int)$width."px;\" $jsCode/>";
                    }
                    break;
                case "file_upload_to_userfile_folder":
                    if (isset($row['ID'])) {
                        if ($value!='') {
                            $file_params = static::get_embedded_file_properties($value);
                            $out =
                                "<input class='fl' id=\"".$field."_mark_delete\" type=\"checkbox\""
                                ." name=\"".$field."_mark_delete\" value=\"1\""
                                ." onclick=\"toggle_attachment_delete_flag('".$field."')\" />"
                                ."<label class='fl' for=\"".$field."_mark_delete\" style=\"padding-top:2px;\">"
                                ."Delete &nbsp;</label>"
                                ."<div id=\"div_".$field."_mark_delete_0\" style=\"float:left;\""
                                ." onclick=\"geid_set('".$field."_mark_delete',1);"
                                ."toggle_attachment_delete_flag('".$field."')\">"
                                .convert_safe_to_php(
                                    "[ICON]15 15 3704 Not marked for deletion - click to change[/ICON]"
                                )
                                ."</div>"
                                ."<div id=\"div_".$field."_mark_delete_1\" style=\"float:left;display:none;\""
                                ." onclick=\"geid_set('".$field."_mark_delete',0);"
                                ."toggle_attachment_delete_flag('".$field."')\">"
                                .convert_safe_to_php(
                                    "[ICON]15 15 3719 Marked for Deletion - click to change[/ICON]"
                                )
                                ."</div>"
                                ."<input id=\"".$field."\" type=\"file\" name=\"".$field."\" value=\"\""
                                ." class='formField fl'"
                                ." style=\"margin-left:5px;width:".((int)$width-98)."px;\" $jsCode/>"
                                ."<div style=\"width:16px;float:left;padding-left:5px\">"
                                ."<a href=\"".BASE_PATH."?command=download_userfile_data"
                                ."&amp;reportID=".$reportID
                                ."&amp;targetID=".$row['ID']
                                ."&amp;targetValue=".$field
                                ."\""
                                ." rel=\"external\" >"
                                .convert_safe_to_php(
                                    "[ICON]16 16 3647 Download ".$file_params['name']
                                    ."\n(".$file_params['type'].", ".$file_params['size']." bytes)[/ICON]"
                                )
                                ."</a>"
                                ."</div>";
                        } else {
                            $out =
                                "<input id=\"".$field."\" type=\"file\" name=\"".$field."\" value=\"\""
                                ." class='formField fl'  style=\"width:".((int)$width-19)."px;\" $jsCode/>"
                                ."<div style=\"padding-left:5px;float:left\">"
                                ."<a href=\"#\" onclick=\"alert('No file to download');return false;\">"
                                .convert_safe_to_php("[ICON]16 16 3631 No file to download[/ICON]")
                                ."</a>"
                                ."</div>";
                        }
                    } else {
                        $out =
                            "<div id=\"".$field."\">"
                            ."You cannot assign files to this record until you have first saved it."
                            ."</div>";
                    }
                    break;
                case "groups_assign":
                    $out = static::draw_list_selector(
                        $field,
                        $value,
                        $selectorSQL,
                        false,
                        $width,
                        ($height ? $height : 110)
                    );
                    break;
                case "groups_assign_person":
                    if ($value=='') {
                        $out =
                            "<div id=\"".$field."\">"
                            ."You cannot assign this person to groups until you have saved the record."
                            ."</div>";
                    } else {
                        $out =
                            "<a class=\"iframe\""
                            ." href=\"".BASE_PATH."report/person_groups?print=2&amp;selectID=".$value."\""
                            ." rel=\"style=width:".$width.";height:".($height ? (int)$height : 240)."px;\">"
                            ."Embedded Content"
                            ."</a>\n";
                    }
                    break;
                case "hh:mm":
                    $out =
                        "<input type=\"text\" id=\"$field\" name=\"$field\" value=\"$value\""
                        ." size=\"6\" maxlength=\"5\" class='admin_formFixed' $jsCode/>"
                        .static::attach_behaviour($field, $type);
                    break;
                case "hidden":
                    $out =
                        "<input type=\"hidden\" id=\"$field\" name=\"$field\" value=\"$value\" $jsCode/>";
                    break;
                case "html":
                    $Obj_FCK = new FCK;
                    $out = $Obj_FCK->draw_editor(
                        $field,
                        $value,
                        $width,
                        ($height ? (int)$height.'px' : '300px'),
                        ($formFieldSpecial ? $formFieldSpecial : 'Page')
                    );
                    break;
                case "html_multi_block":
                    $params_arr = explode("|", $formFieldSpecial);
                    $num = $params_arr[0];
                    $tb = (isset($params_arr[1]) ? $params_arr[1] : 'Page');
                    $lbl = (isset($params_arr[2]) ? $params_arr[2] : 'Block');
                    $spans = array();
                    for ($i=0; $i<$formFieldSpecial; $i++) {
                        $spans[] = "'".$field."_".$i."'";
                    }
                    Output::push('javascript', "var spans_mb_".$field." = [".implode(",", $spans)."];");
                    $out = "<div class='section_sub_tabs'>\n";
                    for ($i=0; $i<$formFieldSpecial; $i++) {
                        $out.=
                            "<div class='"
                            .($i==0 ? 'tab_selected' : 'tab')
                            ."' id='section_".$field."_".$i."_heading' style=\"width:80px;\""
                            ." onclick=\"return show_section(spans_mb_".$field.",'".$field."_".$i."');\">"
                            ."<a title=\"Click to view ".$field." ".($i+1)."\" onclick='return false;'>"
                            .$lbl." ".($i+1)
                            ."</a>"
                            ."</div>\n";
                    }
                    $out.= "</div><div class='clr_b'></div>\n";
                    for ($i=0; $i<$formFieldSpecial; $i++) {
                        $Obj_FCK = new FCK;
                        $out.=
                            "<div id='section_".$field."_".$i."' style='display:".($i==0 ? 'inline' : 'none')."'>"
                            .$Obj_FCK->draw_editor(
                                $field."_".($i+1),
                                (isset($row[$field.'_'.($i+1)]) ? $row[$field.'_'.($i+1)] : ''),
                                $width,
                                ($height ? ((int)$height-30).'px' : '270px'),
                                $tb
                            )
                            ."</div>\n";
                    }
                    break;
                case "html_multi_language":
                case "html_with_text":
                    if (!System::has_feature('multi-language')) {
                        $Obj_FCK = new FCK;
                        $out = $Obj_FCK->draw_editor(
                            $field,
                            $value,
                            $width,
                            ($height ? (int)$height.'px' : '300px'),
                            ($formFieldSpecial ? $formFieldSpecial : 'Page')
                        );
                        break;
                    }
                    $Obj_Lang =   new Language;
                    $supported =  $Obj_Lang->get_supported();
                    $spans =      array();
                    $values =     array();
                    for ($i=0; $i<count($supported); $i++) {
                        $spans[] = "'".$field."_".$supported[$i]['value']."'";
                    }
                    for ($i=0; $i<count($supported); $i++) {
                        $values[] = "";
                    }
                    $pagebits = preg_split("/\[LANG\]|\[\/LANG\]/", $value);
                    if (count($pagebits)<=1) {
                        $values[0] = $value;
                    } else {
                        $renderedbit =    array();
                        $plaintext =      true;
                        foreach ($pagebits as $bit) {
                            $o =      "";
                            $lang =   "";
                            if ($plaintext) {
                                $o.= $bit;
                            } else {
                                if (in_array($bit, $renderedbit)) {
                                    $o.= $renderedbit[$bit];
                                } else {
                                    $bit_arr =  explode('|', $bit);
                                    $lang =     array_shift($bit_arr);
                                    $text =     implode('|', $bit_arr);
                                    $o.=        $text;
                                }
                            }
                            $plaintext = !$plaintext;
                            if ($lang=="") {
                                $values[0].= $o;
                            } else {
                                for ($i=0; $i<count($supported); $i++) {
                                    if ($lang == $supported[$i]['value']) {
                                        $values[$i].= $o;
                                    }
                                }
                            }
                        }
                    }
                    Output::push('javascript', "var spans_html_".$field." = [".implode(",", $spans)."];");
                    $out =
                        "<div class='section_sub_tabs'>\n";
                    for ($i=0; $i<count($supported); $i++) {
                        $out.=
                            "<div class='".($i==0 ? 'tab_selected' : 'tab')."'"
                            ." id='section_".$field."_".$supported[$i]['value']."_heading' style=\"width:80px;\""
                            ." onclick=\"return show_section("
                            ."spans_html_".$field.",'".$field."_".$supported[$i]['value']."'"
                            .");\">"
                            ."<a title=\"Click to view ".$supported[$i]['text']."\" onclick='return false;'>"
                            ." ".$supported[$i]['text']
                            ."</a>"
                            ."</div>\n";
                    }
                    $out.= "</div><div class='clr_b'></div>\n";
                    for ($i=0; $i<count($supported); $i++) {
                        //$value = (isset($row[$field]) ? $row[$field] : '');
                        $Obj_FCK = new FCK;
                        $out.=
                            "<div id='section_".$field."_".$supported[$i]['value']."'"
                            ." style='display:".($i==0 ? 'inline' : 'none')."'>"
                            .$Obj_FCK->draw_editor(
                                $field."_".$supported[$i]['value'],
                                $values[$i],
                                $width,
                                ($height ? ((int)$height-30).'px' : '270px'),
                                ($formFieldSpecial ? $formFieldSpecial : 'Page')
                            )
                            ."</div>\n";
                    }
                    break;
                case "iframe":
                    $src = (substr($formFieldSpecial, 0, 1)=='./' ?
                        BASE_PATH.substr($formFieldSpecial, 2)
                        :
                        $formFieldSpecial
                    );
                    $xhtml_safe_src = str_replace(
                        array(
                            '&ID=',
                            '&eventID=',
                            '&print=',
                            '&selectID=',
                        ),
                        array(
                            '&amp;ID=',
                            '&amp;eventID=',
                            '&amp;print=',
                            '&amp;selectID=',
                        ),
                        $src
                    );
                    $out =
                        "<a class=\"iframe\" href=\"".$xhtml_safe_src."\""
                        ." rel=\"style=width:".$width.";height:".($height ? (int)$height : 280)."px;\">"
                        ."Embedded Content"
                        ."</a>\n";
                    break;
                case "importance":
                    $out =
                        "<input id=\"$field\" name=\"$field\" type=\"checkbox\" value=\"1\" class='formField'"
                        ." style=\"border: 1px solid transparent;background-color: transparent;\""
                        .($value=="1" ? " checked='checked'" : "")
                        ." $jsCode/>";
                    break;
                case "int":
                    $out =
                        "<input id=\"$field\" type=\"text\" name=\"$field\" value=\"".$value."\""
                        ." style=\"width: ".$width.";\" class='formField txt_r' $jsCode/>"
                        .static::attach_behaviour($field, $type);
                    break;
                case "json":
                    $out =
                        "<textarea id=\"".$field."\" name=\"".$field."\" rows='4' cols='80'"
                        ." style=\"width: ".$width.";height: ".($height ? (int)$height : 240)."px;\" ".$jsCode.">"
                        .format_json($value)."\n"
                        ."</textarea>\n";
                    break;

                case "keywords_assign":
                    $isMASTERADMIN =    get_person_permission("MASTERADMIN");
                    $isSYSADMIN =        get_person_permission("SYSADMIN");
                    $isSYSAPPROVER =    get_person_permission("SYSAPPROVER");
                    $can_add =  ($isMASTERADMIN || $isSYSADMIN || $isSYSAPPROVER);
                    if ($can_add) {
                        $_report_name =   "keywords";
                        $_popup_size =    get_popup_size($_report_name);
                        $out =
                            "<div class=\"fl\">"
                            .static::draw_selector_csv(
                                $field,
                                $value,
                                $selectorSQL,
                                ((int)$width-22)."px",
                                ($height ? $height : 35),
                                ($formFieldSpecial=='1' ? '1' : 0)
                            )
                            ."&nbsp; </div>"
                            ."<div class='fl'>\n"
                            ."<a "
                            ."onmouseover=\"window.status='Create a new Keyword...';return true;\" "
                            ."onmouseout=\"window.status='';return true;\""
                            ." href=\"".BASE_PATH."details/".$_report_name."\" rel=\"external\""
                            ." onclick=\"if(confirm("
                            ."'IMPORTANT:\\n\\n  * Cancel and save any unsaved work BEFORE creating new keywords.\\n"
                            ." &nbsp; &nbsp; This screen automatically reloads when keywords are created here.\\n\\n"
                            ."  * Otherwise press \'OK\' to create a new keyword now.'"
                            .")){details("
                            ."'".$_report_name."','','".$_popup_size['h']."','".$_popup_size['w']."','',''"
                            .")};return false;\">"
                            .convert_icons("[ICON]17 16 4788 Create a new Keyword...[/ICON]")."</a><br />\n"
                            ."<a "
                            ."onmouseover=\"window.status='Manage existing Keywords...';return true;\" "
                            ."onmouseout=\"window.status='';return true;\" "
                            ."href=\"".BASE_PATH."report/".$_report_name."?print=1\" rel=\"external\""
                            ." onclick=\"popWin("
                            ."this.href,'".get_js_safe_ID($_report_name)."','resizable=1',770,440,true"
                            .");return false;\">"
                            .convert_icons("[ICON]17 16 4805 Manage Keywords...[/ICON]")."</a>"
                            ."</div>";
                    } else {
                        $out = static::draw_selector_csv(
                            $field,
                            $value,
                            $selectorSQL,
                            $width,
                            ($height ? $height : 35),
                            ($formFieldSpecial=='1' ? '1' : 0)
                        );
                    }
                    break;
                case "label":
                    $out = "$value";
                    break;
                case "label_button_states":
                    $_width =         ((int)$width/4);
                    $_padding_top =   ((int)$height/2);
                    $_div_open =
                        "<div class='admin_formLabel' style='width:".$_width."px;height:".$height."px;"
                        ."padding-top:".$_padding_top."px;text-align:center;float:left'>";
                    $out =
                        $_div_open."Active</div>"
                        .$_div_open."Down</div>"
                        .$_div_open."Normal</div>"
                        .$_div_open."Over</div>"
                        ."<div style='clear:both;height:0;width:0;overflow:hidden'>&nbsp;</div>";
                    $bulk_update = false;
                    break;
                case "languages_assign":
                    $isMASTERADMIN =    get_person_permission("MASTERADMIN");
                    $lbl_entries =        "Languages";
                    $can_add =            $isMASTERADMIN;
                    if ($can_add) {
                        $Obj = new ListType;
                        $ID =             $Obj->get_ID_by_name('lst_language');
                        $_report_name =   "listtype";
                        $_popup_size =    get_popup_size($_report_name);
                        $out =
                            "<div class=\"fl\">"
                            .static::draw_selector_csv(
                                $field,
                                $value,
                                $selectorSQL,
                                ((int)$width-22)."px",
                                ((int)$height ? (int)$height : 35)
                            )
                            ."&nbsp; </div>"
                            .($ID!="" ?
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Edit ".$lbl_entries."';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details("
                                ."'".$_report_name."','".$ID."','".$_popup_size['h']."','".$_popup_size['w']."','',''"
                                .");return false;\">"
                                .convert_icons("[ICON]17 16 1390 Edit ".$lbl_entries."[/ICON]")."</a>"
                                :
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Add a new list type';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details("
                                ."'".$_report_name."','','".$_popup_size['h']."','".$_popup_size['w']."',"
                                ."'','',false,'name=".$params_arr[0]."&systemID=".SYS_ID."'"
                                .");return false;\">"
                                .convert_icons("[ICON]14 14 715 Add new ListType[/ICON]")."</a>"
                            );
                    } else {
                        $out = static::draw_selector_csv(
                            $field,
                            $value,
                            $selectorSQL,
                            $width,
                            ($height ? $height : 35)
                        );
                    }
                    break;
                case "link_programmable_form":
                    $params = explode('|', $formFieldSpecial);
                    $_report_name = $params[0];
                    $_h =             (isset($params[1]) && $params[1] ? $params[1] :       800);
                    $_w =             (isset($params[2]) && $params[2] ? $params[2] :       600);
                    $_tooltip =       (isset($params[3]) && $params[3] ? $params[3] :       'Click to view details');
                    $_link_text =     (isset($params[4]) && $params[4] ? $params[4] :       $value);
                    $_value =         (isset($params[5]) && $params[5] ? $row[$params[5]] : $value);
                    $out =
                        "<a style='font-size: small; font-weight:bold;'"
                        ." href=\"".BASE_PATH."details/".$_report_name."/".$_value."\" "
                        ." onclick=\"details('".$_report_name."','".$_value."','".$_h."','".$_w."');return false;\""
                        ." title=\"".$_tooltip."\">"
                        .$_link_text
                        ."</a>";
                    break;
                case "link_validate_this_content":
                    $params = explode('|', $formFieldSpecial);
                    $_command = $params[0];
                    $_tooltip =     $params[1];
                    $out =
                        "<a style='font-size: small; font-weight:bold;' "
                        ."href=\"#\" onclick=\"validate_at_w3c("
                        ."'".htmlentities($_command.$value)."',800,600"
                        .");return false;\" title=\"Validate content at W3C\">Validate Content</a>";
                    break;
                case "list (a-z)":
                    $out = static::draw_list_selector(
                        $field,
                        $value,
                        $selectorSQL,
                        false,
                        $width,
                        ($height ? $height : 110)
                    );
                    break;
                case "list (sequenced)":
                    $out = static::draw_list_selector(
                        $field,
                        $value,
                        $selectorSQL,
                        true,
                        $width,
                        ($height ? $height : 110)
                    );
                    break;
                case "listdata_value":
                    $path_unsafe = preg_replace('/[^0-9\_\-a-zA-Z]/', '', $value)!==$value;
                    $out =
                        "<span class=\"fl\">\n"
                        ."<input id=\"".$field."\" type=\"text\" name=\"".$field."\" value=\"".$value."\""
                        ." class='formField' style=\"width: ".((int)$width-100)."px;\" ".$jsCode."/>"
                        ."</span>"
                        ."<label class=\"fl\" style=\"margin-left:5px\">Path Safe"
                        ."<input id=\"".$field."_path_safe\" onchange=\"if(this.checked){"
                        ."geid_set('".$field."',geid_val('".$field."').replace(/[\(\)]/g,'').replace(/ {2}/g,' ')"
                        .".replace(/ - /g,'-').replace(/ /g,'-').replace(/\%/g,'pc')"
                        .".replace(/[^0-9\_\-a-zA-Z\(\)\%]/g,''));}\" type=\"checkbox\" value=\"1\""
                        .($path_unsafe ? "" : " checked=\"checked\"")
                        ."/>\n"
                        ."</label>"
                        .static::attach_behaviour($field, 'listdata_value');
                    break;
                case "media_file_upload":
                    $out =
                        "<input id=\"$field\" type=\"file\" name=\"$field\" value=\"\" class='formField'"
                        ." style=\"width: ".$width.";\" $jsCode/>";
                    break;
                case "media_information":
                    $field_type =         $field."_type";
                    $value_type =         (isset($row[$field_type]) ?  $row[$field_type] : "");
                    $field_secs =         $field."_secs";
                    $value_secs =         (isset($row[$field_secs]) ?  format_seconds($row[$field_secs]) : "");
                    $field_size =         $field."_size";
                    $value_size =         (isset($row[$field_size]) ?  format_bytes($row[$field_size]) : "");
                    return
                        "<div class='fl'>"
                        .draw_form_field(
                            $field_size,
                            $value_size,
                            "int",
                            100,
                            "",
                            0,
                            "",
                            1,
                            0,
                            "",
                            "",
                            0
                        )
                        ."</div>"
                        ."<div class='fl admin_formLabel' style='width:160px;text-align:right;padding:4px'>"
                        ."<label for=\"".$field_type."\">File Type\n"
                        ."<small><i>(uses <a title=\"Ecclesiact uses the GPL licensed getid3 module to read MP3 files\""
                        ." href=\"http://www.getid3.org\" rel=\"external\">getid3</a>)</i></small>\n"
                        ."</label></div>"
                        ."<div class='fl'>".draw_form_field(
                            $field_type,
                            $value_type,
                            "selector_listdata",
                            100,
                            "",
                            "",
                            0,
                            1,
                            0,
                            "",
                            "lst_media_type|0",
                            0
                        )
                        ."</div>"
                        ."<div class='fl admin_formLabel' style='width:120px;text-align:right;padding:4px'>"
                        ."<label for=\"".$field_secs."\">Time <small><i>(H:MM:SS)</i></small></label></div>"
                        ."<div class='fl'>"
                        .draw_form_field($field_secs, $value_secs, "int", 80, "", 0, "", 1, 0, "", "", 0)
                        ."</div>"
                        ."<div style='clear:both;overflow:hidden;height:0;width:0;'>&nbsp;</div>";
                    break;
                case "notes":
                    $height = (int)($height ? $height : 200);
                    $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
                    Output::push(
                        'javascript_onload',
                        "  \$J('#".$jq_field."')[0].value="
                        .json_encode(
                            str_replace(
                                array("<<br>>","</textarea>"),
                                array("\r\n","&lt;/textarea&gt;"),
                                $value
                            )
                        )
                        .";"
                    );
                    $out =
                        "<a class='fl'"
                        ." href=\"#\" onclick=\"add_note('".$field."','note');return false;\">"
                        .convert_icons("[ICON]33 35 8165 Add Stamped Note (with date and username)[/ICON]")
                        ."</a>"
                        .(System::has_feature('Allow-Unstamped-Notes') ?
                            "<div class='fl'> &nbsp; </div><a class='fl'"
                            ." href=\"#\" onclick=\"add_note_unstamped('".$field."','note');return false;\">"
                            .convert_icons("[ICON]33 35 917 Add Plain Note[/ICON]")
                            ."</a>"
                            :
                            ""
                        )
                        ." <textarea class='fl' name=\"_notes_".$field."\" rows=\"4\" cols=\"80\""
                        ." style=\"width:"
                        .((int)$width-(System::has_feature('Allow-Unstamped-Notes') ? 78 : 37))
                        ."px;height:".(int)($height/3)."px;\" ".$jsCode."></textarea>"
                        ."<br clear='both' />\n"
                        ."<textarea id=\"".$field."\" readonly=\"readonly\" name=\"".$field."\""
                        ." style=\"width: ".$width.";height:".(int)($height*(2/3))."px;"
                        ."background-color:#f0f0f0;\" rows=\"8\" cols=\"80\">"
                        ."</textarea>";
                    break;
                case "option_list":
                    $value = explode(OPTION_SEPARATOR, str_replace(OPTION_SEPARATOR." ", OPTION_SEPARATOR, $value));
                    asort($value);
                    $value = implode("\n", $value);
                    $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
                    Output::push(
                        'javascript_onload',
                        "  \$J('#".$jq_field."')[0].value=".json_encode($value).";"
                    );
                    $out =
                        "<textarea id=\"".$field."\" name=\"".$field."\" rows='4' cols='80'"
                        ." style=\"width: ".$width.";height: ".($height ? (int)$height : 240)."px;\" ".$jsCode.">"
                        ."</textarea>\n";
                    break;
                case "password":
                    $out =
                        "<input id=\"".$field."\" autocomplete=\"off\" type=\"password\" name=\"".$field."\""
                        ." value=\"".$value."\" style=\"width: ".$width.";\" class='formField' ".$jsCode."/>";
                    break;
                case "percent":
                    $out =
                        "<input id=\"$field\" type=\"text\" name=\"$field\" value=\"$value\""
                        ." style=\"width: ".$width.";\" class='formField txt_r' ".$jsCode."/>%"
                        .static::attach_behaviour($field, $type);
                    break;
                case "php":
                    $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
                    Output::push(
                        'javascript_onload',
                        "  \$J('#".$jq_field."')[0].value="
                        .json_encode(
                            str_replace(
                                array("<<br>>","</textarea>"),
                                array("\r\n","&lt;/textarea&gt;"),
                                $value
                            )
                        )
                        .";"
                    );
                    $out =
                        "<textarea id=\"".$field."\" name=\"".$field."\""
                        ." style=\"width: ".$width."\" rows='20' cols='80' ".$jsCode.">"
                        ."</textarea>\n";
                    break;
                case "posting_name":
                    $Obj_Report = new Report($reportID);
                    $Object_type = $Obj_Report->get_field('primaryObject');
                    if ($Object_type=='Event') {
                        $yyyymmdd = (isset($_REQUEST['effective_date_start']) ?
                            $_REQUEST['effective_date_start']
                            :
                            (isset($row['effective_date_start']) && $row['effective_date_start']!='0000-00-00' ?
                                substr($row['effective_date_start'], 0, 10)
                                :
                                get_timestamp()
                            )
                        );
                    } else {
                        $yyyymmdd =(isset($_REQUEST['date']) ?
                            $_REQUEST['date']
                            :
                            (isset($row['date']) && $row['date']!='0000-00-00' ?
                                substr($row['date'], 0, 10)
                                :
                                get_timestamp()
                            )
                        );
                    }
                    $yyyy = substr($yyyymmdd, 0, 4);
                    $mm = substr($yyyymmdd, 5, 2);
                    $dd = substr($yyyymmdd, 8, 2);
                    switch (POSTING_PREFIX) {
                        case "YYYY":
                            $prefix = $yyyy."/";
                            $width = ((int)$width-35);
                            break;
                        case "YYYY/MM":
                            $prefix = $yyyy."/".$mm."/";
                            $width = ((int)$width-56);
                            break;
                        case "YYYY/MM/DD":
                            $prefix = $yyyy."/".$mm."/".$dd."/";
                            $width = ((int)$width-77);
                            break;
                        default:
                            $prefix = "";
                            $width = (int)$width;
                            break;
                    }
                    $out =
                        "<div style='font-family:monospace;font-size:8pt;float:left;padding-top:5px;'>"
                        .$prefix."</div>"
                        ."<input id=\"$field\" type=\"text\" name=\"$field\" value=\"".$value."\" class='formField'"
                        ." style=\"width: ".$width."px;\" $jsCode/>"
                        .static::attach_behaviour($field, $type);
                    break;
                case "posting_name_unprefixed":
                    $out =
                        "<input id=\"".$field."\" type=\"text\" name=\"".$field."\" value=\"".$value."\""
                        ." class='formField' style=\"width: ".(int)$width."px;\" ".$jsCode."/>"
                        .static::attach_behaviour($field, 'posting_name');
                    break;
                case "push_products_assign":
                    $out = static::draw_selector_csv(
                        $field,
                        $value,
                        $selectorSQL,
                        $width,
                        ($height ? $height : 35),
                        ($formFieldSpecial=='1' ? '1' : 0)
                    );
                    break;
                case "qty":
                    $out =
                        "  <input id=\"".$field."\" name=\"".$field."\" type=\"text\""
                        ." value=\"".$value."\" class=\"formField fl\" size='2'"
                        ." style=\"width:".$width.";text-align:right;background-color:#E1EAFE;height:12px;\""
                        ." ".$jsCode." />\n"
                        ."  <div class='fl' style='width:11px;'>\n"
                        ."    <img id=\"".$field."_up\" alt='+'"
                        ." src=\"".BASE_PATH."img/spacer\" class='icons'"
                        ." style=\"height:8px;width:11px;background-position: -1423px 0px;\" />\n"
                        ."    <img id=\"".$field."_down\" alt='-'"
                        ." src=\"".BASE_PATH."img/spacer\" class='icons'"
                        ." style=\"height:8px;width:11px;background-position: -1423px 8px;\" />\n"
                        ."  </div>\n"
                        .static::attach_behaviour($field, $type);
                    break;
                case "radio_csvlist":
                    $out = static::draw_radio_selector($field, $value, $entries_arr, $width, $jsCode);
                    break;
                case "radio_listdata":
                    $out = static::draw_radio_selector_for_sql($field, $value, $selectorSQL, $width, $jsCode);
                    break;
                case "radio_selector":
                    $out = static::draw_radio_selector_for_sql($field, $value, $selectorSQL, $width, $jsCode);
                    break;
                case "read_only":
                    $out = $value;
                    break;
                case "read_only_person_info":
                    $Obj = new Person($value);
                    $out = $Obj->draw_person_info();
                    break;
                case "sample_buttonstyle":
                    $out =  static::drawNavStyleSample($field, $value, $row);
                    break;
                case "sample_navsuite":
                    $Obj =  new \Nav\Style($row['buttonStyleID']);
                    $row =  $Obj->get_record();
                    $out =  static::drawNavSuiteSample($field, $value, $row);
                    break;
                case "sample_fontface":
                    if (!isset($row['ID']) || $row['ID']=="") {
                        $out = "(Save Font Face first)";
                    } else {
                        $Obj = new Font_Face($row['ID']);
                        $out = $Obj->sample();
                    }
                    break;
                case "select_group_for_person":
                    global $selectID;
                    $Obj = new Group;
                    $selectorSQL = $Obj->get_selector_groups_SQL(false, $selectID);
                    $out = static::draw_selector($field, $value, $selectorSQL, $width, $jsCode);
                    break;
                case "categories_assign":
                case "selector_listdata_csv":
                    $isMASTERADMIN =    get_person_permission("MASTERADMIN");
                    $isSYSADMIN =       get_person_permission("SYSADMIN");
                    $isSYSAPPROVER =    get_person_permission("SYSAPPROVER");
                    $params_arr =       explode("|", $formFieldSpecial);
                    $lbl_entries =
                        ($type=="categories_assign" ? "Categories" : "List Entries")
                        ." for &quot;".$params_arr[0]."&quot;";
                    $can_add = (isset($params_arr[1]) ?
                        ($isMASTERADMIN || $isSYSADMIN || $isSYSAPPROVER) && $params_arr[1]
                        :
                        0
                    );
                    if ($can_add) {
                        $Obj = new ListType;
                        $ID =             $Obj->get_ID_by_name($params_arr[0]);
                        $_report_name =   "listtype";
                        $_popup_size =    get_popup_size($_report_name);
                        $out =
                            "<div class=\"fl\">"
                            .static::draw_selector_csv(
                                $field,
                                $value,
                                $selectorSQL,
                                ((int)$width-22)."px",
                                ($height ? $height : 35)
                            )
                            ."&nbsp; </div>"
                            .($ID!="" ?
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Edit ".$lbl_entries."';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details("
                                ."'".$_report_name."','".$ID."','".$_popup_size['h']."','".$_popup_size['w']."','',''"
                                .");return false;\">"
                                .convert_icons("[ICON]17 16 1390 Edit ".$lbl_entries."[/ICON]")."</a>"
                                :
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Add a new list type';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details("
                                ."'".$_report_name."','','".$_popup_size['h']."','".$_popup_size['w']."','','',false,"
                                ."'name=".$params_arr[0]."&systemID=".SYS_ID."');return false;\">"
                                .convert_icons("[ICON]14 14 715 Add new ListType[/ICON]")."</a>"
                            );
                    } else {
                        $out = static::draw_selector_csv(
                            $field,
                            $value,
                            $selectorSQL,
                            $width,
                            ($height ? $height : 35)
                        );
                    }
                    break;
                case "selector_csvlist":
                    $out =
                        "<select id=\"$field\" name=\"$field\" style=\"width: ".(((int)$width)+4)."px;\""
                        ." class=\"formField\"".($jsCode ? " ".$jsCode : "").">\n";
                    foreach ($entries_arr as $entry) {
                        if (isset($entry['color_text']) || isset($entry['color_background'])) {
                            $_t = (isset($entry['color_text']) ?         $entry['color_text'] : false);
                            $_b = (isset($entry['color_background']) ?   $entry['color_background'] : false);
                            $idx = Page::get_css_idx($_t, $_b);
                        }
                        $out.=
                            "  <option value=\"".$entry['value']."\""
                            .($entry['value']==$value ? " selected='selected'" : "")
                            .($idx ? " class=\"color_".$idx."\"" : "")
                            .">".$entry['text']."</option>\n";
                    }
                    $out.= "</select>\n";
                    break;
                case "selector":
                case "selector_billing_address":
                case "selector_listdata":
                    $isMASTERADMIN =    get_person_permission("MASTERADMIN");
                    $isSYSADMIN =        get_person_permission("SYSADMIN");
                    $params_arr =     explode("|", $formFieldSpecial);
                    $can_add = (isset($params_arr[1]) ? ($isMASTERADMIN || $isSYSADMIN) &&$params_arr[1] : 0);
                    if ($can_add) {
                        $Obj = new ListType;
                        $ID =             $Obj->get_ID_by_name($params_arr[0]);
                        $_report_name =   "listtype";
                        $_popup_size =    get_popup_size($_report_name);
                        $out =
                            "<span class=\"fl\">"
                            .static::draw_selector($field, $value, $selectorSQL, ((int)$width-22)."px", $jsCode)
                            ."&nbsp; </span>"
                            .($ID!="" ?
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Edit list entries';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details("
                                ."'".$_report_name."','".$ID."',"
                                ."'".$_popup_size['h']."','".$_popup_size['w']."','','');return false;\">"
                                .convert_icons("[ICON]17 17 1390 Edit List entries[/ICON]")."</a>"
                                :
                                "<a class='fl' "
                                ."onmouseover=\"window.status='Add a new list type';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"#\" onclick=\"details("
                                ."'".$_report_name."','','".$_popup_size['h']."','".$_popup_size['w']."'"
                                .",'','',false,'name=".$params_arr[0]."&systemID=".SYS_ID."');return false;\">"
                                .convert_icons("[ICON]14 14 715 Add new ListType[/ICON]")."</a>"
                            );
                    } else {
                        $out = static::draw_selector($field, $value, $selectorSQL, $width, $jsCode);
                    }
                    break;
                case "selector_contact":
                case "selector_gallery_album":
                case "selector_link":
                case "selector_podcast_album":
                    $isMASTERADMIN =    get_person_permission("MASTERADMIN");
                    $isSYSADMIN =       get_person_permission("SYSADMIN");
                    $can_add =          ($isMASTERADMIN || $isSYSADMIN);
                    if ($can_add) {
                        switch ($type) {
                            case "selector_contact":
                                $_report_name = "contact";
                                $_tooltip =     "Edit Contact";
                                $_icon =        "[ICON]18 18 7880 ".$_tooltip."[/ICON]";
                                $_h =           600;
                                $_w =           740;
                                break;
                            case "selector_gallery_album":
                                $_report_name = "gallery-albums";
                                $_tooltip =     "Edit Gallery Album";
                                $_icon =        "[ICON]19 19 4713 ".$_tooltip."[/ICON]";
                                $_h =           720;
                                $_w =           960;
                                break;
                            case "selector_link":
                                $_report_name = "links";
                                $_tooltip =     "Edit Contained Link";
                                $_icon =        "[ICON]19 19 4713 ".$_tooltip."[/ICON]";
                                $_h =           440;
                                $_w =           770;
                                break;
                            case "selector_podcast_album":
                                $_report_name = "podcast-albums";
                                $_tooltip =     "Edit Podcast Album";
                                $_icon =        "[ICON]19 19 4750 ".$_tooltip."[/ICON]";
                                $_h =           720;
                                $_w =           960;
                                break;
                        }
                        if (isset($row['ID'])) {
                            $out =
                                "<span class=\"fl\">"
                                .static::draw_selector($field, $value, $selectorSQL, ((int)$width-24)."px", $jsCode)
                                ."&nbsp; </span>"
                                ."<a class='fl' "
                                ."onmouseover=\"window.status='".$_tooltip."';return true;\" "
                                ."onmouseout=\"window.status='';return true;\" "
                                ."href=\"".BASE_PATH."details/".$_report_name."/".$value."\" rel=\"external\""
                                ." onclick=\"popWin(this.href,'".get_js_safe_ID($_report_name)."',"
                                ."'resizable=1,scrollbars=1',".$_w.",".$_h.",true);return false;\">"
                                .convert_icons($_icon)."</a>";
                        } else {
                            $out = static::draw_selector($field, $value, $selectorSQL, $width, $jsCode);
                        }
                    } else {
                        $out = static::draw_selector($field, $value, $selectorSQL, $width, $jsCode);
                    }
                    break;
                case "selector_timezone":
                    $out =
                        "<select id=\"$field\" name=\"$field\""
                        ." style=\"width: ".(((int)$width)+4)."px;\" class=\"formField\""
                        .($jsCode ? " ".$jsCode : "")
                        .">\n";
                    $options = timezone_identifiers_list(DateTimeZone::ALL_WITH_BC);
                    $first_word = false;
                    $bgcolor = array("e0ffe0","c0ffc0");
                    $bgcolor_idx = 0;
                    foreach ($options as $option) {
                        $words = explode('/', $option);
                        if ($first_word!=$words[0]) {
                            $bgcolor_idx = ($bgcolor_idx==1 ? 0 : 1);
                            $first_word=$words[0];
                        }
                        $idx = Page::get_css_idx(false, $bgcolor[$bgcolor_idx]);
                        $out.=
                            "  <option value=\"".$option."\""
                            .($option==$value ? " selected=\"selected\"" : "")
                            .($idx ? " class=\"color_".$idx."\"" : "")
                            .">"
                            .str_replace('/', ' / ', $option)
                            ."</option>\r\n";
                    }
                    $out.= "</select>";

                    break;
                case "selector_url":
                    if ($field=='systemID' &&
                        $value=='' &&
                        ($report_name!='report' && $report_name!='report_columns')
                    ) {
                        $value=SYS_ID;  // Prevents MASTERADMIN from accidentally selecting 'All' by mistake
                    }
                    $out = static::draw_selector($field, $value, $selectorSQL, $width, $jsCode);
                    break;
                case "seq":
                    $out =
                        "<div class='nobr'>\n"
                        ."  <input id=\"".$field."\" name=\"".$field."\" type=\"text\""
                        ." value=\"".($value==='' ? 1 : $value)."\" class=\"formField fl txt_r\" size='4'"
                        ." style=\"width:35px;\" ".$jsCode." />\n"
                        ."  <div class='fl' style='width:11px;'>"
                        ."    <img id=\"".$field."_up\" alt='+'"
                        ." src=\"".BASE_PATH."img/spacer\" class='icons'"
                        ." style=\"margin-top:2px;height:8px;width:11px;background-position: -1445px 0px;\" />"
                        ."    <img id=\"".$field."_down\" alt='-'"
                        ." src=\"".BASE_PATH."img/spacer\" class='icons'"
                        ." style=\"height:8px;width:11px;background-position: -1445px 8px;\" />"
                        ."  </div>"
                        ."</div>"
                        .static::attach_behaviour($field, $type);
                    break;
                case "server_file":
                    $out =
                        "<input type=\"text\" id=\"".$field."\" name=\"".$field."\" value=\"".$value."\""
                        ." style=\"width:".((int)$width - 115)."px;\" class=\"formField\" $jsCode />\n"
                        ."<input type='button' value='Browse Server...' class='formButton' style='width: 110px;'"
                        ." onclick=\"CKFinder.Popup('"
                        .BASE_PATH."js/ckfinder/"
                        ."',null,null,set_serverfile_".get_js_safe_ID($field).")\" />";
                    Output::push(
                        'javascript',
                        "function set_serverfile_".get_js_safe_ID($field)."(fileUrl){"
                        ." geid('".$field."').value = fileUrl; }"
                    );
                    FCK::attach_ckfinder();
                    break;
                case "server_file_folder":
                    $out =
                        "<input type=\"text\" id=\"".$field."\" name=\"".$field."\" value=\"".$value."\""
                        ." style=\"width:".((int)$width - 115)."px;\" class=\"formField\" $jsCode />\n"
                        ."<input type='button' value='Browse Server...' class='formButton' style='width: 110px;'"
                        ." onclick=\"CKFinder.Popup('"
                        .BASE_PATH."js/ckfinder/"
                        ."',null,null,set_serverfilefolder_".get_js_safe_ID($field).")\" />";
                    Output::push(
                        'javascript',
                        "function set_serverfilefolder_".get_js_safe_ID($field)."(fileUrl){"
                        ." var fileUrl_arr = fileUrl.split('/');fileUrl_arr.pop();"
                        ."geid_set('".$field."',fileUrl_arr.join('/')+'/'); }"
                    );
                    FCK::attach_ckfinder();
                    break;
                case "server_file_image":
                    $_image =     "";
                    $_exists =    false;
                    if ($height>20) {
                        $_height = $height-20;
                        if ($value && file_exists('.'.trim('.', $value))) {
                            $_image =   BASE_PATH.'img/resize/?height='.$_height.'&width='.((int)$width).'&img='.$value;
                            $_exists =  true;
                        } else {
                            $_image =   BASE_PATH.'img/color/ffffff';
                            $_exists =  false;
                        }
                    }
                    $out =
                        "<input type=\"text\" id=\"".$field."\" name=\"".$field."\" value=\"".$value."\""
                        ." style=\"width:".((int)$width - 115)."px;\" class=\"formField\" $jsCode />\n"
                        ."<input type='button' value='Browse Server...' class='formButton' style='width: 110px;'"
                        ." onclick=\"CKFinder.Popup('"
                        .BASE_PATH."js/ckfinder/"
                        ."',null,null,set_serverfile_".$field.")\" />"
                        .($_image ?
                            "<div style=\"margin:5px 0 0 0\">"
                            .($_exists ?
                                "<a href=\"".$value."\" rel=\"external\">"
                                :
                                ""
                            )
                            .($value ?
                                "<img src=\"".$_image."\" style='border:1px solid #888'"
                                .($_exists ?
                                    " alt=\"Image Preview\" title=\"Image Preview - click for full-size\""
                                    :
                                    " alt=\"Image not yet saved\""
                                )
                                ." />"
                                :
                                ""
                            )
                            .($_exists ?
                                "</a>"
                                :
                                ""
                            )
                            ."</div>"
                            :
                            ""
                        );
                    Output::push(
                        'javascript',
                        "function set_serverfile_".$field."(fileUrl){ geid('".$field."').value = fileUrl; }"
                    );
                    FCK::attach_ckfinder();
                    break;
                case "swatch":
                    static $_swatch_code_seen=false;
                    if (!$_swatch_code_seen) {
                        $_swatch_code_seen = true;
                        Output::push(
                            'javascript_onload',
                            'setupSwatches();'
                        );
                        Output::push(
                            'javascript_top',
                            '<script type="text/javascript" src="/sysjs/spectrum"></script>'
                        );
                        Output::push(
                            'style_include',
                            '<link rel="stylesheet" href="/css/spectrum" />'
                        );
                    }
                    $out =
                        "<div class='fl' style='font-size:80%;padding-right:5px;'>#</div>\n"
                        ."<input type='text' class='swatch' id='".$field."' name='".$field."'"
                        ." spellcheck='false' value='".$value."'"
                        ." onchange=\"\$('#'+this.id+'_s').spectrum('set',this.value)\"/>"
                        ."<input type='text' class='spectrum' id='".$field."_s' value='".$value."' />"
                        ."<br class='clear' />"
                        .static::attach_behaviour($field, $type);
                    break;
                case "tax_name_and_rate":
                    static $tax_regime_record = false;
                    if (!$tax_regime_record) {
                        global $system_vars;
                        $tax_zoneID =   (isset($row['tax_zoneID']) ?
                            $row['tax_zoneID']
                            :
                            $system_vars['defaultTaxZoneID']
                        );
                        $Obj_Tax_Zone = new Tax_Zone($tax_zoneID);
                        $tax_regime_record = $Obj_Tax_Zone->get_record();
                    }
                    $_tax_rate_field = $field."_rate";
                    $_tax_name_value = $tax_regime_record[$field."_name"];
                    $_tax_rate_value = (isset($row[$_tax_rate_field]) ? $row[$_tax_rate_field] : "0.00");
                    $out =
                        "<div class='formField txt_r fl'"
                        ." style='width:60px;background-color:#e8e8e8;border-color:#c0c0c0;margin-right:5px;'>"
                        .($_tax_name_value ? $_tax_name_value : "&nbsp;")
                        ."</div>"
                        ."<input id=\"".$_tax_rate_field."\" type=\"text\" name=\"".$_tax_rate_field."\""
                        ." value=\"".$_tax_rate_value."\" style=\"width:40px;\" class='formField txt_r'/>%"
                        .static::attach_behaviour($_tax_rate_field, 'percent');
                    break;
                case "text_fixed":
                    $out =
                        "<input id=\"$field\" type=\"text\" name=\"$field\" value=\"$value\" class='admin_formFixed'"
                        ." style=\"width: ".$width.";\" $jsCode/>";
                    break;
                case "textarea":
                    $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
                    Output::push(
                        'javascript_onload',
                        "  \$J('#".$jq_field."')[0].value="
                        .json_encode(
                            str_replace(
                                array("<<br>>","</textarea>"),
                                array("\r\n","&lt;/textarea&gt;"),
                                $value
                            )
                        )
                        .";"
                    );
                    $out =
                        "<textarea id=\"$field\" name=\"$field\" rows='4' cols='80'"
                        ." style=\"width: ".$width.";height:".($height ? (int)$height : 70)."px;\" $jsCode>"
                        ."</textarea>\n";
                    break;
                case "textarea_big":
                    $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
                    Output::push(
                        'javascript_onload',
                        "  \$J('#".$jq_field."')[0].value="
                        .json_encode(
                            str_replace(
                                array("<<br>>","</textarea>"),
                                array("\r\n","&lt;/textarea&gt;"),
                                $value
                            )
                        )
                        .";"
                    );
                    $out =
                        "<textarea id=\"$field\" name=\"$field\" rows='16' cols='80'"
                        ." style=\"width: ".$width.";height:".($height ? (int)$height : 140)."px\" $jsCode>"
                        ."</textarea>\n";
                    break;
                case "textarea_readonly":
                    $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
                    Output::push(
                        'javascript_onload',
                        "  \$J('#".$jq_field."')[0].value="
                        .json_encode(
                            str_replace(
                                array("<<br>>","</textarea>"),
                                array("\r\n","&lt;/textarea&gt;"),
                                $value
                            )
                        )
                        .";"
                    );
                    $out =
                        "<textarea id=\"$field\""
                        ."  style=\"width:".$width.";height:".($height ? (int)$height : 140)."\" rows='16' cols='80'>"
                        ."</textarea>\n";
                    break;
                case "text_alignment_and_offsets":
                    $field_h_align =      $field."_h_align";
                    $value_h_align =      (isset($row[$field_h_align]) ?  $row[$field_h_align] : "");
                    $field_h_offset =     $field."_h_offset";
                    $value_h_offset =     (isset($row[$field_h_offset]) ? $row[$field_h_offset] : "");
                    $field_v_offset =     $field."_v_offset";
                    $value_v_offset =     (isset($row[$field_v_offset]) ? $row[$field_v_offset] : "");
                    return
                        "<div class='fl'>"
                        .draw_form_field(
                            $field_h_align,
                            $value_h_align,
                            "selector_listdata",
                            (int)$width-244,
                            "",
                            "",
                            0,
                            0,
                            $bulk_update,
                            "",
                            "lst_h_align|0",
                            0
                        )
                        ."</div>"
                        ."<div class='fl admin_formLabel' style='width:55px;text-align:right;padding:4px'>"
                        ."<label for=\"".$field_h_offset."\">x Offset</label></div>"
                        ."<div class='fl'>"
                        .draw_form_field(
                            $field_h_offset,
                            $value_h_offset,
                            "int",
                            55,
                            "",
                            0,
                            "",
                            0,
                            $bulk_update,
                            "",
                            "",
                            0
                        )
                        ."</div>"
                        ."<div class='fl admin_formLabel' style='width:55px;text-align:right;padding:4px'>"
                        ."<label for=\"".$field_v_offset."\">y Offset</label></div>"
                        ."<div class='fl'>"
                        .draw_form_field(
                            $field_v_offset,
                            $value_v_offset,
                            "int",
                            55,
                            "",
                            0,
                            "",
                            0,
                            $bulk_update,
                            "",
                            "",
                            0
                        )."</div>"
                        ."<div style='clear:both;overflow:hidden;height:0;width:0;'>&nbsp;</div>";
                    break;
                case "text_font_and_size":
                    $field_font_face =    $field."_font_face";
                    $value_font_face =    (isset($row[$field_font_face]) ? $row[$field_font_face] : "");
                    $field_font_size =    $field."_font_size";
                    $value_font_size =    (isset($row[$field_font_size]) ? $row[$field_font_size] : "");
                    $selectorSQL =
                        "SELECT\n"
                        ."  '1' AS `seq`,\n"
                        ."  '(None)' AS `text`,\n"
                        ."  '' AS `value`,\n"
                        ."  'd0d0d0' AS `color_background`\n"
                        ."UNION SELECT\n"
                        ."  '2',\n"
                        ."  `textEnglish`,\n"
                        ."  `value`,\n"
                        ."  'ffffff'\n"
                        ."FROM\n"
                        ."  `listdata`\n"
                        ."INNER JOIN `listtype` ON\n"
                        ."  `listdata`.`listTypeID` = `listtype`.`ID`\n"
                        ."WHERE\n"
                        ."  `listtype`.`name` = 'lst_font_face' AND\n"
                        ."  `listdata`.`systemID` IN(1,".SYS_ID.")\n"
                        ."ORDER BY\n"
                        ."  `seq`,`text`";
                    return
                        "<div class='fl'>"
                        .draw_form_field(
                            $field_font_face,
                            $value_font_face,
                            'selector',
                            (int)$width-122,
                            $selectorSQL,
                            "",
                            0,
                            0,
                            $bulk_update
                        )
                        ."</div>"
                        ."<div class='fl admin_formLabel' style='width:55px;text-align:right;padding:4px'>"
                        ."<label for=\"".$field_font_size."\">Size</label></div>"
                        ."<div class='fl'>"
                        .draw_form_field(
                            $field_font_size,
                            $value_font_size,
                            "selector_listdata",
                            55,
                            "",
                            "",
                            0,
                            0,
                            $bulk_update,
                            "",
                            'lst_navsuite_fontsize|0',
                            0
                        )
                        ."</div>"
                        ."<div style='clear:both;overflow:hidden;height:0;width:0;'>&nbsp;</div>";
                    break;
                case "toggle_shared":
                    $out =
                        "<input id=\"$field\" name=\"$field\" type=\"checkbox\" value=\"1\" class='formField'"
                        ." style=\"border: 1px solid transparent;background-color: transparent;\""
                        .($value=="1" ? " checked='checked'" : "")
                        ." $jsCode/>";
                    break;
                case "tristate":
                    $out =
                        "<input type=\"radio\" name=\"$field\" value=\"0\" id=\"".$field."_0\""
                        .($value==0 ? " checked" : "")
                        ."/>"
                        ."<label for=\"".$field."_0\">None</label> &nbsp;\n"
                        ."<input type=\"radio\" name=\"$field\" value=\"1\" id=\"".$field."_1\""
                        .($value==1 ? " checked" : "")
                        ."/>"
                        ."<label for=\"".$field."_1\">Read Only</label> &nbsp;\n"
                        ."<input type=\"radio\" name=\"$field\" value=\"2\" id=\"".$field."_2\""
                        .($value==2 ? " checked" : "")
                        ."/>"
                        ."<label for=\"".$field."_2\">Complete</label>\n";
                    break;
                case "url":
                case "url_short":
                    switch ($value) {
                        case "":
                        case "./":
                        case "./?page=forgotten_password":
                        case "./?page=manage_profile":
                        case "./?page=password":
                        case "./?page=signin":
                        case "./?page=sitemap":
                        case "./?page=your_registered_events":
                        case "./?command=signout":
                        case "javascript:popup_help()":
                            $vp = $value;
                            $vu = "";
                            $vp_width = ((int)$width+4).'px';
                            $vu_visible = false;
                            break;
                        default:
                            $regexp =
                                "/^("
                                ."\.\/\?page="
                                ."|http:\/\/"
                                ."|https:\/\/"
                                ."|ftp:\/\/"
                                ."|mailto:"
                                ."|javascript:popup_help\(\)"
                                ."|javascript:"
                                ."|\.\/)?([^<]*)/i";
                            $value_split = preg_match($regexp, $value, $value_bits);
                            $vp = ($value_split ? $value_bits[1] : "");
                            $vu = $value_bits[2];
                            $vp_width = '180px';
                            $vu_visible = true;
                            break;
                    }
                    $vu_width = ((int)$width-185).'px';
                    $opt_none =   " style='background-color: #d8d8d8;'";
                    $opt_std =    " style='background-color: #d0ffd0;'";
                    $p =          "?page=";
                    $out =
                        "<table class='minimal'>\n"
                        ."  <tr>\n"
                        ."    <td><input type=\"hidden\" id=\"$field\" name=\"$field\" value=\"$value\" />"
                        ."<select id=\"".$field."_protocol\" name=\"".$field."_protocol\" class='formField' "
                        ."style=\"width:".$vp_width."; font-family:courier new, courier, monospace; font-size:8pt;\" "
                        ."onchange=\""
                        ."var opt = "
                        ."geid('".$field."_protocol').options[geid('".$field."_protocol').selectedIndex].text;"
                        ."switch(opt) {"
                        ."  case '(None)':case 'Home Page':case 'Site Map':case 'Sign In':case 'Sign Out':"
                        ."  case 'Change Password': case 'Forgot Password': case 'Your Profile':"
                        ."  case 'Registered Events': case 'Help':"
                        ."    geid('".$field."_protocol').style.width='".((int)$width+4)."';"
                        ."    geid('".$field."_url').value='';"
                        ."    geid('".$field."_url').style.display='none';"
                        ."  break;"
                        ."  default:"
                        ."    geid('".$field."_protocol').style.width='180px';"
                        ."    geid('".$field."').value = geid_val('".$field."_protocol') + geid_val('".$field."_url');"
                        ."    geid('".$field."_url').style.display='inline';"
                        ."  break;"
                        ."};"
                        ."geid('".$field."').value=geid_val('".$field."_protocol')+geid_val('".$field."_url');"
                        ."\">\n"
                        ."  <option value=''"
                        .($vp=="" && $vu==""                    ? " selected='selected'" : "")
                        .$opt_none
                        .">(None)</option>\n"
                        ."  <option value='./'"
                        .($vp=="./" && $vu==""                  ? " selected='selected'" : "")
                        .$opt_std
                        .">Home Page</option>\n"
                        ."  <option value='./".$p."sitemap'"
                        .($vp=="./?page=sitemap"                ? " selected='selected'" : "")
                        .$opt_std
                        .">Site Map</option>\n"
                        ."  <option value='./".$p."signin'"
                        .($vp=="./?page=signin"                 ? " selected='selected'" : "")
                        .$opt_std.">Sign In</option>\n"
                        ."  <option value='./?command=signout'"
                        .($vp=="./?command=signout"             ? " selected='selected'" : "")
                        .$opt_std
                        .">Sign Out</option>\n"
                        ."  <option value='./".$p."password'"
                        .($vp=="./?page=password"               ? " selected='selected'" : "")
                        .$opt_std
                        .">Change Password</option>\n"
                        ."  <option value='./".$p."forgotten_password'"
                        .($vp=="./?page=forgotten_password"     ? " selected='selected'" : "")
                        .$opt_std
                        .">Forgot Password</option>\n"
                        ."  <option value='./".$p."manage_profile'"
                        .($vp=="./?page=manage_profile"         ? " selected='selected'" : "")
                        .$opt_std
                        .">Your Profile</option>\n"
                        ."  <option value='./".$p."your_registered_events'"
                        .($vp=="./?page=your_registered_events" ? " selected='selected'" : "")
                        .$opt_std
                        .">Registered Events</option>\n"
                        ."  <option value='javascript:popup_help()'"
                        .($vp=="javascript:popup_help()"        ? " selected='selected'" : "")
                        .$opt_std
                        .">Help</option>\n"
                        ."  <option value='./".$p."'"
                        .($vp=="./?page="                       ? " selected='selected'" : "")
                        ." style='background-color: #ffd0d0;'"
                        .">Page on this site:</option>\n"
                        ."  <option value='./'"
                        .($vp=="./" && $vu!=""                  ? " selected='selected'" : "")
                        ." style='background-color: #ffd0d0;'>Resource on this site:</option>\n"
                        ."  <option value='ftp://'"
                        .($vp=="ftp://"                         ? " selected='selected'" : "")
                        ." style='background-color: #ffd0d0;'>ftp://</option>\n"
                        ."  <option value='http://'"
                        .($vp=="http://"                        ? " selected='selected'" : "")
                        ." style='background-color: #ffd0d0;'>http://</option>\n"
                        ."  <option value='https://'"
                        .($vp=="https://"                       ? " selected='selected'" : "")
                        ." style='background-color: #ffd0d0;'>https://</option>\n"
                        ."  <option value='mailto:'"
                        .($vp=="mailto:"                        ? " selected='selected'" : "")
                        ." style='background-color: #ffffd0;'>mailto:</option>\n"
                        ."  <option value='javascript:'"
                        .($vp=="javascript:"                    ? " selected='selected'" : "")
                        ." style='background-color: #c0c0ff;'>javascript:</option>\n"
                        ."  <option value=''"
                        .($vp=="" && $vu!=""                    ? " selected='selected'" : "")
                        ." style='background-color: #ffff80;'>Other Protocol:</option>\n"
                        ."</select></td>"
                        ."    <td><img src='".BASE_PATH."img/spacer' width='5' height='1' class='b' alt=''/></td>"
                        ."    <td>"
                        ."<input type=\"text\" id=\"".$field."_url\" name=\"".$field."_url\" value=\"$vu\""
                        ." class='formField'"
                        ." style=\"width: ".$vu_width.";".($vu_visible ? "" : "display:none;")."\" "
                        ." onchange=\"geid('".$field."').value="
                        ."geid_val('".$field."_protocol')+geid_val('".$field."_url')\" /></td>"
                        ."  </tr>\n"
                        ."</table>";
                    break;
                case "url_path":
                    $out =
                        "<input id=\"$field\" type=\"text\" name=\"$field\" value=\"$value\" class='formField'"
                        ." style=\"width: ".$width.";\" $jsCode/>";
                    break;
                case "view_credit_memo":
                    $out =
                        "<input type=\"hidden\" id=\"".$field."\" name=\"".$field."\" value=\"".$value."\"/>"
                        .($value ?
                            "<a style='font-size: small; font-weight:bold;' href='#'"
                            ." onclick='view_credit_memo(\"".$value."\",800,550);return false;'"
                            ." title=\"View details for this credit memo\">".$value."</a>"
                            :
                            "&nbsp;"
                        );
                    break;
                case "view_order_details":
                    $out =
                        "<input type=\"hidden\" id=\"".$field."\" name=\"".$field."\" value=\"".$value."\"/>"
                        .($value ?
                            "<a style='font-size: small; font-weight:bold;' href='#'"
                            ." onclick='view_order_details(\"".$value."\",800,550);return false;'"
                            ." title=\"View details for this order\">".$value."</a>"
                            :
                            "&nbsp;"
                        );
                    break;
                default:
                    $out =
                        "<input id=\"$field\" type=\"text\" name=\"$field\" value=\"$value\" class='formField'"
                        ." style=\"width: ".$width.";\" $jsCode/>";
                    break;
            }
        }
        // Provide popup link for these field types:
        switch ($type) {
            case "iframe":
                $url = $formFieldSpecial;
                break;
        }
        switch ($type) {
            case "iframe":
                $xhtml_safe =
                    str_replace(
                        array(
                            '&ID=',
                            '&eventID=',
                            '&print=',
                            '&selectID=',
                        ),
                        array(
                            '&amp;ID=',
                            '&amp;eventID=',
                            '&amp;selectID=',
                            '&amp;selectID=',
                        ),
                        $formFieldSpecial
                    );
                $label.=
                    "<br />&nbsp; &nbsp; <span style='font-size:80%;'>"
                    ."If no content is shown in the embedded frame below, "
                    ."<a href=\"".$xhtml_safe."\" rel='external'"
                    ." title=\"Open embedded content in a popup window\"><b>click here</b></a>"
                    ." to view embedded content.</span>";
                break;
        }
        // Place wrapper and label if label not shown inline:
        switch ($type) {
            case "groups_assign":
            case "groups_assign_person":
            case "html":
            case "html_multi_block":
            case "html_multi_language":
            case "html_with_text":
            case "notes":
            case "option_list":
            case "iframe":
            case "list (sequenced)":
            case "textarea_big":
            case "textarea_readonly":
                $out = ($label!='' ?
                    $label."<br class='clr_b'/><div style='padding-left:10px'>".$out."</div>"
                    :
                    $out
                );
                break;
        }
        // Place wrapper if used in bulk update mode:
        if ($bulk_update && !$readOnly) {
            $out =
                "<input id=\"".$field."_apply\" name=\"".$field."_apply\" title=\"Apply changes to this field\""
                ." type='checkbox' value=\"1\" class=\"fl formField\""
                ." style=\"background-color: #60a060; margin-right: 2px;\">"
                .$out;
        }
        return $out;
    }

}
