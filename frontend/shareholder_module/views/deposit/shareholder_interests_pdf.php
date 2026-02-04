<?php
use yii\helpers\Html;

/**
 * MPDF-compatible interest report view
 *
 * Expected variables:
 * @var common\models\DepositInterest[] $interests
 * @var string|null $date_range
 * @var common\models\Shareholder $shareholder
 */

$rows      = $interests ?? [];
$formatter = Yii::$app->formatter;

/** Totals */
$totalInterest = 0.0;
foreach ($rows as $r) {
    $totalInterest += (float)$r->interest_amount;
}

/**
 * Format helper: Y-m-d / Y-m-d H:i:s → "1 Jan 2026"
 */
$fmt = function ($date) use ($formatter) {
    if (empty($date)) return '-';
    try {
        return $formatter->asDate($date, 'dd MMM yyyy');
    } catch (\Throwable $e) {
        return Html::encode($date);
    }
};

/** Extract FROM / TO from $date_range */
$fromDate = $toDate = null;
if (!empty($date_range) && strpos($date_range, ' - ') !== false) {
    [$fromDate, $toDate] = array_map('trim', explode(' - ', $date_range));
}

/** Shareholder/customer info safe fallback (same pattern as deposits PDF) */
$customerName = $shareholder->customer->full_name ?? ($shareholder->customer->name ?? null);
$customerId   = $shareholder->customer->customerID ?? ($shareholder->customer->id ?? null);
$memberId     = $shareholder->memberID ?? null;
$shares       = $shareholder->shares ?? null;
$initialCap   = $shareholder->initialCapital ?? null;
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

<h3 style="text-align:center;margin-top:30px;margin-bottom:3px">Shareholder Interests Statement</h3>
<hr style="margin-bottom: 50px; margin-top:0px">

<!-- SHAREHOLDER INFO (LEFT) + SUMMARY (RIGHT) -->
<table width="100%" cellspacing="0" cellpadding="0" class="main">
    <tr>

        <!-- Shareholder Information -->
        <td width="50%" valign="top">
            <table class="meta-table" width="100%" cellspacing="2" cellpadding="4">
                <tr>
                    <th>Member ID</th>
                    <td><?= Html::encode($memberId ?: '-') ?></td>
                </tr>
                <tr>
                    <th>Shareholder Name</th>
                    <td><?= Html::encode($customerName ?: '-') ?></td>
                </tr>
                <tr>
                    <th>Customer ID</th>
                    <td><?= Html::encode($customerId ?: '-') ?></td>
                </tr>
                <tr>
                    <th>Shares</th>
                    <td><?= Html::encode($shares !== null ? $shares : '-') ?></td>
                </tr>
                <tr>
                    <th>Initial Capital</th>
                    <td><?= $initialCap !== null ? $formatter->asDecimal($initialCap, 2) : '-' ?></td>
                </tr>
            </table>
        </td>

        <!-- Interest Summary -->
        <td width="50%" valign="top">
            <table class="meta-table" width="100%" cellspacing="2" cellpadding="4">
                <tr>
                    <th>Total Interests</th>
                    <td><?= $formatter->asDecimal($totalInterest, 2) ?></td>
                </tr>
                <tr>
                    <th>Currency</th>
                    <td>TZS</td>
                </tr>
                <tr>
                    <th>Selected Period</th>
                    <td>
                        <?php if ($fromDate && $toDate): ?>
                            <?= Html::encode($fmt($fromDate)) ?>
                            &nbsp;→&nbsp;
                            <?= Html::encode($fmt($toDate)) ?>
                        <?php else: ?>
                            <span class="muted">All dates</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Records</th>
                    <td><?= Html::encode(count($rows)) ?></td>
                </tr>
            </table>
        </td>

    </tr>
</table>

<!-- Interests Table -->
<table class="report-table" width="100%" cellspacing="2" cellpadding="3" style="margin-top:40px">
    <thead>
        <tr class="heading">
            <td style="width:70px;" class="text-start">#</td>
            <td class="text-start">Payment Date</td>
            <td class="text-end">Claim Months</td>
            <td class="text-end">Interest Amount</td>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="4" class="muted" style="text-align:center; padding:14px;">
                    No interests found for the selected date range.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $i => $interest): ?>
                <tr>
                    <td class="text-start"><?= $i + 1 ?></td>
                    <td class="text-start"><?= Html::encode($fmt($interest->payment_date)) ?></td>
                    <td class="text-end"><?= Html::encode((int)$interest->claim_months) ?></td>
                    <td class="text-end"><?= $formatter->asDecimal((float)$interest->interest_amount, 2) ?></td>
                </tr>
            <?php endforeach; ?>

            <tr class="totals">
                <th colspan="3" class="text-start">TOTAL</th>
                <th class="text-end"><?= $formatter->asDecimal($totalInterest, 2) ?></th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
