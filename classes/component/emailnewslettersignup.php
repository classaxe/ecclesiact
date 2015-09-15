<?php
namespace Component;

define("VERSION_NS_COMPONENT_EMAIL_NEWSLETTER_SIGNUP", "1.0.2");
/*
Version History:
  1.0.2 (2015-09-14)
    1) References to Page::push_content() now changed to Output::push()

*/
class EmailNewsletterSignup extends Base
{
    protected $badCaptcha = false;

    public function __construct()
    {
        $this->_ident =             "email_newsletter_signup";
        $this->_parameter_spec =    array(
            'adminEmail' =>       array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Send form to this address'
            ),
            'adminName' =>        array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Address form to this person'
            ),
            'allowHTML' =>        array(
                'match' =>      'enum|0,1',
                'default' =>    0,
                'hint' =>       '0|1'
            ),
            'askComments' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    1,
                'hint' =>       '0|1'
            ),
            'askCountry' =>       array(
                'match' =>      'enum|0,1,2',
                'default' =>    1,
                'hint' =>       '0|1|2 - 2 means required'
            ),
            'askName' =>          array(
                'match' =>      'enum|0,1,2',
                'default' =>    2,
                'hint' =>       '0|1|2 - 2 means required'
            ),
            'askStateProv' =>     array(
                'match' =>      'enum|0,1,2',
                'default' =>    1,
                'hint' =>       '0|1|2 - 2 means required'
            ),
            'askSurname' =>       array(
                'match' =>      '',
                'default' =>    0,
                'hint' =>       '0|1|2 - 2 means required'
            ),
            'field_width' =>      array(
                'match' =>      '',
                'default' =>    350,
                'hint' =>       'width in px'
            ),
            'signupMethod' =>     array(
                'match' =>      'enum|admin,online',
                'default' =>    'admin',
                'hint' =>       'admin|online'
            ),
            'successURL' =>       array(
                'match' =>      '',
                'default' =>    'newsletter-success',
                'hint' =>       'Page to display if succeeded'
            )
        );

    }
    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawJs();
        $this->drawControlPanel(true);
        $this->drawStatus();
        $this->drawForm();
        return $this->_html;
    }

    protected function drawForm()
    {
        $this->_html.=
            "<table class='minimal' summary='Newsletter Signup Form'>\n"
            ."  <tbody>\n"
            .($this->_cp['askName']=='1' || $this->_cp['askName']=='2' ?
                 "    <tr>\n"
                ."      <th class='va_t' style='width:200px;'><label for='name'>"
                .($this->_cp['askName']=='2' ? "<span class='req'>*</span> " : "")
                ."Your Name</label></th>\n"
                ."      <td class='va_t'>"
                .draw_form_field(
                    'name',
                    (!empty($_REQUEST['name']) ? $_REQUEST['name'] : ''),
                    'text',
                    $this->_cp['field_width']
                )
                ."</td>\n"
                ."    </tr>\n"
             :
                ""
            )
            .($this->_cp['askSurname']=='1' || $this->_cp['askSurname']=='2' ?
                 "    <tr>\n"
                ."      <th class='va_t' style='width:200px;'><label for='surname'>"
                .($this->_cp['askSurname']=='2' ? "<span class='req'>*</span> " : "")
                ."Your Surname</label></th>\n"
                ."      <td class='va_t'>"
                .draw_form_field(
                    'surname',
                    (!empty($_REQUEST['surname']) ? $_REQUEST['surname'] : ''),
                    'text',
                    $this->_cp['field_width']
                )
                ."</td>\n"
                ."    </tr>\n"
             :
                ""
            )
            ."    <tr>\n"
            ."      <th class='va_t'><label for='email'><span class='req'>*</span> Your Email Address</label></th>\n"
            ."      <td class='va_t'>"
            .draw_form_field(
                'email',
                (!empty($_REQUEST['email']) && strpos($_REQUEST['email'], '@')>0 ? $_REQUEST['email'] : ''),
                'text',
                $this->_cp['field_width']
            )
            ."</td>\n"
            ."    </tr>\n"
            .($this->_cp['askStateProv']=='1' || $this->_cp['askStateProv']=='2' ?
                 "    <tr>\n"
                ."      <th class='va_t'><label for='sp'>"
                .($this->_cp['askStateProv']=='2' ? "<span class='req'>*</span> " : "")
                ."State / Province</label></th>\n"
                ."      <td class='va_t'>"
                .draw_form_field(
                    'sp',
                    (!empty($_REQUEST['sp']) ? $_REQUEST['sp'] : ''),
                    'combo_listdata',
                    $this->_cp['field_width'],
                    '',
                    0,
                    '',
                    0,
                    0,
                    '',
                    'lst_sp'
                )
                ."</td>\n"
                ."    </tr>\n"
             :
                ""
            )
            .($this->_cp['askCountry']=='1' || $this->_cp['askCountry']=='2' ?
                 "    <tr>\n"
                ."      <th class='va_t'><label for='country'>"
                .($this->_cp['askCountry']=='2' ? "<span class='req'>*</span> " : "")
                ."Country</label></th>\n"
                ."      <td class='va_t'>"
                .draw_form_field(
                    'country',
                    (!empty($_REQUEST['country']) ? $_REQUEST['country'] : 'CAN'),
                    'selector_listdata',
                    $this->_cp['field_width'],
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'lst_country|0'
                )
                ."</td>\n"
                ."    </tr>\n"
             :
                ""
            )
            .($this->_cp['askComments']=='1' ?
                 "    <tr>\n"
                ."      <th class='va_t'><label for='comments'>"
                ."Comments</label></th>\n"
                ."      <td class='va_t'>"
                .draw_form_field(
                    'comments',
                    (!empty($_REQUEST['comments']) ? $_REQUEST['comments'] : ''),
                    'textarea',
                    $this->_cp['field_width'],
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    100
                )
                ."</td>\n"
                ."    </tr>\n"
             :
                ""
            )
            ."    <tr>\n"
            ."      <th class='va_t'>Verification image -<br />\nLetters are lower-case<br />\n"
            ."<a href=\"#\" onclick=\""
            ."geid('captcha_image').src='".BASE_PATH."?command=captcha_img&rnd='+Math.random();"
            ."return false\">(<b><i>Unclear? Try another image)</i></b></a></th>\n"
            ."      <td><img id='captcha_image' class='formField' style='border:1px solid #7F9DB9;padding:2px;'"
            ." src='".BASE_PATH."?command=captcha_img&rnd=".(rand(100000, 999999))."' alt='' /></td>\n"
            ."    </tr>\n"
            ."    <tr>\n"
            ."      <th id='th_captcha_key'".($this->badCaptcha ? " style='color:#ff0000;'" : "").">"
            ."<label for='captcha_key'>Verification text<br />"
            ."<span style='font-weight:normal'>(Type the characters you see)</span></label> </th>\n"
            ."      <td class='va_t'>"
            ."<input type='text' name='captcha_key' id='captcha_key' size='20' style='width: 180px;' value=\"\"/>"
            ."</td>\n"
            ."    </tr>\n"
            ."    <tr>\n"
            ."      <td colspan='2' class='txt_c'>"
            ."<input type='button' onclick=\"email_newsletter_signup_validate()\" value='Send' />"
            ."</td>\n"
            ."    </tr>\n"
            ."  </tbody>\n"
            ."</table>";

    }

    protected function drawJs()
    {
        Output::push(
            'javascript',
            "function email_newsletter_signup_validate() {\n"
            ."  var err = [];\n"
            .($this->_cp['askName']=='2' ?
                "  if (geid_val('name').length==0) { err.push((err.length+1)+') Your Name'); }\n"
             :
                ""
            )
            .($this->_cp['askSurname']=='2' ?
                "  if (geid_val('surname').length==0) { err.push((err.length+1)+') Your Surname'); }\n"
             :
                ""
             )
            ."  if (geid_val('email').indexOf('@')==-1) { err.push((err.length+1)+') A valid Email Address'); }\n"
            .($this->_cp['askStateProv']=='2' ?
                "  if (geid_val('sp').length==0) { err.push((err.length+1)+') Your State or Province'); }\n"
             :
                ""
            )
            .($this->_cp['askCountry']=='2' ?
                "  if (geid_val('country').length==0) { err.push((err.length+1)+') Your Country'); }\n"
             :
                ""
            )
            ."  if (geid_val('captcha_key').length!=6) { err.push((err.length+1)+') Verification text (6 characters)');"
            ." }\n"
            ."  err = err.join('\\n');\n"
            ."  if (err.length==0){\n"
            ."    geid_set('submode','send');geid('form').submit();\n"
            ."  }\n"
            ."  else {\n"
            ."    alert('The following fields are required:\\n\\n'+err);\n"
            ."  }\n"
            ."}"
        );

    }

    protected function setup($instance = '', $args = array(), $disable_params = false)
    {
        parent::setup($instance, $args, $disable_params);
        $this->doSubmode();
    }

    protected function doSubmode()
    {
        global $system_vars, $page_vars;
        if (empty($_REQUEST['submode'])) {
            return;
        }
        if ($_REQUEST['submode']!=='send') {
            return;
        }
        $Obj = new Captcha();
        if (!isset($_POST['captcha_key']) || !$Obj->isKeyRight($_POST['captcha_key'])) {
            $this->badCaptcha = true;
            return;
        }
        get_mailsender_to_component_results(); // Use system default mail sender details
        $title = $system_vars['textEnglish']." newsletter signup via ".$page_vars['absolute_URL'];
        component_result_set('NName', $this->_cp['adminName']);
        component_result_set('PEmail', $this->_cp['adminEmail']);
        component_result_set('from_name', $system_vars['adminName']);
        component_result_set('from_email', $system_vars['adminEmail']);
        $text_arr =     array();
        $html_arr =     array();
        $ignore_arr =   explode(",", SYS_STANDARD_FIELDS.",captcha_key");
        foreach ($_POST as $field => $value) {
            if ($value!=="" && !in_array($field, $ignore_arr)) {
                $value = ($this->_cp['allowHTML'] ? $value : sanitize('html', $value));
                $text_arr[] = pad($field, 25).$value."\n";
                $html_arr[] =
                 "  <tr>\n"
                ."    <th style='text-align:left'>".$field."</th>\n"
                ."    <td>".($value=="" ? "&nbsp;" : $value)."</td>\n"
                ."  </tr>\n";
            }
        }
        component_result_set(
            'content_html',
            "<h1>".$title."</h1>\n"
            ."<table cellpadding='2' cellspacing='0' border='1' bordercolor='#808080' bgcolor='#ffffff'>\n"
            ."  <tr>\n"
            ."    <th style='text-align:left;background-color:#e0e0e0;'>Field</th>\n"
            ."    <th style='text-align:left;background-color:#e0e0e0;'>Value</th>\n"
            ."  </tr>\n"
            .implode("", $html_arr)
            ."</table>"
        );
        component_result_set(
            'content_text',
            "$title\n"
            .pad("FIELD", 25)."VALUE\n"
            ."---------------------------------------------------------\n"
            .implode("", $text_arr)
        );
        $data =               array();
        $data['PEmail'] =     component_result('PEmail');
        $data['NName'] =      component_result('NName');
        $data['subject'] =    $system_vars['textEnglish']." newsletter signup via ".$page_vars['absolute_URL'];
        $data['html'] =       component_result('content_html');
        $data['text'] =       component_result('content_text');
        $mail_result = mailto($data);
        if (substr($mail_result, 0, 12)=="Message-ID: ") {
            header(
                "Location: ".BASE_PATH.$this->_cp['successURL']
            );
            die();
        }
        $this->_msg = "Error: ".$mail_result;
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_EMAIL_NEWSLETTER_SIGNUP;
    }
}
