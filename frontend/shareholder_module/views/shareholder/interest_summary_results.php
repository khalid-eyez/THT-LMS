<?php
use yii\helpers\Html;

/**
 * Expected:
 * @var string|null $date_range  // raw value: "YYYY-MM-DD - YYYY-MM-DD" or null
 * @var array $interest_summaries
 */

$formatter = Yii::$app->formatter;

/**
 * Build human-readable range label:
 * "YYYY-MM-DD - YYYY-MM-DD" → "1 Jan 2026 - 4 Feb 2026"
 */
$rangeLabel = '';
if (!empty($date_range) && strpos($date_range, ' - ') !== false) {
    [$from, $to] = array_map('trim', explode(' - ', $date_range));

    try {
        $rangeLabel =
            $formatter->asDate($from, 'd MMM yyyy')
            . ' - ' .
            $formatter->asDate($to, 'd MMM yyyy');
    } catch (\Throwable $e) {
        $rangeLabel = Html::encode($date_range);
    }
}

/** Total */
$totalAmount = 0.0;
foreach ($interest_summaries as $row) {
    $totalAmount += (float)($row['Total Interest'] ?? 0);
}
?>

<div class="alert alert-info">
    Interest Summary <?= $rangeLabel ? 'for: ' . Html::encode($rangeLabel) : 'for: All' ?>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover table-sm mb-0">
        <thead class="thead-light">
        <tr>
            <th style="width:70px;">#</th>
            <th>Customer ID</th>
            <th>Member ID</th>
            <th>Full Name</th>
            <th class="text-end">Total Interest</th>
        </tr>
        </thead>

        <tbody>
        <?php if (empty($interest_summaries)): ?>
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    No interests found for the selected date range.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($interest_summaries as $i => $row): ?>
                <?php $amount = (float)($row['Total Interest'] ?? 0); ?>
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
