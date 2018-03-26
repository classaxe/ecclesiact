<?php
/*
Version History:
  1.0.1 (2018-03-25)
    1) Bug fix for header text rotation
*/

class Excel_Export extends Record {
    const VERSION = '1.0.1';

    private $ObjPHPExcel;
    private $ObjReport;
    private $ObjWorksheet;

    private $columnList;
    private $columns;
    private $filterField;
    private $filterExact;
    private $filterValue;
    private $primaryTable;
    private $records;
    private $report_record;
    private $targetID;
    private $targetReportID;

    public function draw()
    {
        $this->setupLoadLibrary();
        $this->setupGetVars();
        $this->setupLoadReport();
        $this->setupLoadRecords();
        $this->setupGetFilteredColumns();
        $this->setDocumentProperties();
        $this->setHeaderStyle();
        $this->setBodyStyle();
        $this->drawColumns();
        $this->render();
        die();
    }

    private function drawColumns()
    {
        global $system_vars;
        for ($col=0; $col<count($this->columns); $col++) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col+1).'4';
            $this->ObjWorksheet->setCellValue(
                $cell,
                $this->columns[$col]['textLabel']
            );
            $width = 10;
            for ($row=0; $row<count($this->records); $row++) {
                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col+1).($row+5);

                $this->xmlfields_decode($this->records[$row]);
                $value = (isset($this->records[$row][$this->columns[$col]['reportField']]) ?
                    $this->records[$row][$this->columns[$col]['reportField']]
                    :
                    ""
                );
                $value = str_replace(array("<br />", "&#8211;"), array(" ", "-"), $value);
                $isHyperlink =  false;
                switch ($this->columns[$col]['fieldType']) {
                    case 'bool':
                        $value=($value != '' ? $value : 0);
                        $width = 3;
                        break;
                    case 'currency':
                        $this->ObjWorksheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
                        $width = 12;
                        break;
                    case 'date':
                    case 'datetime':
                        $width = 18;
                        break;
                    case 'file_upload':
                        if ($value) {
                            $isHyperlink =  true;
                            $file_params = $this->get_embedded_file_properties($value);
                            $value = "Download";
                            $url =
                                trim($system_vars['URL'], '/')
                                .BASE_PATH
                                ."?command=download_data"
                                ."&amp;reportID=".$this->targetReportID
                                ."&amp;targetID=".$this->records[$row]['ID']
                                ."&amp;targetValue=".$this->columns[$col]['reportField'];
                            $this->ObjWorksheet->getCell($cell)
                                ->getHyperlink()
                                ->setURL($url);
                            $this->ObjWorksheet->getCell($cell)
                                ->getHyperlink()
                                ->setTooltip(
                                    "Download ".$file_params['name']
                                    ." (".$file_params['type'].", "
                                    .$file_params['size']." bytes)"
                                );
                        }
                        break;
                    case 'int':
                        $width = 5;
                        break;
                    case 'link_view_tickets':
                        if ($value) {
                            $ID_csv = $this->records[$row]['ID_csv'];
                            $isHyperlink =  true;
                            $value = "View Tickets (".strip_tags($value).")";
                            $url =
                                trim($system_vars['URL'], '/')
                                .BASE_PATH
                                ."_ticket?ID=".$ID_csv;
                            $this->ObjWorksheet->getCell($cell)->getHyperlink()->setURL($url);
                            $this->ObjWorksheet->getCell($cell)->getHyperlink()->setTooltip('View Tickets');
                        }
                        $this->ObjWorksheet
                            ->getStyle($cell)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        break;
                    case 'view_order_details':
                        $isHyperlink =  true;
                        $url =
                            trim($system_vars['URL'], '/')
                            .BASE_PATH
                            ."view_order/?print=2&ID=".$value;
                        $this->ObjWorksheet->getCell($cell)->getHyperlink()->setURL($url);
                        $this->ObjWorksheet->getCell($cell)->getHyperlink()->setTooltip('View Order Details');
                        break;
                    case 'view_record_pdf':
                        $isHyperlink =  true;
                        $value = 'PDF';
                        $url =
                            trim($system_vars['URL'], '/')
                            .BASE_PATH
                            ."?command=download_record_pdf"
                            ."&targetID=".$this->records[$row]['ID']
                            ."&columnID=".$this->columns[$col]['ID'];
                        $this->ObjWorksheet->getCell($cell)->getHyperlink()->setURL($url);
                        $this->ObjWorksheet->getCell($cell)->getHyperlink()->setTooltip('Open PDF');
                        break;
                }
                if ($isHyperlink) {
                    $this->ObjWorksheet->getStyle($cell)->getFont()
                        ->setBold(true)
                        ->setUnderline(true)
                        ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
                }
                $this->ObjWorksheet->setCellValue($cell, $value);
            }
            $this->ObjWorksheet->getColumnDimension(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col)
            )->setWidth($width);
        }
    }

    private function render()
    {
        $this->ObjPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"".$this->report_record['reportTitle'].".xlsx\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->ObjPHPExcel, 'Xlsx');
        $objWriter->save('php://output');

    }

    private function setBodyStyle()
    {
        $cellStyle = array(
            'borders' =>    array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => '808080')
                ),
            ),
            'width'
        );
        $this->ObjWorksheet->getStyle(
            'A5:'
            .(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                count($this->columns))
            ).(count($this->records)+4)
        )->applyFromArray($cellStyle);
    }

    private function setHeaderStyle()
    {
        $headerStyle = array(
            'font' =>       array(
                'bold' => true,
                'color' => array('rgb' => 'FFFFFF')
            ),
            'alignment' =>  array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'rotation' => -90,
                'wrapText' => true
            ),

            'borders' =>    array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ),
            ),
            'fill' =>       array(
                'fillType' =>       \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' =>   45,
                'startColor' => array('argb' => 'FF808080'),
                'endColor' =>   array('argb' => 'FFA0A0A0')
            )
        );
        $this->ObjWorksheet->getStyle(
            'A4:'
            .(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns))).'4'
        )->applyFromArray($headerStyle);
    }

    private function setDocumentProperties()
    {
        global $system_vars;
        $author =       get_userFullName();
        $generator =
            System::get_item_version('system_family')." "
            .System::get_item_version('codebase')
            .".".$system_vars['db_version'];
        $subtitle =     "Created ".date('M j Y \a\t H:i', time())." for ".$author;
        $title =        $system_vars['textEnglish'].' > '.$this->report_record['reportTitle'];
        switch ($this->report_record['name']) {
            case "email_job":
                $title.= " > Email Job #".get_var('selectID');
                break;
            case "group_members":
                $Obj_Group = new Group(get_var('selectID'));
                $title.= " > ".$Obj_Group->get_name();
                break;
        }
        $this->ObjPHPExcel
            ->getProperties()
            ->setCreator($author)
            ->setLastModifiedBy($author)
            ->setSubject($this->report_record['reportTitle'])
            ->setTitle($title)
            ->setDescription("Excel export from ".$system_vars['textEnglish']." using ".$generator);
        $this->ObjWorksheet = $this->ObjPHPExcel->setActiveSheetIndex(0);
        $ObjTitle =     new PhpOffice\PhpSpreadsheet\RichText\RichText;
        $ObjSubtitle =  new PhpOffice\PhpSpreadsheet\RichText\RichText;

        $ObjTitle->createTextRun($title)->getFont()->setBold(true);
        $ObjSubtitle->createTextRun($subtitle)->getFont()->setItalic(true);
        $this->ObjWorksheet
            ->setCellValue('A1', $ObjTitle)
            ->setCellValue('A2', $ObjSubtitle);
    }

    private function setupGetFilteredColumns()
    {
        $this->columns =          array();
        foreach ($this->columnList as $_c) {
            switch (strToLower($_c['fieldType'])) {
                case "add":
                case "cancel":
                case "checkbox":
                case "copy":
                case "delete":
                case "password_set":
                    break;
                default:
                    $_c['textLabel'] = get_image_alt($_c['reportLabel']);
                    if ($_c['textLabel'] && $_c['access']=='1') {
                        $this->columns[] = $_c;
                    }
                    break;
            }
        }
    }

    private function setupGetVars()
    {
        $this->targetID =         get_var('targetID');
        $this->targetReportID =   get_var('targetReportID');
        $this->filterField =      get_var('filterField');
        $this->filterExact =      get_var('filterExact');
        $this->filterValue =      get_var('filterValue');
    }

    private function setupLoadLibrary()
    {
        if (file_exists(SYS_SHARED.'vendor/autoload.php')) {
            require SYS_SHARED.'vendor/autoload.php';
        }
        if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            die(
                "<html><h1>Missing Library</h1>"
                ."<p><b>Error</b> the PHP Spreadsheet module needs to be installed.</p>\n"
                ."To install it, open a shell console and enter the following commands:</p>\n"
                ."<p><code><b>cd ".realpath(SYS_SHARED).";<br />\n"
                ."composer require phpoffice/phpspreadsheet</b></code></p>\n"
                ."</html>"
            );
        }
        $this->ObjPHPExcel = new PhpOffice\PhpSpreadsheet\Spreadsheet;
    }

    private function setupLoadRecords()
    {
        global $sortBy;
        // sortBy HAS to be global or this doesn't work

        $filterField_sql = "";
        if ($this->filterField != '') {
            $ObjReportColumn =  new Report_Column;
            $ObjReportColumn->_set_ID($this->filterField);
            $filter_column_record = $ObjReportColumn->get_record();
            if ($filter_column_record['reportID'] == $this->targetReportID) {
                $filterField_sql = $filter_column_record['reportFilter'];
            }
            Report::convert_xml_field_for_filter($filterField_sql, $this->primaryTable);
        }
        Report::get_and_set_sortOrder($this->report_record, $this->columnList, $sortBy);
        $all_records =
            $this->ObjReport->getReportRecords(
                $this->report_record,
                $this->columnList,
                $filterField_sql,
                $this->filterExact,
                $this->filterValue,
                false,
                -1,
                0
            );

        if ($this->targetID) {
            $this->records = array();
            $targetID_arr = explode(',', $this->targetID);
            foreach ($all_records as $record) {
                if (in_array($record['ID'], $targetID_arr)) {
                    $this->records[] = $record;
                }
            }
        } else {
            $this->records = $all_records;
        }
    }

    private function  setupLoadReport()
    {
        $this->ObjReport =      new Report($this->targetReportID);
        $this->primaryTable =   $this->ObjReport->get_field('primaryTable');
        $this->report_record =  $this->ObjReport->get_record();
        $this->columnList =     $this->ObjReport->get_columns();
    }
}