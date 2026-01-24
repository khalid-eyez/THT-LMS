<?php
namespace frontend\loans_module\models;

use yii\base\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii;

class ExcelReports extends Model
{
    public function ExcelSchedule($loan)
    {
$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer Loan Statement');

        // ---------------------------
        // Column widths for info tables
        // ---------------------------
        $sheet->getColumnDimension('A')->setWidth(2);   // blank spacer
        $sheet->getColumnDimension('B')->setWidth(26);  // Table 1 labels wider
        $sheet->getColumnDimension('C')->setWidth(25);  // Table 1 values
        $sheet->getColumnDimension('D')->setWidth(22);  // Principal Amount widened
        $sheet->getColumnDimension('E')->setWidth(18);  // Interest Amount
        $sheet->getColumnDimension('F')->setWidth(18);  // Installment Amount
        $sheet->getColumnDimension('G')->setWidth(18);  // Loan Balance

        $row = 1;

        // Title
        $sheet->setCellValue("B{$row}", 'Customer Loan Statement');
        $sheet->mergeCells("B{$row}:G{$row}");
        $sheet->getStyle("B{$row}")->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;

        // ---------------------------
        // Table 1: Loan + Customer Info (Left)
        // ---------------------------
        $loanInfoLabels = [
            'Loan ID','Customer Name','Customer ID',
            'Repayment Frequency','Loan Duration',
            'Interest Rate','Status'
        ];

        $loanInfoValues = [
            $loan->loanID,
            ucfirst($loan->customer->full_name),
            $loan->customer->customerID,
            ucwords(strtolower($loan->repayment_frequency)),
            $loan->loan_duration_units . ' Periods',
            $loan->interest_rate . '%',
            $loan->status
        ];

        foreach ($loanInfoLabels as $idx => $label) {
            $sheet->setCellValue("B" . ($row + $idx), $label);
            $sheet->setCellValue("C" . ($row + $idx), $loanInfoValues[$idx]);
        }

        // Bold Table 1 labels
        $sheet->getStyle("B{$row}:B" . ($row + count($loanInfoLabels) - 1))->getFont()->setBold(true);

        // ---------------------------
        // Table 2: Amounts (Right)
        // ---------------------------
        $amountLabels = ['Loan Amount','Interest Amount','Total Repayment','Currency'];
        $amountValues = [
            $loan->loan_amount + $loan->topup_amount,
            $loan->getRepaymentSchedules()->sum('interest_amount'),
            $loan->getRepaymentSchedules()->sum('installment_amount'),
            'TZS'
        ];

        $rowAmountsStart = $row;
        foreach ($amountLabels as $idx => $label) {
            $sheet->setCellValue("E" . ($rowAmountsStart + $idx), $label);
            $sheet->setCellValue("F" . ($rowAmountsStart + $idx), $amountValues[$idx]);
        }

        // Bold Table 2 labels
        $sheet->getStyle("E{$rowAmountsStart}:E" . ($rowAmountsStart + count($amountLabels) - 1))->getFont()->setBold(true);

        // Right-align numeric values including currency
        $sheet->getStyle("C{$row}:C" . ($row + count($loanInfoLabels) - 1))
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("F{$rowAmountsStart}:F" . ($rowAmountsStart + count($amountLabels) - 1))
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Money format
        $sheet->getStyle("C{$row}:C" . ($row + count($loanInfoLabels) - 1))
              ->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle("F{$rowAmountsStart}:F" . ($rowAmountsStart + count($amountLabels) - 2))
              ->getNumberFormat()->setFormatCode('#,##0.00');

        $row += max(count($loanInfoLabels), count($amountLabels)) + 2;

        // ---------------------------
        // Repayment Table
        // ---------------------------
        $headers = ['#','Repayment Date','Loan Amount','Principal Amount','Interest Amount','Installment Amount','Loan Balance'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}{$row}", $header);
            $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true);
            $sheet->getStyle("{$col}{$row}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("{$col}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                  ->getStartColor()->setRGB('D9E5F7');
            $col++;
        }

        $row++;
        $count = 1;
        $principal = 0; $interest = 0; $installment = 0; $loan_balance = 0;

