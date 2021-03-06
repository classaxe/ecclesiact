<?php
/*
Version History:
  1.0.25 (2016-01-16)
    1) FCK::draw_editor() now uses the VERSION constant internally
*/


class FCK extends Record
{
    const VERSION = '1.0.25';

    public static function attach_ckfinder()
    {
        static $ckfinder_js_included = false;
        if (!$ckfinder_js_included) {
            Output::push(
                'javascript_top',
                "<script type='text/javascript' src='".BASE_PATH."js/ckfinder/ckfinder.js'></script>\n"
            );
            $ckfinder_js_included = true;
        }
    }

    public static function do_fck()
    {
        switch (get_var('submode')){
            case "ecl":
                header("Content-type: application/json");
                print FCK::draw_plugin_ecl();
                die;
            break;
            case "transformer":
                Transformer::admin();
                die();
            break;
        }
    }

    public static function draw_editor($field, $value, $width, $height, $toolbar = "Page")
    {
        static $js_included = false;
        if (!$js_included) {
            $js_included=true;
            Output::push(
                'javascript_top',
                "<script type=\"text/javascript\""
                ." src=\"".BASE_PATH."sysjs/ckeditor/ckeditor.js\">"
                ."</script>\n"
            );
        }
        $sanitized = str_replace('[', '{{[}}', str_replace('textarea', 'sanitizedtextarea', $value));
        $jq_field =   str_replace(array('.',':'), array('\\\\.','\\\\:'), $field);
        Output::push(
            'javascript_onload',
            "  \$J('#".$jq_field."')[0].value=".json_encode($sanitized).";\n"
            ."  CKEDITOR.timestamp = '".static::VERSION."';\n"
            ."  ckeditor_".$field." = CKEDITOR.replace( \"".$field."\", { toolbar: '".$toolbar."',height: 0});\n"
            ."  ckeditor_".$field.".on('instanceReady',\n"
            ."    function(e) {\n"
            ."      var instance = e.editor;\n"
            ."      instance.setData(instance.getData()"
            .".replace(/\{{\[}}/g,'[').replace(/sanitizedtextarea/g,'textarea'));\n"
            ."      instance.resize("
            .(preg_match('/%/', $width) ? "'".$width."'" : (int)$width)
            .","
            .(preg_match('/%/', $height) ? "'".$height."'" : (int)$height)
            .");\n"
            ."      var rules = {\n"
            ."        indent: true,\n"
            ."        breakBeforeOpen: true,\n"
            ."        breakAfterOpen: true,\n"
            ."        breakBeforeClose: true,\n"
            ."        breakAfterClose: false\n"
            ."      }\n"
            ."      instance.dataProcessor.writer.indentationChars = '  ';\n"
            ."      instance.dataProcessor.writer.setRules( 'p',rules);\n"
            ."      instance.dataProcessor.writer.setRules( 'div',rules);\n"
            ."    }\n"
            ."  );\n"
        );
        return
            "<textarea id=\"".$field."\" name=\"".$field."\" style='display:none;' rows=\"4\" cols=\"80\">"
      //       .$sanitized
            ."</textarea>\n";
    }

    public static function draw_plugin_ecl()
    {
        $Obj =      new ECL_Tag;
        $out =      array();
        $tags =     $Obj->get_all();
        for ($i=0; $i<count($tags['nameable']); $i++) {
            $out[] = array($tags['tag'][$i], $tags['text'][$i], $tags['nameable'][$i]);
        }
        return json_encode($out);
    }
}
