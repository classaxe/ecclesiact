<?php
namespace Component;
/*
Version History:
  1.0.6 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class CalendarSmall extends Base
{
    const VERSION = '1.0.6';

    public function __construct()
    {
        $this->_ident =         'calendar';
        $this->_parameter_spec = array(
            'filter_category_list' =>   array(
                'match' =>      '',
                'default' =>    '*',
                'hint' =>       '*|CSV value list'
            ),
            'filter_memberID' =>        array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>
                    'ID of Community Member to restrict by that criteria - or zero to exclude all member content'
            ),
            'link_enlarge' =>           array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'URL for enlarged view'
            ),
            'link_enlarge_popup' =>     array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'link_help' =>              array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'shadow' =>                 array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'show' =>                   array(
                'match' =>      'enum|days,events,sample',
                'default' =>    'events',
                'hint' =>       'days|events|sample'
            ),
            'weeks' =>                  array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'width' =>                  array(
                'match' =>      '',
                'default' =>    '0',
                'hint' =>       '0..x'
            )
        );
    }

    public function draw($args = array(), $disable_params = false)
    {
        global $YYYY, $MM, $DD, $system_vars, $page_vars;
        $instance =         '';
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $isMASTERADMIN =    get_person_permission("MASTERADMIN");
        $isSYSADMIN =       get_person_permission("SYSADMIN");
        $isSYSEDITOR =      get_person_permission("SYSEDITOR");
        $canPublish =       ($isMASTERADMIN || $isSYSADMIN || $isSYSEDITOR);
        $_DD = $DD;
        $sd_arr = array();
        if ($this->_cp['show']=='events') {
            $Obj_Event = new \Event;
            $arr_cal = $Obj_Event->get_calendar_dates(
                $MM,
                $YYYY,
                $this->_cp['filter_memberID'],
                $this->_cp['filter_category_list']
            );
            $special_days = array();
            foreach ($arr_cal as $item) {
                if ($item['evt']) {
                    $date = $item['YYYY']."-".$item['MM']."-".$item['DD'];
                    $special_days[$date] = array();
                    foreach ($item['evt'] as $event) {
                        $special_days[$date][] = ($event['systemID']==SYS_ID ? "  " : "* ").$event['title'];
                    }
                }
            }
    //      y($special_days);die;
            foreach ($special_days as $key => $array) {
                $sd_arr[] = "'".$key."' : [\"".implode("\",\"", $array)."\"]";
            }
        }
  //    y($sd_arr);die;
        if ($this->_cp['show']=='sample') {
            $sd_arr[] = "'".$YYYY."-".$MM."-".($_DD=="01" ? "03" : "01")."' : ['   Test event','* Shared event']";
            $sd_arr[] = "'".$YYYY."-".$MM."-".($_DD=="15" ? "16" : "15")."' : ['   Test event']";
            $_DD = ($_DD=="01" ? "02" : "01");
        }
        $js =
            "var calendar_special_days = {\n"
            ."  ".implode(",\n  ", $sd_arr)."\n"
            ."};\n";
        \Output::push('javascript', $js);
        $flatCallback = false;
        switch($this->_cp['show']){
            case "days":
            case "events":
                $flatCallback = ($canPublish ? "calendar_changed_admin_fn" : "calendar_changed_fn");
                break;
        }
        $js_onload =
             "  Calendar.setup({\n"
            ."    date:               new Date(".(int)$YYYY.",".((int)$MM-1).",".(int)$_DD."),\n"
            ."    dateStatusFunc:     calendar_status_fn,\n"
            ."    dateToolTipFunc:    calendar_tooltip_fn,\n"
            ."    flat:               '".$this->_safe_ID."',\n"
            .($flatCallback ? "    flatCallback:       ".$flatCallback.",\n" : "")
            ."    link_enlarge:       '".$this->_cp['link_enlarge']."',\n"
            ."    link_enlarge_popup: '".$this->_cp['link_enlarge_popup']."',\n"
            ."    link_help:          '".$this->_cp['link_help']."',\n"
            ."    showOthers:         true,\n"
            ."    weekNumbers:        ".($this->_cp['weeks'] ? 'true' : 'false')."\n"
            ."  });\n";
        \Output::push('javascript_onload', $js_onload);
        return
             $this->_html
            ."<div id=\"".$this->_safe_ID."\""
            .($this->_cp['shadow'] ? " class='shadow'" : "")
            ." style='".($this->_cp['width'] ? "width:".$this->_cp['width']."px" : "xheight:1%")."'></div>";
    }
}
