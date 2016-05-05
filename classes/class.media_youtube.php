<?php
/*
Version History:
  1.0.7 (2016-05-05)
    1) Modified to make clips use same transfer protocol as site to avoid mixed protocol warning messages
*/
class Media_Youtube extends Base
{
    const VERSION = '1.0.7';

    protected $url;
    protected $width;
    protected $height;
    protected $start = 0;

    public function __construct($url = "", $width = 425, $height = 350, $start = 0)
    {
        $this->url =
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? "https:" : "http:")
            .substr($url, strpos($url, '/'));
        $this->width =  $width;
        $this->height = $height;
        if ($start) {
            $this->start = hhmmss_to_seconds($start);
        }
    }

    public function draw_clip()
    {
        return
             "<a class=\"iframe\""
            ." href=\"".$this->url."?wmode=transparent&amp;rel=0".($this->start ? "&amp;start=".$this->start : "")."\""
            ." rel=\"frameborder=0|height=".$this->height."|scrolling=no|width=".$this->width."\""
            .">Embedded Content</a>";
    }
}