        foreach ($loan->repaymentSchedules as $due) {
            $sheet->setCellValue("A{$row}", $count++);
            $sheet->setCellValue("B{$row}", \Yii::$app->formatter->asDate($due->repayment_date, 'php:d M Y'));
            $sheet->setCellValue("C{$row}", $due->loan_amount);
            $sheet->setCellValue("D{$row}", $due->principle_amount);
            $sheet->setCellValue("E{$row}", $due->interest_amount);
            $sheet->setCellValue("F{$row}", $due->installment_amount);
            $sheet->setCellValue("G{$row}", $due->loan_balance);

            $principal += $due->principle_amount;
            $interest += $due->interest_amount;
            $installment += $due->installment_amount;
            $loan_balance = $due->loan_balance;

            $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("A{$row}:G{$row}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_HAIR);

            // Format numbers as money
            foreach (range('C','G') as $colNum) {
                $sheet->getStyle("{$colNum}{$row}")
                      ->getNumberFormat()->setFormatCode('#,##0.00');
            }

            $row++;
        }

        // ---------------------------
        // Totals row
        // ---------------------------
        $sheet->setCellValue("B{$row}", "TOTALS");
        $sheet->setCellValue("C{$row}", $loan_balance);
        $sheet->setCellValue("D{$row}", $principal);
        $sheet->setCellValue("E{$row}", $interest);
        $sheet->setCellValue("F{$row}", $installment);
        $sheet->setCellValue("G{$row}", $loan_balance);

        $sheet->getStyle("B{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("B{$row}:G{$row}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Format totals as money
        $sheet->getStyle("C{$row}:G{$row}")
              ->getNumberFormat()->setFormatCode('#,##0.00');

        // ---------------------------
        // Export Excel
        // ---------------------------
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="LoanStatement.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;

    }
    public function excelStatement($loan)
    {
$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer Repayment Statement');

        // ---------------------------
        // Column widths (ONLY B fixed)
        // ---------------------------
        $sheet->getColumnDimension('A')->setWidth(2);   
        $sheet->getColumnDimension('B')->setWidth(26);  // ONLY widened column
        $sheet->getColumnDimension('D')->setWidth(2);   

        $row = 1;

        // Title
        $sheet->setCellValue("B{$row}", 'Customer Loan Repayment Statement');
        $sheet->mergeCells("B{$row}:G{$row}");
        $sheet->getStyle("B{$row}")->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;

        // ---------------------------
        // Left Column: Loan Info
        // ---------------------------
        $loanInfoLabels = [
            'Loan ID','Customer Name','Customer ID',
            'Repayment Frequency','Loan Duration',
            'Interest Rate','Status'
        ];

        $loanInfoValues = [
            $loan->loanID,
            ucfirst($loan->customer->full_name),
            $loan->customer->customerID,
            ucwords(strtolower($loan->repayment_frequency)),
            $loan->loan_duration_units . ' Periods',
            $loan->interest_rate . '%',
            $loan->status
        ];

        foreach ($loanInfoLabels as $idx => $label) {
            $sheet->setCellValue("B" . ($row + $idx), $label);
            $sheet->setCellValue("C" . ($row + $idx), $loanInfoValues[$idx]);
        }

        $sheet->getStyle("B{$row}:B" . ($row + count($loanInfoLabels) - 1))
              ->getFont()->setBold(true);

        // ---------------------------
        // Right Column: Amounts
        // ---------------------------
        $amountLabels = ['Loan Amount','Interest Amount','Total Repayment','Currency'];
        $amountValues = [
            $loan->loan_amount + $loan->topup_amount,
            $loan->getRepaymentSchedules()->sum('interest_amount'),
            $loan->getRepaymentSchedules()->sum('installment_amount'),
            'TZS'
        ];

        $rowAmountsStart = $row;
        foreach ($amountLabels as $idx => $label) {
            $sheet->setCellValue("E" . ($rowAmountsStart + $idx), $label);
            $sheet->setCellValue("F" . ($rowAmountsStart + $idx), $amountValues[$idx]);
        }

        $sheet->getStyle("E{$rowAmountsStart}:E" . ($rowAmountsStart + count($amountLabels) - 1))
              ->getFont()->setBold(true);

        // Align loan info and amounts
        $sheet->getStyle("C{$row}:C" . ($row + count($loanInfoLabels) - 1))
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("F{$rowAmountsStart}:F" . ($rowAmountsStart + count($amountLabels) - 1))
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("C{$row}:C" . ($row + count($loanInfoLabels) - 1))
              ->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle("F{$rowAmountsStart}:F" . ($rowAmountsStart + count($amountLabels) - 2))
              ->getNumberFormat()->setFormatCode('#,##0.00');

        $row += max(count($loanInfoLabels), count($amountLabels)) + 2;

        // ---------------------------
        // Repayment Table Header
        // ---------------------------
        $tableHeaders = ['#','Date','Loan Amount','Top-up','Principal','Interest','Installment','Paid','Unpaid','Penalty','Prepayment','Balance'];
        $colLetter = 'A';
        foreach ($tableHeaders as $header) {
            $sheet->setCellValue("{$colLetter}{$row}", $header);
            $sheet->getStyle("{$colLetter}{$row}")->getFont()->setBold(true);
            // LEFT align header
            $sheet->getStyle("{$colLetter}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("{$colLetter}{$row}")
                  ->getFill()->setFillType(Fill::FILL_SOLID)
                  ->getStartColor()->setRGB('D9E5F7');
            $colLetter++;
        }

        $row++;
        $count = 1;
        $principal = $interest = $installment = $paid = $unpaid = $penalties = $prepayment = $topup = $balance = 0;

        foreach ($loan->repaymentStatements as $due) {
            $sheet->setCellValue("A{$row}", $count++);
            $sheet->setCellValue("B{$row}", Yii::$app->formatter->asDate($due->payment_date,'php:d M Y'));
            $sheet->setCellValue("C{$row}", $due->loan_amount);
            $sheet->setCellValue("D{$row}", $due->topup_amount);
            $sheet->setCellValue("E{$row}", $due->principal_amount);
            $sheet->setCellValue("F{$row}", $due->interest_amount);
            $sheet->setCellValue("G{$row}", $due->installment);
            $sheet->setCellValue("H{$row}", $due->paid_amount);
            $sheet->setCellValue("I{$row}", $due->unpaid_amount);
            $sheet->setCellValue("J{$row}", $due->penalty_amount);
            $sheet->setCellValue("K{$row}", $due->prepayment);
            $sheet->setCellValue("L{$row}", $due->balance);

            // LEFT-ALIGN all repayment table cells (data rows)
            $sheet->getStyle("A{$row}:L{$row}")
                  ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            // Keep number format
            foreach (range('C','L') as $col) {
                $sheet->getStyle("{$col}{$row}")
                      ->getNumberFormat()->setFormatCode('#,##0.00');
            }

            $principal += $due->principal_amount;
            $interest += $due->interest_amount;
            $installment += $due->installment;
            $paid += $due->paid_amount;
            $unpaid += $due->unpaid_amount;
            $penalties += $due->penalty_amount;
            $prepayment += $due->prepayment;
            $topup += $due->topup_amount;
            $balance = $due->balance;

            $row++;
        }

        // ---------------------------
        // Totals row
        // ---------------------------
        $sheet->setCellValue("B{$row}", 'TOTALS');
        $sheet->setCellValue("C{$row}", $balance);
        $sheet->setCellValue("D{$row}", $topup);
        $sheet->setCellValue("E{$row}", $principal);
        $sheet->setCellValue("F{$row}", $interest);
        $sheet->setCellValue("G{$row}", $installment);
        $sheet->setCellValue("H{$row}", $paid);
        $sheet->setCellValue("I{$row}", $unpaid);
        $sheet->setCellValue("J{$row}", $penalties);
        $sheet->setCellValue("K{$row}", $prepayment);
        $sheet->setCellValue("L{$row}", $balance);

        // LEFT-ALIGN totals row
        $sheet->getStyle("A{$row}:L{$row}")
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle("C{$row}:L{$row}")
              ->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle("B{$row}:L{$row}")->getFont()->setBold(true);
        $sheet->getStyle("B{$row}:L{$row}")
              ->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        // Auto-fit remaining columns
        foreach (range('C','L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ---------------------------
        // Export
        // ---------------------------
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CustomerRepaymentStatement.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;

    }
}
