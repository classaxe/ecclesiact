<?php
/*
Version History:
  1.0.0 (2016-02-28)
    1) Initial release
*/
class CommunityMemberStats extends Community_Member
{
    const VERSION = '1.0.0';

    public function draw()
    {
        $this->setup();
        $r = $this->_record;
        $this->_html.=
             "<table cellpadding='2' cellspacing='0' border='1' width='100%'>\n"
            ."  <thead>\n"
            ."    <tr bgcolor='#dddddd'>\n"
            ."      <th rowspan='3' width='14%'>Month</th>\n"
            ."      <th colspan='4' width='43%'>"
            ."Community of ".$this->_community_record['title']
            ."</th>\n"
            ."      <th colspan='4' width='43%'>".$r['title']."</th>\n"
            ."    </tr>\n"
            ."    <tr bgcolor='#dddddd'>\n"
            ."      <th rowspan='2'>Hits</th>\n"
            ."      <th rowspan='2'>Visits</th>\n"
            ."      <th colspan='2'>Visit Time</th>\n"
            ."      <th rowspan='2'>Hits</th>\n"
            ."      <th rowspan='2'>Visits</th>\n"
            ."      <th colspan='2'>Visit Time</th>\n"
            ."    </tr>\n"
            ."    <tr bgcolor='#dddddd'>\n"
            ."      <th>Avg</th>\n"
            ."      <th>Tot</th>\n"
            ."      <th>Avg</th>\n"
            ."      <th>Tot</th>\n"
            ."    </tr>\n"
            ."  </thead>\n"
            ."  <tbody>\n";
        $community_url =    BASE_PATH.trim($this->_community_record['URL'], '/');
        $member_url =       $community_url.'/'.trim($r['name'], '/');
        $row = 0;
        for ($i=count($this->_stats_dates)-1; $i>=0; $i--) {
            $row++;
            if ($row > 26) {
                continue;
            }
            $YYYYMM = $this->_stats_dates[$i];
            $comm =   $this->_stats[$YYYYMM]['visits'][$community_url];
            $prof =   $this->_stats[$YYYYMM]['visits'][$member_url];
            $link =   $this->_stats[$YYYYMM]['links'];
            $this->_html.=
                 "    <tr".($i%2 ? "" : " bgcolor='#eeeeee'").">\n"
                ."      <td align='right'>".$YYYYMM."</td>\n"
                ."      <td align='right'>".$comm['hits']."</td>\n"
                ."      <td align='right'>".$comm['visits']."</td>\n"
                ."      <td align='right'>".($comm['time_a'] ? format_seconds($comm['time_a']) : "&nbsp;")."</td>\n"
                ."      <td align='right'>".($comm['time_t'] ? format_seconds($comm['time_t']) : "&nbsp;")."</td>\n"
                ."      <td align='right'>".$prof['hits']."</td>\n"
                ."      <td align='right'>".$prof['visits']."</td>\n"
                ."      <td align='right'>".($prof['time_a'] ? format_seconds($prof['time_a']) : "&nbsp;")."</td>\n"
                ."      <td align='right'>".($prof['time_t'] ? format_seconds($prof['time_t']) : "&nbsp;")."</td>\n"
                ."    </tr>\n";
            if (substr($YYYYMM,5,2)=='01') {
                $this->_html.= "<tr bgcolor='#808080'><td colspan='9'></td></tr>";
            }
        }
        $this->_html.=
             "  </tbody>\n"
            ."</table>\n";
        return $this->_html;
    }

    protected function setup()
    {
        $this->_record = $this->load();
        $this->ObjCommunity = new Community($this->record['primary_communityID']);
        $this->_community_record = $this->ObjCommunity->load();
        $this->get_stats();
    }
}
