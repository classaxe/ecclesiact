<?php
namespace Component;

/*
Version History:
  1.0.15 (2015-12-26)
    1) Now has CP for show_watermark - default is off
    2) WOWSlider::drawCssInclude() now only includes generic css for all WOW Slider instances once
       and no longer encodes & in URL as an html entity
*/
class WOWSlider extends Base
{
    const VERSION = '1.0.15';

    protected $_first_image =   array();
    protected $_first_idx =     0;
    protected $fx =
        array(
            'basic',
            'basic_linear',
            'blast',
            'blinds',
            'blur',
            'book',
            'brick',
            'collage',
            'cube',
            'domino',
            'fade',
            'flip',
            'fly',
            'kenburns',
//            'overlay', // overlay not working here
            'page',
            'photo',
            'rotate',
            'seven',
            'slices',
            'squares',
            'stack',
            'stack_vertical'
        );
    // overlay not working here
    protected $fx_fixed_size =  "blinds,book,collage,cube,flip,slices,squares";
    // these cannot be used for responsive websites
    protected $_images =        array();
    protected $_records =       array();

    public function __construct()
    {
        $this->_ident = "wow_slider";
        $this->_parameter_spec =    array(
            'bullets_show' =>               array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'bullets_margin_top' =>         array(
                'match' =>      'range|0,n',
                'default' =>    '0',
                'hint' =>       'Give a value to alter distance of bullets from top of frame'
            ),
            'caption_show' =>               array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'content_background' =>         array(
                'match' =>      '',
                'default' =>    '000000',
                'hint' =>       'Background colour for caption'
            ),
            'content_color' =>              array(
                'match' =>      '',
                'default' =>    'ffffff',
                'hint' =>       'Text colour for caption'
            ),
            'content_opacity' =>            array(
                'match' =>      'range|0,100',
                'default' =>    '80',
                'hint' =>       'Opacity in % for caption area'
            ),
            'controls_show' =>              array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'effect' =>                     array(
                'match' =>      'enum|'.implode(',', $this->fx),
                'default' =>    'fade',
                'hint' =>       implode('|', $this->fx)
            ),
            'effect_reverse' =>             array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1'
            ),
            'filter_category_list' =>       array(
                'match' =>      '',
                'default' =>    '*',
                'hint' =>       'Optionally limits items to those in this gallery album - / means none'
            ),
            'filter_category_master' =>     array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Optionally INSIST on this category'
            ),
            'filter_container_path' =>      array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Optionally limits items to those contained in this folder'
            ),
            'filter_container_subs' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       'If filtering by container folder, enable this setting to include subfolders'
            ),
            'filter_memberID' =>            array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Community Member to restrict by that criteria'
            ),
            'filter_personID' =>            array(
                'match' =>      'range|0,n',
                'default' =>    '',
                'hint' =>       'ID of Person to restrict by that criteria'
            ),
            'hide_if_path_extended' =>      array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       'If set, then the slider will not show when the path is extended'
            ),
            'max_height' =>                 array(
                'match' =>      'range|1,n',
                'default' =>    '200',
                'hint' =>       'Maximum height in pixels'
            ),
            'max_width' =>                  array(
                'match' =>      'range|1,n',
                'default' =>    '200',
                'hint' =>       'Maximum width in pixels'
            ),
