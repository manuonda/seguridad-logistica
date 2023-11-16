<?php
namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;



class DownloadExcel extends BaseController
{
    public function createExcel()
    {
        /*
        **PARAMETRO QUE SE RECIBE COMO UN JSON
         */
        $data = json_decode($_POST['data']);

        $encabezado = $_POST['encabezado'];

        $fileName = 'data.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $columnas = ['','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],                        
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFCCFFCC'],
            ],            
        ];        

        $band = true;

        for ($i = 0, $l = sizeof($data); $i < $l; $i++) { // row $i
            $j = 1;
            foreach ($data[$i] as $k => $v) { // column $j
                if (!empty($encabezado[0][$k])) { 
                    if ($band){
                        $sheet->setCellValueByColumnAndRow($j, 1, $encabezado[0][$k]);
                        $sheet->getStyle($columnas[$j].'1')->applyFromArray($styleArray2);
                        $sheet->getColumnDimension($columnas[$j])->setAutoSize(true);                        
                    }
                    $sheet->setCellValueByColumnAndRow($j, ($i + 1 + 1), $v);

                    $sheet->getStyle($columnas[$j].($i+2))->applyFromArray($styleArray);
                    // $sheet->getStyle($columnas[$j].($i+2))
                    //     ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                    // $sheet->getStyle($columnas[$j].($i+2))
                    //     ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                    // $sheet->getStyle($columnas[$j].($i+2))
                    //     ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                    // $sheet->getStyle($columnas[$j].($i+2))
                    //     ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);                    
                    $j++;
                }
            }
            $band = false;
        }
        /*******************este bloque funciona para llamadas que no son via ajax*******************
        $writer = new Xlsx($spreadsheet);        

        $writer->save($fileName);
                
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="'. $fileName.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('pragma: public');
        header('Content-Lenght:' . filesize($fileName));        
        flush();
        readfile($fileName);
        
        exit();
        */

        /*******************************ESTE BLOQUE ES PARA LLAMADAS AJAX*****************************************************/
       //$writer = IOFactory::createWriter($spreadsheet, 'Html');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');        

        ob_start();
        $writer->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();
                   
        $response =  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
        );
    
        die(json_encode($response));
        
                   
    }

}