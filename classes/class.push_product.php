<?php
define('VERSION_PUSH_PRODUCT','1.0.1');
/*
Version History:
  1.0.1 (2018-03-20)
    1) Push_Product::get_selector_sql() now declared as static
*/
class Push_Product extends Product {
    const VERSION = '1.0.1';

    public function __construct($ID=""){
        parent::__construct($ID);
        $this->_set_object_name('Push Product');
    }

    public static function get_selector_sql($getID=false) {
        $isMASTERADMIN =	get_person_permission("MASTERADMIN");
        return
            "SELECT\n"
            ."  'd0d0d0' AS `color_background`,\n"
            ."  '000000' AS `color_text`,\n"
            ."  '(None)' AS `text`,\n"
            ."  '' AS `value`,\n"
            ."  0 AS `seq`\n"
            ."UNION SELECT\n"
            ."  IF(`product`.`systemID`=1,\n"
            ."    'e0e0ff',\n"
            ."    IF(`product`.`systemID`=".SYS_ID.",\n"
            ."      'c0ffc0',\n"
            ."      'ffe0e0'\n"
            ."    )\n"
            ."  ) AS `color_background`,\n"
            ."  '000000' AS `color_text`,\n"
            ."  CONCAT(\n"
            ."    IF(`product`.`systemID` = 1,\n"
            ."      '* ',\n"
            .($isMASTERADMIN ?
                "      CONCAT(UPPER(`system`.`textEnglish`),' | ')\n"
              :
                "      ''\n"
            )
            ."    ),\n"
            ."    `itemCode`,\n"
            ."    ' (',\n"
            ."    (SELECT COUNT(`push_product_assign`.`ID`) FROM `push_product_assign` WHERE `productID` = `product`.`ID`),\n"
            ."    ')'\n"
            ."  ) `text`,\n"
            .($getID ?
                "  `product`.`ID` `value`,\n"
              :
                "  `product`.`itemCode` `value`,\n"
            )
            ."  IF(`product`.`systemID`=1,1,2)\n"
            ."FROM\n"
            ."  `product`\n"
            .($isMASTERADMIN ?
                 "INNER JOIN `system` ON\n"
                ."   `system`.`ID` = `product`.`systemID`\n"
              :
                ""
            )
            ."WHERE\n"
            .($isMASTERADMIN ?
                "  1\n"
              :
                "  `product`.`systemID` IN(1,".SYS_ID.")\n"
            )
            ."ORDER BY\n"
            ."  `seq`,`text`";
    }
}
