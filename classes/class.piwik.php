<?php
/*
Version History:
  1.0.6 (2019-01-06)
    1) Added several new methods to get stats in bulk:
           Piwik::getSiteVisitsForMonths()
           Piwik::getVisitsForMonths()
           Piwik::getOutlinksForMonths()
    2) Older methods, now presumed to be unused will be removed later
*/
class Piwik extends System
{
    const VERSION = '1.0.6';
    private $_base_URL;
    private $_idSite;
    private $_token_auth;
    private $_URL;
    protected $_date;
    protected $_period;

    public function __construct()
    {
        global $system_vars;
        $this->_base_URL =      trim($system_vars['URL'],'/').'/piwik/';
        $this->_idSite =        $system_vars['piwik_id'];
        $this->_token_auth =    $system_vars['piwik_token'];
    }

    public function isOnline()
    {
        global $system_vars;
        return
            $system_vars['piwik_online'] &&
            $this->_idSite &&
            $this->_token_auth;
    }

    public function getServerVersion()
    {
        if (!$this->isOnline()) {
            return false;
        }
        $params =   array(
            'module=API',
            'method=API.getPiwikVersion',
            'token_auth='.$this->_token_auth
        );
        $request =  implode('&', $params);
        $url =      $this->_base_URL.'?'.$request;

        $ch =       curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        $xml_doc =   curl_exec($ch);
        curl_close($ch);
        $out =              new SimpleXMLElement($xml_doc);

        return $out->result;
    }

    private function do_xml_request($params, $debug = false)
    {
        $params[] =         "module=API";
        $params[] =         "format=xml";
        $params[] =         "idSite=".$this->_idSite;
        $params[] =         "token_auth=".$this->_token_auth;
        $this->_request =   implode('&',$params);
        $Obj_CURL =         new Curl($this->_base_URL,$this->_request);
        $xml_doc =          $Obj_CURL->get();
        if (strpos($xml_doc, '<')===false) {
            return false;
        }
        $out =              new SimpleXMLElement($xml_doc);
        //    print $this->_base_URL.'?'.$this->_request."<pre>".print_r($out,true)."</false>";
        if ($debug) {
            print $this->_base_URL.'?'.$this->_request;
            print "<pre>".print_r($out,true)."</pre>";
        }
        return $out;
    }

    public function setup($date_start = '', $date_end = '')
    {
        $this->_date_start =    $date_start;
        $this->_date_end =      $date_end;
        $this->_get_period();
        $this->_get_date();
    }

    protected function _get_date()
    {
        if (strlen($this->_date_start)==4){
            $this->_date_start.='-01-01';
        }
        if (strlen($this->_date_start)==7){
            $this->_date_start.='-01';
        }
        if (strlen($this->_date_end)==4){
            $this->_date_end.='-01-01';
        }
        if (strlen($this->_date_end)==7){
            $this->_date_end.='-01';
        }
        $this->_date =
            ($this->_date_start && $this->_date_end ?
                ($this->_date_start==$this->_date_end ?
                    $this->_date_start
                 :
                    $this->_date_start.",".$this->_date_end
                )
             :
                $this->_date_start
            );
    }

    protected function _get_period()
    {
        if (($this->_date_start && $this->_date_end) && $this->_date_start!==$this->_date_end) {
            $this->_period='range';
            return;
        }
        switch (strlen($this->_date_start)) {
            case 4:
                $this->_period='year';
                break;
            case 7:
                $this->_period='month';
                break;
            default:
                $this->_period='day';
                break;
        }
    }

    public function get_outlink($date_start = '', $date_end = '', $urls = '')
    {
        $this->setup($date_start, $date_end);
        $url_arr =  explode('|',$urls);
        $out =      array();
        foreach($url_arr as $url) {
            if ($url==='') {
                $out[$url] = array(
                    'hits' =>   0,
                    'visits' => 0
                );
                continue;
            }
            $params =       array();
            $params[] =     "method=Actions.getOutlink";
            $params[] =     "outlinkUrl=".$url;
            $params[] =     "period=".$this->_period;
            $params[] =     "date=".$this->_date;
            $xml =          $this->do_xml_request($params);
            if ($xml !== false) {
                $out[$url] =  array(
                    'hits' =>   (int)(string)$xml->row->nb_hits,
                    'visits' => (int)(string)$xml->row->nb_visits
                );
            }
        }
        return $out;
    }

    public function get_outlinks($date_start = '', $date_end = '')
    {
        $this->setup($date_start, $date_end);
        $params =       array();
        $params[] =     "method=Actions.getOutlinks";
        $params[] =     "period=".$this->_period;
        $params[] =     "date=".$this->_date;
        $params[] =     "expanded=1";
        $xml =          $this->do_xml_request($params);
        if ($xml === false) {
            return false;
        }
        $out = array();
        foreach ($xml->row as $type => $node) {
            $url =    (string)$node->url;
            $out[$url] =  array(
                'hits' =>   (string)$node->nb_hits,
                'visits' => (string)$node->nb_visits
            );
            $this->_get_visits_for_subtable($node, $out);
        }
        ksort($out);
        return $out;
    }

    public function getSiteVisitsForMonths($date_start='', $date_end='')
    {
        $out =      [];
        $step =     '+1 month';
        $format =   'Y-m';
        $dates_to_check = get_dates_in_range($date_start, $date_end, $step, $format);
        $this->setup($date_start, $date_end);
        $params = [
            'method=VisitsSummary.get',
            'period=month',
            'date='.$this->_date
        ];
        $xml =          $this->do_xml_request($params);
        if ($xml === false) {
            return $out;
        }
        $dates = array_values($dates_to_check);
        foreach ($dates as $key => $date) {
            $out[$date] = [
                'visits' =>     (int)$xml->result[$key]->nb_visits,
                'actions' =>    (int)$xml->result[$key]->nb_actions,
                'time_a' =>     (int)$xml->result[$key]->avg_time_on_site,
                'time_t' =>     (int)$xml->result[$key]->sum_visit_length,
            ];
        }
        return $out;
    }

