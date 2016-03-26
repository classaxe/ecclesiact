<?php
namespace Component;

/*
Version History:
  1.0.11 (2016-03-26)
    1) ArticlesRotator::setupLoadRecords replaced parameter filter_category with filter_category_list
*/
class ArticlesRotator extends Base
{
    const VERSION = '1.0.11';

    protected $_ObjArticle;
    protected $_records;
    protected $_itemsFeatured = array();
    protected $_itemsRotated =  array();

    public function __construct()
    {
        $this->_ident =             "articles_rotator";
        $this->_parameter_spec =    array(
            'author_show' =>            array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'block_layout' =>           array(
                'match' =>      '',
                'default' =>    'Articles',
                'hint' =>       'Name of Block Layout to use'
            ),
            'category_show' =>          array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_char_limit' =>     array(
                'match' =>      'range|0,n',
                'default' =>    '0',
                'hint' =>       '0..n'
            ),
            'content_plaintext' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_show' =>           array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'content_use_summary' =>    array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'date_show' =>              array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'extra_fields_list' =>      array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'CSV list format: field|label|group,field|label|group...'
            ),
            'filter_category_list' =>   array(
                'match' =>      '',
                'default' =>    '*',
                'hint' =>       '*|CSV value list'
            ),
            'filter_category_master' => array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Optionally INSIST on this category'
            ),
            'filter_memberID' =>        array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Community Member to restrict by that criteria'
            ),
            'filter_personID' =>        array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Person to restrict by that criteria'
            ),
            'headers_show' =>           array(
                'match' =>      'enum|0,1,2',
                'default' =>    '0',
                'hint' =>       '0|1|2'
            ),
            'item_footer_component' =>  array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Name of component rendered below each displayed Article'
            ),
            'keywords_show' =>          array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'links_point_to_URL' =>     array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1 - If there is a URL, both title and thumbnails links go to it'
            ),
            'limit_featured' =>         array(
                'match' =>      'range|0,n',
                'default' =>    '1',
                'hint' =>       '0..n'
            ),
            'limit_rotated' =>          array(
                'match' =>      'range|0,n',
                'default' =>    '1',
                'hint' =>       '0..n'
            ),
            'limit_other' =>            array(
                'match' =>      'range|0,n',
                'default' =>    '1',
                'hint' =>       '0..n'
            ),
            'more_link_text' =>         array(
                'match' =>      '',
                'default' =>    '(More)',
                'hint' =>       'text for \'Read More\' link'
            ),
            'related_show' =>           array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'results_order' =>          array(
                'match' =>      'enum|date,title',
                'default' =>    'date',
                'hint' =>       'date|title'
            ),
            'results_limit' =>          array(
                'match' =>      'range|0,n',
                'default' =>    '3',
                'hint' =>       '0..n'
            ),
            'subtitle_show' =>          array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'thumbnail_at_top' =>       array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'thumbnail_image' =>        array(
                'match' =>      'enum|s,m,l',
                'default' =>    's',
                'hint' =>       's|m|l - Choose only \'s\' unless Multiple-Thumbnails option is enabled'
            ),
            'thumbnail_link' =>         array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'thumbnail_show' =>         array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'thumbnail_width' =>        array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       '|0..n - give width in px to resize'
            ),
            'title_featured' =>         array(
                'match' =>      '',
                'default' =>    'Featured Article',
                'hint' =>       'title (not plural)'
            ),
            'title_rotated' =>          array(
                'match' =>      '',
                'default' =>    'Other Article',
                'hint' =>       'title (not plural)'
            ),
            'title_other' =>            array(
                'match' =>      '',
                'default' =>    'Additional Article',
                'hint' =>       'title (not plural)'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        $this->_html.=  "<div id=\"".$this->_safe_ID."\">";
        $this->drawFeatured();
        $this->drawRotated();
        $this->drawOther();
        $this->_html.="</div>";
        if (isset($_REQUEST['command']) && $_REQUEST['command']=="articles_panel_".$this->_instance."_load") {
            print $this->_html;
            die;
        }
        return $this->_html;
    }

    protected function drawFeatured()
    {
        if (!count($this->_itemsFeatured)) {
            return;
        }
        $this->_html.=
             "<div class='articles_featured'>\n"
            .($this->_cp['headers_show']>1 ?
                "<h2 class='header'>".$this->_cp['title_featured'].(count($this->_itemsFeatured)==1 ? '' : 's')."</h2>"
             :
                ""
             );
        $this->drawFromRecordSet($this->_itemsFeatured);
        $this->_html.=
            "<div class='clr_b'></div></div>\n"
            .(count($this->_itemsRotated) || count($this->_records) ? "<hr />\n" : "");

    }

    protected function drawOther()
    {
        if (!count($this->_records) || !$this->_cp['limit_other']) {
            return;
        }
        $this->_html.=
             "<div class='articles_other'>\n"
            .($this->_cp['headers_show']>0 ?
                 "<h2 class='header'>"
                .$this->_cp['title_other']
                .(count($this->_records)==1 || $this->_cp['limit_other']==1 ? '' : 's')
                ."</h2>"
              :
                ""
             );
        foreach ($this->_records as $r) {
            if ($this->_cp['limit_other']-- == 0) {
                break;
            }
            $URL =        $this->_ObjArticle->get_URL($r);
            $canEdit =
                $this->_current_user_rights['canEdit'] &&
                $r['ID'] &&
                ($r['systemID']==SYS_ID || $this->_current_user_rights['isMASTERADMIN']);
            $this->_html.=
                 "<div"
                .($canEdit ?
                      " onmouseover=\""
                     ."if(!CM_visible('CM_article')) {"
                     ."this.style.backgroundColor='"
                     .($r['systemID']==SYS_ID ? '#ffff80' : '#ffe0e0')
                     ."';"
                     ."_CM.type='article';"
                     ."_CM.ID=".$r['ID'].";"
                     ."_CM_text[0]='&quot;"
                     .str_replace(array("'","\""), array('','&quot;'), $r['title'])
                     ."&quot;';"
                     ."_CM_text[1]=_CM_text[0];}\" "
                     ." onmouseout=\"this.style.backgroundColor='';_CM.type='';\""
                  :
                    ""
                 )
                .">\n"
                ."<h2 class='title'>"
                ."<a href=\"".$URL."\""
                .($r['systemID']!=SYS_ID ?
                    " rel='external' title=\"Read Article (opens in a new window)\""
                  :
                    " title=\"Read Article\""
                 )
                .">"
                .$r['title']
                ."</a>"
                ."</h2>\n"
                .($this->_cp['date_show'] ?
                     "<div class='subhead' style='padding-bottom: 0.5em;'>"
                    .format_date($r['date'])
                    .($r['comments_count'] ?
                         " | <a href=\"".$URL."#anchor_comments_list\">".$r['comments_count']." comment"
                        .($r['comments_count']==1 ? "" : "s")
                        ." &raquo;</a>"
                      :
                        ""
                     )
                     ."</div>\n"
                  :
                    ""
                 )
                 ."</div>\n";
        }
        $this->_html.="</div>";
    }

    protected function drawRotated()
    {
        if (!count($this->_itemsRotated)) {
            return;
        }
        $this->_html.=
             "<div class='articles_rotated'>\n"
            .($this->_cp['headers_show']>1 ?
                "<h2 class='header'>".$this->_cp['title_rotated'].(count($this->_itemsRotated)==1 ? '' : 's')."</h2>\n"
            :
                ""
             );
        $this->drawFromRecordSet($this->_itemsRotated);
        $this->_html.=
             "<div class='clr_b'></div></div>\n"
            .(count($this->_records) && $this->_cp['limit_other'] ? "<hr />\n" : "");
    }

    protected function drawFromRecordSet($items)
    {
        $args = array(
            'author_show' =>            $this->_cp['author_show'],
            'block_layout' =>           $this->_cp['block_layout'],
            'category_show' =>          $this->_cp['category_show'],
            'content_char_limit' =>     $this->_cp['content_char_limit'],
            'content_plaintext' =>      $this->_cp['content_plaintext'],
            'content_show' =>           $this->_cp['content_show'],
            'content_use_summary' =>    $this->_cp['content_use_summary'],
            'extra_fields_list' =>      $this->_cp['extra_fields_list'],
            'date_show' =>              $this->_cp['date_show'],
            'item_footer_component' =>  $this->_cp['item_footer_component'],
            'links_point_to_URL' =>     $this->_cp['links_point_to_URL'],
            'more_link_text' =>         $this->_cp['more_link_text'],
            'related_show' =>           $this->_cp['related_show'],
            'subtitle_show' =>          $this->_cp['subtitle_show'],
            'thumbnail_at_top' =>       $this->_cp['thumbnail_at_top'],
            'thumbnail_image' =>        $this->_cp['thumbnail_image'],
            'thumbnail_link' =>         $this->_cp['thumbnail_link'],
            'thumbnail_show' =>         $this->_cp['thumbnail_show'],
            'thumbnail_width' =>        $this->_cp['thumbnail_width']
        );
        $this->_html.= $this->_ObjArticle->draw_from_recordset($items, $args);
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadRecords();
        $this->setupLoadUserRights();
    }

    protected function setupLoadRecords()
    {
        $this->_ObjArticle = new \Article;
        // Get last n articles
        $results = $this->_ObjArticle->get_records(
            array(
                'filter_category_list' =>   $this->_cp['filter_category_list'],
                'filter_category_master' => $this->_cp['filter_category_master'],
                'filter_memberID' =>        $this->_cp['filter_memberID'],
                'filter_personID' =>        $this->_cp['filter_personID'],
                'results_limit' =>          $this->_cp['results_limit'],
                'results_offset' =>         (isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0),
                'results_order' =>          $this->_cp['results_order']
            )
        );
        $this->_records =  $results['data'];
        $this->_itemsFeatured =       array();
        $this->_itemsRotated =        array();
        if (count($this->_records)<$this->_cp['limit_featured']) {
            $this->_cp['limit_featured'] = count($this->_records);
        }
        if (count($this->_records)>=$this->_cp['limit_featured']) {
            for ($i=0; $i<$this->_cp['limit_featured']; $i++) {
                $this->_itemsFeatured[] =     array_shift($this->_records);
            }
        }
        if (count($this->_records)>=$this->_cp['limit_featured']+$this->_cp['limit_rotated']) {
            for ($i=0; $i<$this->_cp['limit_rotated']; $i++) {
                $n =            rand(0, count($this->_records)-1);
                $this->_itemsRotated[] =  $this->_records[$n];
                array_splice($this->_records, $n, 1);
            }
        }
    }
}
