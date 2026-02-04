<?php
use yii\helpers\Html;

/**
 * MPDF-compatible interest summary report view
 *
 * Expected variables:
 * @var string|null $date_range  Raw picker value: "YYYY-MM-DD - YYYY-MM-DD" or null/empty
 * @var array $interest_summaries Each row:
 *   [
 *     'Member ID' => ...,
 *     'Customer ID' => ...,
 *     'Full Name' => ...,
 *     'Total Interest' => ...
 *   ]
 */

$formatter = Yii::$app->formatter;

/**
 * Compute $from / $to from $date_range (robust)
 * Accepts:
 * - "YYYY-MM-DD - YYYY-MM-DD"
 * - "YYYY-MM-DD  -  YYYY-MM-DD" (extra spaces)
 * - null/empty → All
 */
$from = $to = null;
if($date_range)
    {
     [$from,$to]=explode(' - ',$date_range);
    }


/** Totals */
$grandTotal = 0.0;
foreach (($interest_summaries ?? []) as $row) {
    $grandTotal += (float)($row['Total Interest'] ?? 0);
}

$records = is_array($interest_summaries) ? count($interest_summaries) : 0;

/** Safe formatted range labels */
$fromLabel = 'All';
$toLabel   = 'All';

if ($from && $to) {
    try {
        $fromLabel = Html::encode($formatter->asDate($from, 'dd MMM yyyy'));
    } catch (\Throwable $e) {
        $fromLabel = Html::encode($from);
    }

    try {
        $toLabel = Html::encode($formatter->asDate($to, 'dd MMM yyyy'));
    } catch (\Throwable $e) {
        $toLabel = Html::encode($to);
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

    /* Head row mimics your loan statement design */
    .heading td {
        font-weight: bold;
        background-color: #2890c5;
        color: #ffffff;
    }

    /* Report table styling for MPDF */
    .report-table th,
    .report-table td {
        border: none;
        padding: 6px 6px;
        vertical-align: top;
        text-align: right;
    }
    .report-table thead th {
        font-weight: bold;
        background-color: #f3f5f7;
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
        vertical-align: top;
    }
    .meta-table td {
        padding: 4px 6px;
        vertical-align: top;
        text-align: left;
    }
</style>

<p align="center">
    <img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="width:120px;height:90px">
</p>

<h3 style="text-align:center;margin-top:30px;margin-bottom:3px">Shareholder Interests Summary</h3>
<hr style="margin-bottom: 50px; margin-top:0px">

<!-- META -->
<table width="100%" cellspacing="0" cellpadding="0" class="main">
    <tr>
        <td width="50%" valign="top">
            <table class="meta-table" width="100%" cellspacing="2" cellpadding="4">
                <tr>
                    <th>From</th>
                    <td><?= ($from && $to) ? $fromLabel : 'All' ?></td>
                </tr>
                <tr>
                    <th>To</th>
                    <td><?= ($from && $to) ? $toLabel : 'All' ?></td>
                </tr>
            </table>
        </td>

        <td width="50%" valign="top">
            <table class="meta-table" width="100%" cellspacing="2" cellpadding="4">
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

<!-- Summary Table -->
<table class="report-table" width="100%" cellspacing="2" cellpadding="3" style="margin-top:40px">
    <thead>
        <tr class="heading">
            <td style="width:70px;" class="text-start">#</td>
            <td class="text-start">Customer ID</td>
            <td class="text-start">Member ID</td>
            <td class="text-start">Full Name</td>
            <td class="text-end">Total Interest</td>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($interest_summaries)): ?>
            <tr>
                <td colspan="5" class="muted" style="text-align:center; padding:14px;">
                    No interests found for the selected date range.
                </td>
            </tr>

            <tr class="totals">
                <th colspan="4" class="text-start">TOTAL</th>
                <th class="text-end"><?= $formatter->asDecimal(0, 2) ?></th>
            </tr>
        <?php else: ?>
            <?php foreach ($interest_summaries as $i => $row): ?>
                <?php
                    $customerId = $row['Customer ID'] ?? '';
                    $memberId   = $row['Member ID'] ?? '';
                    $fullName   = $row['Full Name'] ?? '';
                    $amount     = (float)($row['Total Interest'] ?? 0);
                ?>
                <tr>
                    <td class="text-start"><?= $i + 1 ?></td>
                    <td class="text-start"><?= Html::encode($customerId) ?></td>
                    <td class="text-start"><?= Html::encode($memberId) ?></td>
                    <td class="text-start"><?= Html::encode($fullName) ?></td>
                    <td class="text-end"><?= $formatter->asDecimal($amount, 2) ?></td>
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
