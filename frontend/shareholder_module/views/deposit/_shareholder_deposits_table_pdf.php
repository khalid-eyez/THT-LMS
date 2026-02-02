<?php
use yii\helpers\Html;

/**
 * MPDF-compatible report view
 *
 * Expected variables:
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\Shareholder $shareholder
 * @var int $shareholderID
 *
 * NOTE:
 * - $shareholder should be loaded in controller with customer relation:
 *   $shareholder = Shareholder::find()->with('customer')->where(['id'=>$shareholderID])->one();
 */

$rows      = $dataProvider->getModels();
$formatter = Yii::$app->formatter;

/** Totals */
$totalDeposits = 0.0;
foreach ($rows as $deposit) {
    $totalDeposits += (float)$deposit->amount;
}

/**
 * Extract FROM / TO from date range (posted from the search form)
 * Expected: "YYYY-MM-DD - YYYY-MM-DD"
 */
$dateRange = Yii::$app->request->post('DepositSearch')['deposit_date']
    ?? Yii::$app->request->get('DepositSearch')['deposit_date']
    ?? null;

$fromDate = $toDate = null;
if ($dateRange && strpos($dateRange, ' - ') !== false) {
    [$fromDate, $toDate] = array_map('trim', explode(' - ', $dateRange));
}

/** Shareholder/customer info safe fallback */
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

    /* Deposit table styling for MPDF */
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
    }
</style>

<p align="center">
    <img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="width:120px;height:90px">
</p>

<h3 style="text-align:center;margin-top:30px;margin-bottom:3px">Shareholder Deposits</h3>
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

        <!-- Deposits Summary -->
        <td width="50%" valign="top">
            <table class="meta-table" width="100%" cellspacing="2" cellpadding="4">
                <tr>
                    <th>Total Deposits</th>
                    <td><?= $formatter->asDecimal($totalDeposits, 2) ?></td>
                </tr>
                <tr>
                    <th>Currency</th>
                    <td>TZS</td>
                </tr>
                <tr>
                    <th>Selected Period</th>
                    <td>
                        <?php if ($fromDate && $toDate): ?>
                            <?= Html::encode($formatter->asDate($fromDate, 'dd MMM yyyy')) ?>
                            &nbsp;→&nbsp;
                            <?= Html::encode($formatter->asDate($toDate, 'dd MMM yyyy')) ?>
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

<!-- Deposits Table -->
<table class="report-table" width="100%" cellspacing="2" cellpadding="3" style="margin-top:40px">
    <thead>
        <tr class="heading">
            <td style="width:70px;">#</td>
            <td>Deposit Date</td>
            <td>Type</td>
            <td class="text-end">Amount</td>
            <td class="text-end">Interest %</td>
            <td style="width:170px;">Record Date</td>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="6" class="muted" style="text-align:center; padding:14px;">
                    No deposits found for the selected date range.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $i => $deposit): ?>
                <tr>
                    <td><?= $i + 1 ?></td>

                    <td>
                        <?= Html::encode(
                            $formatter->asDate($deposit->deposit_date, 'dd MMM yyyy')
                        ) ?>
                    </td>

                    <td><?= Html::encode($deposit->type) ?></td>

                    <td class="text-end">
                        <?= $formatter->asDecimal($deposit->amount, 2) ?>
                    </td>

                    <td class="text-end">
                        <?= $formatter->asDecimal($deposit->interest_rate, 2) ?>
                    </td>

                    <td>
                        <?= Html::encode(
                            $formatter->asDatetime($deposit->created_at, 'php:d M Y')
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <!-- TOTAL ROW -->
            <tr class="totals">
                <th colspan="3" class="">TOTAL</th>
                <th class=""><?= $formatter->asDecimal($totalDeposits, 2) ?></th>
                <th colspan="2"></th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
