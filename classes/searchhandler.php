<?php
/*
Version History:
  1.0.0 (2016-02-10)
    1) Initial release
*/
class SearchHandler extends Base
{
    const VERSION = '1.0.0';

    protected static $config = array(
        'highlight' =>  true,
        'recursive' =>  false,
        'search_in' =>  array('html', 'htm'),
        'search_dir' => '.',
        'side_chars' => 15
    );
    protected $files =              array();
    protected $mode =               '';
    protected $results =            array();
    protected $searchTerm =         '';
    protected $searchTermLength =   0;

    public function __construct()
    {
    }

    public function draw()
    {
        $this->setup();
        if ($this->mode=='page') {
            print
                 "<!DOCTYPE HTML>\n"
                ."<html lang=\"en-US\" class=\"search-frame\">\n"
                ."<head>\n"
                ."  <title>Search results</title>\n"
                ."  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n"
                ."  <link rel=\"stylesheet\" href=\"../css/search.css\">\n"
                ."<!--[if lt IE 9]>\n"
                ."<html class=\"lt-ie9\">\n"
                ."  <script src=\"js/html5shiv.js\"></script>\n"
                ."  <script src=\"js/selectivizr-min.js\"></script>\n"
                ."  <![endif]-->\n"
                ."</head>\n"
                ."<body>\n"
                ."<script>\n"
                ."(function(){\n"
                ."  document.body.onload=resize;\n"
                ."  window.onresize=resize;\n"
                ."  function resize(){\n"
                ."    parent._resize(document.getElementById('search-results').offsetHeight);\n"
                ."  }\n"
                ."})()\n"
                ."</script>";
        }
        print
             "<div id=\"search-results\">\n"
            ."  <ol class=\"search_list\">\n";
        $sum_of_results = 0;
        $match_count = 0;
        for ($i=0; $i < count($this->results); $i++) {
            if (!empty($this->results[$i]['search_result'][0]) || $this->results[$i]['search_result'][0] !== '') {
                if (isset($this->results[$i]['file_name'])) {
                    $match_count++;
                    $sum_of_results+=count($this->results[$i]['search_result']);
                    if ($this->mode=='live') {
                        if ($i > 2) {
                            // // don't add the result - we have enough
                        } else {
                            print
                                 "    <li class=\"result-item\">\n"
                                ."      <a target=\"_top\" href=\"".$this->results[$i]['file_name'][0]."\""
                                ." class=\"search_link\">\n"
                                ."        <h4 class=\"search_title\">".$this->results[$i]['page_title'][0]."</h4>\n"
                                ."        <p>...".$this->results[$i]['search_result'][0]."...</p>\n"
                                ."      </a>\n"
                                ."    </li>\n";
                        }
                    } else {
                        print
                             "    <li class=\"result-item\">\n"
                            ."      <h4 class=\"search_title\">\n"
                            ."        <a target=\"_top\" href=\"".$this->results[$i]['file_name'][0]."\""
                            ." class=\"search_link\">"
                            .$this->results[$i]['page_title'][0]
                            ."</a>\n"
                            ."      </h4>\n"
                            ."      <p>...".$this->results[$i]['search_result'][0]."...</p>\n"
                            ."      <p class=\"match\"><em>Terms matched: ".count($this->results[$i]['search_result'])
                            ." - URL: ".$this->results[$i]['file_name'][0]."</em></p>\n"
                            ."    </li>\n";
                    }
                }
            }
        }

        if ($match_count == 0) {
            print
                 "    <li>\n"
                ."      <h4 class=\"search_error\">No results found for "
                ."<span class=\"search\">".$this->searchTerm."</span>"
                ."      </h4>\n"
                ."    </li>";
        }
        if ($this->mode=='live' && $match_count > 0) {
            print
                 "    <li>\n"
                ."      <button type=\"submit\">"
                .$sum_of_results
                .($sum_of_results < 2 ? " result on " : " results on ")
                .$match_count
                .($match_count < 2 ? " page." : " pages.")
                ."</button>\n"
                ."    </li>\n";
        }
        print
             "  </ol>\n"
            ."</div>";
        if ($this->mode=='page') {
            print
                 "</body>\n"
                ."</html>\n";
        }
    }

