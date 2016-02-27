<?php
namespace Component;
/*
Version History:
  1.0.3 (2016-02-27)
    1) Now uses VERSION class constant for version control
*/
class DocumentReader extends Base
{
    const VERSION = '1.0.3';

    public function __construct()
    {
        $this->_ident =             "document_reader";
        $this->_parameter_spec =    array(
            'cover_file' =>         array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'Path and filename for cover image'),
            'named_pages' =>        array(
                'match' =>      '',
                'default' =>    '1|Front Cover,-1|Back Cover',
                'hint' =>       'csv list of pages'
            ),
            'number_offset' =>      array(
                'match' =>      '',
                'default' =>    '0',
                'hint' =>       'Page on which to start numbering'
            ),
            'pages_filepath' =>     array(
                'match' =>      '',
                'default' =>    '/online_',
                'hint' =>       'path prefix to read'
            ),
            'pages_filetype' =>     array(
                'match' =>      'enum|.gif,.jpg,.png',
                'default' =>    '.png',
                'hint' =>       '.gif|.jpg|.png'
            ),
            'pages_per_image' =>    array(
                'match' =>      '',
                'default' =>    '1',
                'hint' =>       'pages shown per page image'
            ),
            'pages_total' =>        array(
                'match' =>      '',
                'default' =>    '',
                'hint' =>       'total number of pages'
            )
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawJs();
        $this->drawHtml();
        return $this->_html;
    }

    protected function drawHtml()
    {
        $this->drawControlPanel(true);
        $this->_html.=
             "<div id='div_document_reader'>Loading...</div>"
            ."<script type='text/javascript'>document_reader('div_document_reader')</script>";
    }

    protected function drawJs()
    {
        $named_pages_arr =  explode(",", $this->_cp['named_pages']);
        $tmp_page_arr = array();
        foreach ($named_pages_arr as $tmp) {
            $tmp_arr = explode('|', $tmp);
            $tmp_page_arr[] = "    ".pad("'".$tmp_arr[0]."':", 6)."'".$tmp_arr[1]."'";
        }
        $this->_cp['named_pages'] = implode(",\n", $tmp_page_arr);
        \Output::push(
            "javascript",
            "\n// Support for document_reader:\n"
            ."var doc = {\n"
            ."  cover_file:  '".BASE_PATH."img/sysimg/?img=".BASE_PATH.trim($this->_cp['cover_file'], '/')."',\n"
            ."  named_pages: {\n".$this->_cp['named_pages']."\n  },\n"
            ."  number_offset:   ".$this->_cp['number_offset'].",\n"
            ."  pages_filepath:  '".BASE_PATH."img/sysimg/?img=".BASE_PATH.trim($this->_cp['pages_filepath'].'/')."',\n"
            ."  pages_filetype:  '".$this->_cp['pages_filetype']."',\n"
            ."  pages_per_image: ".$this->_cp['pages_per_image'].", \n"
            ."  pages_total:     ".$this->_cp['pages_total']."\n"
            ."}\n"
        );
    }
}
