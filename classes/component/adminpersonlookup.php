<?php
namespace Component;
/*
Version History:
  1.0.6 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class AdminPersonLookup extends Base
{
    const VERSION = '1.0.6';

    public function __construct()
    {
        $this->_ident =         'admin_person_lookup';
        $this->_parameter_spec = array(
            'field_display' =>    array(
                'match' =>      '',
                'default' =>    'PUsername',
                'hint' =>       'Field to place value in search box when found'
            ),
            'field_linked' =>     array(
                'match' =>      '',
                'default' =>    'ID',
                'hint' =>       'Field used to open detail form'
            ),
            'filter_mode' =>      array(
                'match' =>      '',
                'default' =>    'Contains',
                'hint' =>
                     'Contains|Does not contain|Starts with|Ends with|Is exactly equal to|Is not exactly equal to|'
                    .'Is greater than|Is less than|In date range|Contains this word|Contains a word beginning with|'
                    .'Contains a word ending with|Value in this CSV list|Value like one in this CSV list'
            ),
            'filter_field' =>     array(
                'match' =>      '',
                'default' =>    'PUsername',
                'hint' =>
                     'Field to match searches against - '
                    .'use XML to define options with option entries each containing label, filter and mode'
            ),
            'form_name' =>        array(
                'match' =>      'enum|contact,user',
                'default' =>    'user',
                'hint' =>       'Label to place beside search field'
            ),
            'initial_text' =>     array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Text shown underneath field label initially'
            ),
            'label_text' =>       array(
                'match' =>      '',
                'default' =>    'Search for Person:',
                'hint' =>       'Report to use when opening detail form'
            ),
            'report_name' =>      array(
                'match' =>      '',
                'default' =>    'User Lookup',
                'hint' =>       'Report to use wen searching'
            ),
            'results_height' =>   array(
                'match' =>      'range|50,n',
                'default' =>    '100',
                'hint' =>       'Height of scrollable results box'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawStatus();
        $this->drawForm();
        return $this->_html;
    }

    protected function drawForm()
    {
        if (!$this->_isAdmin) {
            return;
        }
        $control_num =  \Ajax::generate_control_num();
        $form =         $this->_cp['form_name'];
        $popup =        get_popup_size($form);
        $Obj_RFFL =     new \Report_Form_Field_Lookup;
        $args = array(
            'field' =>                    $this->_safe_ID,
            'value' =>                    '',
            'control_num' =>              $control_num,
            'report_name' =>              $this->_cp['report_name'],
            'report_field' =>             $this->_cp['filter_field'],
            'report_matchmode' =>         $this->_cp['filter_mode'],
            'linked_field' =>             $this->_cp['field_linked'],
            'displayed_field' =>          $this->_cp['field_display'],
            'autocomplete' =>             1,
            'row_js' =>
                "details('".$form."',geid('".$this->_safe_ID."').value,".$popup['h'].",".$popup['w'].");",
            'onematch_js' =>
                "details('".$form."',geid('".$this->_safe_ID."').value,".$popup['h'].",".$popup['w'].");",
            'nomatch_js' =>               '',
            'lookup_info_initial' =>      '',
            'lookup_result_initial' =>    $this->_cp['initial_text'],
            'results_height' =>           $this->_cp['results_height']
        );
        $Obj_RFFL->init($args);
        $this->_html.=
             "<div class='".$this->_ident."'>\n"
            ."  <label class='fl' style='padding-right:0.5em;' for='"
            .($this->_cp['field_display']!='' ? 'x_' : '')
            .$this->_safe_ID."'>"
            .$this->_cp['label_text']
            ."</label>\n"
            ."  ".$Obj_RFFL->draw($args)
            ."</div>\n";
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadUserRights();
    }
}
