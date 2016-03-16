<?php
namespace Component;

/*
Version History:
  1.0.3 (2016-03-15)
    1) SearchWordCloud::setupLoadText() now provides filter_... prefixed parameters for all filters
*/
class SearchWordCloud extends Base
{
    const VERSION = '1.0.3';

    protected $filtered =   array();
    protected $maxMatches = 0;
    protected $text =       array();
    protected $words =      array();

    public function __construct()
    {
        $this->_ident =            "search_word_cloud";
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
            'min_characters' =>     array(
                'match' =>      'range|1,n',
                'default' =>    '4',
                'hint' =>       'Minimum number of characters for words shown'
            ),
            'max_matches' =>        array(
                'match' =>      'range|1,n',
                'default' =>    '1000',
                'hint' =>       'Maximum number of matches for words shown'
            ),
            'link_path' =>     array(
                'match' =>      '',
                'default' =>    '/search_results/',
                'hint' =>       'URL to prefix all linked words with'
            ),
            'min_matches' =>        array(
                'match' =>      'range|1,n',
                'default' =>    '5',
                'hint' =>       'Minimum number of matches for words shown'
            ),
            'summary_show' =>       array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'top_n' =>              array(
                'match' =>      'range|0,n',
                'default' =>    '0',
                'hint' =>       'Just show the top n matches - or 0 to show everything'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        foreach ($this->filtered as $word => $count) {
            $this->_html.=
                 "<a href=\""
                .BASE_PATH.trim($this->_cp['link_path'], '/')
                ."?search_text=".$word
                .($this->_cp['filter_type']!=='' ?
                    "&amp;search_type=".strtolower($this->_cp['filter_type'])
                 :
                    ""
                 )
                ."\""
                ." style=\"font-size:".(int)(500*$count/$this->maxMatches)."%;"
                ."color:"
                .get_color_for_weight(
                    100*$count/$this->maxMatches,
                    $this->_cp['colour_min'],
                    $this->_cp['colour_max']
                )
                ."\""
                ." title=\"".$word." (".$count.")\">"
                .$word
                ."</a> ";
        }
        if ($this->_cp['summary_show']==='1') {
            $this->_html.=
                 "<p>Total: ".count($this->filtered)."</p>\n"
                ."<p>Criteria used:<br />\n"
                ."Minimum letters per word: ".$this->_cp['min_characters']." &nbsp; "
                ."Minimum number of matches: ".$this->_cp['min_matches']." &nbsp; "
                ."Maximum number of matches: ".$this->_cp['max_matches']."</p>";
        }
        return $this->_html;
    }
    
    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadText();
        $this->setupLoadWords();
        $this->setupFilterWords();
    }

    protected function setupLoadText()
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
        $records = $Obj->get_records(
            array(
                'byRemote' =>               false,
                'filter_category' =>        $this->_cp['filter_category_list'],
                'filter_category_master' => $this->_cp['filter_category_master'],
                'filter_has_video' =>       $this->_cp['filter_has_video'],
                'filter_important' =>       $this->_cp['filter_important'],
                'filter_memberID' =>        $this->_cp['filter_memberID'],
                'filter_personID' =>        $this->_cp['filter_personID']
            )
        );
        foreach ($records['data'] as $record) {
            $this->text[] = $record['content_text'];
        }
    }
    
    protected function setupLoadWords()
    {
        $wc = html_entity_decode(implode(" ", $this->text));
        $wc = trim(str_replace("><", "> <", $wc));
        $wc = strip_tags($wc);
        $wc = preg_replace("/\s\s+/", " ", $wc);
        $wc = str_replace(array("&#39;"), array("'"), $wc);
        $wc = html_entity_decode($wc);
        $wc = trim(preg_replace("/&hellip;|&ldquo;|&lsquo;|&mdash;|&ndash;|&rdquo;|&rsquo;|&trade;/", " ", $wc));
        $wc = strToLower($wc);
        # remove 'words' that don't consist of alphanumerical characters or punctuation
        $pattern = "#[^(\w|\d|\'|\"|\.|\!|\?|;|,|\\|\/|\-|:|\&|@)]+#";
        $wc = trim(preg_replace($pattern, " ", $wc));
        # remove one-letter 'words' that consist only of punctuation
        $wc = trim(preg_replace("#\s*[(\'|\"|\!|\?|;|,|\\|\/|\-|_|:|\&)]\s*#", " ", $wc));
        # remove superfluous whitespace
        $wc = preg_replace("/\s\s+/", " ", $wc);
        # split string into an array of words
        $wc = explode(" ", $wc);
        # Trim periods at start and end of words - preserves email addresses and website URLs
        foreach ($wc as &$word) {
            $word = trim($word, '.');
        }
        # remove empty elements
        $wc = array_filter($wc);
        $this->words = array_count_values($wc);
    }

    protected function setupFilterWords()
    {
        $filtered = array();
        foreach ($this->words as $word => $count) {
            if (
                !is_numeric(substr($word, 0, 1)) &&
                strlen($word)>=$this->_cp['min_characters'] &&
                $count>=$this->_cp['min_matches'] &&
                $count<=$this->_cp['max_matches']
                ) {
                    $filtered[$word] = $count;
                if ($count>$this->maxMatches) {
                    $this->maxMatches = $count;
                }
            }
        }
        if ($this->_cp['top_n']!=='0') {
            $top_n = $this->_cp['top_n'];
            natsort($filtered);
            $filtered = array_reverse($filtered);
            $filtered2 = array();
            foreach ($filtered as $word => $count) {
                if ($top_n>0) {
                    $filtered2[$word] = $count;
                }
                $top_n--;
            }
            $filtered = $filtered2;
        }
        ksort($filtered);
        $this->filtered = $filtered;
    }
}
