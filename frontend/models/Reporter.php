<?php
namespace frontend\models;
use yii\base\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use common\models\Budgetyear;
use yii;



class Reporter extends Model
{
    public $financialyear;

    public function __construct($config=[])
    {
        $this->financialyear=Budgetyear::findOne(yii::$app->session->get('financialYear')->yearID);
        parent::init($config);
    }

    public function incomesReportBuilder()
    {
        $mothlyincomes=$this->financialyear->annualbudget->monthlyincomes;
        $otherincomes=$this->financialyear->annualbudget->otherincomes;
        $table="<table><tr><th></th>";

        //building heading
        foreach($mothlyincomes as $mothlyincome)
        {
            $table.="<th>".$mothlyincome->month."</th>";
        }
        $table.="</tr><tr><td>Monthly Collections</td>";

        foreach($mothlyincomes as $mothlyincome)
        {
            $table.="<td>".$mothlyincome->receivedAmount."</td>";
        }

        //other income row
        $table.="</tr>";

        foreach($otherincomes as $otherincome)
        {
            $table.="<tr><td>".$otherincome->incomeType."</td><td>".$otherincome->amount."</td></tr>";
        }
        $table.="</table>";

        return $table;
    }

    public function expensesBuilder()
    {

    }

    public function balanceBuilder()
    {

    }
//sample
    public function downloadPDFReport($ca)
    {
        $content=$ca;
        if($ca!=null)
        {
        $instructor=Yii::$app->user->identity->instructor;
        $name=$instructor->full_name;
        $college=$instructor->department->college->college_name;
        $year=yii::$app->session->get('currentAcademicYear')->title;
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->setFooter('{PAGENO}');
        $course=yii::$app->session->get('ccode');
        $courseTitle=Course::findOne($course)->course_name;
        $stylesheet = file_get_contents('css/capdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetWatermarkText('civeclassroom.udom.ac.tz',0.09);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="125px" height="125px"/></div>',2);
        $mpdf->WriteHTML('<p align="center"><font size=7>The University of Dodoma</font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5>'.$college.'</font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5>'.$course.' '.$courseTitle.'</font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5>Final course assessment results ('.$year.')</font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=3>By '.$name.'</font></p>',3);
        $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">',2);
        $mpdf->WriteHTML($content,3);
          
        $filename=yii::$app->session->get('ccode')."_CA.pdf";
        $filename = str_replace(' ', '', $filename);
        $mpdf->Output($filename,"D");

        return null;
    }
    else
    {
      return 'no content';
    }
    }
//sample
    public function downloadExcelReport()
    {
        $content=$this->incomesReportBuilder();
        if($content!=null)
        {
        $reader = new Html();
        $spreadsheet=new SpreadSheet();
        $spreadsheet = $reader->loadFromString($content);
        $sheet=$spreadsheet->getActiveSheet();
        
        //the logo

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('THTU Logo');
        $drawing->setDescription('THTU Logo');
        $drawing->setPath('img/logo.png');
        $drawing->setHeight(25);
        $drawing->setWorksheet($sheet);
        //setting autoresize and styles
        $styleArray = [
          'font' => [
              'bold' => true,
          ],
          'fill' => [
              'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
              'color' => [
                'argb' => 'FFC4ECFF'
              ]
             
            
      ]];
      //border styles

      $borderstyleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'aa000000'],
            ],
        ],
    ];

       //the styles
     

          $sheet->getStyle('A1:' . $sheet->getHighestColumn().'2')->applyFromArray($styleArray);
          $sheet->getStyle('A1:' . $sheet->getHighestColumn().$sheet->getHighestRow())->applyFromArray($borderstyleArray);
         

    
      
        $list= $sheet->rangeToArray('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow(), '', TRUE, TRUE, TRUE);
        
        //the auto resizing
        for($c=1;$c<=count($list);$c++)
        {
          $col=$list[$c];

          foreach($col as $header=>$cont)
          {

            $sheet->getColumnDimension($header)->setAutoSize(true);
            
          }

        }
   
       
     
        ob_clean();
        $writer=IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        $filename="THTU_FINANCE_ANNUAL_REPORT_".$this->financialyear->startingyear.".Xlsx";
        $filename = str_replace(' ', '', $filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');

        ob_end_clean();
        $writer->save('php://output'); 

        exit();
        return true;
      }
      else
      {
        return 'no content';
      }
    }



}











?>