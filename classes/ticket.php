<?php
/*
Version History:
  1.0.0 (2015-10-16)
    1) Newly added to allow for override of export SQL function

*/
class Ticket extends Order
{
    const VERSION = '1.0.0';

    public function __construct($ID = "")
    {
        parent::__construct("orders", $ID);
        $this->_set_assign_type('order');
        $this->_set_has_archive(true);
        $this->_set_has_categories(true);
        $this->_set_object_name('Order');
        $this->_set_message_associated('and tickets');
    }

    public function exportSql($targetID, $show_fields)
    {
        $header =
             "Selected ".$this->_get_object_name().$this->plural($targetID)
            ." with history, items and tickets";
        $extra_delete =
             "DELETE FROM `orders`                 WHERE `archive`=1 AND `archiveID` IN(".$targetID.");\n"
            ."DELETE FROM `order_items`            WHERE `orderID` IN(".$targetID.");\n"
            ."DELETE FROM `registerevent`          WHERE `orderID` IN(".$targetID.");\n";
        $Obj = new Backup;
        $extra_select =
            $Obj->db_export_sql_query(
                "`orders`                ",
                "SELECT * FROM `orders` WHERE `archive`=1 AND `archiveID` IN(".$targetID.") "
                ."ORDER BY `archiveID`,`history_created_date`",
                $show_fields
            )
            .$Obj->db_export_sql_query(
                "`order_items`           ",
                "SELECT * FROM `order_items` WHERE `orderID` IN(".$targetID.") ORDER BY `orderID`",
                $show_fields
            )
            .$Obj->db_export_sql_query(
                "`registerevent`         ",
                "SELECT * FROM `registerevent` WHERE `orderID` IN(".$targetID.") ORDER BY `orderID`",
                $show_fields
            )
            ."\n";
        return parent::sqlExport($targetID, $show_fields, $header, '', $extra_delete, $extra_select);
    }
}
