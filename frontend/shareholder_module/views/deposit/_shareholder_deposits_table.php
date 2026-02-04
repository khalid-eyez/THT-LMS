<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $shareholderID */

$rows = $dataProvider->getModels();
$formatter = Yii::$app->formatter;

// Calculate totals
$totalAmount = 0.0;
foreach ($rows as $deposit) {
    $totalAmount += (float) $deposit->amount;
}

/**
 * Extract FROM / TO from date range if available
 * Expected format: "YYYY-MM-DD - YYYY-MM-DD"
 */
$dateRange = Yii::$app->request->post('DepositSearch')['deposit_date']
    ?? Yii::$app->request->get('DepositSearch')['deposit_date']
    ?? null;

$fromDate = $toDate = null;

if ($dateRange && strpos($dateRange, ' - ') !== false) {
    [$fromDate, $toDate] = array_map('trim', explode(' - ', $dateRange));
}
?>

<?php if ($fromDate && $toDate): ?>
    <div class="mb-2">
        <strong>Period:</strong>
        <?= Html::encode($formatter->asDate($fromDate, 'dd MMM yyyy')) ?>
        &nbsp;→&nbsp;
        <?= Html::encode($formatter->asDate($toDate, 'dd MMM yyyy')) ?>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover table-sm mb-0">
        <thead class="thead-light">
            <tr>
                <th style="width:70px;">#</th>
                <th>Deposit Date</th>
                <th>Type</th>
                <th class="text-end">Amount</th>
                <th class="text-end">Interest %</th>
                <th style="width:170px;">Record Date</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
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
                    <td><a href="<?= Url::to(['/shareholder/deposit/delete-deposit', 'depositID' => $deposit->depositID]) ?>"
                    data-confirm="Are you sure you want to delete this deposit?"
                    data-method="post"
                    data-toggle="tooltip"
                    data-title="Delete deposit">
                    <i class="fa fa-trash"></i>
                    </a></td>
                </tr>
            <?php endforeach; ?>

            <!-- TOTAL ROW -->
            <tr class="font-weight-bold bg-light">
                <th colspan="3" class="text-end">TOTAL</th>
                <th class="text-end">
                    <?= $formatter->asDecimal($totalAmount, 2) ?>
                </th>
                <th colspan="2"></th>
            </tr>

        <?php endif; ?>
        </tbody>
    </table>
</div>
