<?php
namespace Component;
/*
Version History:
  1.0.1 (2016-03-15)
    1) LatestYoutube::setupLoadLatestYoutube() now provides filter_... prefixed parameters for all filters
*/
class LatestYoutube extends Base
{
    const VERSION = '1.0.1';

    protected $youtubeURL;

    public function __construct()
    {
        $this->_ident =            "latest_youtube";
        $this->_parameter_spec =   array(
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
            'height' =>         array(
                'match' =>      'range|1,n',
                'default' =>    '300',
                'hint' =>       '|1..n'
            ),
            'results_order' =>            array(
                'match' =>      'enum|date,date_a,date_d_name_a,date_d_title_a,name,title',
                'default' =>    'date',
                'hint' =>       'date|date_a|date_d_title_a|date_d_name_a|name|title'
            ),
            'width' =>         array(
                'match' =>      'range|1,n',
                'default' =>    '400',
                'hint' =>       '|1..n'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        try {
            $this->setup($instance, $args, $disable_params);
        } catch (\Exception $e) {
            $this->drawControlPanel(true);
            $this->_msg.= $e->getMessage();
            $this->drawStatus();
            return $this->_html;
        }
        $this->drawControlPanel(true);
        $this->_html.="[youtube: ".$this->youtubeURL."|".$this->_cp['width']."|".$this->_cp['height']."]";
        return $this->_html;
    }

    protected function setupLoadLatestYoutube()
    {
        $this->Article = new \Article();
        $results = $this->Article->get_records(
            array(
                'filter_category' =>        $this->_cp['filter_category_list'],
                'filter_category_master' => $this->_cp['filter_category_master'],
                'filter_has_video' =>       true,
                'filter_important' =>       $this->_cp['filter_important'],
                'filter_memberID' =>        $this->_cp['filter_memberID'],
                'filter_personID' =>        $this->_cp['filter_personID'],
                'results_limit' =>          1,
                'results_offset' =>         0,
                'results_order' =>          $this->_cp['results_order']
            )
        );
        if (isset($results['data'][0])) {
            $this->youtubeURL = $results['data'][0]['video'];
        }
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadLatestYoutube();
    }
}
