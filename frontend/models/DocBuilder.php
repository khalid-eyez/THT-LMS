<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Mpdf\Mpdf;

class DocBuilder extends Model{

    
  private function spreadsheet($content,$logocoord)
  {
    if($content!=null)
    {
    $reader = new Html();
    $spreadsheet=new SpreadSheet();
    $spreadsheet = $reader->loadFromString($content);
    $sheet=$spreadsheet->getActiveSheet();
    
    //the logo

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
    $drawing->setName('THTU Logo');
    $drawing->setDescription('THTU Logo');
    $drawing->setPath('img/logo.png');
    $drawing->setHeight(90);
    $drawing->setCoordinates($logocoord.'1');

    //centering image

      $colWidth = $sheet->getColumnDimension($logocoord)->getWidth();
      if ($colWidth == -1) { 
      $colWidthPixels = 64; 
      } else {                
      $colWidthPixels = $colWidth * 7.0017094; 
      }
      $offsetX = $colWidthPixels + ($drawing->getWidth());
      $drawing->setOffsetX($offsetX); 

     //setting autoresize and styles
     $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'color' => [
            'argb' => 'FF01FF00'
          ],
        
          
         
        
  ]];

  //header styles

  $headerArray = [
    'font' => [
      'bold' => true,
      'size'=>'20'
  ]
  ];
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
     

     $sheet->getStyle('A1:' . $sheet->getHighestColumn().'1')->applyFromArray($styleArray);
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

        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->insertNewRowBefore(1);
        $sheet->getStyle('A1:' . $sheet->getHighestColumn().'8')->getAlignment()->setHorizontal('center');
  
