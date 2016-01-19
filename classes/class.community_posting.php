<?php
/* Custom Fields used:
custom_1 = denomination (must be as used in other SQL-based controls)

/*
Version History:
  1.0.6 (2016-01-18)
    1) Fixes for PHP 5.6 - no more 'magic this' passing, now uses delegate pattern throughout
    2) Community_Posting::_get_records_get_sql() ->       Community_Posting::getRecordsGetSqlWithDelegate()
    3) Community_Posting::BL_shared_source_link() ->      Community_Posting::BLsharedSourceLinkWithDelegate()
    4) Community_Posting::BL_mini_shared_source_link() -> Community_Posting::BLminiSharedSourceLinkWithDelegate()
*/

class Community_Posting extends Posting
{
    const VERSION = '1.0.6';

    public static function getRecordsGetSqlWithDelegate($Obj)
    {
        return
             "SELECT\n"
            ."  `system`.`textEnglish` `systemTitle`,\n"
            ."  `system`.`URL` `systemURL`,\n"
            ."  COALESCE(\n"
            ."    (SELECT `title`          FROM `community_member` `cm` WHERE `cm`.`ID` = `postings`.`memberID`),\n"
            ."    ''\n"
            ."  ) `member_title`,\n"
            ."  COALESCE(\n"
            ."    (SELECT `name`           FROM `community_member` `cm` WHERE `cm`.`ID` = `postings`.`memberID`),\n"
            ."    ''\n"
            ."  ) `member_url`,\n"
            ."  COALESCE(\n"
            ."    (SELECT `shortform_name` FROM `community_member` `cm` WHERE `cm`.`ID` = `postings`.`memberID`),\n"
            ."    ''\n"
            ."  ) `member_shortform`,\n"
            ."  `postings`.*\n"
            ."FROM\n"
            ."  `postings`\n"
            ."INNER JOIN `system` ON\n"
            ."  `postings`.`systemID` = `system`.`ID`\n"
            .($Obj->_show_latest_for_each_member ?
                 "INNER JOIN (SELECT\n"
                ."    `memberID` `m_ID`,\n"
                ."     MAX(`date`) `m_date`\n"
                ."  FROM\n"
                ."    `postings`\n"
                ."  WHERE\n"
                .$Obj->_get_records_get_sql_filter_date()
                .($Obj->_get_records_args['category']!="*" ?
                     "  `postings`.`category` REGEXP \""
                    .implode("|", explode(',', $Obj->_get_records_args['category']))
                    ."\" AND\n"
                :
                    ""
                )
                .($Obj->_get_records_args['category_master'] ?
                     "  `postings`.`category` REGEXP \""
                    .implode("|", explode(',', $Obj->_get_records_args['category_master']))
                    ."\" AND\n"
                 :
                    ""
                )
                ."    `type`='".$Obj->_get_type()."' AND\n"
                ."    `permSHARED`=1 AND\n"
                ."    (\n"
                ."      `memberID` IN(\n"
                ."         SELECT\n"
                ."           `memberID`\n"
                ."         FROM\n"
                ."           `community_membership`\n"
                ."         WHERE\n"
                ."           `communityID` = ".$Obj->community_record['ID']."\n"
                ."       ) OR\n"
                ."      `communityID` = ".$Obj->community_record['ID']."\n"
                ."    )\n"
                ."  GROUP BY\n"
                ."    `m_ID`\n"
                .") `m` ON\n"
                ."  `m_ID` = `memberID` AND\n"
                ."  `m_date` = `date`\n"
            :
                ""
            )
            ."WHERE\n"
            ."  `postings`.`type` = '".$Obj->_get_type()."' AND\n"
            ."  `postings`.`permSHARED` = 1 AND\n"
            .$Obj->_get_records_get_sql_filter_date()
            .($Obj->_get_records_args['category']!="*" ?
                 "  `postings`.`category` REGEXP \""
                .implode("|", explode(',', $Obj->_get_records_args['category']))
                ."\" AND\n"
             :
                ""
            )
            .($Obj->_get_records_args['category_master'] ?
                 "  `postings`.`category` REGEXP \""
                .implode("|", explode(',', $Obj->_get_records_args['category_master']))
                ."\" AND\n"
             :
                ""
            )
            ."  `postings`.`systemID` = '".$Obj->_get_systemID()."' AND\n"
            ."  (\n"
            ."    `postings`.`memberID` IN(\n"
            ."       SELECT\n"
            ."         `memberID`\n"
            ."       FROM\n"
            ."         `community_membership`\n"
            ."       WHERE\n"
            ."         `communityID` = ".$Obj->community_record['ID']."\n"
            ."     ) OR\n"
            ."     `postings`.`memberID`=0 AND `postings`.`communityID`= ".$Obj->community_record['ID']."\n"
            ."   )";
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

    public static function BLSharedSourceLinkWithDelegate($Obj, $anchor='')
    {
        $URL =          $Obj->community_record['URL'].'/'.$Obj->record['member_url'].$anchor;
        $title =
            $Obj->record['member_title'] ?
                $Obj->record['member_title']
            :
                "The Community of ".$Obj->community_record['title'];
        $shortform =
            $Obj->record['member_shortform'] ?
                $Obj->record['member_shortform']
            :
                "Community of ".$Obj->community_record['title'];
        $href =
             "<a class='shared_source' rel=\"external\""
            ." href=\"".$URL."\""
            ." title=\"Shared by ".str_replace('& ', '&amp; ', $title)." - click to visit\""
            .">";
        return
             $href
            ."<img src='".BASE_PATH."img/spacer' class='icons'"
            ." style='padding:0;margin:0 2px 0 0;height:13px;width:15px;background-position:-1173px 0px;'"
            ." alt=\"External content from ".str_replace('& ', '&amp; ', $Obj->record['member_title'])."\" />\n"
            ."</a> "
            .$href
            ."<b>".str_replace('& ', '&amp; ', $shortform)."</b></a>";
    }

    public static function BLMiniSharedSourceLinkWithDelegate($Obj, $anchor)
    {
        $URL =          $Obj->community_record['URL'].'/'.$Obj->record['member_url'].$anchor;
        $title =
            $Obj->record['member_title'] ?
                $Obj->record['member_title']
            :
                "The Community of ".$Obj->community_record['title'];
        $shortform =
            $Obj->record['member_shortform'] ?
                $Obj->record['member_shortform']
            :
                "Community of ".$Obj->community_record['title'];
        $href =
             "<a class='shared_source' href=\"".$URL."\""
            ." title=\"Shared by ".str_replace('& ', '&amp; ', $title)." - click to visit\""
            .">";
        return
             "<div style='padding:2px'>"
            .$href
            ."<img src='".BASE_PATH."img/spacer' class='icons'"
            ." style='padding:0;margin:0 2px 0 0;height:8px;width:10px;background-position:-6144px 0px;'"
            ." alt=\"External content from ".str_replace('& ', '&amp; ', $Obj->record['member_title'])."\" />\n"
            ."</a> "
            .$href
            .str_replace('& ', '&amp; ', $shortform)."</a></div>";
    }
}
