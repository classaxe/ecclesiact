<?php
define('COMMUNITY_MEMBER_POSTING_VERSION', '1.0.4');
/*
Version History:
  1.0.5 (2016-01-18)
    1) Fixes for PHP 5.6 - no more 'magic this' passing, now uses delegate pattern throughout
    2) Community_Member_Posting::_get_records_get_sql() ->       Community_Member_Posting::getRecordsGetSqlWithDelegate()
    3) Community_Member_Posting::BL_shared_source_link() ->      Community_Member_Posting::BLsharedSourceLinkWithDelegate()
    4) Community_Member_Posting::BL_mini_shared_source_link() -> Community_Member_Posting::BLminiSharedSourceLinkWithDelegate()
*/

class Community_Member_Posting extends Posting
{
    const VERSION = '1.0.5';

    public static function getRecordsGetSqlWithDelegate($Obj)
    {
        $sql =
             "SELECT\n"
            ."  (SELECT `textEnglish` FROM `system` WHERE `postings`.`systemID` = `system`.`ID`) `systemTitle`,\n"
            ."  (SELECT `URL` FROM `system` WHERE `postings`.`systemID` = `system`.`ID`) `systemURL`,\n"
            ."  (SELECT `title` FROM `community_member` WHERE `community_member`.`ID` = `postings`.`memberID`) `member_title`,\n"
            ."  (SELECT `name` FROM `community_member` WHERE `community_member`.`ID` = `postings`.`memberID`) `member_url`,\n"
            ."  `postings`.*\n"
            ."FROM\n"
            ."  `postings`\n"
            ."WHERE\n"
            ."  `postings`.`type` = '".$Obj->_get_type()."' AND\n"
            ."  (\n"
            ."    (`postings`.`memberID` = ".$Obj->memberID.") OR\n"
            ."    (\n"
            ."      `postings`.`permSHARED`=1 AND\n"
            ."      `postings`.`important`=1 AND\n"
            ."      `postings`.`memberID` IN(\n"
            ."        SELECT\n"
            ."          `cm1`.`ID`\n"
            ."        FROM\n"
            ."          `community_member` `cm1`\n"
            ."        WHERE\n"
            ."          `cm1`.`primary_communityID` IN(\n"
            ."            SELECT `cm2`.`primary_communityID` FROM `community_member` `cm2` WHERE `cm2`.`ID` = ".$Obj->memberID."\n"
            ."          )\n"
            ."      )\n"
            ."    )\n"
            .($Obj->partner_csv ? " OR (`memberID` IN(".$Obj->partner_csv.") AND `postings`.`permSHARED`=1)" : "")
            .") AND\n"
            .$Obj->_get_records_get_sql_filter_date()
            .($Obj->_get_records_args['category']!="*" ?    "  `postings`.`category` REGEXP \"".implode("|", explode(',', $Obj->_get_records_args['category']))."\" AND\n": "")
            .($Obj->_get_records_args['category_master'] ?  "  `postings`.`category` REGEXP \"".implode("|", explode(',', $Obj->_get_records_args['category_master']))."\" AND\n": "")
            ."  `postings`.`systemID` = '".$Obj->_get_systemID()."'\n";
      //    z($sql);
        return $sql;
    }

    public static function BLCategoryWithDelegate($Obj)
    {
        if (!isset($Obj->_cp['category_show']) || $Obj->_cp['category_show']!='1') {
            return '';
        }
        if ($Obj->record['category']=='') {
            return '';
        }
        $Obj_Category = new Category;
        $categories = array();
        $category_csv = explode(",", $Obj->record['category']);
        foreach ($category_csv as $cat) {
            $categories[$cat] = $cat;
        }
        $categories = $Obj_Category->get_labels_for_values(
            "'".implode("','", array_keys($categories))."'",
            "'Community Posting Category'"
        );
        $category_text = implode(", ", $categories);
        return $category_text;
    }

    public static function BLMiniSharedSourceLinkWithDelegate($Obj, $anchor = '')
    {
        if ($Obj->record['memberID']==$Obj->memberID) {
            return;
        }
        $href =
             "<a class='shared_source' style='color:#fff;text-decoration:none;' rel=\"external\""
            ." href=\"".$Obj->community_record['URL'].'/'.$Obj->record['member_url'].$anchor."\""
            ." title=\"Shared by ".$Obj->record['member_title']." - click to visit\""
            .">";
        return
             "<div class='lce_shared css3'>"
            .$href
            ."<img src='".BASE_PATH."img/spacer' class='icons'"
            ." style='padding:0;margin:0 2px 0 0;height:8px;width:10px;background-position:-6144px 0px;'"
            ." alt=\"External content from ".$Obj->record['member_title']."\" />\n"
            ."</a> "
            .$href."<b>".$Obj->record['member_title']."</b></a></div>";
    }

    public static function BLSharedSourceLinkWithDelegate($Obj, $anchor = '')
    {
        if ($Obj->record['memberID']==$Obj->memberID) {
            return;
        }
        $href =
             "<a class='shared_source' href=\"".$Obj->community_URL.'/'.$Obj->record['member_url'].$anchor."\""
            ." title=\"Shared by ".$Obj->record['member_title']." - click to visit\""
            ." rel=\"external\">";
        return
             $href
            ."<img src='".BASE_PATH."img/spacer' class='icons'"
            ." style='padding:0;margin:0 2px 0 0;height:13px;width:15px;background-position:-1173px 0px;'"
            ." alt=\"External content from ".$Obj->record['systemTitle']."\" />\n"
            ."</a> "
            .$href.$Obj->record['member_title']."</a>";
    }
}
