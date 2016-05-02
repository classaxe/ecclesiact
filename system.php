<?php
// Ecclesiact Version
define("SYSTEM_FAMILY", "Ecclesiact");
define("SYSTEM_FAMILY_URL", "http://www.ecclesiact.com");
define("SYSTEM_VERSION", "1.0.37 (ECC)");

/*
Version History:
  1.0.37 (2016-05-02)
    1) Bug fix for protocol path switching -
       http://www.prayforthem.ca/aurora/contact now goes correctly to https://prayforthem.ca/aurora/contact 
*/

if (get_magic_quotes_gpc()) {
    hvFF9mrhFbntrDgfGb9wc1gf("magic_quotes");
}
if (!(
    extension_loaded('curl') &&
    extension_loaded('gd') &&
    extension_loaded('mysqli')
)) {
    hvFF9mrhFbntrDgfGb9wc1gf("extensions");
}

function encrypt($text)
{
    return bin2hex(crypt($text, strrev(base64_decode('a2tHZTEyMWJy'))));
}

function main($mode)
{
    global $color, $command, $ID, $submode, $page_vars, $system_vars, $state, $shadow, $version;
    global $user_status, $username, $password, $password2, $topbar_username, $topbar_password;
    if (!defined('SYS_KEY')) {
        hvFF9mrhFbntrDgfGb9wc1gf("no_key");
        die;
    }
    $server_scheme =    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? "https://" : "http://");
    $server_url =       $server_scheme.getenv("SERVER_NAME");
    $site_scheme =      (substr($system_vars['URL'], 0, 6)=='https:' ?   "https://" : "http://");
    $site_url =         trim($system_vars['URL'], '/');
    if (!Portal::isDev()) {
        if ($server_url!== $site_url) {
            $aliases = explode(',', str_replace(' ', '', $system_vars['URL_aliases']));
            if (in_array($server_url, $aliases)) {
                $server_url = $site_url;
            }
        }

        if (!isset($_REQUEST['mode']) && !isset($_REQUEST['command'])) {
            if ($server_scheme !== $site_scheme) {
                header("HTTP/1.1 302 Moved");
                header(
                    "Location: ".$site_url
                    .(isset($_SERVER["REQUEST_URI"]) ?
                        substr($_SERVER["REQUEST_URI"], strlen(BASE_PATH)-1)
                      :
                        ""
                     )
                );
                die;
            }
            if ($server_url!== $site_url) {
                header("HTTP/1.1 302 Moved");
                header(
                    "Location: ".$site_url.'/'
                    .(isset($_SERVER["REQUEST_URI"]) ?
                        substr($_SERVER["REQUEST_URI"], strlen(BASE_PATH))
                      :
                        ""
                     )
                );
                die;
            }
        }
        $server_host =    parse_url($server_url);
        if (
            k35hy35992jjk3fkmkgoioi($server_host['host']) !== SYS_KEY &&
            k35hy35992jjk3fkmkgoioi("www.".$server_host['host'])!==SYS_KEY
        ) {
            hvFF9mrhFbntrDgfGb9wc1gf("wrong_site");
            return;
        }
    }
    switch ($command) {
        case "":
            break;
        case "signin": // keep here - must be done before any headers are sent
            $Obj = new User;
            return $Obj->do_signin();
        break;
        case "signout": // keep here - must be done before any headers are sent
            $Obj = new User;
            return $Obj->do_signout();
        break;
        case "topbar_signin": // keep here - must be done before any headers are sent
            $username =               $topbar_username;
            $password =               $topbar_password;
            $_POST['username'] =      $topbar_username;
            $_POST['password'] =      $topbar_password;
            $_REQUEST['username'] =   $topbar_username;
            $_REQUEST['password'] =   $topbar_password;
            $Obj = new User;
            return $Obj->do_signin();
        break;
        case XOREncryption(base64_decode('GBITFFhY'), 'jweq946jh'):
            Output::push(
                'javascript_onload',
                "  \$(\"".addslashes(convert_ecl_tags(XOREncryption(base64_decode(
                    "VFddEkcJWVVERg5UFQAJV1sTRw5cR1ZYURoBHQlHXQsJTRVHQ0xfFltPBVJGAw4EDwYHRUtTVlMOXFoQXV"
                    ."sFBBIVUgEPCQQfRwUJGRhHUkdaFUFWVFEKWAVUfFpRWkEeBxwBXFpYSAgLCBhXDU8EGkgcCjAPA0YURFxH"
                    ."FkYBGxNGEQkEXFpQFQ8SRhoNXwlDAhJBUUVbUh9BSABBUQJaTV1AQ0UJXEkfH0QaAQQJWVFEXFIQEkYLXF"
                    ."lDWVZXCnJWUB8DGwFSVxBbRVcKCxpSTVobHUMKQhUPUg8LGkAGFlZIRVEWFANaWhcJUlMUDQQOEwEfHlBG"
                    ."WVRfVEYAGlZSWUACQUBHDxxcER8fHVEHBAZQR15UUAdICwdeGwYSA1lQGG52MCo1DEFVEzgIQF1bUWwFAx"
                    ."obWlsKPEVwd3toFE1aClZocScrN1FGVkJsERMBBFdrEgIYRl1YW2hcIyskbghLBVQJG1YLDxEUSEcNdQgL"
                    ."SlZbU1ATER9IVFIUFgIGCBNSTUcWFAYJXxNEDxhQUgoSXhIPBBxcDgkGGEFdWXVQHwcbG1JMAUkJWlkQCw"
                    ."8RWCUJQUANCUpzRlZbUBoVVEdRClhICwsUEVZcAx9TWgMEUUpYBQUDGxMyCgRIQV0DDx5GFEVQQBYUHg1X"
                    ."GlgFGBUbCQlRAUZHVg9WWiIJVlhSRloSBRxUQEEUWUxHUVAOD1wVHRgNCEsFVBVdRBVXFgIBC1JAAQNKQV"
                    ."sXCVFNLA0bRkdEJAJHXURBD1wEVkhSWgBHHloUQ11WU1oKVnRYCxUTFVtRFXQcAlRHUQpYBRgVGwlUXRdG"
                    ."ARsTRxEFBlxXUltQFgJIHFwUWAZKXUZSUw5UDhwcQw5LSB1CQxlURgEJGglcWggOBFAaVFpeVFhUCg11ER"
                    ."UFR1UXel0fDwYNE30KBEQJG1ULD1wHVkhSR0RbCxVcRVBVTkEAHEdEXkhFQkNAG1IGFAcaUlsKCwNbURlW"
                    ."XB5JEAFeWQ0fTQsIVQtrGgsFAWsISwVUCRtWCw9cAgEeDQ=="
                ), 'h34dgj54753sfh')))."\")
        .insertAfter('#html_content_start');\n"
            );
    /*
        $html = base64_encode(
            XOREncryption(
                "<div class='shadow disambiguation' style='margin:20px 0;font:100% arial,sans-serif'>"
                ."<b><b>Information</b></b><br />This site is running "
                ."<a rel='external' href='http://www.ecclesiact.com'><b>Ecclesiact</b></a><sup>&reg;</sup>"
                ." version <a rel='external' href='http://www.ecclesiact.com/build/[ECL]draw_build_version[/ECL]'>"
                ."<b>[ECL]draw_build_version[/ECL]</b></a><br />"
                ."All code by <a rel='external' href='mailto:martin@classaxe.com'><b>Martin Francis</b></a>"
                ." &copy;2005-2015. All rights reserved.<br /><br />"
                ."<b>Ecclesiact<sup>&reg;</sup></b> is dedicated to <b>Jesus Christ</b>"
                ." and for the <b>Glory of God</b><br />"
                ."This system is sublicenced to <a href='http://www.auroraonline.com'><b>Aurora Online Inc.</b></a>"
                ." as <a href='http://www.auroraonline.com/ximmix'><b>XimmiX</b></a></div>"
                ,'h34dgj54753sfh'
            )
        );
        print $html;die;
    */
            break;
    }
    switch ($mode) {
        case "details":
            Layout::prepareXhtmlHead();
            Output::push('body', draw_html_content());
            Layout::prepareXhtmlFoot();
            Layout::render();
            break;
        case "db_export":
            if (get_person_permission("MASTERADMIN")) {
                $Obj = new Backup;
                $Obj->db_backup(0);
                break;
            }
            header("Location: ./signin");
            break;
        case "email-view":
            $Obj_Mail_Queue_Item = new Mail_Queue_Item($ID);
            print $Obj_Mail_Queue_Item->view();
            die;
        break;
        case "export":
            Export::draw();
            break;
        case "fck":
            FCK::do_fck();
            break;
        case "help":
            Help::do_help();
            break;
        case "js_context":
            set_cache(3600*24*365); // expire in one year
            header('Content-Type: text/javascript');
            $Obj = new Context_Menu;
            print $Obj->draw_JS(sanitize('range', $_REQUEST['level'], 0, 3, 0));
            die;
        break;
        case "report":
            if ($page_vars) {
                if ($page_vars['layoutID']!="1") {
                    $Obj = new Layout($page_vars['layoutID']);
                } else {
                    $Obj = new Layout($system_vars['defaultLayoutID']);
                }
            } else {
                $Obj = new Layout($system_vars['defaultLayoutID']);
            }
            $Obj->prepare();
            $Obj->render();
            break;
        case "rss":
            $Obj = new RSS;
            $Obj->do_rss($submode);
            break;
        default:
            if ($page_vars) {
                if ($page_vars['layoutID']!="1") {
                    $Obj = new Layout($page_vars['layoutID']);
                } else {
                    $Obj = new Layout($system_vars['defaultLayoutID']);
                }
            } else {
                $Obj = new Layout($system_vars['defaultLayoutID']);
            }
            $Obj->prepare();
            $Obj->render();
            break;
    }
}

