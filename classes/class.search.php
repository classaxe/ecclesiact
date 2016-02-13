<?php
/*
Version History:
  1.0.12 (2016-02-12)
    1) Refactored for PSR-2

*/
class Search extends Component_Base
{
    const VERSION = '1.0.12';

    protected $systemIDs_csv;

    public function __construct()
    {
        global $system_vars;
        $this->_ident =             'search_results';
        $this->_parameter_spec = array(
            'controls' =>                 array(
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'controls_width' =>           array(
                'default' =>    '570',
                'hint' =>       '0..n'
            ),
            'name_search' =>              array(
                'default' =>    '0',
                'hint' =>       '0|1 - matches name for postings, itemCode for products'
            ),
            'name_search_label' =>        array(
                'default' =>    'Name',
                'hint' =>'Text for label for name column in results'
            ),
            'page_limit' =>               array(
                'default' =>    '20',
                'hint' =>       '0..n'
            ),
            'search_articles' =>          array(
                'default' =>    System::has_feature('Articles') ? 1 :0,
                'hint' =>       '0|1'
            ),
            'search_events' =>            array(
                'default' =>    System::has_feature('Events') ?   1 :0,
                'hint' =>       '0|1'
            ),
            'search_gallery_images' =>    array(
                'default' =>    System::has_feature('Gallery-Images') ? 1 :0,
                'hint' =>       '0|1'
            ),
            'search_jobs' =>              array(
                'default' =>    System::has_feature('Jobs') ? 1 :0,
                'hint' =>       '0|1'
            ),
            'search_news' =>              array(
                'default' =>    System::has_feature('News') ? 1 :0,
                'hint' =>       '0|1'
            ),
            'search_pages' =>             array(
                'default' =>    1,
                'hint' =>       '0|1'
            ),
            'search_podcasts' =>          array(
                'default' =>    System::has_feature('Podcasting') ? 1 :0,
                'hint' =>       '0|1'
            ),
            'search_products' =>          array(
                'default' =>    System::has_feature('E-Commerce') ? 1 :0,
                'hint' =>       '0|1'
            ),
            'sites_list' =>               array(
                'default' =>    $system_vars['URL'],
                'hint' =>       'CSV list of local site URLs'
            ),
            'sortby' =>                   array(
                'default' =>    'relevance',
                'hint' =>       'date|relevance|title'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        global $search_categories, $search_date_end, $search_date_start, $search_keywords, $search_name, $search_text;
        global $search_offset, $search_sites, $search_type;
        global $system_vars;

        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $args = array(
            'search_categories' =>           $search_categories,
            'search_communityID' =>          0,
            'search_date_end' =>             $search_date_end,
            'search_date_start' =>           $search_date_start,
            'search_keywords' =>             $search_keywords,
            'search_keywordIDs' =>           ($search_keywords ? Keyword::get_keywordIDs_list_by_keywords_list($search_keywords) : ""),
            'search_memberID' =>             0,
            'search_name' =>                 ($this->_cp['name_search'] ? addslashes($search_text) : ''),
            'search_name_label' =>           $this->_cp['name_search_label'],
            'search_offset' =>               $search_offset,
            'search_results_page_limit' =>   $this->_cp['page_limit'],
            'search_results_sites_list' =>   $this->_cp['sites_list'],
            'search_sites' =>                $search_sites,
            'search_results_sortBy' =>       $this->_cp['sortby'],
            'search_text' =>                 addslashes($search_text),
            'search_type' =>                 $search_type,
            'systemIDs_csv' =>               $this->systemIDs_csv
        );
        $search_results = $this->getResults($args, $this->_cp);
        $this->_html.= $this->drawResults($search_results, $args, $this->_cp);
        return $this->_html;
    }

    protected function drawControls()
    {
        global $search_categories, $search_date_end, $search_date_start, $search_keywords, $search_name, $search_sites, $search_text, $search_type;
        global $system_vars;
        $Obj_RC = new Report_Column;
        $search_keywordIDs =                    Keyword::get_keywordIDs_list_by_keywords_list($search_keywords);
        $systemIDs_csv =                        System::get_IDs_for_URLs($this->_cp['sites_list']);
        $global_date_range =                    System::get_global_date_range($systemIDs_csv);
        Output::push(
            "javascript",
            "// Used with Search Controls:\n"
            ."_global_date_range_min = '".$global_date_range['min']."';\n"
            ."_global_date_range_max = '".$global_date_range['max']."';\n"
        );
        $out =
        "<div style='border:1px solid #c0c0c0;width:auto;background:#e0e0e0'>"
        ."<table summary='Search Form'>"
        ."  <tr>\n"
        ."    <th class='txt_r va_t' style='width:120px;'>Text:</th>\n"
        ."    <td>".draw_form_field('_search_text', $search_text, 'text', $this->_cp['controls_width'])."</td>"
        ."  </tr>\n";
        if (System::has_feature('Categories')) {
            $list_types_arr = array();
            if ($this->_cp['search_articles']) {
                $list_types_arr[] = 'Article Category';
            }
            if ($this->_cp['search_events']) {
                $list_types_arr[] = 'Event Category';
            }
            if ($this->_cp['search_gallery_images']) {
                $list_types_arr[] = 'Gallery-Image Category';
            }
            if ($this->_cp['search_jobs']) {
                $list_types_arr[] = 'Job Category';
            }
            if ($this->_cp['search_news']) {
                $list_types_arr[] = 'News Category';
            }
            if ($this->_cp['search_podcasts']) {
                $list_types_arr[] = 'Podcast Category';
            }
            if ($this->_cp['search_products']) {
                $list_types_arr[] = 'Product Category';
            }
            $selectorSQL =    Category::get_selector_sql("'".implode("','", $list_types_arr)."'");
            $out.=
            "  <tr>\n"
             ."    <th class='txt_r va_t'>Categories:</th>\n"
             ."    <td>"
             .$Obj_RC->draw_selector_csv('_search_categories', $search_categories, $selectorSQL, $this->_cp['controls_width'], 35)
             ."</td>"
             ."  </tr>\n";
        }
        if (System::has_feature('Keywords')) {
            $selectorSQL = Keyword::get_selector_sql(false);
            $out.=
              "  <tr>\n"
             ."    <th class='txt_r va_t'>Tags:</th>\n"
             ."    <td>"
             .$Obj_RC->draw_selector_csv('_search_keywords', $search_keywords, $selectorSQL, $this->_cp['controls_width'], 35)
             ."</td>"
             ."  </tr>\n";
        }
        $out.=
        "  <tr>\n"
        ."    <th class='txt_r va_t'>Date Range:</th>\n"
        ."    <td>\n"
        ."      <div id='_search_date_start' class='fl'></div>\n"
        ."      <div class='fl txt_c' style='width:5em;font-weight:bold'>- to -</div>\n"
        ."      <div id='_search_date_end' class='fl'></div>\n"
        ."      <div class='clr_b'></div>\n"
        ."<script type='text/javascript'>search_setup_date_range()</script>"
        ."    </td>"
        ."  </tr>\n";
        Output::push(
            "javascript",
            "addEvent(window,\"load\",function(e){ date_selector_onchange('_search_date_start');date_selector_onchange('_search_date_end')});"
        );
        $show_everything_option =
        ($this->_cp['search_articles'] ? 1 : 0) +
        ($this->_cp['search_events'] ? 1 : 0) +
        ($this->_cp['search_gallery_images'] ? 1 : 0) +
        ($this->_cp['search_jobs'] ? 1 : 0) +
        ($this->_cp['search_news'] ? 1 : 0) +
        ($this->_cp['search_pages'] ? 1 : 0) +
        ($this->_cp['search_podcasts'] ? 1 : 0) +
        ($this->_cp['search_products'] ? 1 : 0) > 1 ? true : false;
        $out.=
        "  <tr>\n"
        ."    <th class='txt_r'>Items:</th>\n"
        ."    <td><select id='_search_type' class='formField' style='width:".($this->_cp['controls_width']+3)."px;'>\n"
        .($show_everything_option ?      "<option value='*' style='background-color:#e0ffe0'".($search_type=='*' ? " selected='selected'" : "").">Everything</option>\n" : "")
        .($this->_cp['search_articles'] ?       "<option value='article'".($search_type=='article' ? " selected='selected'" : "").">Articles only</option>\n" : "")
        .($this->_cp['search_events'] ?         "<option value='event'".($search_type=='event' ? " selected='selected'" : "").">Events only</option>\n" : "")
        .($this->_cp['search_gallery_images'] ? "<option value='gallery-image'".($search_type=='gallery-image' ? " selected='selected'" : "").">Gallery Images only</option>\n" : "")
        .($this->_cp['search_jobs'] ?           "<option value='job-posting'".($search_type=='job-posting' ? " selected='selected'" : "").">Job Postings only</option>\n" : "")
        .($this->_cp['search_news'] ?           "<option value='news-item'".($search_type=='news-item' ? " selected='selected'" : "").">News Items only</option>\n" : "")
        .($this->_cp['search_pages'] ?          "<option value='page'".($search_type=='page' ? " selected='selected'" : "").">Web Pages only</option>\n" : "")
        .($this->_cp['search_podcasts'] ?       "<option value='podcast'".($search_type=='podcast' ? " selected='selected'" : "").">Podcasts only</option>\n" : "")
        .($this->_cp['search_products'] ?       "<option value='product'".($search_type=='product' ? " selected='selected'" : "").">Products only</option>\n" : "")
        ."</select>\n</td>"
        ."  </tr>\n";
        $search_results_sites_list_csv = explode(",", $this->_cp['sites_list']);
        if (count($search_results_sites_list_csv)>1) {
            if ($search_sites=='') {
                $search_sites = $system_vars['URL'];
            }
            $search_sites_arr = explode(",", $search_sites);
            $out.=
             "  <tr>\n"
            ."    <th class='txt_r va_t'>Sites:</th>\n"
            ."    <td>"
            .draw_form_field('search_sites', $search_sites, 'hidden')
            ."<select class='formField'"
            ." style='width:".($this->_cp['controls_width']+3)."px;height:".(1.5*count($search_results_sites_list_csv))."em;'"
            ." id='search_sites_selector' multiple='multiple'>\n";
            foreach ($search_results_sites_list_csv as $URL) {
                $URL = trim($URL);
                if (substr($URL, strlen($URL)-1)=='/') {
      // Remove any trailing slash for now:
                    $URL = substr($URL, 0, strlen($URL)-1);
                }
                $URLs_arr_find[] = $URL;
                $URLs_arr_find[] = $URL."/";
            }

            $sql =
             "SELECT\n"
            ."  `textEnglish` AS `text`,\n"
            ."  `URL` AS `value`\n"
            ."FROM\n"
            ."  `system`\n"
            ."WHERE\n"
            ."  `URL` IN(\n"
            ."     \"".implode("\",\n     \"", $URLs_arr_find)."\"\n"
            ."  )\n"
            ."ORDER BY `textEnglish`";
    //        z($sql);die;
            $records = $this->get_records_for_sql($sql);
            foreach ($records as $record) {
                $out.=
                 "<option value=\"".$record['value']."\""
                .(in_array($record['value'], $search_sites_arr) ? " selected='selected'" : "").">"
                .$record['text']."</option>\n";
            }
            $out.=
             "</select>\n"
            ."</td>"
            ."  </tr>\n";
        }
        $out .=
        "  <tr>\n"
        ."    <td colspan='2' class='txt_c'><input type='button' onclick=\"this.value='Searching...';this.disabled=true;search_results_go()\" class='formButton' alt='Go' value='Search Again' /></td>"
        ."  </tr>\n"
        ."</table></div><br />"
        ;
        return $out;
    }

    public function drawResults($search_results, $args)
    {
        $search_categories =            (isset($args['search_categories']) ? $args['search_categories'] : "");
        $search_date_end =              (isset($args['search_date_end']) ? $args['search_date_end'] : "");
        $search_date_start =            (isset($args['search_date_start']) ? $args['search_date_start'] : "");
        $search_keywords =              (isset($args['search_keywords']) ? $args['search_keywords'] : "");
        $search_name =                  (isset($args['search_name']) ? $args['search_name'] : "");
        $search_name_label =            (isset($args['search_name_label']) ? $args['search_name_label'] : "");
        $search_results_sites_list =    (isset($args['search_results_sites_list']) ? $args['search_results_sites_list'] : "");
        $search_results_sortBy =        (isset($args['search_results_sortBy']) ? $args['search_results_sortBy'] : "relevance");
        $search_text =                  (isset($args['search_text']) ? $args['search_text'] : "");
        $category_arr =     explode(",", $search_categories);
        $category_count =   count($category_arr);
        $list_types_arr = array();
        if ($this->_cp['search_articles']) {
            $list_types_arr[] = 'Article Category';
        }
        if ($this->_cp['search_events']) {
            $list_types_arr[] = 'Event Category';
        }
        if ($this->_cp['search_jobs']) {
            $list_types_arr[] = 'Job Category';
        }
        if ($this->_cp['search_news']) {
            $list_types_arr[] = 'News Category';
        }
        if ($this->_cp['search_podcasts']) {
            $list_types_arr[] = 'Podcast Category';
        }
        if ($this->_cp['search_products']) {
            $list_types_arr[] = 'Product Category';
        }
        if (count($list_types_arr)) {
            $category_lookup_result =
            Category::get_labels_for_values(
                "'".implode("','", $category_arr)."'",
                "'".implode("','", $list_types_arr)."'"
            );
            $category_labels_array = array();
            foreach ($category_lookup_result as $value => $text) {
                $category_labels_array[] = $text;
            }
        }
        $keyword_arr =      explode(",", $search_keywords);
        $keyword_count =    count($keyword_arr);
        $out=
             "<div id='search_results'>\n"
            ."<h1>Search Results "
            .($search_text ? "for text <em>".stripslashes($search_text)."</em>" : "")
            .($search_keywords ?
                 ($search_text!=="" ||$search_name!=="" ? ", having" : " for")
                ." tag".($keyword_count==1 ? "" : "s")
                ." <em>".implode(", ", $keyword_arr)."</em>"
             :
                ""
            )
            .($search_categories ?
                 ($search_text || $search_keywords ? ", matching" : " for")
                ." categor".($category_count==1 ? "y" : "ies")
                ." <em>".implode(", ", $category_labels_array)."</em>"
             :
                ""
            )
            .($search_date_start || $search_date_end ?
                 ($search_text || $search_keywords || $search_categories ? "" : " ")
                .($search_date_start && $search_date_end && $search_date_start != $search_date_end?
                    " between <em>".$search_date_start."</em> and <em>".$search_date_end."</em>"
                 :
                    ""
                )
                .($search_date_start == $search_date_end ? " for <em>".$search_date_start."</em>" : "")
                .($search_date_start && !$search_date_end ? " from <em>".$search_date_start."</em>" : "")
                .(!$search_date_start && $search_date_end ? " up to <em>".$search_date_end."</em>" : "")
             :
                ""
            )
            ."</h1>\n"
            .($this->_cp['controls'] ? $this->drawControls($this->_cp) : "")
            ;
        if (
            (isset($search_results['article']) ?          $search_results['article']['count'] :       0) +
            (isset($search_results['event']) ?            $search_results['event']['count'] :         0) +
            (isset($search_results['gallery-image']) ?    $search_results['gallery-image']['count'] : 0) +
            (isset($search_results['job-posting']) ?      $search_results['job-posting']['count'] :   0) +
            (isset($search_results['news-item']) ?        $search_results['news-item']['count'] :     0) +
            (isset($search_results['page']) ?             $search_results['page']['count'] :          0) +
            (isset($search_results['podcast']) ?          $search_results['podcast']['count'] :       0) +
            (isset($search_results['product']) ?          $search_results['product']['count'] :       0)
            ==0
        ) {
            $out.="<p>Sorry - nothing matched your query.</p>";
        }
        if (isset($search_results['page']) && count($search_results['page'])) {
            $Obj = new Page;
            $out.= $Obj->draw_search_results($search_results['page']);
        }
        if (isset($search_results['product']) && count($search_results['product'])) {
            $Obj = new Product;
            $out.= $Obj->draw_search_results($search_results['product']);
        }
        if (isset($search_results['article']) && count($search_results['article']['results'])) {
            $Obj = new Article;
            $out.= $Obj->draw_search_results($search_results['article']);
        }
        if (isset($search_results['event']) && count($search_results['event'])) {
            $Obj = new Event;
            $out.= $Obj->draw_search_results($search_results['event']);
        }
        if (isset($search_results['gallery-image']) && count($search_results['gallery-image'])) {
            $Obj = new Gallery_Image;
            $out.= $Obj->draw_search_results($search_results['gallery-image']);
        }
        if (isset($search_results['news-item']) && count($search_results['news-item'])) {
            $Obj = new News_Item;
            $out.= $Obj->draw_search_results($search_results['news-item']);
        }
        if (isset($search_results['job-posting']) && count($search_results['job-posting'])) {
            $Obj = new Job_Posting;
            $out.= $Obj->draw_search_results($search_results['job-posting']);
        }
        if (isset($search_results['podcast']) && count($search_results['podcast'])) {
            $Obj = new Podcast;
            $out.= $Obj->draw_search_results($search_results['podcast']);
        }
        $out.= "</div>";
        return $out;
    }

    public function getResults($args)
    {
        $search_type =              (isset($args['search_type']) ? $args['search_type'] : "*");
        switch ($search_type) {
            case '*':
                $Obj_Posting =      new Posting;
                $out =              $Obj_Posting->get_search_results($args, $this->_cp);
                if ($this->_cp['search_pages']) {
                    $Obj_Page =         new Page;
                    $out['page'] =      $Obj_Page->get_search_results($args);
                }
                if ($this->_cp['search_products']) {
                    $Obj_Product =      new Product;
                    $out['product'] =   $Obj_Product->get_search_results($args);
                }
                break;
            case "page":
                $out = array();
                if ($this->_cp['search_pages']) {
                    $Obj_Page =         new Page;
                    $out['page'] =      $Obj_Page->get_search_results($args);
                }
                break;
            case "product":
                $out = array();
                if ($this->_cp['search_products']) {
                    $Obj_Product =      new Product;
                    $out['product'] =   $Obj_Product->get_search_results($args);
                }
                break;
            default:
                $Obj_Posting =      new Posting;
                $out =              $Obj_Posting->get_search_results($args, $this->_cp);
                break;
        }
        return $out;
    }

    protected function getSystemIDsCsv()
    {
        $search_sites = get_var('search_sites');
        if (!$search_sites) {
            $this->systemIDs_csv = SYS_ID;
            return;
        }
        $sites_csv = $search_sites;
        if ($sites_csv!=='') {
            $sites_arr =      array();
            $search_arr =     explode(",", $search_sites);
            $_search_arr =    explode(",", $sites_csv);
            foreach ($search_arr as $search) {
                if (in_array(trim($search), $search_arr)) {
                    $sites_arr[] =    trim($search);
                }
            }
            $sites_csv = implode(",", $sites_arr);
        }
        switch ($sites_csv) {
            case "":
                $this->systemIDs_csv = SYS_ID;
                break;
            default:
                $this->systemIDs_csv = System::get_IDs_for_URLs($sites_csv);
                break;
        }
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->getSystemIDsCsv();
    }
}