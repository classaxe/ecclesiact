<?php
namespace Component;

define("VERSION_NS_COMPONENT_CUSTOM_FORM", "1.0.5");
/*
Version History:
  1.0.5 (2015-03-29)
    1) Moved from Component_Activity_Tabber and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class CustomForm extends Base
{
    protected $CustomFormID;
    protected $ObjCF;
    protected $XML;

    public function __construct()
    {
        $this->_ident =            "custom_form";
        if (\System::has_feature('Fedex')) {
            $dropoffOptions =
                'BUSINESS_SERVICE_CENTER,DROP_BOX,REGULAR_PICKUP,REQUEST_COURIER,STATION';
            $packagingOptions =
                'FEDEX_10KG_BOX,FEDEX_25KG_BOX,FEDEX_BOX,FEDEX_ENVELOPE,FEDEX_PAK,FEDEX_TUBE,YOUR_PACKAGING';
            $this->_parameter_spec =   array(
                'name' =>                     array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Name for custom form to use'
                ),
                'new_user_email' =>           array(
                    'match' =>      'enum|0,1',
                    'default' =>    '0',
                    'hint' =>       'Whether or not to send an email iof a new user is created'
                ),
                'new_user_email_template' =>  array(
                    'match' =>      '',
                    'default' =>    'user_signup',
                    'hint' =>       'If an email is to be sent to new users, use this email'
                ),
                'payment_gateway_setting' =>  array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Payment Gateway Settings to use for any payments that might occur'
                ),
                'redirect_to_page' =>         array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>
                         'Optional - takes 1 or two | delimited parameters.'
                        .' If options>1 and 1st is checkout, second for when cart is empty'
                ),
                'ship_FEDEX_AccountNumber' => array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Account Number for shipping gateway'
                ),
                'ship_FEDEX_MeterNumber' =>   array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Meter Number for shipping gateway'
                ),
                'ship_FEDEX_DropoffType' =>   array(
                    'match' =>      'enum|'.$dropoffOptions,
                    'default' =>    'REGULAR_PICKUP',
                    'hint' =>       str_replace(',', '|', $dropoffOptions)
                ),
                'ship_FEDEX_LiveGateway' =>   array(
                    'match' =>      'enum|0,1',
                    'default' =>    '0',
                    'hint' =>       '0|1'
                ),
                'ship_FEDEX_PackagingType' => array(
                    'match' =>      'enum|'.$packagingOptions,
                    'default' =>    'FEDEX_PAK',
                    'hint' =>       str_replace(',', '|', $packagingOptions)
                ),
                'ship_FEDEX_username' =>      array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Key for shipping gateway'
                ),
                'ship_FEDEX_password' =>      array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Password for shipping gateway'
                ),
                'ship_from_AAddress1' =>      array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Ship from Address Line 1'
                ),
                'ship_from_AAddress2' =>      array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Ship from Address Line 2'
                ),
                'ship_from_ACity' =>          array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Ship from City'
                ),
                'ship_from_ASpID' =>          array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Ship from State / Province'
                ),
                'ship_from_APostal' =>        array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Ship from Postal Code'
                ),
                'ship_from_ACountryID' =>     array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Ship from Country'
                )
            );
        } else {
            $this->_parameter_spec =   array(
                'name' =>                     array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Name for custom form to use'
                ),
                'new_user_email' =>           array(
                    'match' =>      'enum|0,1',
                    'default' =>    '0',
                    'hint' =>       'Whether or not to send an email iof a new user is created'
                ),
                'new_user_email_template' =>  array(
                    'match' =>      '',
                    'default' =>    'user_signup',
                    'hint' =>       'If an email is to be sent to new users, use this email'
                ),
                'payment_gateway_setting' =>  array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>       'Payment Gateway Settings to use for any payments that might occur'
                ),
                'redirect_to_page' =>         array(
                    'match' =>      '',
                    'default' =>    '',
                    'hint' =>
                         'Optional - takes 1 or two | delimited parameters If options &gt; 1 and 1st is checkout,'
                        .' second for when cart is empty'
                )
            );
        }
    }


    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel();
        $this->drawCustomFormToolbar();
        $this->drawCustomForm();
        return $this->_html;
    }

    protected function drawCustomForm()
    {
        $this->_html.=  $this->ObjCF->draw($this->XML, $this->_cp);
    }

    protected function drawCustomFormToolbar()
    {
        if (get_var('print')==1) {
            return;
        }
        $Obj_HTML =     new \HTML;
        $this->_html.=  $Obj_HTML->draw_toolbar(
            'custom_form',
            array(
                'ID'=>$this->CustomFormID,
                'name'=> $this->_cp['name']
            )
        );
    }

    protected function setup($instance, $args, $disable_params)
    {
        $this->_parameter_spec['name']['default'] = $instance;
        $this->setupLoadGatewayDefaults();
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadCustomForm();
    }

    protected function setupLoadCustomForm()
    {
        $this->ObjCF =          new \Custom_Form;
        $this->CustomFormID =   $this->ObjCF->get_ID_by_name($this->_cp['name']);
        $this->ObjCF->_set_ID($this->CustomFormID); // Custom form now has its own implementation
        $this->XML =            $this->ObjCF->get_field('content');
    }

    protected function setupLoadGatewayDefaults()
    {
        global $system_vars;
        $Obj_GS = new \Gateway_Setting($system_vars['gatewayID']);
        $this->_parameter_spec['payment_gateway_setting']['default'] = $Obj_GS->get_field('name');

    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_CUSTOM_FORM;
    }
}
