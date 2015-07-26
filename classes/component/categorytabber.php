<?php
namespace Component;

define("VERSION_COMPONENT_NS_CATEGORY_TABBER", "1.0.6");
/*
Version History:
  1.0.6 (2015-07-26)
    1) Add icon now correctly hidden for non-admins

*/
class CategoryTabber extends Base
{
    protected $records = array();
    protected $Obj;
    protected $ObjBlockLayout;
    protected $popupForm;
    protected $popupSize;

    public function __construct()
    {
        $this->_ident =            "category_tabber";
        $this->_parameter_spec =   array(
            'author_show' =>                    array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'block_layout' =>                   array(
                'match' =>      '',
                'default' =>    'Category Tabber',
                'hint' =>       'Name of Block Layout to use'
            ),
            'category_show' =>                  array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_char_limit' =>             array(
                'match' =>      'range|0,n',
                'default' =>    '0',
                'hint' =>       '0..n'
            ),
            'content_plaintext' =>              array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_show' =>                   array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'content_use_summary' =>            array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'date_show' =>                      array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'extra_fields_list' =>              array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list format: field|label|group,field|label|group...'
            ),
            'filter_category_list' =>           array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list of categories'
            ),
            'filter_category_master' =>         array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Optionally INSIST on this category'
            ),
            'filter_sites_list' =>              array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list of site URLs'
            ),
            'filter_type' =>                    array(
                'match' =>      'enum|Article,Product',
                'default' =>    'Article',
                'hint' =>       'Article|Product'
            ),
            'links_point_to_URL' =>             array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1 - If there is a URL, both title and thumbnails links go to it'
            ),
            'more_link_text' =>                 array(
                'match' =>      '',
                'default' =>    '(More)',
                'hint' =>       'text for more link'
            ),
            'related_show' =>                   array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'results_limit_per_category' =>     array(
                'match' =>      'range|1,n',
                'default' =>    '1',
                'hint' =>       'Max articles per tab'
            ),
            'results_order' =>                  array(
                'match' =>      'enum|latest,title',
                'default' =>    'latest',
                'hint' =>       'latest|title'
            ),
            'subtitle_show' =>                  array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'thumbnail_at_top' =>               array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'thumbnail_height' =>               array(
                'match' =>      'range|1,n',
                'default' =>    '600',
                'hint' =>       '|1..n'
            ),
            'thumbnail_image' =>                array(
                'match' =>      'enum|s,m,l',
                'default' =>    's',
                'hint' =>       's|m|l - Choose only \'s\' unless Multiple-Thumbnails option is enabled'
            ),
            'thumbnail_link' =>                 array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'thumbnail_show' =>                 array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'thumbnail_width' =>                array(
                'match' =>      'range|1,n',
                'default' =>    '200',
                'hint' =>       '|1..n'
            ),
            'title_linked' =>                   array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'title_show' =>                     array(
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
        if (!$this->ObjBlockLayout) {
            $this->drawErrorBlockLayoutMissing();
            return $this->renderError();
        }
        if (!$this->records) {
            $this->drawErrorNoRecords();
            return $this->render();
        }
        $this->drawTabs();
        foreach ($this->_tabs as $tab) {
            $this->Obj->_category = $tab;
            $records = array();
            foreach ($this->records as $record) {
                if (strToLower($record['cat'])==strToLower($tab['value'])) {
                    $records[] = $record;
                }
            }
            $this->_html.=    draw_section_tab_div($tab['value'], $this->_selected_section);
            $this->_html.=    $this->Obj->convert_Block_Layout($this->ObjBlockLayout->record['listings_group_header']);
            $this->drawAddIcon($tab['value']);
            for ($i=0; $i<count($records); $i++) {
                $this->Obj->record = $records[$i];
                $this->xmlfields_decode($this->Obj->record);
                $this->Obj->record['computed_sequence_value'] = $i+1;
                $this->Obj->_set('_context_menu_ID', $record['type']);
                if ($i>0) {
                    $this->_html.=
                        $this->Obj->convert_Block_Layout($this->ObjBlockLayout->record['listings_item_separator']);
                }
                $this->_html.=  $this->Obj->convert_Block_Layout($this->ObjBlockLayout->record['listings_item_detail']);
            }
            $this->_html.=    $this->Obj->convert_Block_Layout($this->ObjBlockLayout->record['listings_group_footer']);
            $this->_html.=    "</div>\n";
        }
        return $this->render();
    }

    protected function drawAddIcon($category)
    {
        if (!$this->_isAdmin) {
            return;
        }
        $this->_html.=
             "<a class='fl' href=\"#\" onclick=\"details("
            ."'".$this->popupForm."','','".$this->popupSize['h']."','".$this->popupSize['w']."',"
            ."'','','','&amp;category=".$category."'"
            .");return false;\"  title='Add ".$this->_cp['filter_type']." for ".$category." category&hellip;'>"
            ."[ICON]11 11 1188 [/ICON]</a>\n";
    }

    protected function drawErrorBlockLayoutMissing()
    {
        $this->_html.= "<b>Error:</b> There is no such Block Layout as '".$this->_cp['block_layout']."'";
    }

    protected function drawErrorNoRecords()
    {
        $this->_html.= "No records available to display.";
    }

    protected function drawTabs()
    {
        $this->_html.=  \HTML::draw_section_tabs(
            $this->_tabs,
            $this->_safe_ID,
            $this->_selected_section
        );
    }


    protected function render()
    {
        return
            "<div id=\"".$this->_safe_ID."\">\n"
            .$this->Obj->convert_Block_Layout($this->ObjBlockLayout->record['listings_panel_header'])
            .$this->_html
            .$this->Obj->convert_Block_Layout($this->ObjBlockLayout->record['listings_panel_footer'])
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
        $this->setupLoadBlockLayout();
        $this->setupLoadUserRights();
        $this->setupLoadPopupSpecs();
        $this->setupInitializeObject();
        $this->setupLoadSystemIDs();
        $this->setupLoadRecords();
        $this->setupLoadCategories();
        $this->setupLoadTabs();
    }

    protected function setupLoadBlockLayout()
    {
        if ($this->ObjBlockLayout = parent::setupLoadBlockLayout($this->_cp['block_layout'])) {
            $this->ObjBlockLayout->draw_css_include('listings');
        }
    }

    protected function setupLoadCategories()
    {
        if (!$this->records) {
            return;
        }
        $this->_categories = array();
        foreach ($this->records as $record) {
            $this->_categories[$record['cat']] = $record['cat_label'];
        }
    }

    protected function setupInitializeObject()
    {
        $type =                     '\\'.$this->_cp['filter_type'];
        $this->Obj =               new $type;
        $args = array(
            '_cp' =>                    $this->_cp,
            '_current_user_rights' =>   $this->_current_user_rights,
            '_block_layout' =>          $this->ObjBlockLayout->record,
            '_mode' =>                  'list',
            '_safe_ID' =>               $this->_safe_ID
        );
        $this->Obj->_set_multiple($args);
    }

    protected function setupLoadPopupSpecs()
    {
        if (!$this->_current_user_rights['canEdit']) {
            return;
        }
        switch ($this->_cp['filter_type']){
            case 'Article':
                $this->popupForm =  'articles';
                break;
            case 'Product':
                $this->popupForm =  'product';
                break;
        }
        $this->popupSize =    get_popup_size($this->popupForm);
    }

    protected function setupLoadRecords()
    {
        switch ($this->_cp['results_order']) {
            case "latest":
                $this->_order = "`date` DESC";
                break;
            default:
                $this->_order = false;
                break;
        }
        $args = array(
            'category_list' =>      $this->_cp['filter_category_list'],
            'category_master' =>    $this->_cp['filter_category_master'],
            'systemIDs_csv' =>      $this->_systemIDs_csv,
            'limit_per_category' => $this->_cp['results_limit_per_category'],
            'order' =>              $this->_order
        );
        $this->records = $this->Obj->get_n_per_category($args);
    }

    protected function setupLoadSystemIDs()
    {
        $this->_systemIDs_csv = \System::get_IDs_for_URLs($this->_cp['filter_sites_list']);
    }

    protected function setupLoadTabs()
    {
        if (!$this->records) {
            return;
        }
        $this->_tabs = array();
        $this->_category_arr = array();
        foreach ($this->_categories as $value => $label) {
            $this->_category_arr[] = $value;
            $this->_tabs[] = array(
                'ID' =>       $value,     // Used by HTML::draw_section_tabs()
                'value' =>    $value,     // used in BL tag
                'label' =>    $label
            );
        }
        $temp = get_var('selected_section');
        $this->_selected_section = (in_array($temp, $this->_category_arr) ? $temp : $this->_category_arr[0]);
    }


    public static function getVersion()
    {
        return VERSION_COMPONENT_NS_CATEGORY_TABBER;
    }
}
