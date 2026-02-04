<?php
use yii\helpers\Html;

/**
 * MPDF-compatible summary report view
 *
 * Expected variables:
 * @var array $range ['from' => 'YYYY-MM-DD', 'to' => 'YYYY-MM-DD']
 * @var array $deposits Each row:
 *   ['Customer ID'=>..., 'Member ID'=>..., 'Full Name'=>..., 'Deposit Amount'=>...]
 */

$formatter = Yii::$app->formatter;

$from = $range['from'] ?? null;
$to   = $range['to'] ?? null;

/** Totals */
$grandTotal = 0.0;
foreach ($deposits as $row) {
    $grandTotal += (float)($row['Deposit Amount'] ?? 0);
}

$records = is_array($deposits) ? count($deposits) : 0;

/** Date labels */
$fromLabel = 'All';
$toLabel   = 'All';

if ($from && $to) {
    try {
        $fromLabel = $formatter->asDate($from, 'dd MMM yyyy');
        $toLabel   = $formatter->asDate($to, 'dd MMM yyyy');
    } catch (\Throwable $e) {
        $fromLabel = (string)$from;
        $toLabel   = (string)$to;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        color: #000;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }

    .heading td {
        font-weight: bold;
        background-color: #2890c5;
        color: #ffffff;
    }

    .report-table th,
    .report-table td {
        border: none;
        padding: 6px 6px;
        vertical-align: top;
        text-align: right;
    }

    tr.totals th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .text-end { text-align: right; }
    .text-start { text-align: left; }
    .muted { color: #666; }

    .meta-table th {
        text-align: left;
        width: 35%;
        font-weight: bold;
        padding: 4px 6px;
    }
    .meta-table td {
        padding: 4px 6px;
        text-align: left;
    }
</style>

<p align="center">
    <img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="width:120px;height:90px">
</p>

<h3 style="text-align:center;margin-top:30px;margin-bottom:3px">
    Shareholder Deposits Summary
</h3>
<hr style="margin-bottom: 50px; margin-top:0px">

<!-- META -->
<table width="100%">
    <tr>
        <td width="50%" valign="top">
            <table class="meta-table" width="100%">
                <tr>
                    <th>From</th>
                    <td><?= Html::encode($fromLabel) ?></td>
                </tr>
                <tr>
                    <th>To</th>
                    <td><?= Html::encode($toLabel) ?></td>
                </tr>
            </table>
        </td>

        <td width="50%" valign="top">
            <table class="meta-table" width="100%">
                <tr>
                    <th>Records</th>
                    <td><?= Html::encode($records) ?></td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <td><?= $formatter->asDecimal($grandTotal, 2) ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- TABLE -->
<table class="report-table" width="100%" style="margin-top:40px">
    <thead>
        <tr class="heading">
            <td class="text-start" style="width:70px;">#</td>
            <td class="text-start">Customer ID</td>
            <td class="text-start">Member ID</td>
            <td class="text-start">Full Name</td>
            <td class="text-end">Deposit Amount</td>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($deposits)): ?>
            <tr>
                <td colspan="5" class="muted" style="text-align:center;padding:14px;">
                    No deposits found.
                </td>
            </tr>
            <tr class="totals">
                <th colspan="4" class="text-start">TOTAL</th>
                <th class="text-end"><?= $formatter->asDecimal(0, 2) ?></th>
            </tr>
        <?php else: ?>
            <?php foreach ($deposits as $i => $row): ?>
                <tr>
                    <td class="text-start"><?= $i + 1 ?></td>
                    <td class="text-start"><?= Html::encode($row['Customer ID'] ?? '') ?></td>
                    <td class="text-start"><?= Html::encode($row['Member ID'] ?? '') ?></td>
                    <td class="text-start"><?= Html::encode($row['Full Name'] ?? '') ?></td>
                    <td class="text-end">
                        <?= $formatter->asDecimal((float)($row['Deposit Amount'] ?? 0), 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr class="totals">
                <th colspan="4" class="text-start">TOTAL</th>
                <th class="text-end"><?= $formatter->asDecimal($grandTotal, 2) ?></th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
