<?php
use yii\helpers\Html;

/**
 * MPDF Repayment Schedule PDF View
 *
 * Expected:
 * @var array $repaymentschedules  Structure:
 *   [
 *     'schedules' => [
 *        [
 *          'loan_amount'   => float,
 *          'principal'     => float,
 *          'interest'      => float,
 *          'installment'   => float,
 *          'balance'       => float,
 *          'payment_date'  => 'Y-m-d H:i:s'
 *        ],
 *        ...
 *     ],
 *     'meta' => [
 *        'Loan Amount' => ...,
 *        'Loan Type' => ...,
 *        'Repayment frequency' => ...,
 *        'Loan Duration' => ...,
 *        'Interest Rate' => ...,
 *     ]
 *   ]
 */

$schedules = $repaymentschedules['schedules'] ?? [];
$meta      = is_array($repaymentschedules['meta'] ?? null) ? $repaymentschedules['meta'] : [];

/* ---------- META FIXES ---------- */

// Add Currency to meta (must appear on the right column)
$meta['Currency'] = 'TZS';

// Format Loan Amount in META as money-like decimal ONLY (NO currency symbol)
if (isset($meta['Loan Amount']) && is_numeric($meta['Loan Amount'])) {
    $meta['Loan Amount'] = Yii::$app->formatter->asDecimal((float)$meta['Loan Amount'], 2);
}

// Format Interest Rate in META as percentage if numeric (assume 12.5 means 12.5%)
if (isset($meta['Interest Rate'])) {
    $raw = $meta['Interest Rate'];
    if (is_numeric($raw)) {
        $meta['Interest Rate'] = Yii::$app->formatter->asPercent(((float)$raw) / 100, 2);
    }
}

/* ---------- ORDER + SPLIT META (keep right column vertically aligned) ---------- */
/**
 * To keep right column vertically consistent, we DO NOT alternate items.
 * We split by halves and keep row counts consistent by padding the shorter side with blanks.
 */
$metaKeys = array_keys($meta);

// Ensure "Currency" is on the right by moving it to the end before splitting
if (($idx = array_search('Currency', $metaKeys, true)) !== false) {
    unset($metaKeys[$idx]);
    $metaKeys = array_values($metaKeys);
    $metaKeys[] = 'Currency';
}

$half = (int) ceil(count($metaKeys) / 2);

$leftKeys  = array_slice($metaKeys, 0, $half);
$rightKeys = array_slice($metaKeys, $half);

// Pad to same number of rows so right column aligns vertically with left
$rows = max(count($leftKeys), count($rightKeys));
while (count($leftKeys) < $rows)  { $leftKeys[]  = null; }
while (count($rightKeys) < $rows) { $rightKeys[] = null; }

/* ---------- TOTALS ---------- */
$count = 1;
$principalTotal = 0;
$interestTotal = 0;
$installmentTotal = 0;
$startLoanAmount = 0;
$lastBalance = 0;

if (!empty($schedules)) {
    $startLoanAmount = (float) ($schedules[0]['loan_amount'] ?? 0);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Repayment Schedule</title>
</head>
<body>

<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        text-align:left;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .heading td {
        font-weight: bold;
        background-color: #2890c5;
        color: #ffffff;
        text-align:left;
        font-size: 10px;
        padding: 6px;
    }

    td {
        padding: 5px 6px;
        vertical-align: top;
    }

    tr.totals th{
       border-top: 1px solid #393939;
       background-color: #f8f9fa;
       text-align: left;
       padding: 6px;
    }

    .meta-table th {
        width: 38%;
        font-weight: bold;
        text-align:left;
        white-space: nowrap;
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

<h3 style="text-align:center;margin-top:30px;margin-bottom:3px">
    Repayment Schedule
</h3>
<hr style="margin-bottom:50px;margin-top:0">

<!-- META SECTION (2 columns, vertically aligned rows) -->
<table width="100%" cellspacing="0" cellpadding="0">
<tr>

<td width="50%" valign="top">
    <table class="meta-table" width="100%" cellspacing="2" cellpadding="4">
        <?php for ($i = 0; $i < $rows; $i++): ?>
            <?php $k = $leftKeys[$i]; ?>
            <tr>
                <th><?= $k !== null ? Html::encode($k) : '&nbsp;' ?></th>
                <td><?= $k !== null ? Html::encode(ucfirst($meta[$k])) : '&nbsp;' ?></td>
            </tr>
        <?php endfor; ?>
    </table>
</td>

<td width="50%" valign="top">
    <table class="meta-table" width="100%" cellspacing="2" cellpadding="4">
        <?php for ($i = 0; $i < $rows; $i++): ?>
            <?php $k = $rightKeys[$i]; ?>
            <tr>
                <th style="text-align:left"><?= $k !== null ? Html::encode($k) : '&nbsp;' ?></th>
                <td style="text-align:right"><?= $k !== null ? Html::encode($meta[$k]) : '&nbsp;' ?></td>
            </tr>
        <?php endfor; ?>
    </table>
</td>

</tr>
</table>

<!-- REPAYMENT TABLE -->
<table width="100%" cellspacing="2" cellpadding="3" style="margin-top:40px">
    <tr class="heading">
        <td>#</td>
        <td>DATE</td>
        <td>LOAN</td>
        <td>PRINCIPAL</td>
        <td>INTEREST</td>
        <td>INSTALLMENT</td>
        <td>BALANCE</td>
    </tr>

<?php if (empty($schedules)): ?>
    <tr>
        <td colspan="7" style="padding:10px;color:#666;">No repayment schedule found.</td>
    </tr>
<?php else: ?>
    <?php foreach ($schedules as $due): ?>
        <?php
            $loanAmount  = (float) ($due['loan_amount'] ?? 0);
            $principal   = (float) ($due['principal'] ?? 0);
            $interest    = (float) ($due['interest'] ?? 0);
            $installment = (float) ($due['installment'] ?? 0);
            $balance     = (float) ($due['balance'] ?? 0);
            $paymentDate = $due['payment_date'] ?? null;

            $principalTotal   += $principal;
            $interestTotal    += $interest;
            $installmentTotal += $installment;
            $lastBalance       = $balance;
        ?>
        <tr>
            <td><?= $count++ ?></td>
            <td><?= Yii::$app->formatter->asDate($paymentDate, 'php:d M Y') ?></td>
            <td><?= Yii::$app->formatter->asDecimal($loanAmount, 2) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($principal, 2) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($interest, 2) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($installment, 2) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($balance, 2) ?></td>
        </tr>
    <?php endforeach; ?>

    <tr class="totals">
        <th></th>
        <th>TOTALS</th>
        <th><?= Yii::$app->formatter->asDecimal($lastBalance, 2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($principalTotal, 2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($interestTotal, 2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($installmentTotal, 2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($lastBalance, 2) ?></th>
    </tr>
<?php endif; ?>
</table>

</body>
</html>
