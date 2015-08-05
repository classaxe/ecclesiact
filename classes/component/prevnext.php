<?php
namespace Component;

  define("VERSION_NS_COMPONENT_PREVNEXT", "1.0.2");
/*
Version History:
  1.0.6 (2015-08-03)
    1) Moved here from class.component_nav_links.php
    2) Now PSR-2 Compliant
    3) Nav entity references now namespaced

*/
class PrevNext extends Base
{
    protected $_extra_links = array();
    protected $_nav_links =   array();
    protected $_links =       array();

    public function __construct()
    {
        $this->_ident =            "prev_next";
        $this->_parameter_spec =   array(
            'link_pages_list' =>      array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'csv list of pages containing links to be included in the sequence'
            ),
            'show_top_link' =>        array(
                'match' =>      '',
                'default' =>    '1',
                'hint' =>       'Whether or not to show top link'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        global $page_vars;
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $prev = false;
        $next = false;
        $path = (isset($_SERVER["REQUEST_URI"]) ? trim(urldecode($_SERVER["REQUEST_URI"]), '/') : "");
        for ($i=0; $i<count($this->_links); $i++) {
            $page_path = explode("/", $path);
            if (
            (isset($page_vars['path']) && $page_vars['path']=="//".trim($this->_links[$i]['url'], "/")."/") ||
            ($page_vars['page']!='' && substr($this->_links[$i]['url'], 8)==$page_vars['page']) ||
            $this->_links[$i]['url'] == $path ||
            $this->_links[$i]['url'] == ".".$path ||
            $this->_links[$i]['url'] == "./".$path ||
            $this->_links[$i]['url'] == "/".$path
            ) {
                if ($i>0) {
                    $prev = array(
                    'text' =>   $this->_links[$i-1]['text'],
                    'url' =>    $this->_links[$i-1]['url']
                    );
                }
                if ($i<count($this->_links)-1) {
                    $next = array(
                    'text' =>   $this->_links[$i+1]['text'],
                    'url' =>    $this->_links[$i+1]['url']
                    );
                }
            }
        }
        $link_arr = array();
        if ($prev) {
            $link_arr[] = "<a href=\"".$prev['url']."\" title=\"Back to ".$prev['text']."\"><b>Previous</b></a>";
        }
        if ($this->_cp['show_top_link']) {
            $link_arr[] = "<a href=\"#top\" title=\"Jump to top of this page\"><b>Top</b></a>";
        }
        if ($next) {
            $link_arr[] = "<a href=\"".$next['url']."\" title=\"Forward to ".$next['text']."\"><b>Next</b></a>";
        }
        if (count($link_arr)) {
            $this->_html .= "<span id=\"".$this->_safe_ID."\">[ ".implode(" | ", $link_arr)." ]</span>";
        }
        return $this->_html;
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLinksFromNav();
        $this->setupLinksFromPageListings();
        $this->setupLinksMergeLists();
    }

    protected function setupLinksFromNav()
    {
        $nav_list = substr(\Component_Sitemap::get_sitemap(true, '', true), 3);
        $nav_arr = explode("[!]", $nav_list);
        $urls_arr = array();
        foreach ($nav_arr as $nav) {
            $nav_entry = explode("[?]", $nav);
            if (count($nav_entry)==2) {
                if (!isset($urls_arr[$nav_entry[1]])) {
                    $urls_arr[$nav_entry[1]] = true;
                    if ($nav_entry[1]=='./' || $nav_entry[1]=='./?page=' || $nav_entry[1]=='./?page=home') {
                        $this->_nav_links[] =
                        array(
                        'text' =>     $nav_entry[0],
                        'url' =>      '/',
                        'matched' =>   0
                        );
                    } elseif (
                    substr($nav_entry[1], 0, 8)=='./?page=' ||
                    substr($nav_entry[1], 0, 7)=='/?page=' ||
                    (substr($nav_entry[1], 0, 3)!="./?" && substr($nav_entry[1], 0, 2)!="/?")
                    ) {
                        $this->_nav_links[] =
                        array(
                        'text' =>       $nav_entry[0],
                        'url' =>        $nav_entry[1],
                        'matched' =>    0
                        );
                    }
                }
            }
        }
    }

    protected function setupLinksFromPageListings()
    {
        if ($this->_cp['link_pages_list']==='') {
            return;
        };
        $alt_pages = explode(',', $this->_cp['link_pages_list']);
        $Obj_Page = new \Page;
        foreach ($alt_pages as $page) {
            if (!$ID = $Obj_Page->get_ID_by_path('//'.trim($page, '/').'/')) {
                continue;
            }
            $Obj_Page->_set_ID($ID);
            $content = $Obj_Page->get_field('content');
            $regexp = "<a\s[^>]*href=([\"\']??)([^\"\'>]*?)\\1[^>]*>(.*)<\/a>";
            if (preg_match_all("/".$regexp."/siU", $content, $matches)) {
                for ($i=0; $i<count($matches[2]); $i++) {
                    if (substr($matches[2][$i], 0, 4)!=='http' && substr($matches[2][$i], 0, 2)!=='//') {
                        $this->_extra_links[] = array(
                        'text' =>     $matches[3][$i],
                        'url' =>      $matches[2][$i],
                        'matched' =>  0
                        );
                    }
                }
            }
        }
    }

    protected function setupLinksMergeLists()
    {
        foreach ($this->_nav_links as $nav) {
            $matched = false;
            $this->_links[] = $nav;
            foreach ($this->_extra_links as $extra) {
                if ($extra['url'] === $nav['url']) {
                    $matched = true;
                    break;
                }
            }
            if ($matched) {
                foreach ($this->_extra_links as $extra) {
                    if ($matched) {
                        foreach ($this->_nav_links as $nav2) {
                            if ($extra['url'] === $nav2['url']) {
                                $matched = false;
                                break;
                            }
                        }
                    }
                    if ($matched) {
                        $this->_links[] = $extra;
                    } elseif ($extra['url'] === $nav['url']) {
                        $matched = true;
                    }
                }
            }
        }
  //    y($this->_links);die;
        return;
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_PREVNEXT;
    }
}