        $drawing->setWorksheet($sheet);
        return $spreadsheet;
  }

  
}
    private function sendExcelAttendance($content,$meeting)
    {
        
        $spreadsheet=$this->spreadsheet($content,'D');
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.2);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.2);
        $spreadsheet->getActiveSheet()->setCellValue('D6', $meeting->meetingTitle." - ".date_format(date_create($meeting->meetingTime),"d/m/Y"));
        $spreadsheet->getActiveSheet()->setCellValue('D8',"ATTENDANCE");
        $spreadsheet->getActiveSheet()->getStyle('A6:' . $spreadsheet->getActiveSheet()->getHighestColumn().'8')->getFont()->setSize(15);
        $spreadsheet->getActiveSheet()->getStyle('A8:' . $spreadsheet->getActiveSheet()->getHighestColumn().'8')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->setShowGridlines(false);
        ob_clean();
        $writer=IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        $filename=$meeting->meetingTitle."_".date_format(date_create($meeting->meetingTime),"d-m-Y")."_Attendance.Xlsx";
        $filename=str_replace(" ","-",$filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');

        ob_end_clean();
        $writer->save('php://output'); 

        exit();
        return true;
     
        
    }

   public function downloadExcelAttendance($meeting)
   {
    $attended=$meeting->meetingattendances;
    if($attended==null){throw new \Exception("No Attendees Found");}
     $data="<table><tr><td>#</td><td>Full Name</td><td>Branch</td><td>E-mail</td><td>Rank</td><td>Decision</td>";
     $count=0;
     foreach($attended as $index=>$attendee)
     {
      $count++;
      $name=$attendee->member->fullName();
      $branch=$attendee->member->branch();
      $email=$attendee->member->email;
      $rank=$attendee->member->getRank();
      $rows="<tr><td>{$count}</td><td>{$name}</td><td>{$branch}</td><td>{$email}</td><td>{$rank}</td><td></td>";
      $data.=$rows;
     }
     $data.="</table>";

     $this->sendExcelAttendance($data,$meeting);

   }
    
   private function generatePdfAttendanceContent($meeting)
   {
    $attended=$meeting->meetingattendances;
    if($attended==null){throw new \Exception("No Attendees Found");}
     $data="<table><tr class='bg-success text-bold'><td>#</td><td>Full Name</td><td>Branch</td><td>E-mail</td><td>Rank</td><td>Decision</td>";
     $count=0;
     foreach($attended as $index=>$attendee)
     {
      $count++;
      $name=$attendee->member->fullName();
      $branch=$attendee->member->branch();
      $email=$attendee->member->email;
      $rank=$attendee->member->getRank();
      $rows="<tr><td>{$count}</td><td>{$name}</td><td>{$branch}</td><td>{$email}</td><td>{$rank}</td><td></td>";
      $data.=$rows;
     }
     $data.="</table>";

     return $data;
   }
    public function attendancePdfdownloader($meeting)
    {
        $title=$meeting->meetingTitle." - ".date_format(date_create($meeting->meetingTime),"d/m/Y");
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->setFooter('{PAGENO}');
        $stylesheet = file_get_contents('css/capdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetWatermarkText('THTU - THTU',0.09);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="125px" height="125px"/></div>',2);
        $mpdf->WriteHTML('<p align="center"><font size=5>'.$title.'</font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=6>ATTENDANCE</font></p>',3);
        $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">',2);
        $mpdf->WriteHTML($this->generatePdfAttendanceContent($meeting),3);
          
        $filename=$meeting->meetingTitle."_".date_format(date_create($meeting->meetingTime),"d-m-Y")."_Attendance.pdf";
        $filename=str_replace(" ","-",$filename);
        $mpdf->Output($filename,"D");

        return null;
    
  }
  

  private function getCAsnumber($course)
  {
    $year=yii::$app->session->get('currentAcademicYear');
    $yearTitle=$year->title;
    $ca_location='storage/CAs/'.$yearTitle.'/'.$course;
    $cashome="storage/CAs/".$yearTitle."/".$course."/";
    $ca_number=0;
    if(!is_dir($cashome)){return 0;}

   if($opened_dir=opendir($cashome))
   {
     while(($ca=readdir($opened_dir))!==false)
     {
      if($ca!="." && $ca!="..")
      {
        $ca_number++;
      }
      
     }
     closedir($opened_dir);
   }

   

   return $ca_number;
    
  }

  public function CAsaver($ca)
  {
    if($ca==null){return false;}
    $year=yii::$app->session->get('currentAcademicYear');
    $ca['CA']['year']=$year->yearID;
    $course=str_replace(' ','',yii::$app->session->get('ccode'));
    $ca_version=($ca['CA']['version']!=null)?$ca['CA']['version']:($this->getCAsnumber($course)+1);
    $ca['CA']['version']=$ca_version;
    if($ca_version<=1){$ca['CA']['published']=false;}
    $ca_data=ClassRoomSecurity::encrypt(json_encode($ca));

    //preparing the ca name

    try
    {
    $yearTitle=$year->title;
    $ca_name=$course.'_CA_V'.$ca_version;
    $ca_location='storage/CAs/'.$yearTitle.'/'.$course;
    if(!is_dir($ca_location)){mkdir($ca_location,0777,true);}
    $ca_file=$ca_location.'/'.$ca_name.'.ca';

    file_put_contents($ca_file,$ca_data,LOCK_EX);
    
    $message=($ca_version>1)?"CA saved successfully":"CA updated successfully";
    yii::$app->session->setFlash('success',"<i class='fa fa-info-circle'></i> ".$message);
    return true;
    }
    catch(Exception $c)
    {
        $message=($ca_version>1)?"Could not save CA, try again later":"Could not update CA, try again later";
        yii::$app->session->setFlash('error',"<i class='fa fa-info-circle'></i> ".$message);
        return false;
    }

  }
  private function readCAdata($ca)
  {
    $year=yii::$app->session->get('currentAcademicYear');
    $course=str_replace(' ','',yii::$app->session->get('ccode'));
    $ca_location='storage/CAs/'.$year->title.'/'.$course.'/'.$ca;

    try
    {
    if(!file_exists($ca_location)){return null;}
    $ca_data=file_get_contents($ca_location);

    if($ca_data!=false){

      $ca_data=json_decode(ClassRoomSecurity::decrypt($ca_data),true);
    }
    return $ca_data;
    }
  catch(Exception $r)
    {
    return null;
    }
    
  }
 public function getCaData($ca)
 {
   return $this->readCAdata($ca);
 }
  public function loadCAdata($ca)
  {
    $cadata=$this->readCAdata($ca);
    $this->caTitle=basename($ca,'.ca');
    $this->Assignments=($cadata!=null)?$cadata['CA']['Assignments']:[];
    $this->otherAssessments=($cadata!=null)?$cadata['CA']['otherAssessments']:[];
    $this->LabAssignments=($cadata!=null)?$cadata['CA']['LabAssignments']:[];
    $this->assreduce=($cadata!=null)?$cadata['CA']['assreduce']:null;
    $this->labreduce=($cadata!=null)?$cadata['CA']['labreduce']:null;
    $this->otherassessreduce=($cadata!=null)?$cadata['CA']['otherassessreduce']:null;
    $this->version=($cadata!=null)?$cadata['CA']['version']:null;
  
  }
  public function CAsavePublished($ca)
  {
    if($ca==null){return false;}
    $year=yii::$app->session->get('currentAcademicYear');
    $ca['CA']['year']=$year->yearID;
    $course=str_replace(' ','',yii::$app->session->get('ccode'));
    $ca_version=($ca['CA']['version']!=null)?$ca['CA']['version']:($this->getCAsnumber($course)+1);
    $ca['CA']['version']=$ca_version;
    $ca['CA']['published']=true;
    $ca_data=ClassRoomSecurity::encrypt(json_encode($ca));

    //preparing the ca name

    try
    {
    $yearTitle=$year->title;
    $ca_name=$course.'_CA_V'.$ca_version;
    $ca_location='storage/CAs/'.$yearTitle.'/'.$course;
    if(!is_dir($ca_location)){mkdir($ca_location,0777,true);}
    $ca_file=$ca_location.'/'.$ca_name.'.ca';

    file_put_contents($ca_file,$ca_data,LOCK_EX);
    
    $message=($ca_version>1)?"CA saved successfully":"CA updated successfully";
    yii::$app->session->setFlash('success',"<i class='fa fa-info-circle'></i> ".$message);
    return true;
    }
    catch(Exception $c)
    {
        $message=($ca_version>1)?"Could not save CA, try again later":"Could not update CA, try again later";
        yii::$app->session->setFlash('error',"<i class='fa fa-info-circle'></i> ".$message);
        return false;
    }
  }

  public function findAllCAs()
  {
    $course=str_replace(' ','',yii::$app->session->get('ccode'));
    $year=yii::$app->session->get('currentAcademicYear');
    $yearTitle=$year->title;
    $ca_location='storage/CAs/'.$yearTitle.'/'.$course;
    try
    {
    if(!file_exists($ca_location) || !is_dir($ca_location)){ return [];}
    $CAs=scandir($ca_location);
    $CAnames=array();
    if($CAs=="false"){return [];}
    for($c=0;$c<count($CAs);$c++)
    {
      
      if($CAs[$c]=="." || $CAs[$c]==".."){continue;}
      else{
        if(!$this->isCaCurrent($CAs[$c])){continue;}
        $CA_name=basename($CAs[$c],".ca");
      }
      $CAnames[$CAs[$c]]=$CA_name;
    }
    return $CAnames;
   }
   catch(Exception $ca)
   {
     return [];
   }
}
  
  public function publishCA($ca)
  {
    $ca=$this->readCAdata(ClassRoomSecurity::decrypt($ca));
    return $this->CAsavePublished($ca);
  } 
  
  private function isCaCurrent($ca)
  {
    return ($this->readCAdata($ca))['CA']['year']==(yii::$app->session->get('currentAcademicYear'))->yearID;
  }

  public function deleteCA($ca)
  {
    $ca=ClassRoomSecurity::decrypt($ca);
    $course=str_replace(' ','',yii::$app->session->get('ccode'));
    $year=yii::$app->session->get('currentAcademicYear');
    $yearTitle=$year->title;
    $ca_location='storage/CAs/'.$yearTitle.'/'.$course;
    $ca_target=$ca_location."/".$ca;

    try
    {
      if(file_exists($ca_target))
      {
        unlink($ca_target);
      }

      return true;
    }
    catch(Exception $a)
    {
      return false;
    }
  }

  public function findStudentCaScore()
  {
    $allCas=$this->findAllCAs();


  }
}
?>