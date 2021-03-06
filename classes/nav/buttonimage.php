<?php
namespace Nav;
/*
Version History:
  1.0.10 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class ButtonImage extends \Nav\Button
{
    const VERSION = '1.0.10';

    protected $file_prefix =      "btn_";
    protected $_button_data;
    protected $_palette =         array();
    protected $_img;
    protected $_img_base;
    protected $_img_l;
    protected $_img_m;
    protected $_img_r;
    protected $_navstyleID;
    protected $_send_cache_header;
    protected $_template_colour_map;
    protected $_template_ext;
    protected $_template_file;
    protected $_text_height;
    protected $_text_width;
    protected $_total_height;
    protected $_total_width;


    public function __construct($ID = "")
    {
        parent::__construct($ID);
    }

    public function draw($button_data, $filename, $no_show = 0, $navstyleID = false)
    {
        if ($button_data['text1_uppercase']) {
            $button_data['text1'] = $this->getUppercase($button_data['text1']);
        }
        if ($button_data['text2_uppercase']) {
            $button_data['text2'] = $this->getUppercase($button_data['text2']);
        }
        $this->_button_data =   $button_data;
        $this->_navstyleID =    $navstyleID;
        $this->_button_file =   $filename;
        if (!$this->parseTemplateFilename()) {
  //      y($button_data);die;
            return;
        }
  //    y($button_data);die;
      // Steps:
      // 1) Check for image in web home directory path
      // 2) Ensure this is the real home directory -
      //    masteradmin MAY be viewing from another site
      //    If wrong site show 'incorrect system' and exit
      // 3) Found file?   No, try shared buttons dir
      // 4) Found file yet?   No, show template missing image
        $this->_send_cache_header = true;
        if ($this->_button_data['systemID']==SYS_ID || $this->_button_data['systemID']==1) {
  // This button is for current or global system:
            if (file_exists('.'.trim($this->_template_file, '.'))) {
                $this->_template_file = '.'.trim($this->_template_file, '.');
            } else {
                $this->_template_file = SYS_BUTTON_TEMPLATES.$this->_template_file;
                if (!file_exists($this->_template_file)) {
      // No, only potential image doesn't match for this site
                    do_log(
                        3,
                        __CLASS__."::".__FUNCTION__."()",
                        '',
                        "Button template missing - ".$this->_template_file
                    );
                    $this->_send_cache_header = false;
                    $this->_template_file = SYS_IMAGES.'error_template_file_is_missing.gif';
                    $this->_template_ext =  'gif';
                    $this->_button_data['text1'] = '';
                    $this->_button_data['text2'] = '';
                    $this->_button_data['width'] = 99;
                }
            }
        } else {
  // Only look in shared area:
            $this->_template_file = SYS_BUTTON_TEMPLATES.$this->_template_file;
            if (file_exists('.'.trim($this->_template_file, '.'))) {
                $this->_template_file = '.'.trim($this->_template_file, '.');
            } else {
                do_log(
                    3,
                    __CLASS__."::".__FUNCTION__."()",
                    '',
                    "Button template may be on another site - ".$this->_template_file
                );
                if (!$this->_navstyleID) {
                    return;
                }
                $this->_template_file = SYS_IMAGES.'error_template_file_may_be_on_another_site.gif';
                $this->_send_cache_header = false;
                $this->_template_ext =  'gif';
                $this->_button_data['text1'] = '';
                $this->_button_data['text2'] = '';
                $this->_button_data['width'] = 99;
            }
        }
        if ($this->_navstyleID) {
            $this->_button_data['text'] = "auto";
        }
        $this->loadBaseImage();
        if (!$this->_img_base) {
            do_log(3, __CLASS__."::".__FUNCTION__."()", '', "Button template invalid file - ".$this->_template_file);
            $this->_button_data['templateFile'] = SYS_IMAGES.'error_template_file_invalid.gif';
            $this->_button_data['text1'] = '';
            $this->_button_data['text2'] = '';
            $this->_button_data['width'] = 99;
            $this->parseTemplateFilename();
            $this->loadBaseImage();
        }
        $this->getTextSize(
            $this->_button_data['text1_font_face'],
            $this->_button_data['text1_font_size'],
            $this->_button_data['text1'],
            $this->_text_width,
            $this->_text_height
        );
        $this->remapTemplateColors();
        $this->loadImageSections();
        $this->getDimensions();
        $this->_img = imagecreatetruecolor($this->_total_width, $this->_total_height);
        $this->setPalette($this->_img);   // Give higher priority to text - makes it sharper
        $this->drawCanvas();
        $this->addButtonBackground();
        $this->addButtonStyleOverlay();
        $this->addText('text1');
        $this->addTextGlow('text1');
        $this->addTextShadow('text1');
        $this->addText('text1');
        $this->addText('text2');
        $this->addTextGlow('text2');
        $this->addTextShadow('text2');
        $this->addText('text2');
        $this->addButtonForeground();
        $this->cloneSecondHalf();
        $this->addDropdownIndicator();
        $this->setTransparency();
        if (!@ImagePNG($this->_img, $this->_button_file)) {
            $this->releaseResources();
            print
                "<b>Problem:</b> Button image cannot be saved as ".$this->_button_file.".\n"
                ."Is the directory writable?<br />";
            do_log(
                3,
                __CLASS__."::".__FUNCTION__."()",
                '',
                "Button image couldn't be written to ".$this->_button_file." is the directory writable?"
            );
            return;
        }
        $this->releaseResources();
        $this->updateChecksum();
        if ($no_show) {
            return;
        }
        if ($this->_send_cache_header) {
            set_cache(3600*24*365); // expire in one year
        }
        readfile($filename);
        die;       // Advice from Zend at http://www.zend.com/zend/tut/counter.php
    }

    protected function addButtonBackground()
    {
        if (!isset($this->_button_data['icon_under_image']) || $this->_button_data['icon_under_image']=="") {
            return;
        }
        $this->addOverlay(
            $this->_button_data['icon_under_image'],
            $this->_button_data['icon_under_h_align']
        );
    }

    protected function addButtonForeground()
    {
        if (!isset($this->_button_data['icon_over_image']) || $this->_button_data['icon_over_image']=="") {
            return;
        }
        $this->addOverlay(
            $this->_button_data['icon_over_image'],
            $this->_button_data['icon_over_h_align']
        );
    }


    protected function addButtonStyleOverlay()
    {
        $seq_arr = explode(',', $this->_button_data['childID_csv']);
        if ($this->_button_data['ID']==$seq_arr[0] && $this->_button_data['overlay_ba_img']!="") {
            $this->addOverlay(
                $this->_button_data['overlay_ba_img'],
                $this->_button_data['overlay_ba_img_align']
            );
        }
        if ($this->_button_data['ID']!=$seq_arr[0] && $this->_button_data['overlay_bm_img']!="") {
            $this->addOverlay(
                $this->_button_data['overlay_bm_img'],
                $this->_button_data['overlay_bm_img_align']
            );
        }
        if ($this->_button_data['ID']==$seq_arr[count($seq_arr)-1] && $this->_button_data['overlay_bz_img']!="") {
            $this->addOverlay(
                $this->_button_data['overlay_bz_img'],
                $this->_button_data['overlay_bz_img_align']
            );
        }
    }

    protected function addDropdownIndicator()
    {
        switch($this->_button_data['dropdownArrow']) {
            case 1:
                switch ($this->_button_data['orientation']){
                    case "|":
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                ($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_active']
                        );
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                (1*$this->_total_height/4)+($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (1*$this->_total_height/4)+(1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (1*$this->_total_height/4)+(1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_down']
                        );
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                (2*$this->_total_height/4)+($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (2*$this->_total_height/4)+(1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (2*$this->_total_height/4)+(1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_normal']
                        );
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                (3*$this->_total_height/4)+($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (3*$this->_total_height/4)+(1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (3*$this->_total_height/4)+(1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_over']
                        );
                        break;
                    default:
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (1*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (1*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (1*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_active']
                        );
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (2*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (2*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (2*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_down']
                        );
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (3*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (3*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (3*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_normal']
                        );
                        ImageFilledPolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (4*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (4*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (4*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_over']
                        );
                        break;
                }
                break;
            case 2:
                switch ($this->_button_data['orientation']){
                    case "|":
                        ImagePolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                ($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_active']
                        );
                        ImagePolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                (1*$this->_total_height/4)+($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (1*$this->_total_height/4)+(1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (1*$this->_total_height/4)+(1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_down']
                        );
                        ImagePolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                (2*$this->_total_height/4)+($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (2*$this->_total_height/4)+(1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (2*$this->_total_height/4)+(1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_normal']
                        );
                        ImagePolygon(
                            $this->_img,
                            array(
                                $this->_total_width-7,
                                (3*$this->_total_height/4)+($this->_total_height/8)+4,
                                $this->_total_width-7,
                                (3*$this->_total_height/4)+(1*$this->_total_height/8)-4,
                                $this->_total_width-3,
                                (3*$this->_total_height/4)+(1*$this->_total_height/8)
                            ),
                            3,
                            $this->_palette['text1_font_color_over']
                        );
                        break;
                    default:
                        ImagePolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (1*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (1*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (1*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_active']
                        );
                        ImagePolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (2*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (2*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (2*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_down']
                        );
                        ImagePolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (3*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (3*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (3*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_normal']
                        );
                        ImagePolygon(
                            $this->_img,
                            array(
                                ($this->_total_width)-12,
                                (4*$this->_total_height/4)-7,
                                ($this->_total_width)-4,
                                (4*$this->_total_height/4)-7,
                                ($this->_total_width-8),
                                (4*$this->_total_height/4)-3
                            ),
                            3,
                            $this->_palette['text1_font_color_over']
                        );
                        break;
                }
                break;
        }
    }

    protected function addText($text_index)
    {
        $this->addTextOverlays($text_index, 'active', $this->_palette[$text_index.'_font_color_active']);
        $this->addTextOverlays($text_index, 'down', $this->_palette[$text_index.'_font_color_down']);
        $this->addTextOverlays($text_index, 'normal', $this->_palette[$text_index.'_font_color_normal']);
        $this->addTextOverlays($text_index, 'over', $this->_palette[$text_index.'_font_color_over']);
    }

    protected function addTextGlow($text_index)
    {
        $this->addTextGlowState($text_index, 'active');
        $this->addTextGlowState($text_index, 'down');
        $this->addTextGlowState($text_index, 'normal');
        $this->addTextGlowState($text_index, 'over');
    }

    protected function makeMatrix($template, $max)
    {
        $out = array();
        foreach ($template as $row) {
            $ok = true;
            foreach ($row as $cell) {
                if ($cell>$max) {
                    $ok=false;
                    break;
                }
            }
            if ($ok) {
                $out[] = $row;
            }
        }
        return $out;
    }

    protected function getMatrixTemplate()
    {
        return array(
            array(0,  0,  0,  0,  0,  0,  5,  0,  0,  0,  0,  0,  0),
            array(0,  0,  0,  0,  0,  5,  10, 5,  0,  0,  0,  0,  0),
            array(0,  0,  0,  0,  5,  10, 20, 10, 5,  0,  0,  0,  0),
            array(0,  0,  0,  5,  10, 20, 30, 20, 10, 5,  0,  0,  0),
            array(0,  0,  5,  10, 20, 30, 40, 30, 20, 10, 5,  0,  0),
            array(0,  5,  10, 20, 30, 40, 50, 40, 30, 20, 10, 5,  0),
            array(5,  10, 20, 30, 40, 50, 0,  50, 40, 30, 20, 10, 5),
            array(0,  5,  10, 20, 30, 40, 50, 40, 30, 20, 10, 5,  0),
            array(0,  0,  5,  10, 20, 30, 40, 30, 20, 10, 5,  0,  0),
            array(0,  0,  0,  5,  10, 20, 30, 20, 10, 5,  0,  0,  0),
            array(0,  0,  0,  0,  5,  10, 20, 10, 5,  0,  0,  0,  0),
            array(0,  0,  0,  0,  0,  5,  10, 5,  0,  0,  0,  0,  0),
            array(0,  0,  0,  0,  0,  0,  5,  0,  0,  0,  0,  0,  0)
        );

    }

    protected function addTextGlowState($text_index, $state)
    {
        if ($this->_button_data[$text_index.'_effect_type_'.$state]!='glow') {
            return;
        }
        $intensity =    $this->_button_data[$text_index.'_effect_level_'.$state];
        if (!$intensity) {
            return;
        }
        switch ($intensity){
            case 1: $max = 20;
                break;
            case 2: $max = 30;
                break;
            case 3: $max = 40;
                break;
            case 4: $max = 50;
                break;
        }
        $template = $this->getMatrixTemplate();
        $this->addTextEffectFromMatrix($text_index, $state, $this->makeMatrix($template, $max), 0, 0);
    }

    protected function addTextShadow($text_index)
    {
        $this->addTextShadowState($text_index, 'active');
        $this->addTextShadowState($text_index, 'down');
        $this->addTextShadowState($text_index, 'normal');
        $this->addTextShadowState($text_index, 'over');
    }

    protected function addTextShadowState($text_index, $state)
    {
        if ($this->_button_data[$text_index.'_effect_type_'.$state]!='shadow') {
            return;
        }
        $intensity =    $this->_button_data[$text_index.'_effect_level_'.$state];
        if (!$intensity) {
            return;
        }
        switch ($intensity){
            case 1: $max = 10;
                break;
            case 2: $max = 20;
                break;
            case 3: $max = 30;
                break;
            case 4: $max = 40;
                break;
        }
        $template = $this->getMatrixTemplate();
        $this->addTextEffectFromMatrix($text_index, $state, $this->makeMatrix($template, $max), 2, 1);
    }

    protected function addTextEffectFromMatrix($text_index, $state, $matrix, $xoffset = 0, $yoffset = 0)
    {
        $y_count =      count($matrix);
        $y_min =        -1 * $y_count/2;
        $y_max =         1 * $y_count/2;
        $x_count =      (count($matrix) ? count($matrix[0]) : 0);
        $x_min =        -1 * $x_count/2;
        $x_max =         1 * $x_count/2;
        $color =        $this->_palette[$text_index.'_effect_color_'.$state];
        $alpha_array =  array();
        for ($y = 0; $y < $y_count; $y++) {
            for ($x = 0; $x < $x_count; $x++) {
                $alpha =    $matrix[$y][$x];
                if (!isset($alpha_array[$alpha])) {
                    $alpha_array[$alpha]= array();
                }
                $alpha_array[$alpha][] =  array($x_min+$x+1, $y_min+$y+1);
            }
        }
        foreach ($alpha_array as $alpha => $offsets_array) {
            $this->addTextOverlays(
                $text_index,
                $state,
                $color,
                $alpha,
                $offsets_array,
                $xoffset,
                $yoffset
            );
        }
    }

    protected function addTextOverlays(
        $text_index,
        $state,
        $color,
        $alpha = 100,
        $offset_arrays = false,
        $xoffset = 0,
        $yoffset = 0
    ) {
        if (!$offset_arrays) {
            $offset_arrays = array(
            array(0,0)
            );
        }
        $text =         $this->_button_data[$text_index];
        if ($text=='' || $alpha==0) {
            return;
        }
        $font_face =    $this->_button_data[$text_index.'_font_face'];
        $font_size =    $this->_button_data[$text_index.'_font_size'];
        if ($font_size==0 || $font_face=='') {
            return;
        }
        $font_file =    SYS_FONTS.$font_face.".ttf";
        if (!file_exists($font_file)) {
            die("Error - Font not found - ".$font_face.".ttf");
        }
        $txt_lines =      explode("\n", $text);
        $text_h_align =   (
        (isset($this->_button_data[$text_index.'_h_align']) ?
            $this->_button_data[$text_index.'_h_align']
         :
            "|--"
        ));
        $button_width =   $this->_total_width/2;
        $button_height =  $this->_total_height/4;
        $margin_left =    imagesx($this->_img_l);
        $margin_right =   imagesx($this->_img_r);
        if ($alpha!=100) {
            $img = imagecreatetruecolor($this->_total_width, $this->_total_height);
            $this->setPalette($img);
            imagecopy($img, $this->_img, 0, 0, 0, 0, $this->_total_width, $this->_total_height);
        }
        foreach ($offset_arrays as $offset_array) {
            $text_h_offset =  $this->_button_data[$text_index.'_h_offset'] + $offset_array[0] + $xoffset;
            $text_v_offset =  $this->_button_data[$text_index.'_v_offset'] + $offset_array[1] + $yoffset;
            for ($i=0; $i<count($txt_lines); $i++) {
                $txt_line = $txt_lines[$i];
                $line_num = $i+1;
                $this->getTextSize($font_face, $font_size, $txt_line, $line_width, $line_height);
                switch ($text_h_align) {
                    case "|--":
                        $xpos =    $text_h_offset + $margin_left;
                        break;
                    case "-|-":
                        $xpos =    $text_h_offset + ($button_width/2) - ($line_width/2);
                        break;
                    case "--|":
                        $xpos =    $text_h_offset + $button_width - $line_width - $margin_right;
                        break;
                }
                $text_block_height =    count($txt_lines)*$line_height;
                switch($state){
                    case 'active':
                        $pos =    0;
                        $nudge =  0;
                        $txt_line =   ($this->_navstyleID ? 'Active' : $txt_line);
                        break;
                    case 'down':
                        $pos =    1;
                        $nudge =  1;
                        $txt_line =   ($this->_navstyleID ? 'Down' : $txt_line);
                        break;
                    case 'normal':
                        $pos =    2;
                        $nudge =  0;
                        $txt_line =   ($this->_navstyleID ? 'Normal' : $txt_line);
                        break;
                    case 'over':
                        $pos =    3;
                        $nudge =  0;
                        $txt_line =   ($this->_navstyleID ? 'Over' : $txt_line);
                        break;
                }
                $ypos =
                ($nudge) +
                ($pos*$button_height) +
                ($button_height/2) -
                ($text_block_height/2) +
                ($line_num*$line_height) +
                ($text_v_offset);
                ImageTTFText(
                    ($alpha!=100 ? $img : $this->_img),
                    $font_size,
                    0,
                    $xpos,
                    $ypos,
                    $color,
                    $font_file,
                    $txt_line
                );
            }
        }

        if ($alpha!=100) {
            ImageColorTransparent($img, $this->_palette['transparent']);
            ImageCopyMerge(
                $this->_img,
                $img,
                0,
                $pos*$this->_total_height/4,
                0,
                $pos*$this->_total_height/4,
                $this->_total_width/2,
                $this->_total_height/4,
                $alpha
            );
            ImageDestroy($img);
        }
    }

    protected function addOverlay($filename, $h_align)
    {
        $filename_arr = explode(",", $filename);
        $filename =     $filename_arr[0];
        if (!file_exists($filename)) {
            $filename =   '.'.trim($filename, '.');
        }
        if (!file_exists($filename)) {
            $filename = SYS_BUTTON_TEMPLATES.$filename_arr[0];
        }
        if (!file_exists($filename)) {
            return;
        }
        array_shift($filename_arr);
        $tmp = explode('.', $filename);
        $icon_ext = $tmp[count($tmp)-1];
        switch ($icon_ext) {
            case "gif":
                $img_overlay =    imageCreateFromGif($filename);
                break;
            case "jpg":
            case "jpeg":
                $img_overlay =    imageCreateFromJpeg($filename);
                break;
            case "png":
                $img_overlay =    imageCreateFromPng($filename);
                break;
            default:
                return;
            break;
        }
        for ($i=0; $i<count($filename_arr); $i++) {
            \Image_Factory::setColorIndex($img_overlay, $i, $filename_arr[$i]);
        }
        $img_overlay_w =   imagesx($img_overlay);
        $img_overlay_h =   imagesy($img_overlay);
        switch($h_align) {
            case "|--":
                $overlay_l =   0;
                break;
            case "-|-":
                $overlay_l =   ($this->_total_width/4)-($img_overlay_w/2);
                break;
            case "--|":
                $overlay_l =   ($this->_total_width/2)-($img_overlay_w);
                break;
        }
        if ($img_overlay_h>$this->_total_height/4) {
            ImageCopy($this->_img, $img_overlay, $overlay_l, 0, 0, 0, $img_overlay_w, $img_overlay_h);
        } else {
            for ($i=0; $i<4; $i++) {
                ImageCopy(
                    $this->_img,
                    $img_overlay,
                    $overlay_l,
                    $i*$this->_total_height/4,
                    0,
                    0,
                    $img_overlay_w,
                    $img_overlay_h
                );
            }
        }
        ImageDestroy($img_overlay);
    }

    protected function getUppercase($string)
    {
        return strTr(
            strToUpper($string),
            array(
                "à" => "À",  "&agrave;" => "À",  "Ã " => "À",
                "á" => "Á",  "&aacute;" => "Á",  "Ã¡" => "Á",
                "â" => "Â",  "&acirc;"  => "Â",  "Ã¢" => "Â",
                "ä" => "Ä",  "&auml;"   => "Ä",  "Ã¤" => "Ä",
                "ã" => "Ã",  "&atilde;" => "Ã",  "Ã£" => "Ã",
                "å" => "Å",  "&aring;" =>  "Å",  "Ã¥" => "Å",

                "è" => "È",  "&egrave;" => "È",  "Ã¨" => "È",
                "é" => "É",  "&eacute;" => "É",  "Ã©" => "É",
                "ê" => "Ê",  "&ecirc;" =>  "Ê",  "Ãª" => "Ê",
                "ë" => "Ë",  "&euml;" =>   "Ë",  "Ã«" => "Ë",

                "ì" => "Ì",  "&igrave;" => "Ì",  "Ã¬" => "Ì",
                "í" => "Í",  "&iacute;" => "Í",  "Ã­" => "Í",
                "î" => "Î",  "&icirc;" =>  "Î",  "Ã®" => "Î",
                "ï" => "Ï",  "&iuml;" =>   "Ï",  "Ã¯" => "Ï",

                "ò" => "Ò",  "&ograve;" => "Ò",  "Ã²" => "Ò",
                "ó" => "Ó",  "&oacute;" => "Ó",  "Ã³" => "Ó",
                "ô" => "Ô",  "&ocirc;" =>  "Ô",  "Ã´" => "Ô",
                "õ" => "Õ",  "&otilde;" => "Õ",  "Ãµ" => "Õ",
                "ö" => "Ö",  "&ouml;" =>   "Ö",  "Ã¶" => "Ö",

                "ù" => "Ù",  "&ugrave;" => "Ù",  "Ã¹" => "Ù",
                "ú" => "Ú",  "&uacute;" => "Ú",  "Ãº" => "Ú",
                "û" => "Û",  "&ucirc;" =>  "Û",  "Ã»" => "Û",
                "ü" => "Ü",  "&uuml;" =>   "Ü",  "Ã¼" => "Ü",

                "ý" => "Ý",  "&yacute;" => "Ý",  "Ã½" => "Ý",
                "ÿ" => "Ÿ",  "&yuml;" =>   "Ÿ",  "Ã¿" => "Ÿ",  // Not supported in fonts

                "æ" => "Æ",  "&aelig;" =>  "Æ",  "Ã¦" => "Æ",
                "ç" => "Ç",  "&ccedil;" => "Ç",  "Ã§" => "Ç",
                "ñ" => "Ñ",  "&ntilde;" => "Ñ",  "Ã±" => "Ñ",
                "œ" => "Œ",  "&oelig;" =>  "Œ",  "Å“" => "Œ",  // Not supported in fonts
            )
        );
    }

    protected function cloneSecondHalf()
    {
        imagecopy(
            $this->_img,
            $this->_img,
            $this->_total_width/2,
            0,
            0,
            0,
            $this->_total_width/2,
            $this->_total_height
        );
    }

    protected function drawCanvas()
    {
        ImageCopyMerge(
            $this->_img,
            $this->_img_l,
            0,
            0,
            0,
            0,
            imagesx($this->_img_l),
            imagesy($this->_img_l),
            100
        );
        @ImageCopyResized(
            $this->_img,
            $this->_img_m,
            imagesx($this->_img_l),
            0,
            0,
            0,
            ($this->_total_width - imagesx($this->_img_l) - imagesx($this->_img_r))/2,
            imagesy($this->_img),
            imagesx($this->_img_m),
            imagesy($this->_img_m)
        );
        ImageCopyMerge(
            $this->_img,
            $this->_img_r,
            ($this->_total_width/2 - imagesx($this->_img_r)),
            0,
            0,
            0,
            imagesx($this->_img_r),
            imagesy($this->_img_r),
            100
        );
    }

    protected function getDimensions()
    {
        $this->_total_height =    imagesy($this->_img_l);
        if ($this->_button_data['width']!=0) {
            $this->_total_width = $this->_button_data['width'] * 2;
            return;
        }
        if ($this->_button_data['navsuite_width']!=0) {
            $this->_total_width = $this->_button_data['navsuite_width'] * 2;
            return;
        }
        $this->_total_width = (imagesx($this->_img_l) + $this->_text_width + imagesx($this->_img_r)) * 2;
    }

    protected function hasTransparency()
    {
        $w = imagesx($this->_img);
        $h = imagesy($this->_img);
        for ($y=0; $y<$h; $y++) {
            for ($x=0; $x<$w; $x++) {
                $rgb =  imagecolorat($this->_img, $x, $y);
                $r =    ($rgb >> 16) & 0xFF;
                $g =    ($rgb >> 8) & 0xFF;
                $b =    $rgb & 0xFF;
                if ($r==18 && $g==52 && $b==86) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function loadBaseImage()
    {
        switch ($this->_template_ext) {
            case "gif":
                $this->_img_base =    @imageCreateFromGif($this->_template_file);
                break;
            case "jpg":
            case "jpeg":
                $this->_img_base =    @imageCreateFromJpeg($this->_template_file);
                break;
            case "png":
                $this->_img_base =    @imageCreateFromPng($this->_template_file);
                break;
            default:
                return false;
            break;
        }
        return ($this->_img_base ? true : false);
    }

    protected function loadImageSections()
    {
        $img_base_w =    imagesx($this->_img_base);
        $img_base_h =    imagesy($this->_img_base);
        $this->_img_l =    ImageCreate(($img_base_w/2), $img_base_h);
        $this->_img_m =    ImageCreate(1, $img_base_h);
        $this->_img_r =    ImageCreate(($img_base_w/2)+1, $img_base_h);
        ImageCopy($this->_img_l, $this->_img_base, 0, 0, 0, 0, ($img_base_w/2), $img_base_h);
        ImageCopy($this->_img_m, $this->_img_base, 0, 0, ($img_base_w/2), 0, 1, $img_base_h);
        ImageCopy($this->_img_r, $this->_img_base, 0, 0, ($img_base_w/2), 0, ($img_base_w/2)+1, $img_base_h);
    }

    protected function parseTemplateFilename()
    {
        if (!isset($this->_button_data['templateFile']) || !$this->_button_data['templateFile']) {
            return false;
        }
        $filename_arr =                 explode(",", $this->_button_data['templateFile']);
        $this->_template_file =         array_shift($filename_arr);
        $this->_template_colour_map =   $filename_arr;
        $filename_arr =                 explode(".", $this->_template_file);
        $this->_template_ext =          array_pop($filename_arr);
        return true;
    }

    protected function releaseResources()
    {
        ImageDestroy($this->_img);
        ImageDestroy($this->_img_l);
        ImageDestroy($this->_img_m);
        ImageDestroy($this->_img_r);
    }

    protected function setPalette(&$img)
    {
        $this->_palette['transparent'] =
            \Image_Factory::allocateColor($img, '123456');
        $this->_palette['text1_font_color_active'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_font_color_active']);
        $this->_palette['text1_font_color_down'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_font_color_down']);
        $this->_palette['text1_font_color_normal'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_font_color_normal']);
        $this->_palette['text1_font_color_over'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_font_color_over']);
        $this->_palette['text1_effect_color_active'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_effect_color_active']);
        $this->_palette['text1_effect_color_down'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_effect_color_down']);
        $this->_palette['text1_effect_color_normal'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_effect_color_normal']);
        $this->_palette['text1_effect_color_over'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text1_effect_color_over']);
        $this->_palette['text2_font_color_active'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_font_color_active']);
        $this->_palette['text2_font_color_down'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_font_color_down']);
        $this->_palette['text2_font_color_normal'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_font_color_normal']);
        $this->_palette['text2_font_color_over'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_font_color_over']);
        $this->_palette['text2_effect_color_active'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_effect_color_active']);
        $this->_palette['text2_effect_color_down'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_effect_color_down']);
        $this->_palette['text2_effect_color_normal'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_effect_color_normal']);
        $this->_palette['text2_effect_color_over'] =
            \Image_Factory::allocateColor($img, $this->_button_data['text2_effect_color_over']);
    }

    protected function setTransparency()
    {
        if ($this->hasTransparency()) {
            ImageColorTransparent($this->_img, $this->_palette['transparent']);
        } else {
            imageTrueColorToPalette($this->_img, false, 1024);
        }
    }

    protected function remapTemplateColors()
    {
        for ($i=0; $i<count($this->_template_colour_map); $i++) {
            \Image_Factory::setColorIndex($this->_img_base, $i, $this->_template_colour_map[$i]);
        }
    }

    protected function updateChecksum()
    {
        $Obj =      new \FileSystem;
        $checksum = $Obj->get_file_checksum($this->_button_file);
        $data =
        array(
        'img_checksum' =>   $checksum,
        'img_height' =>     $this->_total_height/4,
        'img_width' =>      $this->_total_width/2
        );
        if (!$this->_navstyleID) {
            $this->update($data);
        } else {
            $Obj = new \Nav\Style($this->_navstyleID);
            $Obj->update($data);
        }
    }

    public function getTextSize($font_face, $font_size, $text, &$width, &$height)
    {
        $height = 0;
        $width =  0;
        if ($text=="" || $font_face=="" || $font_size==0) {
            return 0;
        }
        $arr_bbox =    imagettfbbox($font_size, 0, SYS_FONTS.$font_face.".ttf", $text);
        $width =     ($arr_bbox[2]-$arr_bbox[6]);
        $arr_bbox =    imagettfbbox($font_size, 0, SYS_FONTS.$font_face.".ttf", "Ij");
        $height =     $arr_bbox[3]-$arr_bbox[7];
    }

    public function getButtonBaseSize($record, &$width, &$height)
    {
        if (!isset($record['templateFile'])) {
            return false;
        }
        if ($record['templateFile']=="") {
            return false;
        }
        $btn_image_arr = explode(",", $record['templateFile']);
        $btn_image =     trim($btn_image_arr[0]);
        if ($record['systemID']==SYS_ID) {
            if (file_exists('.'.trim($btn_image, '.'))) {
                $btn_image = '.'.trim($btn_image, '.');
            } else {
                $btn_image = SYS_BUTTON_TEMPLATES.$btn_image;
                if (!file_exists($btn_image)) {
                    return false;
                }
            }
        } else {
          // Only look in shared area:
            $btn_image = SYS_BUTTON_TEMPLATES.$btn_image;
            if (!file_exists($btn_image)) {
                return false;
            }
        }
        $filename_arr =     explode(".", $btn_image);
        $btn_ext =          array_pop($filename_arr);
        switch ($btn_ext) {
            case "gif":
                $tmp =    imageCreateFromGif($btn_image);
                break;
            case "jpg":
            case "jpeg":
                $tmp =    imageCreateFromJpeg($btn_image);
                break;
            case "png":
                $tmp =    imageCreateFromPng($btn_image);
                break;
            default:
                return false;
            break;
        }
        $img_base_w =    imagesx($tmp);
        $img_base_h =    imagesy($tmp);
        $width =        $img_base_w-1;
        $height =       $img_base_h/4;
        return true;
    }
}
