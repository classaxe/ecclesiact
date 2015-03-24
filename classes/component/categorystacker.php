<?php
namespace Component;

define("VERSION_NS_COMPONENT_CATEGORY_STACKER", "1.0.4");
/*
Version History:
  1.0.4 (2015-03-14)
    1) Moved in here from class.component_category_stacker.php
    2) Extensivly refactored
    3) Now fully PSR-2 Compliant

*/

class CategoryStacker extends Base
{
    protected $Obj;
    protected $categories = array();
    protected $popupForm;
    protected $popupSize;

    public function __construct()
    {
        global $system_vars;
        $this->_ident =             "category_stacker";
        $this->_parameter_spec = array(
            'author_show' =>                array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'category_show' =>              array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_char_limit' =>         array(
                'match' =>      'range|0,n',
                'default' =>    '0',
                'hint' =>       '0..n'
            ),
            'content_plaintext' =>          array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_show' =>               array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'content_use_summary' =>        array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'date_show' =>                  array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'extra_fields_list' =>          array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list format: field|label|group,field|label|group...'
            ),
            'filter_category_list' =>       array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list of categories'
            ),
            'filter_sites_list' =>          array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list of site URLs'
            ),
            'filter_type' =>                array(
                'match' =>      'enum|Article,Product',
                'default' =>    'Article',
                'hint' =>       'Article|Product'
            ),
            'links_point_to_URL' =>         array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1 - If there is a URL, both title and thumbnails links go to it'
            ),
            'more_link_text' =>             array(
                'match' =>      '',
                'default' =>    '(More)',
                'hint' =>       'text for more link'
            ),
            'related_show' =>               array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'results_limit_per_category' => array(
                'match' =>      'range|1,n',
                'default' =>    '1',
                'hint' =>       'Max articles per tab'
            ),
            'results_order' =>              array(
                'match' =>      'enum|latest,title',
                'default' =>    'latest',
                'hint' =>       '|latest|title'
            ),
            'subtitle_show' =>              array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'thumbnail_at_top' =>           array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       ''
            ),
            'thumbnail_image' =>            array(
                'match' =>      'enum|s,m,l',
                'default' =>    's',
                'hint' =>       's|m|l - Choose only \'s\' unless Multiple-Thumbnails option is enabled'
            ),
            'thumbnail_link' =>             array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'thumbnail_show' =>             array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'thumbnail_width' =>            array(
                'match' =>      'range|1,n',
                'default' =>    '',
                'hint' =>       '|1..n'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->drawHtml();
        return $this->_html;
    }

    protected function drawHtml()
    {
        if (!$this->records) {
            $this->_html .=
                __CLASS__."::".__FUNCTION__."()<br />\nNo records available to display.";
            return;
        }
        $args =  array(
            'author_show' =>          $this->_cp['author_show'],
            'category_show' =>        $this->_cp['category_show'],
            'content_plaintext' =>    $this->_cp['content_plaintext'],
            'content_char_limit' =>   $this->_cp['content_char_limit'],
            'content_show' =>         $this->_cp['content_show'],
            'content_use_summary' =>  $this->_cp['content_use_summary'],
            'extra_fields_list' =>    $this->_cp['extra_fields_list'],
            'date_show' =>            $this->_cp['date_show'],
            'links_point_to_URL' =>   $this->_cp['links_point_to_URL'],
            'more_link_text' =>       $this->_cp['more_link_text'],
            'related_show' =>         $this->_cp['related_show'],
            'subtitle_show' =>        $this->_cp['subtitle_show'],
            'thumbnail_at_top' =>     $this->_cp['thumbnail_at_top'],
            'thumbnail_image' =>      $this->_cp['thumbnail_image'],
            'thumbnail_link' =>       $this->_cp['thumbnail_link'],
            'thumbnail_show' =>       $this->_cp['thumbnail_show'],
            'thumbnail_width' =>      $this->_cp['thumbnail_width']
        );
        $this->_html.= "<div id=\"".$this->_safe_ID."\">\n";
        foreach ($this->categories as $value => $text) {
            $these = array();
            foreach ($this->records as $record) {
                if (strToLower($record['cat'])==strToLower($value)) {
                    $these[] = $record;
                }
            }
            if ($this->_isAdmin) {
                $this->drawAddIcon($value);
            }
            $this->_html.= $this->Obj->draw_from_recordset($these, $args);
        }
        $this->_html.= "</div>";
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadUserRights();
        $this->setupLoadPopupSpecs();
        $this->setupLoadRecords();
        $this->setupLoadCategories();
    }

    protected function setupLoadCategories()
    {
        foreach ($this->records as $record) {
            $this->categories[$record['cat']] = $record['cat'];
        }
        $Obj = new \Category;
        $this->categories = $Obj->get_labels_for_values(
            "'".implode("','", array_keys($this->categories))."'",
            "'".$this->_cp['filter_type']." category'"
        );
    }

    protected function setupLoadPopupSpecs()
    {
        switch ($this->_cp['filter_type']){
            case 'Article':
                $this->Obj =        new \Article;
                $this->popupForm =  'articles';
                break;
            case 'Product':
                $this->Obj =        new \Product;
                $this->popupForm =  'product';
                break;
        }
        if ($this->_isAdmin) {
            $this->popupSize =    get_popup_size($this->popupForm);
        }
    }

    protected function setupLoadRecords()
    {
        $systemIDs_csv =    \System::get_IDs_for_URLs($this->_cp['filter_sites_list']);
        switch ($this->_cp['results_order']) {
            case "latest":
                $order = "`date` DESC";
                break;
            case "title":
                $order = "`title` ASC";
                break;
            default:
                $order = false;
                break;
        }
        $args = array(
            'category_list' =>      $this->_cp['filter_category_list'],
            'systemIDs_csv' =>      $systemIDs_csv,
            'limit_per_category' => $this->_cp['results_limit_per_category'],
            'order' =>              $order
        );
        $this->records = $this->Obj->get_n_per_category($args);

    }

    protected function drawAddIcon($category)
    {
        $this->_html.=
             "<a class='fl' href=\"#\" onclick=\"details("
            ."'".$this->popupForm."','','".$this->popupSize['h']."','".$this->popupSize['w']."',"
            ."'','','','&amp;category=".$category."'"
            .");return false;\"  title='Add ".$this->_cp['filter_type']." for ".$category." category&hellip;'>"
            ."[ICON]11 11 1188 [/ICON]</a>\n";
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_CATEGORY_STACKER;
    }
}
