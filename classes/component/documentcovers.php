<?php
namespace Component;

define("VERSION_NS_COMPONENT_DOCUMENT_COVERS", "1.0.1");
/*
Version History:
  1.0.1 (2015-04-22)
    1) Moved from class.component_document_covers.php and reworked to use namespaces
    2) Now Fully PSR-2 compliant
*/
class DocumentCovers extends Base
{
    public function __construct()
    {
        $this->_ident =             "document_covers";
        $this->_parameter_spec =    array(
            'list' =>                 array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'yyyymm read(y|n),yyyymm read'
            ),
            'columns' =>              array(
                'match' =>      'range|1,n',
                'default' =>    '3',
                'hint' =>       '1..n'
            ),
            'ext' =>                  array(
                'match' =>      'enum|.gif,.jpg,.png',
                'default' =>    '.jpg',
                'hint' =>       '.gif|.jpg|.png'
            ),
            'link_prefix' =>          array(
                'match' =>      '',
                'default' =>    '/online_',
                'hint' =>       'path prefix to read'
            ),
            'link_date_separator' =>  array(
                'match' =>      '',
                'default' =>    '_',
                'hint' =>       '-|_'
            ),
            'months_span' =>          array(
                'match' =>      '',
                'default' =>    '1',
                'hint' =>       'Number of months each issue spans'
            ),
            'path' =>                 array(
                'match' =>      '',
                'default' =>    BASE_PATH.'UserFiles/Image/covers/',
                'hint' =>       'path to images'
            ),
            'path_date_separator' =>  array(
                'match' =>      '',
                'default' =>    '_',
                'hint' =>       '-|_'
            ),
            'width' =>                array(
                'match' =>      '',
                'default' =>    '165',
                'hint' =>       '1..x'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawHtml();
        return $this->_html;
    }

    protected function drawHtml()
    {
        $this->drawControlPanel(true);
        $covers_arr =     explode(",", $this->_cp['list']);
        $covers_arr =     array_reverse($covers_arr);
        $thumbs_arr =     array();
        foreach ($covers_arr as $cover) {
            $cover_arr =    explode(" ", $cover);
            $MM =           substr($cover_arr[0], 4, 2);
            $YYYY =         substr($cover_arr[0], 0, 4);
            $read =         $cover_arr[1];
            $MM_end =       lead_zero((int)($MM + ($this->_cp['months_span']-1))%12, 2);
            $date =
                 MM_to_MMM($MM)
                .($this->_cp['months_span'] > 1 ?
                     " / "
                    .MM_to_MMM($MM_end)
                 :
                    ""
                )
                ." ".$YYYY;
            $title = ($read==1 ? "Click to read" : "Click to enlarge picture");
            $url =
                 "<a href=\"".$this->_cp['link_prefix']
                .$YYYY
                .$this->_cp['link_date_separator']
                .$MM."\" title=\"".$title."\">";
            $picture =
                 "<img alt=\"Cover for ".$date." edition\" class='b border_none' "
                ."src=\"".BASE_PATH."img/width/".$this->_cp['width']
                .$this->_cp['path'].$YYYY.$this->_cp['path_date_separator'].$MM.$this->_cp['ext']."\" />";
            $thumbs_arr[] =
                 "  <div class='txt_c fl' style='padding-right:5px;'>"
                .$url.$picture.$date."</a></div>\n";
        }
        $loop_count =   1 + $this->_cp['columns'] + (count($thumbs_arr)/$this->_cp['columns']);
        for ($i = 0; $i < $loop_count; $i+=$this->_cp['columns']) {
            $this->_html.=
                "<div class='clr_b'>";
            for ($j=0; $j<$this->_cp['columns']; $j++) {
                $this->_html.=  (isset($thumbs_arr[$i+$j]) ? $thumbs_arr[$i+$j] : "");
            }
            $this->_html.=    "</div>";
        }
        $this->_html.=
            "<div class='clear'></div>";
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_DOCUMENT_COVERS;
    }
}
