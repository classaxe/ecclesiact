<?php
  define("VERSION_COMPONENT_SURVEY", "1.0.1");
/*
Version History:
  1.0.1 (2016-03-26)
    1) Refactored and fixed for filter and limit
*/
class Component_Survey extends Component_Base
{
    const VERSION = '1.0.1';

    protected $_Obj_Poll;
    protected $_polls;

    public function __construct()
    {
        $this->_ident =             'survey';
        $this->_parameter_spec = array(
            'block_layout' =>           array(
                'match' => '',
                'default'=>'Survey',
                'hint'=>'Name of Block Layout to use'
            ),
            'extra_fields_list' =>      array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list format: field|label|group,field|label|group...'
            ),
            'field_width' =>            array(
                'match' =>      'range|1,n',
                'default' =>    '150',
                'hint' =>       '|1..n - width in px for fields'
            ),
            'filter_category_list' =>   array(
                'match' =>      '',
                'default' =>    '*',
                'hint' =>       '*|CSV value list'
            ),
            'item_footer_component' =>  array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Name of component rendered below displayed New Item'
            ),
            'numbers_show' =>           array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'results_limit' =>          array(
                'match' =>      'range|0,n',
                'default' =>    '3',
                'hint' =>       '0..n'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->_setup($instance, $args, $disable_params);
        $this->_draw_control_panel(true);
        if (!$this->_polls) {
            $this->_html.= "<p>There are no polls available to view at this time.</p>";
            return $this->_html;
        }
        for ($i=0; $i<count($this->_polls); $i++) {
            $poll = $this->_polls[$i];
            $this->drawPoll($poll);
            if ($i!=count($this->_polls)-1) {
                $this->_html.="<br />";
            }
        }
        return $this->_html;
    }

    protected function drawPoll($poll)
    {
        $ID =       $poll['ID'];
        $this->_Obj_Poll->_set_ID($ID);
        $voted =    $this->hasVoted($ID);
        $this->_html.=
         "<div id='poll_".$ID."' class='poll'>\n"
        .($poll['active']=='0' || $voted ?
         $this->_Obj_Poll->draw_result($voted)
         :
         $this->_Obj_Poll->draw_question()
        )
        ."</div>";

    }

    protected function hasVoted($ID)
    {
        return isset($_COOKIE['poll_'.$ID]) || isset($_SESSION["poll_".$ID]);
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->_Obj_Poll = new Poll;
        $this->_polls = $this->_Obj_Poll->get_current_polls(
            $this->_cp['filter_category_list'],
            $this->_cp['results_limit'],
            0
        );
    }
}
