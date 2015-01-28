<?php
define('VERSION_NEWS_ITEM','1.0.23');
/*
Version History:
  1.0.23 (2015-01-28)
    1) Previously cloning a News Item required a new name to be given (which wasn't used)
       Now the clone is nameless.

  (Older version history in class.news_item.txt)
*/
class News_Item extends Posting {

  function __construct($ID="",$systemID=SYS_ID) {
    parent::__construct($ID,$systemID);
    $this->_set_type('news-item');
    $this->_set_assign_type('news-item');
    $this->_set_has_publish_date(true);     // Do now allow item to be seen prior to publish date
    $this->_set_object_name('News Item');
    $this->set_edit_params(
      array(
        'command_for_delete' =>     'news_delete',
        'report' =>                 'news-items',
        'report_rename' =>          false,
        'report_rename_label' =>    'new title',
        'icon_delete' =>            '[ICON]13 13 4447 Delete this News Item[/ICON]',
        'icon_edit' =>              '[ICON]15 15 41 Edit this News Item[/ICON]',
        'icon_edit_disabled' =>     '[ICON]15 15 2395 (Edit this News Item)[/ICON]',
        'icon_edit_popup' =>        '[ICON]18 18 2480 Edit this News Item in a popup window[/ICON]'
      )
    );
    $this->_cp_vars_detail = array(
      'block_layout' =>             array('match' => '',                'default' => 'News Item',       'hint' => 'Name of Block Layout to use'),
      'category_show' =>            array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'comments_show' =>            array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'comments_link_show' =>       array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'extra_fields_list' =>        array('match' => '',                'default' => '',                'hint' => 'CSV list format: field|label|group,field|label|group...'),
      'item_footer_component' =>    array('match' => '',                'default' => '',                'hint' => 'Name of component rendered below displayed New Item'),
      'subscribe_show' =>           array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1 - Whether or not to allow subscriptions'),
      'thumbnail_at_top' =>         array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'thumbnail_height' =>         array('match' => 'range|1,n',       'default' => '300',             'hint' => '|1..n or blank - height in px to resize'),
      'thumbnail_image' =>          array('match' => 'enum|s,m,l',      'default' => 's',               'hint' => 's|m|l - Choose only \'s\' unless Multiple-Thumbnails option is enabled'),
      'thumbnail_link' =>           array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'thumbnail_show' =>           array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'thumbnail_width' =>          array('match' => 'range|1,n',       'default' => '400',             'hint' => '|1..n or blank - width in px to resize'),
      'title_linked' =>             array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'title_show' =>               array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1')
    );
    $this->_cp_vars_listings = array(
      'background' =>               array('match' => 'hex3|',           'default' => '',                'hint' => 'Hex code for background colour to use'),
      'block_layout' =>             array('match' => '',                'default' => 'News Item',       'hint' => 'Name of Block Layout to use'),
      'box' =>                      array('match' => 'enum|0,1,2',      'default' => '0',               'hint' => '0|1|2'),
      'box_footer' =>               array('match' => '',                'default' => '',                'hint' => 'Text below displayed News Items'),
      'box_header' =>               array('match' => '',                'default' => '',                'hint' => 'Text above displayed News Items'),
      'box_rss_link' =>             array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'box_title' =>                array('match' => '',                'default' => 'News',            'hint' => 'text'),
      'box_title_link' =>           array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'box_title_link_page' =>      array('match' => '',                'default' => 'all_news',        'hint' => 'page'),
      'box_width' =>                array('match' => 'range|0,n',       'default' => '0',               'hint' => '0..x'),
      'category_show' =>            array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'comments_link_show' =>       array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'content_char_limit' =>       array('match' => 'range|0,n',       'default' => '0',               'hint' => '0..n'),
      'content_plaintext' =>        array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'content_show' =>             array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'extra_fields_list' =>        array('match' => '',                'default' => '',                'hint' => 'CSV list format: field|label|group,field|label|group...'),
      'filter_category_list' =>     array('match' => '',                'default' => '*',               'hint' => '*|CSV value list'),
      'filter_important' =>         array('match' => 'enum|,0,1',       'default' => '',                'hint' => 'Blank to ignore, 0 for not important, 1 for important'),
      'filter_memberID' =>          array('match' => 'range|0,n',       'default' => '',                'hint' => 'ID of Community Member to restrict by that criteria'),
      'filter_personID' =>          array('match' => 'range|0,n',       'default' => '',                'hint' => 'ID of Person to restrict by that criteria'),
      'item_footer_component' =>    array('match' => '',                'default' => '',                'hint' => 'Name of component rendered below each displayed News Item'),
      'more_link_text' =>           array('match' => '',                'default' => '(More)',          'hint' => 'text for \'Read More\' link'),
      'results_grouping' =>         array('match' => 'enum|,month,year','default' => '',                'hint' => '|month|year'),
      'results_limit' =>            array('match' => 'range|0,n',       'default' => '3',               'hint' => '0..n'),
      'results_paging' =>           array('match' => 'enum|0,1,2',      'default' => '0',               'hint' => '0|1|2 - 1 for buttons, 2 for links'),
      'subscribe_show' =>           array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1 - Whether or not to allow subscriptions'),
      'thumbnail_at_top' =>         array('match' => 'enum|0,1',        'default' => '0',               'hint' => '0|1'),
      'thumbnail_height' =>         array('match' => 'range|1,n',       'default' => '150',             'hint' => '|1..n or blank - height in px to resize'),
      'thumbnail_image' =>          array('match' => 'enum|s,m,l',      'default' => 's',               'hint' => 's|m|l - Choose only \'s\' unless Multiple-Thumbnails option is enabled'),
      'thumbnail_link' =>           array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'thumbnail_show' =>           array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'thumbnail_width' =>          array('match' => 'range|1,n',       'default' => '200',             'hint' => '|1..n or blank - width in px to resize'),
      'title_linked' =>             array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1'),
      'title_show' =>               array('match' => 'enum|0,1',        'default' => '1',               'hint' => '0|1')
    );
  }

  public function get_version(){
    return VERSION_NEWS_ITEM;
  }
}
?>