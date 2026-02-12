<?php

namespace frontend\shareholder_module\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;

use common\models\Shareholder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelReporter
{
    /**
     * Create XLSX to a temp file and send it via Yii response.
     * This is the most compatible pattern in many Yii2 apps.
     */
   public static function shareholderDeposits(
    \common\models\Shareholder $shareholder,
    \yii\data\ActiveDataProvider $dataProvider,
    ?string $dateRange = null
): \yii\web\Response {

    // ---- SAFETY CHECKS ----
    if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
        throw new \RuntimeException(
            'PhpSpreadsheet not found. Run: composer require phpoffice/phpspreadsheet'
        );
    }

    // prevent "headers already sent"
    while (ob_get_level() > 0) {
        @ob_end_clean();
    }

    $rows = $dataProvider->getModels();

    // ---- TOTALS ----
    $totalDeposits = 0.0;
    foreach ($rows as $deposit) {
         if($deposit->type=="capital")
        {
          continue;
        }
        $totalDeposits += (float)$deposit->amount;
    }

    // ---- PERIOD ----
    $periodText = 'All dates';
    if ($dateRange && strpos($dateRange, ' - ') !== false) {
        $periodText = $dateRange;
    }

    // ---- SHAREHOLDER INFO ----
    $customerName = $shareholder->customer->full_name ?? '';
    $memberId     = $shareholder->memberID ?? '';
    $customerId   = $shareholder->customer->customerID ?? '';
    $shares       = $shareholder->shares ?? '';
    $initialCap   = $shareholder->initialCapital ?? 0;

    // ---- SPREADSHEET ----
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Deposits');

    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ---- TITLE ----
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'Shareholder Deposits');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getRowDimension(1)->setRowHeight(26);

    // ---- META BLOCK (LEFT + RIGHT, ALL LEFT-ALIGNED) ----
    // LEFT
    $sheet->mergeCells('B3:C3');
    $sheet->setCellValue('A3', 'Member ID');
    $sheet->setCellValue('B3', $memberId);

    $sheet->mergeCells('B4:C4');
    $sheet->setCellValue('A4', 'Shareholder');
    $sheet->setCellValue('B4', $customerName);

    $sheet->mergeCells('B5:C5');
    $sheet->setCellValue('A5', 'Customer ID');
    $sheet->setCellValue('B5', $customerId);

    $sheet->mergeCells('B6:C6');
    $sheet->setCellValue('A6', 'Shares');
    $sheet->setCellValue('B6', $shares);

    $sheet->mergeCells('B7:C7');
    $sheet->setCellValue('A7', 'Initial Capital');
    $sheet->setCellValue('B7', (float)$initialCap);

    // RIGHT
    $sheet->mergeCells('E3:F3');
    $sheet->setCellValue('D3', 'Period');
    $sheet->setCellValue('E3', $periodText);

    $sheet->mergeCells('E4:F4');
    $sheet->setCellValue('D4', 'Total Deposits');
    $sheet->setCellValue('E4', (float)$totalDeposits);

    $sheet->mergeCells('E5:F5');
    $sheet->setCellValue('D5', 'Currency');
    $sheet->setCellValue('E5', 'TZS');

    $sheet->mergeCells('E6:F6');
    $sheet->setCellValue('D6', 'Records');
    $sheet->setCellValue('E6', count($rows));

    // ---- META STYLING ----
    $sheet->getStyle('A3:A7')->getFont()->setBold(true);
    $sheet->getStyle('D3:D6')->getFont()->setBold(true);

    // Force LEFT alignment for ALL meta cells (including decimals)
    $sheet->getStyle('A3:F7')->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    $sheet->getStyle('B7')->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle('E4')->getNumberFormat()->setFormatCode('#,##0.00');

    // ---- TABLE HEADER ----
    $headerRow = 9;
    $headers = ['#', 'Deposit Date', 'Type', 'Amount', 'Interest %', 'Record Date'];
    $sheet->fromArray($headers, null, "A{$headerRow}");

    $sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '2890C5'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);
    $sheet->getRowDimension($headerRow)->setRowHeight(20);

    // ---- DATA ROWS ----
    $r = $headerRow + 1;
    foreach ($rows as $i => $deposit) {
        if($deposit->type=="capital")
        {
        continue;
        }
        $depositDate = $deposit->deposit_date
            ? date('j M Y', strtotime($deposit->deposit_date))
            : '';

        $recordDate = $deposit->created_at
            ? date('j M Y', strtotime($deposit->created_at))
            : '';

        $sheet->setCellValue("A{$r}", $i + 1);
        $sheet->setCellValue("B{$r}", $depositDate);
        $sheet->setCellValue("C{$r}", (string)$deposit->type);
        $sheet->setCellValue("D{$r}", (float)$deposit->amount);
        $sheet->setCellValue("E{$r}", (float)$deposit->interest_rate);
        $sheet->setCellValue("F{$r}", $recordDate);

        $sheet->getStyle("D{$r}")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("E{$r}")->getNumberFormat()->setFormatCode('0.00');
        $sheet->getStyle("D{$r}:E{$r}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("A{$r}:F{$r}")
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $r++;
    }

    // ---- TOTAL ROW ----
    $sheet->mergeCells("A{$r}:C{$r}");
    $sheet->setCellValue("A{$r}", 'TOTAL');
    $sheet->setCellValue("D{$r}", (float)$totalDeposits);

    $sheet->getStyle("A{$r}:F{$r}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F2F2F2'],
        ],
    ]);
    $sheet->getStyle("D{$r}")->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle("D{$r}")
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // ---- SAVE + SEND ----
    $fileName = 'shareholder-deposits-' . (int)$shareholder->id . '-' . date('Ymd-His') . '.xlsx';

    $tmp = tempnam(sys_get_temp_dir(), 'xlsx_');
    $xlsxPath = $tmp . '.xlsx';
    @rename($tmp, $xlsxPath);

    (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($xlsxPath);

    $response = Yii::$app->response;
    $response->format = \yii\web\Response::FORMAT_RAW;
    $response->headers->set(
        'Content-Type',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );

    $response->sendFile($xlsxPath, $fileName, ['inline' => false]);

    $response->on(\yii\web\Response::EVENT_AFTER_SEND, function () use ($xlsxPath) {
        @unlink($xlsxPath);
    });

    return $response;
}




}
