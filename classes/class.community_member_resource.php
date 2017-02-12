<?php
/*
Version History:
  1.0.12 (2017-02-12)
    1) Call to Portal::_parse_request_search_range() now replaced with static::checkSearchRange
    2) Call to Portal::_parse_request_posting() now goes directly to Posting::get_match_for_name()
    3) Various PSR-2 fixes
*/

class Community_Member_Resource extends Community_Member
{
    const VERSION = '1.0.12';

    protected $_member_name =       '';
    protected $_member_page =       '';
    protected $_record =            false;
    protected $_nav_prev =          false;
    protected $_nav_next =          false;
    protected $_Obj_Community;
    protected $_selected_section =  '';
    protected $_section_tabs_arr =  array();

    public function draw($cp, $member_extension)
    {
        $this->setup($cp, $member_extension);
        $type =     "";
        $ID =       0;
        $request =  $this->_member_page;
        if ($request=='js' || substr($request, 0, 3)=='js/') {
            return $this->serveJsonp(substr($request, 3));
        }
        if ($request=='rss' || substr($request, 0, 4)=='rss/') {
            return $this->drawRss($request);
        }
        if ($request=='photos') {
            return $this->drawPhotos();
        }
        if (Posting::get_match_for_name($request, $type, $ID)) {
            return $this->drawPosting($type, $ID);
        }
        if (static::checkSearchRange($request, $page, $search_date_start, $search_date_end, $search_type)) {
            return $this->drawSearchResults($search_date_start, $search_date_end, $search_type);
        }
        throw new Exception("Unknown member resource \"".$request."\"");
    }

    protected function serveJsonp($type)
    {
        switch ($type) {
            case 'articles':
                $Obj = new Community_Member_Article;
                break;
            case 'calendar':
                $Obj = new \Component\CommunityMemberCalendar;
                break;
            case 'events':
                $Obj = new Community_Member_Event;
                break;
            case 'news':
                $Obj = new Community_Member_News_Item;
                break;
            case 'podcasts':
                $Obj = new Community_Member_Podcast;
                break;
            default:
                throw new Exception("Unknown Community Member JSONP request".($type ? " \"".$type."\"" : ""));
            break;
        }
        $Obj_Community =            new Community($this->_record['communityID']);
        $Obj->community_record =    $Obj_Community->load();
        $Obj->communityID =         $this->_record['communityID'];
        $Obj->memberID =            $this->_record['ID'];
        $Obj->partner_csv =         $this->_record['partner_csv'];
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

    protected function drawPhotos()
    {
        $Obj = new Gallery_Image();
        $Obj->communityID =     $this->_record['communityID'];
        $Obj->memberID =        $this->_record['ID'];
        $Obj->partner_csv =     $this->_record['partner_csv'];
        $args = array(
            'category_show' =>          0,
            'filter_container_path' =>
                "/communities/".$this->_cp['community_name']."/members/".$this->_record['name']."/profile",
            'results_limit' =>          1,
            'results_paging' =>         2,
            'show_watermark' =>         1,
            'thumbnail_height' =>       1000,
            'thumbnail_show' =>         1,
            'thumbnail_width' =>        1000,
            'title_linked' =>           0
        );
        return
             "<div class='inner'>"
            ."<h2>Photos for ".htmlentities($this->_record['title'])."</h2>\n"
            .$Obj->draw_listings('member_photos', $args, false)
            ."</div>\n";
    }

    protected function drawPosting($type, $ID)
    {
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
                throw new Exception("Unexpected type \"".$type."\"");
            break;
        }
        $page_vars['object_name'] =   $Obj->_get_object_name();
        $page_vars['object_type'] =   get_class($Obj);
        $page_vars['ID']=$ID;
        return $Obj->draw_detail();
    }

    protected function drawRss($request)
    {
        global $page_vars;
        $path_arr =  explode('/', $request);
        $submode = (isset($path_arr[1]) ? $path_arr[1] : '');
        $Obj_RSS = new RSS;
        $args = array(
            'base_path' =>  $this->_record['URL_external'].'/rss/',
            'feed_title' => $page_vars['title']." &gt; ".$this->_record['title']." &gt; RSS Service",
            'memberID' =>   $this->_record['ID'],
            'DD' =>         get_var('DD', ''),
            'MM' =>         get_var('MM', ''),
            'offset' =>     get_var('offset', 0),
            'render' =>     true,
            'request' =>    $request,
            'title' =>
                 $page_vars['title']." > RSS > ".$this->_record['title']
                .($submode ? " > ".title_case_string($submode) : ""),
            'what' =>       (get_var('what') ? get_var('what') : 'future'),
            'YYYY' =>       get_var('YYYY', ''),
        );
        $Obj_RSS->serve($args);
    }

    protected function drawSearchResults($search_date_start, $search_date_end, $search_type)
    {
        
        $args = array(
            'search_date_end' =>            $search_date_end,
            'search_date_start' =>          $search_date_start,
            'search_memberID' =>            $this->_record['ID'],
            'search_offset' =>              get_var('search_offset', 0),
            'search_results_page_limit' =>  10,
            'search_type' =>                get_var('search_type', $search_type),
            'systemIDs_csv' =>              SYS_ID,
            'title' =>                      "<h1>Search Results for ".$this->_record['title']."</h1>"
        );
        $Obj_Search = new Search();
        return $Obj_Search->draw('', $args, true);
    }

    protected function setup($cp, $member_extension)
    {
        $this->_cp =    $cp;
        $this->setupLoadMember($member_extension);
        $this->_print = get_var('print')=='1';
    }

    protected function setupLoadMember($member_extension)
    {
        global $page_vars;
        $this->_member_extension =  $member_extension;
        $this->_base_path =         BASE_PATH.trim($page_vars['path'], '/');
        $member_page_arr =          explode('/', $this->_member_extension);
        $this->_member_name =       array_shift($member_page_arr);
        $this->_member_page =       implode('/', $member_page_arr);
        if (!$this->get_member_profile($this->_cp['community_name'], $this->_member_name)) {
            throw new Exception("Member \"".$this->_member_name."\" not found.");
        }
    }
}
