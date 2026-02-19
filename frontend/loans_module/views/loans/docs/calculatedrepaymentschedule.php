<?php
/**
 * Repayment schedule table + meta
 *
 * @var array $repaymentschedules
 */

use yii\helpers\Html;

$schedules = $repaymentschedules['schedules'] ?? [];
$meta      = $repaymentschedules['meta'] ?? [];

$principalTotal   = 0;
$interestTotal    = 0;
$installmentTotal = 0;
$lastBalance      = 0;
$startLoanAmount  = 0;

if (!empty($schedules)) {
    $startLoanAmount = (float) ($schedules[0]['loan_amount'] ?? 0);
}
?>

<style>
    .repay-wrap { border-radius: 12px; overflow: hidden; }

    /* Meta layout */
    .repay-meta {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .repay-meta-item {
        width: 50%;
        padding: 4px 0;
    }

    .repay-meta-item.right {
        text-align: right;
    }

    .repay-meta-item strong {
        font-weight: 600;
    }

    .repay-table thead th { white-space: nowrap; }
    .repay-table td, .repay-table th { vertical-align: middle; }
    .repay-table tfoot th { background: #f8f9fa; }
</style>

<div class="container-fluid mt-2 repay-wrap" style="margin-top: 15px;">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <!-- META -->
            <?php if (!empty($meta) && is_array($meta)): ?>
                <div class="p-3">
                    <div class="repay-meta">
                        <?php
                        $i = 0;
                        foreach ($meta as $label => $value):
                            $isRight = ($i % 2 === 1); // every second item goes right
                        ?>
                            <div class="repay-meta-item <?= $isRight ? 'right' : '' ?>">
                                <strong><?= Html::encode($label) ?>:</strong>
                                <?= Html::encode(ucfirst($value)) ?>
                            </div>
                        <?php
                            $i++;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
            <!-- TABLE -->
            <table class="table table-striped table-hover mb-0 repay-table">
                <thead class="table-light">
                    <tr class="heading">
                        <th style="width:60px">#</th>
                        <th>Repayment Date</th>
                        <th>Loan Amount</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Installment</th>
                        <th>Loan Balance</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($schedules)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No repayment schedule found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $count = 1; ?>
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
                            <td><?= Yii::$app->formatter->asDatetime($paymentDate, 'php:d M Y') ?></td>
                            <td><?= Yii::$app->formatter->asDecimal($loanAmount, 2) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal($principal, 2) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal($interest, 2) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal($installment, 2) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal($balance, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>

                <?php if (!empty($schedules)): ?>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>TOTALS</th>
                        <th><?= Yii::$app->formatter->asDecimal($lastBalance, 2) ?></th>
                        <th><?= Yii::$app->formatter->asDecimal($principalTotal, 2) ?></th>
                        <th><?= Yii::$app->formatter->asDecimal($interestTotal, 2) ?></th>
                        <th><?= Yii::$app->formatter->asDecimal($installmentTotal, 2) ?></th>
                        <th><?= Yii::$app->formatter->asDecimal($lastBalance, 2) ?></th>
                    </tr>
                </tfoot>
                <?php endif; ?>

            </table>

        </div>
    </div>
</div>
</div>
