<?php
use DateTime;
use Yii;
use yii\base\Model;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Mpdf\Mpdf;

class DocBuilder extends Model
{
      public function attendancePdfdownloader($meeting)
    {
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->setFooter('{PAGENO}');
        $stylesheet = file_get_contents('css/docs.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetWatermarkText('THTU - THTU',0.09);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="125px" height="125px"/></div>',2);
        $mpdf->WriteHTML('<p align="center"><font size=6>ATTENDANCE</font></p>',3);
        $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">',2);
        $mpdf->Output("myfile","D");

        return null;
    
  }
}