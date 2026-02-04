<?php
use yii\helpers\Html;

/**
 * Expected:
 * @var array $range   ['from' => 'YYYY-MM-DD', 'to' => 'YYYY-MM-DD']
 * @var array $deposits each item:
 *   ['Customer ID'=>..., 'Member ID'=>..., 'Full Name'=>..., 'Deposit Amount'=>...]
 */

$formatter = Yii::$app->formatter;

$dateRange = null;
if (!empty($range['from']) && !empty($range['to'])) {
    $dateRange = $range['from'] . ' - ' . $range['to'];
}

$totalAmount = 0.0;
foreach ($deposits as $row) {
    $totalAmount += (float)($row['Deposit Amount'] ?? 0);
}
?>

<div class="alert alert-info">
    Deposits Summary <?= $dateRange ? 'for: ' . Html::encode($dateRange) : '' ?>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover table-sm mb-0">
        <thead class="thead-light">
        <tr>
            <th style="width:70px;">#</th>
            <th>Customer ID</th>
            <th>Member ID</th>
            <th>Full Name</th>
            <th class="text-end">Deposit Amount</th>
        </tr>
        </thead>

        <tbody>
        <?php if (empty($deposits)): ?>
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    No deposits found for the selected date range.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($deposits as $i => $row): ?>
                <?php $amount = (float)($row['Deposit Amount'] ?? 0); ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= Html::encode($row['Customer ID'] ?? '') ?></td>
                    <td><?= Html::encode($row['Member ID'] ?? '') ?></td>
                    <td><?= Html::encode($row['Full Name'] ?? '') ?></td>
                    <td class="text-end"><?= $formatter->asDecimal($amount, 2) ?></td>
                </tr>
            <?php endforeach; ?>

            <tr class="font-weight-bold bg-light">
                <th colspan="4" class="text-end">TOTAL</th>
                <th class="text-end"><?= $formatter->asDecimal($totalAmount, 2) ?></th>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
