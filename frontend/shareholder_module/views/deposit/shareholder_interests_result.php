<?php
/** @var yii\web\View $this */
/** @var common\models\DepositInterest[] $interests */
/** @var string|null $date_range */

use yii\helpers\Html;

$total = 0.0;

/**
 * Format helper: Y-m-d / Y-m-d H:i:s → "1 Jan 2026"
 */
$fmt = function ($date) {
    if (empty($date)) {
        return '-';
    }
    try {
        return Yii::$app->formatter->asDate($date, 'd MMM yyyy');
    } catch (\Throwable $e) {
        return Html::encode($date);
    }
};

/**
 * Decide range label only
 */
if (empty($date_range)) {
    $rangeLabel = 'All';
} else {
    $parts = explode(' - ', $date_range);
    if (count($parts) === 2) {
        $rangeLabel = $fmt(trim($parts[0])) . ' – ' . $fmt(trim($parts[1]));
    } else {
        $rangeLabel = Html::encode($date_range);
    }
}
?>

<div class="deposit-interests-paid">

    <div class="row">
        <div class="col-md-12">
            <h6 class="pull-right">
                <?= Html::encode($rangeLabel) ?>
            </h6>
        </div>
    </div>

    <div class="table-responsive" style="margin-top:15px;">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Claim Months</th>
                    <th class="text-right">Interest Amount</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($interests)): ?>
                <?php foreach ($interests as $interest): ?>
                    <?php $total += (float) $interest->interest_amount; ?>
                    <tr>
                        <td><?= Html::encode($fmt($interest->payment_date)) ?></td>
                        <td><?= Html::encode($interest->claim_months) ?></td>
                        <td class="text-right"><?= number_format((float) $interest->interest_amount, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">No paid interests found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">Total</th>
                    <th class="text-right"><?= number_format((float) $total, 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
