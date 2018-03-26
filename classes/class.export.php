<?php
/*
Version History:
  1.0.29 (2018-03-24)
    1) Excel code moved into new class called ExcelExport
*/
class Export extends Record
{
    const VERSION = '1.0.29';

    public function draw()
    {
        global $submode, $report_name, $targetID;
        switch ($submode) {
            case "excel":
                $Obj = new Excel_Export;
                print $Obj->draw();
                break;
            case "icalendar":
                $Obj = new Event($targetID);
                $Obj->export_icalendar();
                die;
            break;
            default:
                switch ($report_name) {
                    case 'report_filters':
                      // There IS no such report so just fake it:
                        $Obj = new Report_Filter;
                        $Obj->_set_ID($targetID);
                        $result = $Obj->sql_export($targetID, 1);
                        header("Content-type: text/plain; charset=UTF-8");
                        print $result;
                        die;
                    break;
                    case 'system':
                        break;
                    default:
                        header("Content-type: text/plain; charset=UTF-8");
                        break;
                }
                $this->ObjReport = new Report;
                $this->ObjReport->_set_ID($this->ObjReport->get_ID_by_name($report_name));
                $reportPrimaryObjectName = $this->ObjReport->get_field('primaryObject');
                $Obj = $this->ObjReport->get_ObjPrimary($report_name, $reportPrimaryObjectName);
                $result = (method_exists($Obj, 'exportSql') ?
                    $Obj->exportSql($targetID, 1)
                 :
                    $Obj->export_sql($targetID, 1)
                );
                print $result;
                exit;
            break;
        }
    }
}
