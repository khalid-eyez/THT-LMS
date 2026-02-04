<?php

namespace frontend\shareholder_module\models;


use yii\base\Model;
use common\models\Shareholder;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use yii;

class ShareholderInterestForm extends Model
{
    public $date_range; // "YYYY-MM-DD - YYYY-MM-DD"

    public function rules()
    {
        return [
            [['date_range'], 'safe'],
        ];
    }
    public function getInterests($shareholderID){

     $shareholder=Shareholder::findOne($shareholderID);
     if($this->date_range==null)
        {
            $from=null;
            $to=null;
        }
        else
            {
              [$from,$to]=explode(" - ",$this->date_range);
            }

            return $shareholder->getPaidInterests($from,$to);
     

    }
public function exportInterestStatementExcel(int $shareholderID): void
{
    $date_range = $this->date_range;

    $shareholder = Shareholder::find()
        ->with('customer')
        ->where(['id' => $shareholderID])
        ->one();

    if (!$shareholder) {
        throw new \yii\web\NotFoundHttpException('Shareholder not found.');
    }

    $interests = $this->getInterests($shareholderID) ?? [];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ✅ update BOTH titles
    $sheet->setTitle('Shareholder Interest Statement');
    $sheet->mergeCells('A1:D1')->setCellValue('A1', 'Shareholder Interest Statement');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $formatter = Yii::$app->formatter;

    $fmtDate = function ($date) use ($formatter) {
        if (empty($date)) return '-';
        try {
            return $formatter->asDate($date, 'dd MMM yyyy');
        } catch (\Throwable $e) {
            return (string)$date;
        }
    };

    // period label
    $from = $to = null;
    if (!empty($date_range) && strpos($date_range, ' - ') !== false) {
        [$from, $to] = array_map('trim', explode(' - ', $date_range));
    }
    $periodLabel = ($from && $to) ? ($fmtDate($from) . ' → ' . $fmtDate($to)) : 'All dates';

    // shareholder meta
    $customerName = $shareholder->customer->full_name ?? ($shareholder->customer->name ?? '-');
    $customerId   = $shareholder->customer->customerID ?? ($shareholder->customer->id ?? '-');
    $memberId     = $shareholder->memberID ?? '-';
    $shares       = $shareholder->shares;
    $initialCap   = $shareholder->initialCapital;

    // totals
    $total = 0.0;
    foreach ($interests as $it) {
        $total += (float)$it->interest_amount;
    }

    // meta section
    $sheet->setCellValue('A3', 'Member ID')->setCellValue('B3', $memberId);
    $sheet->setCellValue('A4', 'Shareholder Name')->setCellValue('B4', $customerName);
    $sheet->setCellValue('A5', 'Customer ID')->setCellValue('B5', $customerId);

    // shares (formatted like money too)
    $sheet->setCellValue('A6', 'Shares');
    $sheet->setCellValue('B6', $shares !== null ? (float)$shares : '-');

    $sheet->setCellValue('A7', 'Initial Capital');
    $sheet->setCellValue('B7', $initialCap !== null ? (float)$initialCap : '-');

    $sheet->setCellValue('C3', 'Selected Period')->setCellValue('D3', $periodLabel);
    $sheet->setCellValue('C4', 'Currency')->setCellValue('D4', 'TZS');
    $sheet->setCellValue('C5', 'Records')->setCellValue('D5', count($interests));
    $sheet->setCellValue('C6', 'Total Interests')->setCellValue('D6', $total);

    // label styling
    $sheet->getStyle('A3:A7')->getFont()->setBold(true);
    $sheet->getStyle('C3:C6')->getFont()->setBold(true);

    // ✅ align ALL meta values to LEFT (numbers + strings)
    $sheet->getStyle('B3:B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D3:D6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    // number formats (even if aligned left)
    if ($shares !== null) {
        $sheet->getStyle('B6')->getNumberFormat()->setFormatCode('#,##0.00');
    }
    if ($initialCap !== null) {
        $sheet->getStyle('B7')->getNumberFormat()->setFormatCode('#,##0.00');
    }
    $sheet->getStyle('D6')->getNumberFormat()->setFormatCode('#,##0.00');

    // table header
    $startRow = 10;
    $sheet->setCellValue("A{$startRow}", '#');
    $sheet->setCellValue("B{$startRow}", 'Payment Date');
    $sheet->setCellValue("C{$startRow}", 'Claim Months');
    $sheet->setCellValue("D{$startRow}", 'Interest Amount');

    $headerStyle = $sheet->getStyle("A{$startRow}:D{$startRow}");
    $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
    $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2890C5');
    $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // data rows
    $r = $startRow + 1;

    if (empty($interests)) {
        $sheet->mergeCells("A{$r}:D{$r}")->setCellValue("A{$r}", 'No interests found for the selected date range.');
        $sheet->getStyle("A{$r}")->getFont()->setItalic(true)->getColor()->setARGB('FF666666');
        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $r++;
    } else {
        foreach ($interests as $i => $interest) {
            $sheet->setCellValue("A{$r}", $i + 1);
            $sheet->setCellValue("B{$r}", $fmtDate($interest->payment_date));
            $sheet->setCellValue("C{$r}", (int)$interest->claim_months);
            $sheet->setCellValue("D{$r}", (float)$interest->interest_amount);
            $r++;
        }
    }

    // total row
    $sheet->mergeCells("A{$r}:C{$r}");
    $sheet->setCellValue("A{$r}", 'TOTAL');
    $sheet->setCellValue("D{$r}", $total);

    $sheet->getStyle("A{$r}:D{$r}")->getFont()->setBold(true);
    $sheet->getStyle("A{$r}:D{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8F9FA');

    // formats + alignments for table
    if ($r > $startRow) {
        $sheet->getStyle("D" . ($startRow + 1) . ":D{$r}")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("C" . ($startRow + 1) . ":C{$r}")->getNumberFormat()->setFormatCode('0');
    }

    $sheet->getStyle("A" . ($startRow + 1) . ":A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("B" . ($startRow + 1) . ":B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("C" . ($startRow + 1) . ":D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    foreach (['A', 'B', 'C', 'D'] as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $filename = 'Shareholder_Interest_Statement_' . ($shareholder->memberID ?: $shareholderID) . '_' . date('Ymd_His') . '.xlsx';

    // stream safely
    Yii::$app->response->clear();
    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    Yii::$app->response->headers->removeAll();

    if (Yii::$app->has('log')) {
        foreach (Yii::$app->log->targets as $target) {
            if ($target instanceof \yii\debug\LogTarget) {
                $target->enabled = false;
            }
        }
    }

    while (ob_get_level() > 0) {
        @ob_end_clean();
    }

    $tmp = tempnam(sys_get_temp_dir(), 'xls_') . '.xlsx';
    (new Xlsx($spreadsheet))->save($tmp);

    Yii::$app->response->sendFile(
        $tmp,
        $filename,
        ['mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    )->send();

    @unlink($tmp);
    Yii::$app->end();
}
public function interests_summary()
{
   if($this->date_range==null)
        {
            $from=null;
            $to=null;
        }
        else
            {
              [$from,$to]=explode(" - ",$this->date_range);
            }
            
            $shareholders=Shareholder::find()->with('customer')->all();
            $summaries=array_map(function($shareholder) use($from,$to){
               return [
                  'Member ID'=>$shareholder->memberID,
                  'Customer ID'=>$shareholder->customer->customerID,
                  'Full Name'=>$shareholder->customer->full_name,
                  'Total Interest'=>$shareholder->getTotalPaidInterests($from,$to)
               ];
            },$shareholders);

            return $summaries;

}
public function exportInterestSummaryExcel( )
{
    $interest_summaries=$this->interests_summary();
    $date_range=$this->date_range;
    // --- compute from/to ---
    $from = $to = null;
    if (!empty($date_range) && strpos($date_range, ' - ') !== false) {
        [$from, $to] = array_map('trim', explode(' - ', $date_range, 2));
    }

    $formatter = Yii::$app->formatter;

    // labels
    $fromLabel = 'All';
    $toLabel   = 'All';
    if ($from && $to) {
        try { $fromLabel = $formatter->asDate($from, 'dd MMM yyyy'); } catch (\Throwable $e) { $fromLabel = (string)$from; }
        try { $toLabel   = $formatter->asDate($to,   'dd MMM yyyy'); } catch (\Throwable $e) { $toLabel   = (string)$to; }
    }

    // totals
    $grandTotal = 0.0;
    foreach (($interest_summaries ?? []) as $row) {
        $grandTotal += (float)($row['Total Interest'] ?? 0);
    }
    $records = is_array($interest_summaries) ? count($interest_summaries) : 0;

    // --- spreadsheet ---
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // titles
    $sheet->setTitle('Shareholder Interest Summary');
    $sheet->mergeCells('A1:E1')->setCellValue('A1', 'Shareholder Interests Summary');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // meta (two columns blocks like pdf)
    $sheet->setCellValue('A3', 'From')->setCellValue('B3', $from && $to ? $fromLabel : 'All');
    $sheet->setCellValue('A4', 'To')->setCellValue('B4', $from && $to ? $toLabel : 'All');

    $sheet->setCellValue('D3', 'Records')->setCellValue('E3', $records);
    $sheet->setCellValue('D4', 'Grand Total')->setCellValue('E4', (float)$grandTotal);

    $sheet->getStyle('A3:A4')->getFont()->setBold(true);
    $sheet->getStyle('D3:D4')->getFont()->setBold(true);

    // keep meta values aligned LEFT (numbers + strings)
    $sheet->getStyle('B3:B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('E3:E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    // format grand total as decimal
    $sheet->getStyle('E4')->getNumberFormat()->setFormatCode('#,##0.00');

    // header row
    $startRow = 7;
    $sheet->setCellValue("A{$startRow}", '#');
    $sheet->setCellValue("B{$startRow}", 'Customer ID');
    $sheet->setCellValue("C{$startRow}", 'Member ID');
    $sheet->setCellValue("D{$startRow}", 'Full Name');
    $sheet->setCellValue("E{$startRow}", 'Total Interest');

    // header style (blue like your pdf)
    $headerStyle = $sheet->getStyle("A{$startRow}:E{$startRow}");
    $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
    $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2890C5');
    $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // data rows
    $r = $startRow + 1;

    if (empty($interest_summaries)) {
        $sheet->mergeCells("A{$r}:E{$r}")->setCellValue("A{$r}", 'No interests found for the selected date range.');
        $sheet->getStyle("A{$r}")->getFont()->setItalic(true)->getColor()->setARGB('FF666666');
        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $r++;
    } else {
        foreach ($interest_summaries as $i => $row) {
            $amount = (float)($row['Total Interest'] ?? 0);

            $sheet->setCellValue("A{$r}", $i + 1);
            $sheet->setCellValue("B{$r}", (string)($row['Customer ID'] ?? ''));
            $sheet->setCellValue("C{$r}", (string)($row['Member ID'] ?? ''));
            $sheet->setCellValue("D{$r}", (string)($row['Full Name'] ?? ''));
            $sheet->setCellValue("E{$r}", $amount);

            $r++;
        }
    }

    // total row
    $sheet->mergeCells("A{$r}:D{$r}");
    $sheet->setCellValue("A{$r}", 'TOTAL');
    $sheet->setCellValue("E{$r}", (float)$grandTotal);

    $sheet->getStyle("A{$r}:E{$r}")->getFont()->setBold(true);
    $sheet->getStyle("A{$r}:E{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8F9FA');

    // alignment
    $sheet->getStyle("A" . ($startRow + 1) . ":A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("B" . ($startRow + 1) . ":D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("E" . ($startRow + 1) . ":E{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    // number formats
    if ($r > $startRow) {
        $sheet->getStyle("E" . ($startRow + 1) . ":E{$r}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
    }

    // borders (light grid)
    $sheet->getStyle("A{$startRow}:E{$r}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    // autosize
    foreach (['A','B','C','D','E'] as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $filename = 'shareholder_interest_summary_' . date('Ymd_His') . '.xlsx';

    // stream safely
    Yii::$app->response->clear();
    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    Yii::$app->response->headers->removeAll();

    if (Yii::$app->has('log')) {
        foreach (Yii::$app->log->targets as $target) {
            if ($target instanceof \yii\debug\LogTarget) {
                $target->enabled = false;
            }
        }
    }

    while (ob_get_level() > 0) {
        @ob_end_clean();
    }

    $tmp = tempnam(sys_get_temp_dir(), 'xls_') . '.xlsx';
    (new Xlsx($spreadsheet))->save($tmp);

    Yii::$app->response->sendFile(
        $tmp,
        $filename,
        ['mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    )->send();

    @unlink($tmp);
    Yii::$app->end();
}





}