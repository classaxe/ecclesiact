<?php
namespace Component;

/*
Version History:
  1.0.3 (2016-12-31)
    1) SearchCategoryList::setupLoadCategories() now uses newly named getFilteredSortedAndPagedRecords() method
*/
class SearchCategoryList extends Base
{
    const VERSION = '1.0.3';

    protected $categories =      array();

    public function __construct()
    {
        $this->_ident =            "search_category_list";
        $this->_parameter_spec = array(
            'colour_min' =>         array(
                'match' =>      'hex3|#808080',
                'default' =>    '#808080',
                'hint' =>       'Hex colour code for minimum significance'
            ),
            'colour_max' =>         array(
                'match' =>      'hex3|#404040',
                'default' =>    '#800000',
                'hint' =>       'Hex colour code for maximum significance'
            ),
            'filter_category_list' =>     array(
                'match' =>      '',
                'default' =>    '*',
                'hint' =>       '*|CSV value list'
            ),
            'filter_category_master' =>   array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Optionally INSIST on this category'
            ),
            'filter_has_video' =>         array(
                'match' =>      'enum|,0,1',       'default' =>  '',
                'hint' =>       'Blank to ignore, 0 for no video, 1 for with video'
            ),
            'filter_important' =>         array(
                'match' =>      'enum|,0,1',       'default' =>  '',
                'hint' =>       'Blank to ignore, 0 for not important, 1 for important'
            ),
            'filter_memberID' =>          array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Community Member to restrict by that criteria'
            ),
            'filter_personID' =>          array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Person to restrict by that criteria'
            ),
            'filter_type' =>          array(
                'match' =>      'enum|,Article,Event,Podcast',
                'default' =>    '',
                'hint' =>       'Type of object to limit results to - or blank for anything'
            ),
            'link_friendly' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1 - sets URL to friendly mode used with listings on pages having path extender flag'
            ),
            'link_path' =>     array(
                'match' =>      '',
                'default' =>    '/search_results/',
                'hint' =>       'URL to prefix all linked words with'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->_html.= "<ul id='".$this->_safe_ID."'>\n";
        foreach ($this->categories as $word => $count) {
            if ($this->_cp['link_friendly']) {
                $url =
                    BASE_PATH.trim($this->_cp['link_path'], '/')
                   ."/category/".$word;
            } else {
                $url =
                     BASE_PATH.trim($this->_cp['link_path'], '/')
                    ."?search_categories=".$word
                    .($this->_cp['filter_type']!=='' ?
                        "&amp;search_type=".strtolower($this->_cp['filter_type'])
                        :
                        ""
                    );
            }
            $this->_html.=
                 "    <li><a href=\"".$url."\""
                ." style=\""
                ."color:".get_color_for_weight(
                    100*$count/$this->maxMatches,
                    $this->_cp['colour_min'],
                    $this->_cp['colour_max']
                )
                ."\""
                ." title=\"".$word." (".$count.")\">"
                .$word
                ." (".$count.")"
                ."</a></li>\n";
        }
        $this->_html.= "</ul>\n";
        return $this->_html;
    }
    
    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadCategories();
    }

    protected function setupLoadCategories()
    {
        switch ($this->_cp['filter_type']) {
            case 'Article':
            case 'Event':
            case 'Podcast':
                $type = '\\'.$this->_cp['filter_type'];
                break;
            default:
                $type = '\\Posting';
                break;
        }
        $Obj = new $type;
        $records = $Obj->getFilteredSortedAndPagedRecords(
            array(
                'byRemote' =>                   false,
                'filter_category_list' =>       $this->_cp['filter_category_list'],
                'filter_category_master' =>     $this->_cp['filter_category_master'],
                'filter_has_video' =>           $this->_cp['filter_has_video'],
                'filter_important' =>           $this->_cp['filter_important'],
                'filter_memberID' =>            $this->_cp['filter_memberID'],
                'filter_personID' =>            $this->_cp['filter_personID']
            )
        );
        $found = array();
        foreach ($records['data'] as $record) {
            $categories = explode(',', $record['category']);
            foreach ($categories as $category) {
                if (trim($category)!=='') {
                    $found[] = trim($category);
                }
            }
        }
        $this->categories = array_count_values($found);
        $this->maxMatches = max($this->categories);
        ksort($this->categories);
    }
}
