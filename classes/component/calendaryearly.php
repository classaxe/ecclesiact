<?php
namespace Component;

define("VERSION_NS_COMPONENT_CALENDAR_YEARLY", "1.0.1");
/*
Version History:
  1.0.1 (2015-03-14)
    1) Moved in here from class.component_calendar_yearly.php
    2) Some refactoring to separate setup and draw operations
    3) Now fully PSR-2 Compliant

*/
class CalendarYearly extends Base
{
    // With ideas from http://www.gaijin.at/en/scrphpcalj.php
    protected $categories = array();
    protected $days =       array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa') ;
    protected $months =     array(
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    );
    protected $events;
    protected $DD;
    protected $MM;
    protected $YYYY;
    protected $memberID;

    public function __construct()
    {
        $this->_ident =     "calendar_yearly";
        $this->_parameter_spec = array(
            'spacing' =>    array(
                'match' => '',
                'default'=>10,
                'hint'=>'0..x'
            ),
            'width' =>      array(
                'match' => '',
                'default'=>800,
                'hint'=>'1..x'
            )
        );
    }



    public function draw($instance = '', $args = array(), $disable_params = false)
    {
        $this->setup($instance, $args, $disable_params);
        $this->drawCss();
        $this->drawControlPanel(true);
        $this->drawHtml();
        return $this->_html;
    }

    protected function drawCss()
    {
        foreach ($this->categories as $item) {
            if ($item['value']) {
                $this->_css.=
                     ".category_".$item['value']." { "
                    ."color:#".$item['color_text']."; "
                    ."background-color:#".$item['color_background'].";} /* ".$item['text']." *"."/\n";
            }
        }
        \Page::push_content('style', "/* Style for category highlighting */\n".$this->_css);
    }

