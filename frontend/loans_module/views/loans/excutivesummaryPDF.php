<?php
use Yii;

[$start, $end] = explode(' - ', $model->date_range);

$report  = $model->buildLoansExecutiveSummaryReport();
$loans   = $report['loans'] ?? [];
$totals  = $report['totals'] ?? [];
?>

<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    #cashtable td {
        padding: 6px;
        vertical-align: middle;
    }

    #heading td {
        font-weight: bold;
        background-color: #2890c5;
        color: #ffffff;
        text-align: center;
        white-space: nowrap;
    }

    /* Customer ID column */
    #cashtable td:nth-child(1),
    #heading td:nth-child(1) {
        width: 110px;
        text-align: left;
    }

    /* Loan Amount column */
    #cashtable td:nth-child(3),
    #heading td:nth-child(3) {
        width: 100px;
    }

    /* All numeric columns */
    #cashtable td.text-right {
        white-space: nowrap;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .bold td {
        font-weight: bold;
        border-top: solid 1px black;
        white-space: nowrap;
    }
</style>

<p align="center">
    <img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="width:120px;height:90px">
</p>

<h3 style="text-align: center;margin-top:30px;margin-bottom:3px">
    EXECUTIVE SUMMARY
</h3>
<hr style="margin-bottom: 50px; margin-top:0px">

<div>

    <!-- Header -->
    <table style="margin-bottom:30px;">
        <tr>
            <td class="text-left">
                <strong>Start Date:</strong> <?= date('d M Y', strtotime($start)) ?><br>
                <strong>End Date:</strong> <?= date('d M Y', strtotime($end)) ?>
            </td>
            <td class="text-right">
                <strong>Currency:</strong> TZS
            </td>
        </tr>
    </table>

    <?php if (!empty($loans)) : ?>

        <table id="cashtable">
            <tr id="heading">
                <td>CUSTOMER ID</td>
                <td>LOAN ID</td>
                <td>LOAN AMOUNT</td>
                <td>TOP UP</td>
                <td>PRINCIPAL</td>
                <td>INTEREST</td>
                <td>INSTALLMENT</td>
                <td>PAID</td>
                <td>UNPAID</td>
                <td>PENALTY</td>
                <td>PREPAYMENT</td>
                <td>BALANCE</td>
            </tr>

            <?php foreach ($loans as $row) : ?>
                <tr>
                    <td><?= $row['customerID'] ?></td>
                    <td><?= $row['loanID'] ?></td>

                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['loan_amount'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['topup_amount_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['principal_amount_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['interest_amount_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['installment_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['paid_amount_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['unpaid_amount_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['penalty_amount_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['prepayment_total'], 2) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['balance'], 2) ?></td>
                </tr>
            <?php endforeach; ?>

            <!-- Totals -->
            <tr class="bold">
                <td colspan="2" class="text-right">TOTAL</td>

                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['loan_amount_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['topup_amount_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['principal_amount_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['interest_amount_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['installment_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['paid_amount_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['unpaid_amount_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['penalty_amount_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['prepayment_total'] ?? 0, 2) ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($totals['balance_total'] ?? 0, 2) ?></td>
            </tr>
        </table>

    <?php else : ?>
        <p style="text-align:center;"><strong>No loans found for the selected date range.</strong></p>
    <?php endif; ?>

</div>
