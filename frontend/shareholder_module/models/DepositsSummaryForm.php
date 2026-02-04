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

class DepositsSummaryForm extends Model
{
    public $date_range; // "YYYY-MM-DD - YYYY-MM-DD"

    public function rules()
    {
        return [
            [['date_range'], 'safe'],
        ];
    }
    public function depositsSummary()
    {
        if($this->date_range==null)
            {$from=null; $to=null;}
        else{
         [$from,$to]=explode(' - ',$this->date_range);
        }
       
       $shareholders=Shareholder::find()->with('customer')->all();
       $deposits=array_map(function($shareholder) use($from,$to){
        return $shareholder->depositsSummary($from,$to);
       },$shareholders);

       return $deposits;
    }
  function exportShareholdersDepositsSummaryXlsx(): void
{
    $deposits = $this->depositsSummary();

    if ($this->date_range == null) {
        $from = null;
        $to   = null;
    } else {
        [$from, $to] = explode(' - ', $this->date_range);
    }

    // Date formatter: 1 Jan 2026
    $formatDate = function ($date) {
        return $date ? date('j M Y', strtotime($date)) : 'All';
    };

    // ---------------- SPREADSHEET ----------------
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ✅ updated sheet title
    $sheet->setTitle('Shareholder Deposits Summary');

    // ---------- Helpers ----------
    $thinAllBorders = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ],
    ];

    $wrapTopLeft = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical'   => Alignment::VERTICAL_TOP,
            'wrapText'   => true,
        ],
    ];

    // ---------- Column widths ----------
    $sheet->getColumnDimension('A')->setWidth(6);   // #
    $sheet->getColumnDimension('B')->setWidth(22);  // Customer ID
    $sheet->getColumnDimension('C')->setWidth(22);  // Member ID
    $sheet->getColumnDimension('D')->setWidth(30);  // Full Name
    $sheet->getColumnDimension('E')->setWidth(20);  // Deposit Amount

    // ---------- Title ----------
    $sheet->mergeCells('A1:E1');

    // ✅ updated inside title
    $sheet->setCellValue('A1', 'Shareholder Deposits Summary');

    $sheet->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(22);

    // Separator
    $sheet->mergeCells('A2:E2');
    $sheet->getStyle('A2:E2')->applyFromArray([
        'borders' => [
            'bottom' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '999999'],
            ],
        ],
    ]);

    // ---------- Meta (From / To) ----------
    $sheet->setCellValue('A4', 'From');
    $sheet->setCellValue('B4', ($from === null && $to === null) ? 'All' : $formatDate($from));

    $sheet->setCellValue('A5', 'To');
    $sheet->setCellValue('B5', ($from === null && $to === null) ? 'All' : $formatDate($to));

    // ---------- Totals ----------
    $records = count($deposits);
    $grandTotal = 0.0;
    foreach ($deposits as $r) {
        $grandTotal += (float)($r['Deposit Amount'] ?? 0);
    }

    $sheet->setCellValue('C4', 'Records');
    $sheet->setCellValue('D4', $records);
    $sheet->setCellValue('C5', 'Grand Total');
    $sheet->setCellValue('D5', $grandTotal);

    // Meta styling
    $sheet->getStyle('A4:D5')->applyFromArray($thinAllBorders);
    $sheet->getStyle('A4:A5')->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => 'F3F3F3'],
        ],
    ]);
    $sheet->getStyle('C4:C5')->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => 'F3F3F3'],
        ],
    ]);
    $sheet->getStyle('A4:D5')->applyFromArray($wrapTopLeft);
    $sheet->getStyle('D5')->getNumberFormat()->setFormatCode('#,##0.00');

    // ---------- Table Header ----------
    $headerRow = 7;
    $sheet->setCellValue("A{$headerRow}", '#');
    $sheet->setCellValue("B{$headerRow}", 'Customer ID');
    $sheet->setCellValue("C{$headerRow}", 'Member ID');
    $sheet->setCellValue("D{$headerRow}", 'Full Name');
    $sheet->setCellValue("E{$headerRow}", 'Deposit Amount');

    $sheet->getStyle("A{$headerRow}:E{$headerRow}")->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => '2890C5'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
    ]);
    $sheet->getStyle("E{$headerRow}")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    // ---------- Table Body ----------
    $row = $headerRow + 1;

    if (empty($deposits)) {
        $sheet->mergeCells("A{$row}:E{$row}");
        $sheet->setCellValue("A{$row}", 'No deposits found for the selected date range.');
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("E{$row}", 0);
    } else {
        $i = 1;
        foreach ($deposits as $r) {
            $sheet->setCellValue("A{$row}", $i);
            $sheet->setCellValue("B{$row}", (string)($r['Customer ID'] ?? ''));
            $sheet->setCellValue("C{$row}", (string)($r['Member ID'] ?? ''));
            $sheet->setCellValue("D{$row}", (string)($r['Full Name'] ?? ''));
            $sheet->setCellValue("E{$row}", (float)($r['Deposit Amount'] ?? 0));

            $sheet->getStyle("E{$row}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E{$row}")
                ->getNumberFormat()->setFormatCode('#,##0.00');

            $row++;
            $i++;
        }

        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("E{$row}", $grandTotal);
    }

    // ---------- Borders + Total Styling ----------
    $lastRow = $row;
    $sheet->getStyle("A{$headerRow}:E{$lastRow}")->applyFromArray($thinAllBorders);

    $sheet->getStyle("A{$lastRow}:E{$lastRow}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => 'F8F9FA'],
        ],
    ]);
    $sheet->getStyle("E{$lastRow}")
        ->getNumberFormat()->setFormatCode('#,##0.00');

    // ---------- OUTPUT ----------
    $filename = 'shareholders_deposits_summary_' . date('Ymd_His') . '.xlsx';

    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

}

