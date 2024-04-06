<?php
namespace frontend\models;
use common\models\BranchAnnualBudget;
use common\models\BranchMonthlyRevenue;
use common\models\Monthlyincome;
use common\models\Otherincomes;
use yii\base\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use common\models\Budgetyear;
use yii\helpers\ArrayHelper;
use Mpdf\Mpdf;
use yii;



class Reporter extends Model
{
    public $financialyear;
    public $months=[
      '1'=>'January',
      '2'=>'February',
      '3'=>'March',
      '4'=>'April',
      '5'=>'May',
      '6'=>'June',
      '7'=>'July',
      '8'=>'August',
      '9'=>'September',
      '10'=>'October',
      '11'=>'November',
      '12'=>'December'
    ];

    public function __construct($config=[])
    {
        $this->financialyear=Budgetyear::findOne(yii::$app->session->get('financialYear')->yearID);


        parent::init($config);
    }
    public function incomesBufferBuilder()
    {
       $incomesbuffer=[];
       $monthlycollectionsbuffer=[];
       $budget=$this->financialyear->annualbudget->budgetID;

       //building monthyly incomes

       foreach($this->months as $index=>$month)
       {
          $monthlycollectionsbuffer[$index]=(new Monthlyincome)->getIncomeFor($budget,$index);
          
       }
       $incomesbuffer['Monthly Collections']=$monthlycollectionsbuffer;
       foreach($this->months as $index=>$month)
       {
        $otherincome=(new Otherincomes)->getIncomeFor($budget,$index);
        if($otherincome!=null)
        {
          $incomesbuffer[$otherincome->incomeType][$index]=$otherincome->amount;
        }
        else
        {

        }
        
          
       }

       //adding the total row
       foreach($this->months as $index=>$month)
       {
          $income=(new Monthlyincome)->getIncomeFor($budget,$index);

          $otherincome=(new Otherincomes)->getIncomeFor($budget,$index);
          $otherincome=$otherincome!=null?$otherincome->amount:0;

          $incomesbuffer["total"][$index]=$otherincome+$income;

       }

       return $incomesbuffer;

    }

    public function expensesBufferBuilder()
    {
      $branchbudgets=$this->financialyear->annualbudget->branchAnnualBudgets;
      $expensesBuffer=[];

      foreach($branchbudgets as $branchbudget)
      {
      
      if($branchbudget->branch0->isHQ()){ continue; }
      foreach($this->months as $index=>$month)
      {
        if(isset($expensesBuffer['Branch Returns'][$branchbudget->bbID][$index]) && $expensesBuffer['Branch Returns'][$branchbudget->bbID][$index]!=null)
        {
          $expensesBuffer['Branch Returns'][$branchbudget->bbID][$index]+=(new BranchMonthlyRevenue)->totalRevenueFor($index, $branchbudget->bbID);
        }
        else{
          $expensesBuffer['Branch Returns'][$branchbudget->bbID][$index]=(new BranchMonthlyRevenue)->totalRevenueFor($index, $branchbudget->bbID);
        }
        
        }

      }

      // adding the total row
      foreach($this->months as $index=>$month)
      {
      foreach($branchbudgets as $branchbudget)
      {
      
      if($branchbudget->branch0->isHQ()){ continue; }
   
        if(isset($expensesBuffer['Branch Returns']['Total'][$index]) && $expensesBuffer['Branch Returns']['Total'][$index]!=null)
        {
          $expensesBuffer['Branch Returns']['Total'][$index]+=(new BranchMonthlyRevenue)->totalRevenueFor($index, $branchbudget->bbID);
        }
        else{
          $expensesBuffer['Branch Returns']['Total'][$index]=(new BranchMonthlyRevenue)->totalRevenueFor($index, $branchbudget->bbID);
        }
        
        }

      }

      //Adding other expenses

      $HQbudget=$this->financialyear->annualbudget->HQbudget();

      $projections=ArrayHelper::map($HQbudget->budgetprojections,'budgetItem','totalExpenses');
      $expensesBuffer['Other Expenses']['expenses']=$projections;
      $expensesBuffer['Other Expenses']['Total_expenses']=$HQbudget->getTotalExpenses();

      return $expensesBuffer;
    }

    public function summaryBuilder()
    {
      $summary=[];
      $HQbudget=$this->financialyear->annualbudget->HQbudget();
      $total_revenue=$this->financialyear->annualbudget->totalRevenue();
      $branchreturns_total=$this->financialyear->annualbudget->totalReturns();
      $total_expenses=$HQbudget->getTotalExpenses();

      $balance_or_deficit=$total_revenue-($branchreturns_total+$total_expenses);

      $summary['TOTAL REVENUE']=$total_revenue;
      $summary['TOTAL BRANCH RETURNS']=$branchreturns_total;
      $summary['TOTAL EXPENSES']=$total_expenses;
      $summary['BALANCE/DEFICIT']=$balance_or_deficit;

      return $summary;


    }
    public function incomesReportBuilder()
    {
        $expenses=$this->expensesBufferBuilder();

        //print_r($expenses); return null;
        $incomesbuffer=$this->incomesBufferBuilder();
        $table="<table><tr><th></th>";

        //building heading
        foreach($this->months as $index=>$month)
        {
            $table.="<th>".$month."</th>";
        }
        $table.="</tr>";
        

        foreach($incomesbuffer as $type=>$amounts)
        {
          $table.="<tr><td>".$type."</td>";

          foreach($amounts as $index=>$amount)
          {
            $table.="<td>".$amount."</td>";
          }
          $table.="</tr>";
        }
         
        $table.="</table><table><tr><th>Branch Returns</th></tr>";

        //adding branch returns table

        foreach($expenses['Branch Returns'] as $index=>$returns)
        {
          $column_name=($index!='Total')?(BranchAnnualBudget::findOne($index))->branch0->branch_short:$index;
          $table.="<tr><td>".$column_name."</td>";

          foreach($returns as $month=>$amount)
          {
            $table.="<td>".$amount."</td>";
          }
          $table.="</tr>";
        }
        //adding other expenses
       
        $table.="</table><table><tr><th>Other Expenses</th></tr>";
        foreach($expenses['Other Expenses']['expenses'] as $index=>$expense)
        {
         
          $table.="<tr><td>".$index."</td><td>".$expense."</td></tr>";
        }
        $table.="<tr><td>Total</td><td>".$expenses['Other Expenses']['Total_expenses']."</td></tr>";

        //adding the summary table
        $table.="</table><table><tr><th>SUMMARY</th></tr>";
        $summary=$this->summaryBuilder();

        foreach($summary as $index=>$sum)
        {
          $table.="<tr><td>".$index."</td><td>".$sum."</td></tr>";
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
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->setFooter('{PAGENO}');
        $stylesheet = file_get_contents('css/capdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetWatermarkText('civeclassroom.udom.ac.tz',0.09);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="125px" height="125px"/></div>',2);
        $mpdf->WriteHTML('<p align="center"><font size=7>The University of Dodoma</font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5></font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5></font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5>Final course assessment results </font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=3></font></p>',3);
        $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">',2);
        $mpdf->WriteHTML($content,3);
          
        $filename="_CA.pdf";
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
        //print_r($this->incomesBufferBuilder()); return null;
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
        ob_clean();
        $writer=IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        $filename="THTU_FINANCE_ANNUAL_REPORT_".$this->financialyear->startingyear.".Xlsx";
        $filename = str_replace(' ', '', $filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');

        ob_end_clean();
        $writer->save('php://output'); 

        exit();
      }
      else
      {
        return 'no content';
      }
    }



}











?>