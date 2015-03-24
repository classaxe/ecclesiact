<?php
namespace Component;

define("VERSION_NS_COMPONENT_ACTIVITY_TABBER", "1.0.6");

/*
Version History:
  1.0.6 (2015-03-04)
    1) Moved from Component_Activity_Tabber and reworked to use namespaces
    2) Now Fully PSR-2 compliant

*/
class ActivityTabber extends Base
{
    protected $_activities =    array();
    protected $_records =       false;
    protected $_tabs =          array();
    protected $_ObjDisplayableItem;

    public function __construct()
    {
        $this->_ident =            "activity_tabber";
        $this->_parameter_spec =   array(
            'activity_list' =>        array(
                'match' =>      'enum_csv|comments,emails,ratings,visits',
                'default' =>    'visits,emails',
                'hint' =>       'CSV list may include: comments,emails,ratings,visits'
            ),
            'block_layout' =>         array(
                'match' =>      '',
                'default' =>    'Activity Tabber',
                'hint' =>       'Name of Block Layout to use'
            ),
            'comments_show' =>        array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'comments_link_show' =>   array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'content_char_limit' =>   array(
                'match' =>      'range|0,n',
                'default' =>    '0',
                'hint' =>       '0..n'
            ),
            'content_plaintext' =>    array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_show' =>         array(
                'match' =>  'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_use_summary' =>  array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'date_show' =>            array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'exclude_list' =>         array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list of page and postings to exclude'
            ),
            'label_comments' =>       array(
                'match' =>      '',
                'default' =>    'Most Commented',
                'hint' =>       'Text for Label'
            ),
            'label_emails' =>         array(
                'match' =>      '',
                'default' =>    'Most Emailed',
                'hint' =>       'Text for Label'
            ),
            'label_ratings' =>        array(
                'match' =>      '',
                'default' =>    'Most Rated',
                'hint' =>       'Text for Label'
            ),
            'label_visits' =>         array(
                'match' =>      '',
                'default' =>    'Most Viewed',
                'hint' =>       'Text for Label'
            ),
            'limit_per_activity' =>   array(
                'match' =>      'range|1,n',
                'default' =>    '5',
                'hint' =>       '1..n Max items to show per activity'
            ),
            'more_link_text' =>       array(
                'match' =>      '',
                'default' =>    '(More)',
                'hint' =>       'text for \'Read More\' link'
            ),
            'section_tabs_always' =>  array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1 - if set will always show section tabs, even if there is only one of them'
            ),
            'title_linked' =>         array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'title_show' =>           array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        if (!\System::has_feature('Activity-Tracking')) {
            $this->drawErrorTrackingNotEnabled();
            return $this->renderError();
        }
        if (!$this->_ObjBlockLayout) {
            $this->drawErrorBlockLayoutMissing();
            return $this->renderError();
        }
        if (!$this->_records) {
            $this->drawErrorNoRecords();
            return $this->render();
        }
        $this->drawTabs();
        foreach ($this->_tabs as $tab) {
            $this->_html.=
                 draw_section_tab_div($tab['ID'], $this->_selected_section)."\n"
                ."<div class='items'>\n";
            foreach ($this->_records as $record) {
                if ($record['activity']==$tab['ID']) {
                    $this->_html.=  $this->drawItem($record);
                }
            }
            $this->_html.="</div></div>";
        }
        return $this->render();
    }

    protected function drawErrorBlockLayoutMissing()
    {
        $this->_html.= "<b>Error:</b> There is no such Block Layout as '".$this->_cp['block_layout']."'";
    }

    protected function drawErrorNoRecords()
    {
        $this->_html.= "No records available to display.";
    }

    protected function drawErrorTrackingNotEnabled()
    {
        $this->_html.= "<b>Error:</b> Activity Tracking is not enabled.";
    }

    protected function drawItem($record)
    {
        $ObjectType =       '\\'.$record['object_type'];
        $Obj =              new $ObjectType;
        $Obj->record =      $record;
        $args = array(
            '_cp' =>                          $this->_cp,
            '_current_user_rights' =>         $this->_current_user_rights,
            '_block_layout' =>                $this->_ObjBlockLayout->record,
            '_context_menu_ID' =>             $record['type'],
            '_mode' =>                        'list',
            '_safe_ID' =>                     $this->_safe_ID
        );
        $Obj->_set_multiple($args);
        return
             $Obj->convert_Block_Layout($this->_ObjBlockLayout->record['listings_item_detail'])
            .$Obj->convert_Block_Layout($this->_ObjBlockLayout->record['listings_item_separator']);
    }

    protected function drawTabs()
    {
        if (!$this->_cp['section_tabs_always'] && count($this->_tabs)<2) {
            return;
        }
        $this->_html.= \HTML::draw_section_tabs(
            $this->_tabs,
            $this->_safe_ID,
            $this->_selected_section
        );
    }

    protected function render()
    {
        return
             "<div id=\"".$this->_safe_ID."\">\n"
            .$this->_ObjDisplayableItem->convert_Block_Layout(
                $this->_ObjBlockLayout->record['listings_panel_header']
            )
            .$this->_html
            .$this->_ObjDisplayableItem->convert_Block_Layout(
                $this->_ObjBlockLayout->record['listings_panel_footer']
            )
            ."</div>";
    }

    protected function renderError()
    {
        return
             "<div id=\"".$this->_safe_ID."\">\n"
            .$this->_html
            ."</div>";
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->_ObjDisplayableItem = new \Displayable_Item;
        $this->setupLoadBlockLayout();
        $this->setupLoadUserRights();
        $this->setupLoadRecords();
        $this->setupLoadTabs();
    }

    protected function setupLoadBlockLayout()
    {
        if ($this->_ObjBlockLayout = parent::setupLoadBlockLayout($this->_cp['block_layout'])) {
            $this->_ObjBlockLayout->draw_css_include('listings');
        }
    }

    protected function setupLoadRecords()
    {
        $args = array(
            'activity_list' =>      $this->_cp['activity_list'],
            'exclude_list' =>       $this->_cp['exclude_list'],
            'limit_per_activity' => $this->_cp['limit_per_activity']
        );
        $Obj = new \Activity;
        $this->_records = $Obj->get_n_per_activity($args);
    }

    protected function setupLoadTabs()
    {
        if (!$this->_records) {
            return;
        }
        foreach ($this->_records as $record) {
            $activity = $record['activity'];
            if (!in_array($activity, $this->_activities)) {
                $this->_activities[] = $activity;
                $this->_tabs[] = array(
                    'ID' =>     $activity,
                    'label' =>  $this->_cp['label_'.$activity]
                );
            }
        }
        $temp = get_var('selected_section');
        $this->_selected_section = (in_array($temp, $this->_activities) ? $temp : $this->_activities[0]);
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_ACTIVITY_TABBER;
    }
}