    public function setup()
    {
        if (!isset($_GET['s'])) {
            die('You must define a search term!');
        }
        $this->mode = (isset($_GET['liveSearch']) && $_GET['liveSearch'] == "true" ? 'live' : 'page');
        $this->searchTerm =          mb_strtolower($_GET['s'], 'UTF-8');
        $this->searchTerm =          preg_replace('/^\/$/', '"/"', $this->searchTerm);
        $this->searchTermLength =   strlen($this->searchTerm);
        if (!strlen($this->searchTerm)) {
            die();
        }
        $this->files =          static::listFiles(static::$config['search_dir']);
        $file_count =           0;
        foreach ($this->files as $file) {
            if (0 == filesize($file)) {
                continue;
            }
            $contents = file_get_contents($file);
            preg_match("/\<title\>(.*)\<\/title\>/", $contents, $page_title); //getting page title
            if (preg_match("#\<body.*\>(.*)\<\/body\>#si", $contents, $body_content)) {
                //getting content only between <body></body> tags
                $clean_content = strip_tags($body_content[0]); //remove html tags
                $clean_content = preg_replace('/\s+/', ' ', $clean_content);
                //remove duplicate whitespaces, carriage returns, tabs, etc
                //$found = static::strposRecursive($clean_content, $this->searchTerm);
                $found = static::strposRecursive(mb_strtolower($clean_content, 'UTF-8'), $this->searchTerm);
                if (!isset($this->results[$file_count])) {
                    $this->results[$file_count] = array();
                }
                if (!isset($this->results[$file_count]['page_title'])) {
                    $this->results[$file_count]['page_title'] = array();
                }
                if (isset($page_title[1])) {
                    $this->results[$file_count]['page_title'][] = $page_title[1];
                }
                $this->results[$file_count]['file_name'][] = BASE_PATH.substr($file, strlen('./'));
            }
            if (isset($found) && !empty($found)) {
                for ($z = 0; $z < count($found[0]); $z++) {
                    $pos = $found[0][$z][1];
                    $side_chars = static::$config['side_chars'];
                    if ($pos < static::$config['side_chars']) {
                        $side_chars = $pos;
                        if ($this->mode=='live') {
                            $pos_end = static::$config['side_chars'] + $this->searchTermLength+15;
                        } else {
                            $pos_end = static::$config['side_chars']*9 + $this->searchTermLength;
                        }
                    } else {
                        if ($this->mode=='live') {
                            $pos_end = static::$config['side_chars'] + $this->searchTermLength+15;
                        } else {
                            $pos_end = static::$config['side_chars']*9 + $this->searchTermLength;
                        }
                    }
                    $pos_start = $pos - $side_chars;
                    $str = substr($clean_content, $pos_start, $pos_end);
                    $result = preg_replace('#'.$this->searchTerm.'#ui', '<span class="search">\0</span>', $str);
                    $this->results[$file_count]['search_result'][] = $result;
                }
            } else {
                $this->results[$file_count]['search_result'][] = '';
            }
            $file_count++;
        }
        //Sort final result
        $search_result = array();
        foreach ($this->results as $key => $row) {
            $search_result[$key]  = $row['search_result'];
        }
        array_multisort($search_result, SORT_DESC, $this->results);
    }

    protected static function getFileExtension($filename)
    {
        $parts = explode(".", $filename);
        return (is_array($parts) && count($parts) > 1 ? array_pop($parts) : '');
    }

    //lists all the files in the directory given (and sub-directories if it is enabled)
    protected static function listFiles($dir)
    {
        $result = array();
        if (is_dir($dir) && $dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!($file == '.' || $file == '..')) {
                    $file = $dir.'/'.$file;
                    if (is_dir($file) && static::$config['recursive'] == true && $file != './.' && $file != './..') {
                        $result = array_merge($result, static::listFiles($file, static::$config));
                    } elseif (!is_dir($file)) {
                        if (in_array(static::getFileExtension($file), static::$config)) {
                            $result[] = $file;
                        }
                    }
                }
            }
        }
        return $result;
    }

    protected static function strposRecursive($haystack, $needle)
    {
        if (stripos($haystack, $needle, 0) === false) {
            return array();
        }
        $results = array();
        $pattern = '/'.$needle.'/ui';
        preg_match_all($pattern, $haystack, $results, PREG_OFFSET_CAPTURE);
        return $results;
    }
}