//          'onchange' =>                   array(
//              'match' =>      '',
//              'default' =>    '',
//              'hint' =>       'javascript to execute when an image changes'
//          ),
            'random_order' =>               array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1 - Whether to start with first image or play from a random position'
            ),
            'results_limit' =>              array(
                'match' =>      'range|0,n',
                'default' =>    '3',
                'hint' =>       '0..n'
            ),
            'results_order' =>              array(
                'match' =>      'enum|date,title',
                'default' =>    'date',
                'hint' =>       'date|title'
            ),
            'secCaption' =>                 array(
                'match' =>      'range|0,n',
                'default'=>'0.5',
                'hint' =>       'Decimal time in seconds for fade'
            ),
            'secFade' =>                    array(
                'match' =>      'range|0,n',
                'default'=>'1',
                'hint' =>       'Decimal time in seconds for fade'
            ),
            'secShow' =>                    array(
                'match' =>      'range|0,n',
                'default'=>'4',
                'hint' =>       'Decimal time in seconds for show'
            ),
            'show_watermark' =>           array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       'Whether or not to watermark large images - ignored if image has \'no watermark\' set'
            ),
            'title' =>                      array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'If given, set title of all images to this'
            ),
            'title_linked' =>               array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'title_prefix' =>               array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Text to place before each title where shown'
            ),
            'title_show' =>                 array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       '0|1'
            ),
            'thumbnail_maintain_aspect' =>  array(
                'match' =>      'enum|0,1',
                'default' =>    '1',
                'hint' =>       'Maximum height in pixels'
            ),
            'thumbnail_height' =>           array(
                'match' =>      'range|1,n',
                'default' =>    '19',
                'hint' =>       'Maximum height in pixels'
            ),
            'thumbnail_width' =>            array(
                'match' =>      'range|1,n',
                'default' =>    '80',
                'hint' =>       'Maximum width in pixels'
            ),
            'URL' =>                        array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'If given, clicking on any image launches this URL'
            ),
            'URL_popup' =>                  array(
                'match' =>      'enum|0,1',
                'default' =>    '0',
                'hint' =>       '0|1 - Used when URL is fixed'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        global $page_vars;
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel($this->_cp['hide_if_path_extended'] ? 1 : 0);
        $this->drawStatus();
        if (!count($this->_records)) {
            $this->_html.="(No images to show)";
            return $this->render();
        }
        if ($this->_cp['hide_if_path_extended'] && $page_vars['path_extension']!='') {
            return $this->_html;
        }
        $this->drawCssInclude();
        $this->drawJs();
        $this->drawImages();
        $this->drawImageBullets();
        $this->_html.= "  <div class=\"ws_shadow\"></div>\n";
        return $this->render();
    }

    protected function drawCssInclude()
    {
        static $shown = false;
        global $page_vars;
        $url =      BASE_PATH.trim($page_vars['path'], '/').'?submode=css&targetValue='.$this->_safe_ID;
        \Output::push(
            'head_top',
            ($shown ? "" : "<link rel=\"stylesheet\" type=\"text/css\" href=\"".BASE_PATH."css/ws\" />\n")
            ."<link rel=\"stylesheet\" type=\"text/css\" href=\"".$url."\" />\n"
        );
        $shown = true;
    }

    protected function drawCss()
    {
        header("Content-type: text/css", true);
        $images =   count($this->_records);
        $show =     $this->_cp['secShow'];
        $fade =     $this->_cp['secFade'];
        $dur =      $images * ($fade + $show);
        $slices =   100 / $images;
        $show_pc =  ($slices * $this->_cp['secShow']) / ($this->_cp['secShow'] + $this->_cp['secFade']);
        $fade_pc =  ($slices * $this->_cp['secFade']) / ($this->_cp['secShow'] + $this->_cp['secFade']);
        $sequence = "";
        for ($i=0; $i<$images; $i++) {
            $pc = $i*($show_pc+$fade_pc);
            $sequence.= number_format($pc, 2)."%{left:-".($i*100)."%} ";
            $sequence.= number_format($pc+$show_pc, 2) ."%{left:-".($i*100)."%} ";
        }
        // Effects requiring absolute sizing:
        print
              (in_array($this->_cp['effect'], explode(',', $this->fx_fixed_size)) ?
                   "#".$this->_safe_ID." { max-width:".$this->_cp['max_width']."px; }\n"
                  ."* html #".$this->_safe_ID." { width:".$this->_cp['max_width']."px; }\n"
               :
                    ""
              )
              ."#".$this->_safe_ID." .ws_bullets a{ background:url(".BASE_PATH."img/sysimg/bullet.png) left top;}\n"
              ."#".$this->_safe_ID." .ws_bullets a.ws_selbull,#".$this->_safe_ID." .ws_bullets a:hover{\n"
              ."  background-position: 0 100%;\n"
              ."}\n"
              ."#".$this->_safe_ID." .ws_next,#".$this->_safe_ID." .ws_prev{\n"
              ."  background-image:url(".BASE_PATH."img/sysimg/arrows.png);\n"
              ."}\n"
              ."#".$this->_safe_ID." .ws_pause { background-image: url(".BASE_PATH."img/sysimg/pause.png);}\n"
              ."#".$this->_safe_ID." .ws_play  { background-image: url(".BASE_PATH."img/sysimg/play.png);}\n"
              ."#".$this->_safe_ID." .ws_bullets  a img{ left:-".($this->_cp['thumbnail_width']/2)."px;}\n"
              ."#".$this->_safe_ID." .ws_bulframe div div{ height:".$this->_cp['thumbnail_height']."px;}\n"
              ."#".$this->_safe_ID." .ws_bulframe div{width:".$this->_cp['thumbnail_width']."px;}\n"
              ."#".$this->_safe_ID." .ws_bulframe span{\n"
              ."  left:".($this->_cp['thumbnail_width']/2)."px; background:url(".BASE_PATH."img/sysimg/triangle.png);\n"
              ."}\n"
              ."#".$this->_safe_ID." .ws_images ul {\n"
              ."  animation:         wsBasic ".$dur."s infinite;\n"
              ."  -moz-animation:    wsBasic ".$dur."s infinite;\n"
              ."  -webkit-animation: wsBasic ".$dur."s infinite;\n"
              ."}\n"
              ."@keyframes         wsBasic{".$sequence." }\n"
              ."@-moz-keyframes    wsBasic{".$sequence." }\n"
              ."@-webkit-keyframes wsBasic{".$sequence." }\n";
                          ;
    }

    protected function drawImages()
    {
        $this->_html.=
             "  <div class=\"ws_images\">\n"
            ."    <ul>\n";
        for ($i=0; $i<count($this->_images); $i++) {
            $image = $this->_images[$i];
            $this->_html.=
                 "    <li>"
                .($image['url'] ?
                     "<a href=\"".$image['url']."\""
                    .($image['url_popup'] ? " rel='external'" : '')
                    .">"
                  :
                    ""
                 )
                ."<img"
                ." id=\"".$this->_safe_ID."_".$i."\""
                ." src=\"".htmlentities($image['image'])."\""
                ." alt=\"".$image['title']."\""
                ." title=\"".($this->_cp['title_show']=='1' ? $image['title'] : '')."\""
                ."/>"
                .($image['url'] ? "</a>" : "")
                .($this->_cp['caption_show']=='1' ? $image['caption'] : '')
                ."</li>\n";
        }
        $this->_html.=
         "    </ul>\n"
        ."  </div>\n";
    }

    protected function drawImageBullets()
    {
        if (!$this->_isAdmin && !$this->_cp['bullets_show']) {
            return;
        }
        $Obj_GI = new \Gallery_Image;
        $Obj_GI->_set('_current_user_rights', $this->_get('_current_user_rights'));
        $Obj_GI->_set('_safe_ID', $this->_get('_safe_ID'));
        $Obj_GI->_set('_context_menu_ID', 'gallery_image');
        $this->_html.=
             "  <div class=\"ws_bullets\">\n"
            ."    <div>\n";
        for ($i=0; $i<count($this->_images); $i++) {
            $image = $this->_images[$i];
            $Obj_GI->load($image);
            $CM = substr($Obj_GI->convert_Block_Layout("[BL]context_selection_start[/BL]"), 4, -1);
            $this->_html.=
                 "      <a href=\"#\" title=\"".$image['title']."\""
                .$CM.">"
                ."<img src=\"".htmlentities($image['thumbnail'])."\""
                ." alt=\"Thumbnail of ".$image['title']."\""
                ." title=\"Thumbnail of ".$image['title']."\""
                ." height=\"".$image['thumbnail_h']."\""
                ." width=\"".$image['thumbnail_w']."\""
                ."/>".($i+1)
                ."</a>\n";
        }
        $this->_html.=
             "    </div>\n"
            ."  </div>\n";
    }

    protected function drawJs()
    {
        \Output::push(
            "body_bottom",
            "<script type=\"text/javascript\" src=\"".BASE_PATH."lib/ws/common/wowslider.js\"></script>\n"
            ."<script type=\"text/javascript\" src=\"/lib/ws/effects/".$this->_cp['effect']."\"></script>\n"
        );
        \Output::push(
            "javascript_onload",
            "  jQuery('#".$this->_safe_ID."').wowSlider({\n"
            ."    autoPlay:         true,\n"
            ."    bullets:          false,\n"
            ."    caption:          true,\n"
            ."    captionDuration:  ".(1000*$this->_cp['secCaption']).",\n"
            ."    captionEffect:    'move',  // fade|move|slide\n"
            ."    controls:         ".($this->_cp['controls_show']=='1' ? 'true' : 'false').",\n"
            ."    delay:            ".(1000*$this->_cp['secShow']).",\n"
            ."    duration:         ".(1000*$this->_cp['secFade']).",\n"
            ."    effect:           '".$this->_cp['effect']."',\n"
            ."    height:           ".$this->_cp['max_height'].",\n"
            ."    images:           0,\n"
            ."    loop:             false,   // if a number is given, will stop when that number is reached\n"
            ."    next:             '',\n"
            ."    onBeforeStep:     "
            .($this->_cp['random_order']=='1' ?
                "function(curIdx,count){return(curIdx+1 + Math.floor((count-1)*Math.random()));}"
              :
                "0"
             )
            .",\n"
            ."    playPause:true,\n"
            ."    prev:             '',\n"
            ."    preventCopy:      ".($this->_isAdmin ? '0' : '1').",\n"
            ."    revers:           ".$this->_cp['effect_reverse'].",\n"
            //      ."    startSlide:1,\n"
            //      ."    startSlide:Math.round(Math.random()*99999),\n"
            //      ."    stopOn:4,\n"
            ."    stopOnHover:      false,\n"
            //      ."    thumbRate:        1,\n"
            ."    width:            ".$this->_cp['max_width']."\n"
            ."  });\n"
        );
    }

    protected function render()
    {
        return
             "<div id=\"".$this->_safe_ID."\" class='ws'>\n"
            .$this->_html
            ."</div>\n";
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupLoadUserRights();
        $this->setupLoadRecords();
        $this->setupDoSubmode();
        $this->setupImages();
    }

    protected function setupDoSubmode()
    {
        if ($this->_isAdmin && get_var('source')==$this->_safe_ID) {
            $Obj = new \Gallery_Image;
            $this->_msg = $Obj->do_submode();
        }
        switch(get_var('submode')){
            case 'css':
                if (get_var('targetValue')==$this->_safe_ID) {
                    $this->drawCss();
                    die;
                }
                break;
        }
    }

    protected function setupLoadRecords()
    {
        $Obj =              new \Gallery_Image;
        $args =     array(
            'filter_category_list' =>
                $this->_cp['filter_category_list'],
            'filter_category_master' =>
                (isset($this->_cp['filter_category_master']) ?  $this->_cp['filter_category_master'] : false),
            'filter_container_path' =>
                (isset($this->_cp['filter_container_path']) ?   $this->_cp['filter_container_path'] : ''),
            'filter_container_subs' =>
                (isset($this->_cp['filter_container_subs']) ?   $this->_cp['filter_container_subs'] : ''),
            'filter_memberID' =>
                (isset($this->_cp['filter_memberID']) ?         $this->_cp['filter_memberID'] : ''),
            'filter_offset' =>
                (isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0),
            'filter_personID' =>
                (isset($this->_cp['filter_personID']) ?         $this->_cp['filter_personID'] : ''),
            'results_limit' =>
                $this->_cp['results_limit'],
            'results_order' =>
                (isset($this->_cp['results_order']) ?           $this->_cp['results_order'] : 'date')
        );
        $results = $Obj->get_records_matching($args);
        $this->_records = array();
        $this->_records_total = 0;
        foreach ($results['data'] as $record) {
            if ($record['enabled']) {
                $this->_records[] = $record;
                $this->_records_total++;
            }
        }
    }

    protected function setupImages()
    {
        foreach ($this->_records as $record) {
            $_ID =        $record['ID'];
            $_category =  $record['category'];
            $_caption =   \Language::convert_tags($record['content']);
            $_enabled =   $record['enabled'];
            $_image =
                 trim($record['systemURL'], '/')
                ."/img/"
                .($this->_cp['show_watermark'] && !$record['no_watermark'] ? 'wm' : 'resize')
                ."?maintain=0"
                ."&height=".$this->_cp['max_height']
                ."&width=".$this->_cp['max_width']
                ."&img=".$record['thumbnail_small']
                .(isset($record['thumbnail_cs_small']) && $record['thumbnail_cs_small'] ?
                    "&cs=".$record['thumbnail_cs_small']
                  :
                    ""
                 );
            $_thumbnail =
                 trim($record['systemURL'], '/')
                ."/img/sysimg"
                ."?resize=1"
                ."&maintain=".$this->_cp['thumbnail_maintain_aspect']
                ."&height=".$this->_cp['thumbnail_height']
                ."&width=".$this->_cp['thumbnail_width']
                ."&img=".$record['thumbnail_small']
                .(isset($record['thumbnail_cs_small']) && $record['thumbnail_cs_small'] ?
                    "&cs=".$record['thumbnail_cs_small']
                  :
                    ""
                 );
            $_parentID =  ($this->_cp['filter_container_path'] ? $record['parentID'] : 0);
            $_parentTitle =  ($this->_cp['filter_container_path'] ? $record['parentTitle'] : '');
            $_subtype =   $record['subtype'];
            $_title =     ($this->_cp['title_prefix'] ? $this->_cp['title_prefix'] : "").$record['title'];
            $_url =       ($this->_cp['URL'] ?          $this->_cp['URL'] :       $record['URL']);
            $_url_popup = ($this->_cp['URL_popup']==1 ? $this->_cp['URL_popup'] : $record['popup']);
            $this->_images[] = array(
                'ID' =>             $_ID,
                'category' =>       $_category,
                'caption' =>        $_caption,
                'enabled' =>        $_enabled,
                'image' =>          $_image,
                'image_h' =>        $this->_cp['max_height'],
                'image_w' =>        $this->_cp['max_width'],
                'parentID' =>       $_parentID,
                'parentTitle' =>    $_parentTitle,
                'systemID' =>       $record['systemID'],
                'systemURL' =>      $record['systemURL'],
                'systemTitle' =>    $record['systemTitle'],
                'subtype' =>        $_subtype,
                'title' =>          $_title,
                'thumbnail' =>      $_thumbnail,
                'thumbnail_h' =>    $this->_cp['thumbnail_height'],
                'thumbnail_w' =>    $this->_cp['thumbnail_width'],
                'url' =>            $_url,
                'url_popup' =>      $_url_popup
            );
        }
    }
}