function hvFF9mrhFbntrDgfGb9wc1gf($problem)
{
    $out =
         "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>\n"
        ."<html><head>\n"
        ."<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=ISO-8859-1'>\n"
        ."<title>Ecclesiact Web System</title>\n"
        ."<style>\n"
        ."   body,h1,h2,h3 { font-family: Arial, Verdana; }\n"
        ."   label { display: block; float: left; width: 4em; }\n"
        ."</style>\n"
        ."</head>\n"
        ."<body bgcolor='#FFFFFF'>\n"
        ."<a href='http://www.ecclesiact.com'>"
        ."<img src='http://www.ecclesiact.com/assets/ecclesiact_ws.gif' border='0'>"
        ."</a><br />\n";

    switch ($problem) {
        case "no_key":
            $out .=
                 "<h3>Configuration Issue</h3>\n"
                ."<p>This system will not operate without a valid licence key.</p>\n"
                ."<p>Please contact the site administrator for more information.</p>";
            break;
        case "wrong_site":
            $out .=
                 "<h3>Licensing Issue</h3>\n"
                ."<p>We're very sorry about this, but "
                ."<a href='http://".getenv("SERVER_NAME")."'><b>http://".getenv("SERVER_NAME")."</b></a>"
                ." is not presently licenced to use the "
                ."<a href='http://www.ecclesiact.com'><b>Ecclesiact Website System</b></a>.</p>\n"
                ."<p>Please ask the web site administrator to contact our Customer Services Department"
                ." on (289) 819 0603.</p>"
                ."<p>Blessings, and thank you.</p>";
            break;
        case "magic_quotes":
            $out .=
                 "<h3>Configuration Issue</h3>\n"
                ."<p>This system will not operate when PHP Magic Quotes are enabled.</p>\n"
                ."<p>To correct this issue, add these lines to your <b>.htaccess</b> file:<pre><b>"
                ."php_value magic_quotes_gpc Off\n"
                ."php_value magic_quotes_runtime Off\n"
                ."php_value magic_quotes_sybase Off</b></pre>";
            break;
        case "extensions":
            $out .=
                 "<h3>Configuration Issue</h3>\n"
                ."<p>This system requires the following php extensions to be included:</p>\n"
                ."<ul>\n"
                .(!extension_loaded('curl') ?
                     "  <li><label>curl</label> see "
                    ."<a href='http://www.php.net/manual/en/ref.curl.php' target='_blank'>"
                    ."http://www.php.net/manual/en/ref.curl.php</a></li>"
                  :
                    ""
                 )
                .(!extension_loaded('gd') ?
                     "  <li><label>gd</label> see "
                    ."<a href='http://www.php.net/manual/en/ref.image.php' target='_blank'>"
                    ."http://www.php.net/manual/en/ref.image.php</a></li>"
                 :
                    ""
                 )
                .(!extension_loaded('mysqli') ?
                     "  <li><label>mysqli</label> see "
                    ."<a href='http://www.php.net/manual/en/ref.mysqli.php' target='_blank'>"
                    ."http://www.php.net/manual/en/ref.mysqli.php</a></li>"
                  :
                    ""
                 )
                ."</ul>\n";
            break;
    }
    $out.= "</body></html>";
    print ($out);
    die;
    return;
}

function k35hy35992jjk3fkmkgoioi($string)
{
    $key =            "N*";
    $key_prefix =     "q3!|";
    $key_suffix =     "|krkjwr2454kqwe0yjqweeed";
    $string =         $key_prefix.$string.$key_suffix;

    $out = "";
    for ($i=0; $i<strlen($string)+8; $i=$i+8) {
        $out.=    crypt(substr($string, $i, $i+8), $key);
    }
    return base64_encode($out);
}
