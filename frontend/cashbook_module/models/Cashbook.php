<?php
namespace frontend\cashbook_module\models;
use yii\base\Model;
use yii\base\UserException;
use common\models\Cashbook as Book;
use common\helpers\UniqueCodeHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use yii;

class Cashbook extends Model{

public $record=[];

public $date_range;

public function rules()
{
    return [
        ['date_range','required']
    ];
}

public function __construct($data=[],$config = [])
{
    $this->record=$data;
    return parent::__construct($config);
}
public function save($reference_prefix,$suffix_no)
{
    $transaction=yii::$app->db->beginTransaction();
    try
    {
    if($this->record==null)
        {
            throw new UserException('Cashbook record empty');
        }
                $cashbook=new Book();
                $cashbook->credit=$this->record['credit'];
                $cashbook->debit=$this->record['debit'];
                $cashbook->reference_no=UniqueCodeHelper::generate($reference_prefix,5).'-'.$suffix_no.date("y");
                $cashbook->description=$this->record['description'];
                $cashbook->payment_document=$this->record['payment_doc'];
                $cashbook->category=$this->record['category'];
                $cashbook->balance=$cashbook->updatedBalance();

                if(!$cashbook->save())
                    {
                        throw new UserException("unable to save cashbook record.". json_encode($cashbook->getErrors()));
                    }
                    $transaction->commit();
                    $cashbook->refresh();

                    return $cashbook;
    }
    catch(UserException $e)
    {
        $transaction->rollBack();
        throw $e;
    }
    catch(\Throwable $t)
    {
       $transaction->rollBack();
       throw $t;
    }
}
public function save_with_reference()
{
     $transaction=yii::$app->db->beginTransaction();
    try
    {
    if($this->record==null)
        {
            throw new UserException('Cashbook record empty');
        }
                $cashbook=new Book();
                $cashbook->credit=$this->record['credit'];
                $cashbook->debit=$this->record['debit'];
                $cashbook->reference_no=$this->record['reference'];
                $cashbook->description=$this->record['description'];
                $cashbook->payment_document=$this->record['payment_doc'];
                $cashbook->category=$this->record['category'];
                $cashbook->balance=$cashbook->updatedBalance();

                if(!$cashbook->save())
                    {
                        throw new UserException("Unable to save cashbook record.". json_encode($cashbook->getErrors()));
                    }
                    $transaction->commit();
                    $cashbook->refresh();
                    return $cashbook;

                      }
    catch(UserException $e)
    {
        $transaction->rollBack();
        throw $e;
    }
    catch(\Throwable $t)
    {
       $transaction->rollBack();
       throw $t;
    }
}

public function getCashFlows()
{
        if (!empty($this->date_range)) {
            [$start, $end] = explode(' - ', $this->date_range);
            }
            else{
               [$start, $end] = [date("Y-m-d H:i:s"),date("Y-m-d H:i:s")]; 
            }
            $flows=Book::find()->where(['between','created_at',$start, $end])->all();

            return $flows;
            
}

public function cashbookExcel()
{
    $model=$this;
    [$start, $end] = ($model->date_range==null)?[date("Y-m-d H:i:s"),date("Y-m-d H:i:s")]:explode(' - ', $model->date_range);
$records = $model->getCashFlows();

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Cashbook Report');

// Default font
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);

// Report Title
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', 'CASHBOOK REPORT');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Report Dates & Currency
$sheet->setCellValue('A3', 'Start Date: ' . date('d M Y', strtotime($start)));
$sheet->setCellValue('A4', 'End Date: ' . date('d M Y', strtotime($end)));
$sheet->setCellValue('F3', 'Currency: TZS');
$sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

// Opening Balance
if (!empty($records)) {
    $obalance = $records[0]->openingBalance();
    $balancedisp = ($obalance < 0) ? abs($obalance) : abs($obalance); // numeric for Excel

    $sheet->mergeCells('A6:F6');
    $sheet->setCellValue('A6', 'Opening Balance: ' . number_format(abs($obalance), 2));
    $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('A6')->getFont()->setBold(true);
}

// Table Header
$startRow = 8;
$sheet->setCellValue('A' . $startRow, 'DATE');
$sheet->setCellValue('B' . $startRow, 'REFERENCE');
$sheet->setCellValue('C' . $startRow, 'DESCRIPTION');
$sheet->setCellValue('D' . $startRow, 'DEBIT');
$sheet->setCellValue('E' . $startRow, 'CREDIT');
$sheet->setCellValue('F' . $startRow, 'BALANCE');

// Header styling
$headerStyle = $sheet->getStyle('A' . $startRow . ':F' . $startRow);
$headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
$headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2890C5');
$headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Fill Table
$row = $startRow + 1;
$totaldebit = 0;
$totalcredit = 0;
$finalcumul = 0;

foreach ($records as $record) {
    $totaldebit += $record->debit;
    $totalcredit += $record->credit;
    $finalcumul = $record->balance;

    $sheet->setCellValue('A' . $row, date('d M Y', strtotime($record->created_at)));
    $sheet->setCellValue('B' . $row, $record->reference_no);
    $sheet->setCellValue('C' . $row, $record->description);
    $sheet->setCellValue('D' . $row, abs($record->debit));
    $sheet->setCellValue('E' . $row, abs($record->credit));
    $sheet->setCellValue('F' . $row, abs($record->balance));

    // Right align numeric columns
    $sheet->getStyle('D' . $row . ':F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    // Format as decimal with 2 places
    $sheet->getStyle('D' . $row . ':F' . $row)
        ->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

    $row++;
}

// Totals row
$sheet->setCellValue('A' . $row, 'TOTAL');
$sheet->mergeCells("A{$row}:C{$row}");
$sheet->setCellValue('D' . $row, abs($totaldebit));
$sheet->setCellValue('E' . $row, abs($totalcredit));
$sheet->setCellValue('F' . $row, abs($finalcumul));

// Format totals as decimal
$sheet->getStyle("D{$row}:F{$row}")
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

// Bold totals and top border
$sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
$sheet->getStyle("A{$row}:F{$row}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

// Auto size columns
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output to browser
$filename = 'Cashbook_Report.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"{$filename}\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
}


}