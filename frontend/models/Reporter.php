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
       $incomesbuffer['Collections']=$monthlycollectionsbuffer;
       foreach($this->months as $index=>$month)
       {
        $otherincome=(new Otherincomes)->getIncomeFor($budget,$index);
        if($otherincome!=null)
        {
          $incomesbuffer[$otherincome->incomeType][$index]=$otherincome->amount;
        }
      
        
          
       }

       //adding the total row
       foreach($this->months as $index=>$month)
       {
          $income=(new Monthlyincome)->getIncomeFor($budget,$index);

          $otherincome=(new Otherincomes)->getIncomeFor($budget,$index);
          $otherincome=$otherincome!=null?$otherincome->amount:0;

          $incomesbuffer["Total"][$index]=$otherincome+$income;

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
      $branchtakeover=$this->financialyear->annualbudget->HQTakeOver();
      $total_revenue=$this->financialyear->annualbudget->totalRevenue()+$branchtakeover;
      $branchreturns_total=$this->financialyear->annualbudget->totalReturns();
      
      $total_expenses=$HQbudget->getTotalExpenses();

      $balance_or_deficit=$total_revenue-($branchreturns_total+$total_expenses);
      $summary['TOTAL INCOMES']=$this->financialyear->annualbudget->totalRevenue();
      $summary['TAKE OVER']=$branchtakeover;
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
        $table="<table><tr ><th colspan=2 class='hd'>TAKE OVER</th></tr>";
        $table.="<tr><td class='bold'>AMOUNT</td><td class='bold'>".yii::$app->MoneyFormatter->format($this->financialyear->annualbudget->HQTakeOver())."</td></tr></table>";
        $table.="<table width='100%'><tr><th colspan=13 class='hd'>INCOMES</th></tr><tr ><td class='bold'>TYPE</td>";

        //building heading
        foreach($this->months as $index=>$month)
        {
            $table.="<td class='bold'>".$month."</td>";
        }
        $table.="</tr>";
        

        foreach($incomesbuffer as $type=>$amounts)
        {
          $table.="<tr><td>".$type."</td>";

          foreach($amounts as $index=>$amount)
          {
            $table.="<td>".yii::$app->MoneyFormatter->format($amount)."</td>";
          }
          $table.="</tr>";
        }
         
        $table.="</table><table width='100%'><tr><th colspan=13 class='hd'>BRANCH RETURNS</th></tr><tr ><td class='bold'>BRANCH</td>";

        //adding branch returns table
        foreach($this->months as $index=>$month)
        {
            $table.="<td class='bold'>".$month."</td>";
        }
       
        $table.="</tr>";
        foreach($expenses['Branch Returns'] as $index=>$returns)
        {
          $column_name=($index!='Total')?(BranchAnnualBudget::findOne($index))->branch0->branch_short:$index;
          $table.="<tr><td>".$column_name."</td>";

          foreach($returns as $month=>$amount)
          {
            $table.="<td>".yii::$app->MoneyFormatter->format($amount)."</td>";
          }
          $table.="</tr>";
        }
        //adding other expenses
       
        $table.="</table><table><tr><th colspan=2 class='hd'>OTHER EXPENSES</th></tr>";
        foreach($expenses['Other Expenses']['expenses'] as $index=>$expense)
        {
         
          $table.="<tr><td>".$index."</td><td>".yii::$app->MoneyFormatter->format($expense)."</td></tr>";
        }
        $table.="<tr><td>Total</td><td>".yii::$app->MoneyFormatter->format($expenses['Other Expenses']['Total_expenses'])."</td></tr>";

        //adding the summary table
        $table.="</table><table><tr ><th colspan=2 class='hd'>SUMMARY</th></tr>";
        $summary=$this->summaryBuilder();

        foreach($summary as $index=>$sum)
        {
          $table.="<tr><td>".$index."</td><td>".yii::$app->MoneyFormatter->format($sum)."</td></tr>";
        }
        $table.="</table>";
        //adding the branches budget summaries

        $table.=$this->branchBudgetReporter();
        return $table;
    }
public function branchBudgetReporter()
{
    $table="<table><tr ><th colspan=7 class='hd'>BRANCHES BUDGET SUMMARY</th></tr>";
    $table.="<tr><td class='bold'>BRANCH</td><td class='bold'>TOT. REVENUE</td>";
    $table.="<td class='bold'>MONTHLY INCOME</td><td class='bold'>OTHER INCOME</td><td class='bold'>TAKE OVER</td><td class='bold'>TOT. EXPENSES</td><td class='bold'>BALANCE/DEFICIT</td>";
                      $branchbudgets=$this->financialyear->annualbudget->branchAnnualBudgets;

                        foreach($branchbudgets as $bbudget)
                        {
                        if($bbudget->branch0->isHQ()){continue;}
                        $table.="<tr>";
                        $table.="<td>".$bbudget->branch0->branch_short."</td>";
                        $table.="<td>".yii::$app->MoneyFormatter->format($bbudget->branchTotalRevenue())."</td>";
                        $table.="<td>".yii::$app->MoneyFormatter->format($bbudget->totalIncome())."</td>";
                        $table.="<td>".yii::$app->MoneyFormatter->format($bbudget->totalOtherIncomes())."</td>";
                        $table.="<td>".yii::$app->MoneyFormatter->format(($bbudget->takeover!=null)?$bbudget->takeover->amount:0)."</td>";
                        $table.="<td>".yii::$app->MoneyFormatter->format($bbudget->getTotalExpenses())."</td>";
                        $table.="<td>".yii::$app->MoneyFormatter->format($bbudget->getBalance())."</td>";
                        $table.="</tr>";
                    }

                    $table.="</table>";
                    return $table;
       
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
        $mpdf->SetWatermarkText('THTU MIS',0.09);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="125px" height="125px"/></div>',2);
        $mpdf->WriteHTML('<p align="center"><font size=7>Tanzania Higher Learning Institutions Trade Union</font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5></font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5></font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=5>Financial Report - '.$this->financialyear->startingyear.' </font></p>',3);
        $mpdf->WriteHTML('<p align="center"><font size=3></font></p>',3);
        $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">',2);
        $mpdf->WriteHTML($content,3);
          
        $filename="THTU_FINANCE_ANNUAL_REPORT_".$this->financialyear->startingyear.".pdf";
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