    public function getVisitsForMonths($date_start='', $date_end='', $urls='')
    {
        $out =      [];
        $step =     '+1 month';
        $format =   'Y-m';
        $dates_to_check = get_dates_in_range($date_start, $date_end, $step, $format);

        $this->setup($date_start, $date_end);
        $find_arr = explode('|', $urls);
        foreach($find_arr as $url) {
            $params =       array();
            $params[] =     "method=Actions.getPageUrl";
            $params[] =     "pageUrl=".$url;
            $params[] =     "period=month";
            $params[] =     "date=".$this->_date;
            $xml =          $this->do_xml_request($params);
            if ($xml !== false) {
                $dates = array_values($dates_to_check);
                foreach ($dates as $idx => $date) {
                    $out[$url][$date] = array(
                        'hits' =>   (string)$xml->result[$idx]->row->nb_hits,
                        'visits' => (string)$xml->result[$idx]->row->nb_visits,
                        'time_a' => (string)$xml->result[$idx]->row->avg_time_on_page,
                        'time_t' => (string)$xml->result[$idx]->row->sum_time_spent,
                        'bounce' => (string)$xml->result[$idx]->row->bounce_rate
                    );
                }
            }
        }
        return $out;
    }

    public function getOutlinksForMonths($date_start='', $date_end='', $urls='')
    {
        $out =      [];
        $step =     '+1 month';
        $format =   'Y-m';
        $dates_to_check = get_dates_in_range($date_start, $date_end, $step, $format);

        $this->setup($date_start, $date_end);
        $url_arr =  explode('|',$urls);
        $out =      [];
        foreach($url_arr as $url) {
            $params = [
                'method=Actions.getOutlink',
                'outlinkUrl='.$url,
                'period=month',
                'date='.$this->_date
            ];
            $xml =          $this->do_xml_request($params);
            if ($xml !== false) {
                $out[$url] = [];
                foreach ($dates_to_check as $idx => $YYYYMM) {
                    $out[$url][$YYYYMM] =  array(
                        'hits' =>   (int)(string)$xml->result[$idx]->row->nb_hits,
                        'visits' => (int)(string)$xml->result[$idx]->row->nb_visits
                    );
                }
            }
        }
        return $out;
    }

    public function get_visit($date_start='', $date_end='', $urls='')
    {
        $out =      array();
        $this->setup($date_start, $date_end);
        $find_arr = explode('|', $urls);
        foreach($find_arr as $url) {
            if ($url==='') {
                $out[$url] = array(
                    'hits' =>   0,
                    'visits' => 0,
                    'time_a' => 0,
                    'time_t' => 0,
                    'bounce' => 0
                );
                continue;
            }
            $params =       array();
            $params[] =     "method=Actions.getPageUrl";
            $params[] =     "pageUrl=".$url;
            $params[] =     "period=".$this->_period;
            $params[] =     "date=".$this->_date;
            $xml =          $this->do_xml_request($params);
            if ($xml !== false) {
                $out[$url] =  array(
                    'hits' =>   (string)$xml->row->nb_hits,
                    'visits' => (string)$xml->row->nb_visits,
                    'time_a' => (string)$xml->row->avg_time_on_page,
                    'time_t' => (string)$xml->row->sum_time_spent,
                    'bounce' => (string)$xml->row->bounce_rate
                );
            }
        }
        //    y($xml);y($params);die;
        return $out;
    }

    public function get_visits($date_start='',$date_end='',$find=''){
        $this->setup($date_start, $date_end);
        $params =       array();
        $params[] =     "method=Actions.getPageUrls";
        $params[] =     "period=".$this->_period;
        $params[] =     "date=".$this->_date;
        $params[] =     "expanded=1";
        $params[] =     "filter_limit=-1";
        if ($find){
            $params[] =   "filter_column_recursive=label";
            $params[] =   "filter_pattern_recursive=".$find;
        }
        $xml =          $this->do_xml_request($params);
        if ($xml === false) {
            return false;
        }
        $out = array();
        //    y($xml);die;
        foreach ($xml->row as $type=>$node) {
            $url_arr  =   explode('?',(string)$node->url);
            $url =        $url_arr[0];
            if (!isset($out[$url])) {
                $out[$url] =  array(
                    'hits' =>   0,
                    'visits' => 0
                );
            }
            $out[$url]['hits']+=(int)(string)$node->nb_hits;
            $out[$url]['visits']+=(int)(string)$node->nb_visits;
            $this->_get_visits_for_subtable($node, $out);
        }
        ksort($out);
        //    y($out);
        return $out;
    }

    public function _get_visits_for_subtable($node, &$out)
    {
        foreach($node->subtable as $subtable) {
            foreach ($subtable->row as $type=>$node) {
                $url_arr  =   explode('?',(string)$node->url);
                $url =        $url_arr[0];
                if (!isset($out[$url])) {
                    $out[$url] =  array(
                        'hits' =>   0,
                        'visits' => 0
                    );
                }
                $out[$url]['hits']+=(int)(string)$node->nb_hits;
                $out[$url]['visits']+=(int)(string)$node->nb_visits;
                $this->_get_visits_for_subtable($node, $out);
            }
        }
    }
}
