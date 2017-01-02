<?php
define("FUNCTIONS_VERSION", "1.0.21");
/*
Version History:
  1.0.21 (2017-01-02)
    1) Multi-clause 'if' statements no longer enclosed using double brackets trick
*/
function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    if ($errno === E_STRICT && strpos($errstr, 'Declaration of')!==false) {
        /* Don't execute PHP internal error handler for this error */
        return true;
    }
    return false;
}

if (isset($_SERVER["REMOTE_ADDR"]) && (
        $_SERVER["REMOTE_ADDR"]==='192.168.0.1' ||
        $_SERVER["REMOTE_ADDR"]==='127.0.0.1'
    )
) {
    $old_error_handler = set_error_handler("errorHandler");
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

function includes_monitor($className = '', $filePath = '')
{
    static $includes = array();
    if ($className=='') {
        return $includes;
    }
    $includes[$className] = $filePath;
}

function mem($label = '')
{
    static $mem_usage = array();
    if ($label!='') {
        $mem_usage[] =
        array(
        'lbl' =>            $label,
        'mem' =>            number_format(memory_get_usage()),
        'class_files' =>    includes_monitor(),
        'html_classes'=>    array()
        );
        return;
    }
    $out =
         "<div id='memory_monitor'>\n"
        ."<h1 id='memory_monitor_handle'>Memory Monitor</h1>\n"
        ."<h2 class='fl'>".SYSTEM_FAMILY."</h2>\n"
        ."<h2 class='fr'>".System::get_item_version('build')."</h2>\n"
        ."<br class='clear' />\n"
        ."<h2 class='fl'>PHP</h2>\n"
        ."<h2 class='fr'>".System::get_item_version('php')."</h2>\n"
        ."<br class='clear' />\n"
        ."<h2 class='fl'>MySQL</h2>\n"
        ."<h2 class='fr'>".System::get_item_version('mysql')."</h2>\n"
        ."<br class='clear' />\n"
        ."<h2 class='fl'>HTTP</h2>\n"
        ."<h2 class='fr'>".System::get_item_version('http_software')."</h2>\n"
        ."<br class='clear' />\n"
        ."<table>"
        ."  <tr>\n"
        ."    <th class='txt_l'>Marker</th>\n"
        ."    <th class='txt_r'>Memory</th>\n"
        ."  </tr>\n";
    foreach ($mem_usage as $mu) {
        $id = str_replace(array(' ','-',',','.','(',')','{','}','[',']'), '_', $mu['lbl']);
        ksort($mu['class_files']);
        foreach ($mu['class_files'] as $className => $filePath) {
            $Obj =            new $className;
            $version =        (method_exists($Obj, 'get_version') ? $Obj->get_version() : $Obj->getVersion());
            $size =           number_format(filesize($filePath));
            $file =           file_get_contents($filePath);
            $crc32 =          dechex(crc32($file));
            $mu['html_classes'][] =
                 "  <tr>\n"
                ."    <td>".$className."</td>\n"
                ."    <td class='num'>".$version."</td>\n"
                ."    <td class='cs'>".$crc32."</td>\n"
                ."    <td class='num'>".$size."</td>\n"
                ."  </tr>";
        }
        $out.=
             "<tr>\n"
            ."  <td>"
            .(count($mu['class_files']) ?
                 "<a href=\"#\" onclick=\"div_toggle(geid('classes_".$id."'));this.blur();return false;\">"
                .$mu['lbl']
                ."</a>"
             :
                $mu['lbl']
            )
            ."</td>\n"
            ."  <td class='txt_r'>".$mu['mem']."</td>\n"
            ."</tr>"
            ."<tr id='classes_".$id."' style='display:none'>\n"
            ."  <td colspan='2'>"
            .(count($mu['html_classes']) ?
                 "<table border='0' cellpadding='0' cellspacing='0'  class='report'>\n"
                ."  <tr class='head'>\n"
                ."    <th>Class</th>\n"
                ."    <th>Version</th>\n"
                ."    <th>Checksum</th>\n"
                ."    <th>Size</th>\n"
                ."  </tr>\n"
                .implode('', $mu['html_classes'])
                ."</table>"
             :
                ""
            )
            ."</td>\n"
            ."</tr>\n"
        ;
    }
    $memory_monitor_clipboard = "\t".System::get_item_version('build');
    foreach ($mem_usage as $mu) {
        $memory_monitor_clipboard.= "\n".$mu['lbl']."\t".$mu['mem'];
    }
    if (function_exists('memory_get_peak_usage')) {
        $memory_monitor_clipboard.= "\nPeak Usage\t".number_format(memory_get_peak_usage());
        $out.=
             "  <tr>\n"
            ."    <th style='text-align:left;'>Peak Usage</th>\n"
            ."    <th style='text-align:right;'>".number_format(memory_get_peak_usage())."</th>\n"
            ."  </tr>\n";
    }
    $out.=
         "  <tr>\n"
        ."    <th colspan='2'>\n"
        ."      <input type=\"button\""
        ." onclick=\"copy_clip(geid_val('memory_monitor_data'))\" value='Copy' />\n"
        ."      <input type=\"button\""
        ." onclick=\"window.focus();geid('memory_monitor').style.display='none';\" value='Close' />\n"
        ."    </th>\n"
        ."  </tr>\n"
        ."</table>"
        .draw_form_field('memory_monitor_data', $memory_monitor_clipboard, 'hidden')
        ."</div>";
    return $out;
}

mem("functions.php start");

ini_set('display-errors', 1);
include_once(SYS_SHARED."db_connect.php");
include_once(SYS_SHARED.'codebase.php');
mem("codebase.php post-include");
if (isset($_SESSION['person'])) {
    // Refresh permissions:
    get_person_to_session(
        $_SESSION['person']['PUsername'],
        $_SESSION['person']['PPassword']
    );  // Updates permissions each page view
}
mem("After get_person_to_session()");
//$system_vars =  get_system_vars();
//mem("After get_system_vars()");
$page_vars =    get_page_vars();
mem("After get_page_vars");
$Obj =          new System(SYS_ID);
mem("After new System()");
$Obj->do_commands();    // execute commands (if any)
mem("After System->do_commands()");

main($mode);            // This function is in system.php
