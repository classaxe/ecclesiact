<?php
/*
Version History:
  1.0.9 (2020-10-24)
    1) Now supports fullscreen mode
*/
class Media_Youtube extends Base
{
    const VERSION = '1.0.9';

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
        global $system_vars;
        if (DEBUG_NO_INTERNET || $system_vars['debug_no_internet']) {
            return
                 "<div style=\"width:".$this->width."px;height:".$this->height."px;background:#a0c0a0;\">"
                ."<div style=\"line-height:".$this->height."px;text-align: center; font-size:14pt;\">"
                ."YouTube Clip (No Internet Connection)</div>"
                ."</div>";

        }
        return
             "<a class=\"iframe\""
            ." href=\"".$this->url."?wmode=transparent&amp;rel=0".($this->start ? "&amp;start=".$this->start : "")."\""
            ." rel=\"height=".$this->height."|width=".$this->width."|scrolling=no|frameborder=0|allow=accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture|allowfullscreen=allowfullscreen\""
            .">Embedded Content</a>";
    }
}
