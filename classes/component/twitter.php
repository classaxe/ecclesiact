<?php
namespace Component;

/*
Version History:
  1.0.1 (2017-12-13)
    1) Implemented handling of system constant DEBUG_NO_INTERNET where set
*/
class Twitter extends Base
{
    const VERSION = '1.0.1';

    public function __construct()
    {
        $this->_ident =            "twitter_profile";
        $this->_parameter_spec =   array(
            'height' =>           array(
                'match' =>      'range|0,n',
                'default' =>    '500',
                'hint' =>       'Height for panel'
            ),
            'limit' =>           array(
                'match' =>      'range|1,20',
                'default' =>    '',
                'hint' =>       'Limit 1-20 or blank for no limit'
            ),
            'user' =>             array(
                'match' =>      '',
                'default' =>    'twitter',
                'hint' =>       'Twitter account to link to'
            ),
            'widget-id' =>        array(
                'match' =>      'range|0,n',
                'default' =>    '728714986382626816',
                'hint' =>       'Widget ID as created on Twitter site'
            ),
            'width' =>           array(
                'match' =>      'range|0,n',
                'default' =>    '300',
                'hint' =>       'Width for panel'
            ),
        );
    }

    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        global $system_vars;
        $this->setup($instance, $args, $disable_params);
        $this->drawControlPanel(true);
        if (DEBUG_NO_INTERNET || $system_vars['debug_no_internet']==1) {
            $this->_html.="<b>Twitter Panel Offline</b> -<br />No Internet Connection";
            return $this->_html;
        }
        $this->_html.=
            "<a class='twitter-timeline'  href='https://twitter.com/"
           .$this->_cp['user']
           ."'"
           ." data-widget-id='".$this->_cp['widget-id']."'"
           .($this->_cp['height'] ? " height='".$this->_cp['height']."'" : "")
           .($this->_cp['width'] ?  " width='".$this->_cp['width']."'" : "")
           .($this->_cp['limit'] ?  " data-tweet-limit='".$this->_cp['limit']."'" : "")
           .">"
//           ."' data-widget-id='729659523309416448'>"
           ."Tweets by @".$this->_cp['user']
           ."</a>"
           ."<script type=\"text/javascript\">\n"
           ."!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p="
           ."/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;"
           ."js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}("
           ."document,'script','twitter-wjs');</script>";
        return $this->_html;
    }
}
