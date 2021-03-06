<?php
/*
Version History:
  1.0.10 (2017-06-19)
    1) Community_Resource::drawGallery() now invokes Component_Community_Gallery_Album not Component_Gallery_Album
*/

class Community_Resource extends Community_Display
{
    const VERSION = '1.0.10';

    public function drawResource($cp, $path_extension, $community_record)
    {
        $this->setup($cp, $path_extension, $community_record);
        if ($this->_path_extension=='js' || substr($this->_path_extension, 0, 3)=='js/') {
            return $this->serveJsonp();
        }
        if ($this->_path_extension=='rss' || substr($this->_path_extension, 0, 4)=='rss/') {
            return $this->drawRss();
        }
        if ($this->_path_extension=='sermons' || substr($this->_path_extension, 0, 8)=='sermons/') {
            return $this->drawSermons();
        }
        if ($this->_path_extension=='gallery' || substr($this->_path_extension, 0, 8)=='gallery/') {
            return $this->drawGallery();
        }
        if (Posting::get_match_for_name($this->_path_extension, $type, $ID)) {
            return $this->drawPosting($type, $ID);
        }
        if (static::checkSearchRange(
            $this->_path_extension,
            $page,
            $search_date_start,
            $search_date_end,
            $search_type
        )
        ) {
            return $this->drawSearchResults($search_date_start, $search_date_end, $search_type);
        }
        return $this->drawProfile();
    }

    protected function drawGallery()
    {
        $Obj = new Component_Community_Gallery_Album;
        $args = array(
             'filter_root_path' =>        '//communities/'.$this->_cp['community_name'].'/members/',
             'folder_tree_height' =>      800,
             'folder_tree_width' =>       300,
             'indicated_root_folder' =>   'members',
             'path_prefix' =>             '//communities/'.$this->_cp['community_name'].'/gallery',
             'show_folder_tree' =>        1,
             'show_folder_icons' =>       1,
             'show_watermark' =>          1,
             'sort_by' =>                 'name'
        );
        return $Obj->draw($this->_instance, $args, true);
    }

    protected function drawPosting($type, $ID)
    {
        global $page_vars;
        switch ($type) {
            case 'article':
                $Obj = new Article($ID);
                break;
            case 'event':
                $Obj = new Event($ID);
                break;
            case 'news-item':
                $Obj = new News_Item($ID);
                break;
            case 'podcast':
                $Obj = new Podcast($ID);
                break;
            default:
                throw new Exception("Unknown Community Posting type \"".$type."\"");
            break;
        }
        $page_vars['object_name'] =   $Obj->_get_object_name();
        $page_vars['object_type'] =   get_class($Obj);
        $page_vars['ID']=$ID;
        return $Obj->draw_detail();
    }

    protected function drawProfile()
    {
        $Obj_CMD = new Community_Member_Display;
        return $Obj_CMD->draw($this->_cp, $this->_path_extension);
    }

    protected function drawRss()
    {
        global $page_vars;
        $path_arr =  explode('/', $this->_path_extension);
        $submode =  (isset($path_arr[1]) ? $path_arr[1] : '');
        $Obj_RSS =  new RSS;
        $args = array(
            'base_path' =>      $this->_community_record['URL_external'].'/rss/',
            'feed_title' =>     $page_vars['title']." &gt; RSS Service",
            'isShared'=>        1,
            'communityID' =>    $this->_get_ID(),
            'DD' =>             get_var('DD', ''),
            'MM' =>             get_var('MM', ''),
            'offset' =>         get_var('offset', 0),
            'render' =>         true,
            'request' =>        $this->_path_extension,
            'title' =>          $page_vars['title']." > RSS".($submode ? " > ".title_case_string($submode) : ""),
            'what' =>           (get_var('what') ? get_var('what') : 'future'),
            'YYYY' =>           get_var('YYYY', ''),
        );
        $Obj_RSS->serve($args);
    }

    protected function drawSearchResults($search_date_start, $search_date_end, $search_type)
    {
        $args = array(
            'search_date_end' =>            $search_date_end,
            'search_date_start' =>          $search_date_start,
            'search_communityID' =>         $this->_get_ID(),
            'search_offset' =>              get_var('search_offset', 0),
            'search_results_page_limit' =>  10,
            'search_type' =>                get_var('search_type', $search_type),
            'systemIDs_csv' =>              SYS_ID,
            'show_member' =>                1,
            'title' =>
                "<h1>Search Results for Community of ".$this->_community_record['title']."</h1>"
        );
        
        $Obj_Search = new Search(SYS_ID);
        return $Obj_Search->draw('', $args, true);
    }

    protected function drawSermons()
    {
        $Obj = new \Component\CommunityCollectionViewer;
        $args = array(
             'text_prompt_to_choose' =>
                '<h2>Sermons</h2><p>Please choose a member or speaker to view their sermons.</p>',
             'controls_albums_heading' =>     'Members',
             'controls_albums_show' =>        1,
             'controls_albums_url' =>         'sermons/member',
             'controls_authors_heading' =>    'Speakers',
             'controls_authors_show' =>       1,
             'controls_authors_url' =>        'sermons/speaker',
             'controls_width' =>              380,
             'filter_album_order_by' =>       'title',
             'filter_container_path' =>       '//communities/'.$this->_cp['community_name'].'/members/',
             'filter_podcast_order' =>        'desc',
             'results_limit' =>               10,
             'results_paging' =>              2
        );
        return $Obj->draw($this->_instance, $args, false);
    }

    protected function serveJsonp()
    {
        $type = substr($this->_path_extension, 3);
        switch ($type) {
            case 'articles':
                $Obj = new Community_Article;
                break;
            case 'calendar':
                $Obj = new \Component\CommunityCalendar;
                break;
            case 'events':
                $Obj = new Community_Event;
                break;
            case 'news':
                $Obj = new Community_News_Item;
                break;
            case 'podcasts':
                $Obj = new Community_Podcast;
                break;
            default:
                throw new Exception("Unknown Community JSONP request".($type ? " \"".$type."\"" : ""));
            break;
        }
        $Obj->community_record = $this->_community_record;
        switch ($type) {
            case 'calendar':
                $args = array(
                    'show_controls' =>    0,
                    'show_heading' =>     0,
                );
                $out =                  $Obj->drawJson('', $args, true);
                break;
            default:
                $args = array(
                    'author_show' =>      1,
                    'content_show' =>     1,
                    'results_limit' =>    get_var('limit', 10),
                    'results_paging' =>   2
                );
                $out = $Obj->draw_listings_json('', $args, true);
                break;
        }
        if ($this->_cp['community_title']) {
            $sourceline =
                 "<div style='text-align:center'>\n"
                ."<a target='_blank' href=\"".$this->_cp['community_URL']."\">"
                .$this->_cp['community_title']
                ."</a>\n"
                ."</div>";
            $pos =  strrpos($out['html'], '</div>');
            $html = substr($out['html'], 0, $pos).$sourceline.substr($out['html'], $pos);
            $out['html'] = $html;
        }
        header('Content-Type: application/javascript;charset=utf-8');
        print get_var('callback')."(".json_encode($out).");\n";
        die;
    }

    protected function setup($cp, $path_extension, $community_record)
    {
        $this->_cp =                $cp;
        $this->_path_extension =    $path_extension;
        $this->_community_record =  $community_record;
        $this->_set_ID($this->_community_record['ID']);
    }
}