    protected function drawHtml()
    {
        $this->_html.=
             "<table border=\"0\" cellspacing=\"".$this->_cp['spacing']."\">\n"
            ."  <tr>\n"
            ."    <th colspan=\"4\"><h1 style='margin:0'><a href=\"?YYYY=".($this->YYYY-1)."\">&lt;</a> "
            .$this->YYYY." <a href=\"?YYYY=".($this->YYYY+1)."\">&gt;</a></h1></th>\n"
            ."  </tr>\n";
        for ($row=0; $row<3; $row++) {
            $this->_html.= '<tr>';
            for ($column=1; $column<=4; $column++) {
                $MM =  $row * 4 + $column;
                $DD =  date('w', mktime(0, 0, 0, $MM, 1, $this->YYYY))-1;
                $DIM = date('t', mktime(0, 0, 0, $MM, 1, $this->YYYY));
                $width = floor($this->_cp['width']/(4*7));
                $this->_html.=
                     "<td width=\"25%\" valign=\"top\">\n"
                    ."  <table border=1 cellspacing='0' cellpadding='2' class='cal_yearly cal_table'"
                    ." style=\"border-collapse:collapse;font-size:8pt; font-family:Verdana;\"\">\n"
                    ."    <thead>\n"
                    ."      <tr>\n"
                    ."        <th colspan=7 class='cal_head'>"
                    .$this->months[$MM-1]
                    ."</th>\n"
                    ."      </tr>\n"
                    ."      <tr>\n"
                    ."        <th class='cal_days weekend' style=\"width:".$width."px\">".$this->days[0]."</th>\n"
                    ."        <th class='cal_days' style=\"width:".$width."px\">".$this->days[1]."</th>\n"
                    ."        <th class='cal_days' style=\"width:".$width."px\">".$this->days[2]."</th>\n"
                    ."        <th class='cal_days' style=\"width:".$width."px\">".$this->days[3]."</th>\n"
                    ."        <th class='cal_days' style=\"width:".$width."px\">".$this->days[4]."</th>\n"
                    ."        <th class='cal_days' style=\"width:".$width."px\">".$this->days[5]."</th>\n"
                    ."        <th class='cal_days weekend' style=\"width:".$width."px\">".$this->days[6]."</th>\n"
                    ."      </tr>\n"
                    ."    </thead>\n"
                    ."    <tr>\n";
                for ($i=0; $i<=$DD; $i++) {
                    $this->_html.="<td class='cal cal_then'>&nbsp;</td>\n";
                }
                for ($i=1; $i<=$DIM; $i++) {
                    $DOW=($i+$DD)%7;
                    $YYYYMMDD = $this->YYYY.'-'.lead_zero($MM, 2).'-'.lead_zero($i, 2);
                    $events = count($this->events[$YYYYMMDD]);
                    $items = array();
                    $category =    false;
                    if ($events>0) {
                        foreach ($this->events[$YYYYMMDD] as $event) {
                            $items[] = $event['title'];
                        }
                        $category = "category_".$this->events[$YYYYMMDD][0]['category'];
                    }
                    $tooltip =
                    ($events>0 ?
                    ($events>1 ? "EVENTS: (".$events.")\n" : "EVENT:\n")
                    .implode("\n", $items)
                    :
                    ''
                    );
                    $this->_html.=
                         "<td class='cal cal_current"
                        .($events==1 ? " cal_has_event" : "")
                        .($events>1 ? " cal_has_events" : "")
                        .($events>0 ? " ".$category : "")
                        .(($i==$this->DD) && (lead_zero($MM, 2)==$this->MM) ? " cal_today" : "")
                        .($DOW==0 || $DOW==6 ? " cal_current_we" : "")
                        ."'"
                        .($tooltip ? " title=\"".$tooltip."\"" : "")
                        .">"
                        .$i
                        ."</td>\n";
                    if ($DOW==6) {
                        $this->_html.= "</tr>\n<tr>\n";
                    }
                }
                if ($DIM+$DD<35) {
                    for ($i=$i; $i<35-$DD; $i++) {
                        $this->_html.="<td class='cal cal_then'>&nbsp;</td>\n";
                    }
                    $this->_html.= "</tr><tr>";
                    for ($i=0; $i<7; $i++) {
                        $this->_html.="<td class='cal cal_then'>&nbsp;</td>\n";
                    }
                } else {
                    for ($i=$i; $i<42-$DD; $i++) {
                        $this->_html.="<td class='cal cal_then'>&nbsp;</td>\n";
                    }
                }
                $this->_html.=
                     "</tr>\n"
                    ."</table>\n"
                    ."</td>";
            }
            $this->_html.= '</tr>';
        }

        $this->_html.= '</table>';
    }

    protected function setup($instance, $args, $disable_params)
    {
        parent::setup($instance, $args, $disable_params);
        $this->setupInitializeRequestVars();
        $this->setupLoadEvents();
        $this->setupLoadCategories();
    }

    protected function setupInitializeRequestVars()
    {
        global $MM, $YYYY, $DD, $page_vars;
        $this->DD =             $DD;
        $this->MM =             $MM;
        $this->YYYY =           $YYYY;
        $this->memberID =      (isset($page_vars['memberID']) ?  $page_vars['memberID'] : 0);

    }

    protected function setupLoadCategories()
    {
        $Obj = new \ListType;
        $Obj->_set_ID($Obj->get_ID_by_name('Event Category'));
        if (!$listdata = $Obj->get_listdata()) {
            return;
        }
        foreach ($listdata as $item) {
            $this->categories[$item['value']] = array(
                'value' => $item['value'],
                'text' => ($item['value'] ? $item['textEnglish'] : "(No category)"),
                'count'=> 0,
                'color_text' => $item['color_text'],
                'color_background' => $item['color_background']
            );
        }
        sort($this->categories);
    }

    protected function setupLoadEvents()
    {
        $Obj =              new \Event;
        $this->events =     $Obj->get_yearly_dates($this->YYYY, $this->memberID);
    }

    public static function getVersion()
    {
        return VERSION_NS_COMPONENT_CALENDAR_YEARLY;
    }
}
