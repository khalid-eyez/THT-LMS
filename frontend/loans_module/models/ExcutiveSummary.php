<?php
namespace frontend\loans_module\models;
use yii\base\Model;
use common\models\CustomerLoan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class ExcutiveSummary extends Model{

public $date_range;

public function rules()
{
    return [
        ['date_range','required']
    ];
}



public function buildLoansExecutiveSummaryReport(): array
{
    [$startDate, $endDate] =($this->date_range==null)?[date("Y-m-d H:i:s"),date("Y-m-d H:i:s")]:explode(' - ', $this->date_range);

    $startDateTime = $startDate;
    $endDateTime   = $endDate;

    // 1) Select loans by customer_loans.created_at ONLY
    //    status: active/finished only
    $loans = CustomerLoan::find()
        ->alias('l')
        ->andWhere(['between', 'l.created_at', $startDateTime, $endDateTime])
        ->andWhere(['l.status' => [CustomerLoan::STATUS_ACTIVE, CustomerLoan::STATUS_FINISHED]])
        ->andWhere(['l.isDeleted' => 0])
        ->all();

    // 2) For each loan, include ALL repayment statements (no date condition here)
    $summaries = [];
    foreach ($loans as $loan) {
        $summaries[] = $loan->getRepaymentExecutiveSummary(); // no range params
    }

    // 3) Grand totals across returned loans
    $totals = [
        'loan_amount_total' => 0, // sum of latest loan_amount per loan
        'balance_total' => 0,     // sum of latest balance per loan

        'installment_total' => 0,
        'unpaid_amount_total' => 0,
        'paid_amount_total' => 0,
        'interest_amount_total' => 0,
        'prepayment_total' => 0,
        'penalty_amount_total' => 0,
        'principal_amount_total' => 0,
        'topup_amount_total' => 0,
    ];

    foreach ($summaries as $s) {
        $totals['loan_amount_total'] += (float)$s['loan_amount'];
        $totals['balance_total']     += (float)$s['balance'];

        $totals['installment_total']      += (float)$s['installment_total'];
        $totals['unpaid_amount_total']    += (float)$s['unpaid_amount_total'];
        $totals['paid_amount_total']      += (float)$s['paid_amount_total'];
        $totals['interest_amount_total']  += (float)$s['interest_amount_total'];
        $totals['prepayment_total']       += (float)$s['prepayment_total'];
        $totals['penalty_amount_total']   += (float)$s['penalty_amount_total'];
        $totals['principal_amount_total'] += (float)$s['principal_amount_total'];
        $totals['topup_amount_total']     += (float)$s['topup_amount_total'];
    }

    return [
        'start' => $startDate,
        'end' => $endDate,
        'loans' => $summaries,
        'totals' => $totals,
    ];
}
public function excutivesummaryExcel()
{
    [$start, $end] = ($this->date_range==null)?[date("Y-m-d H:i:s"),date("Y-m-d H:i:s")]:explode(' - ', (string)$this->date_range);

        $report = $this->buildLoansExecutiveSummaryReport();
        $loans  = $report['loans'] ?? [];
        $totals = $report['totals'] ?? [];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Executive Summary');

        // Order as requested
        $headers = [
            'A' => 'CUSTOMER ID',
            'B' => 'LOAN ID',
            'C' => 'LOAN AMOUNT',
            'D' => 'TOP UP',
            'E' => 'PRINCIPAL',
            'F' => 'INTEREST',
            'G' => 'INSTALLMENT',
            'H' => 'PAID',
            'I' => 'UNPAID',
            'J' => 'PENALTY',
            'K' => 'PREPAYMENT',
            'L' => 'BALANCE',
        ];

        // ---- Styles ----
        $titleStyle = [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $labelStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ];

        $currencyStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ];

        $tableHeaderStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2890C5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => false,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ];

        $cellBorders = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E6E6E6'],
                ],
            ],
        ];

        $moneyStyle = [
            'numberFormat' => ['formatCode' => '#,##0.00'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ];

        $leftStyle = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ];

        $totalsRowStyle = [
            'font' => ['bold' => true],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E6E6E6'],
                ],
            ],
        ];

        // ---- Layout ----
        $row = 1;

        // Title
        $sheet->mergeCells("A{$row}:L{$row}");
        $sheet->setCellValue("A{$row}", 'EXECUTIVE SUMMARY');
        $sheet->getStyle("A{$row}:L{$row}")->applyFromArray($titleStyle);
        $row += 2;

        // Header info
        $sheet->setCellValue("A{$row}", 'Start Date:');
        $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
        $sheet->setCellValue("B{$row}", date('d M Y', strtotime($start)));

        $sheet->setCellValue("E{$row}", 'End Date:');
        $sheet->getStyle("E{$row}")->applyFromArray($labelStyle);
        $sheet->setCellValue("F{$row}", date('d M Y', strtotime($end)));

        $sheet->setCellValue("L{$row}", 'Currency: TZS');
        $sheet->getStyle("L{$row}")->applyFromArray($currencyStyle);

        $row += 2;

        // Table header
        $headerRow = $row;
        foreach ($headers as $col => $name) {
            $sheet->setCellValue($col . $headerRow, $name);
        }
        $sheet->getStyle("A{$headerRow}:L{$headerRow}")->applyFromArray($tableHeaderStyle);
        $sheet->getRowDimension($headerRow)->setRowHeight(20);
        $row++;

        // Data rows
        $firstDataRow = $row;

        foreach ($loans as $r) {
            $sheet->setCellValue("A{$row}", $r['customerID'] ?? '');
            $sheet->setCellValue("B{$row}", $r['loanID'] ?? '');

            $sheet->setCellValue("C{$row}", (float)($r['loan_amount'] ?? 0));
            $sheet->setCellValue("D{$row}", (float)($r['topup_amount_total'] ?? 0));
            $sheet->setCellValue("E{$row}", (float)($r['principal_amount_total'] ?? 0));
            $sheet->setCellValue("F{$row}", (float)($r['interest_amount_total'] ?? 0));
            $sheet->setCellValue("G{$row}", (float)($r['installment_total'] ?? 0));
            $sheet->setCellValue("H{$row}", (float)($r['paid_amount_total'] ?? 0));
            $sheet->setCellValue("I{$row}", (float)($r['unpaid_amount_total'] ?? 0));
            $sheet->setCellValue("J{$row}", (float)($r['penalty_amount_total'] ?? 0));
            $sheet->setCellValue("K{$row}", (float)($r['prepayment_total'] ?? 0));
            $sheet->setCellValue("L{$row}", (float)($r['balance'] ?? 0));

            $row++;
        }

        $lastDataRow = $row - 1;

        if ($lastDataRow >= $firstDataRow) {
            $sheet->getStyle("A{$firstDataRow}:L{$lastDataRow}")->applyFromArray($cellBorders);
            $sheet->getStyle("A{$firstDataRow}:B{$lastDataRow}")->applyFromArray($leftStyle);
            $sheet->getStyle("C{$firstDataRow}:L{$lastDataRow}")->applyFromArray($moneyStyle);
        }

        // Totals row
        $totalsRow = $row;

        $sheet->mergeCells("A{$totalsRow}:B{$totalsRow}");
        $sheet->setCellValue("A{$totalsRow}", 'TOTAL');

        $sheet->setCellValue("C{$totalsRow}", (float)($totals['loan_amount_total'] ?? 0));
        $sheet->setCellValue("D{$totalsRow}", (float)($totals['topup_amount_total'] ?? 0));
        $sheet->setCellValue("E{$totalsRow}", (float)($totals['principal_amount_total'] ?? 0));
        $sheet->setCellValue("F{$totalsRow}", (float)($totals['interest_amount_total'] ?? 0));
        $sheet->setCellValue("G{$totalsRow}", (float)($totals['installment_total'] ?? 0));
        $sheet->setCellValue("H{$totalsRow}", (float)($totals['paid_amount_total'] ?? 0));
        $sheet->setCellValue("I{$totalsRow}", (float)($totals['unpaid_amount_total'] ?? 0));
        $sheet->setCellValue("J{$totalsRow}", (float)($totals['penalty_amount_total'] ?? 0));
        $sheet->setCellValue("K{$totalsRow}", (float)($totals['prepayment_total'] ?? 0));
        $sheet->setCellValue("L{$totalsRow}", (float)($totals['balance_total'] ?? 0));

        $sheet->getStyle("A{$totalsRow}:L{$totalsRow}")->applyFromArray($totalsRowStyle);
        $sheet->getStyle("C{$totalsRow}:L{$totalsRow}")->applyFromArray($moneyStyle);

        // Freeze below header
        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(0);

        /**
         * ---- Column sizing ----
         * 1) Give TOP UP a guaranteed minimum width (your complaint)
         * 2) Auto-size all columns to fit the largest content (numbers growing)
         *    (PhpSpreadsheet will expand based on rendered text width.)
         */
        $minWidths = [
            'A' => 14,
            'B' => 16,
            'C' => 14,
            'D' => 16, // TOP UP widened here
            'E' => 12,
            'F' => 12,
            'G' => 14,
            'H' => 12,
            'I' => 12,
            'J' => 12,
            'K' => 14,
            'L' => 14,
        ];

        foreach (array_keys($headers) as $col) {
            // autosize first (best fit)
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Apply minimum widths AFTER autosize so it never becomes too small
        foreach ($minWidths as $col => $w) {
            $current = $sheet->getColumnDimension($col)->getWidth();
            // Some writers return -1 when autosize; set min width in that case too
            if ($current === -1 || $current < $w) {
                $sheet->getColumnDimension($col)->setWidth($w);
            }
        }

        // Output
        $filename = 'executive_summary_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
}




}