<?php
use yii\helpers\Html;

/** @var common\models\Customer $customer */

$shareholder = $customer->shareholder;

/* shareholder aggregates */
$totalDeposits = 0;
$totalDepositInterest = 0;

if ($shareholder) {
    foreach ($shareholder->deposits as $deposit) {
        $totalDeposits += $deposit->amount;
        foreach ($deposit->depositInterests as $interest) {
            $totalDepositInterest += $interest->amount;
        }
    }
}
?>

<div class="sale-statistic-area" style="margin-top:0!important;">
    <div class="container">
        <div class="row">

            <!-- LEFT: CUSTOMER INFO + SHAREHOLDER INFO (conditional) -->
            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12 col-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <!-- Customer Info -->
                        <div class="statistic-right-area notika-shadow mg-tb-30 sm-res-mg-t-0">

                            <div class="text-center mb-3">
                                <div class="rounded-circle bg-light text-primary d-flex
                                            align-items-center justify-content-center mx-auto"
                                     style="width:80px;height:80px;font-size:32px;">
                                    <?= strtoupper(substr($customer->full_name, 0, 1)) ?>
                                </div>
                            </div>

                            <p><strong>Customer ID:</strong> <?= Html::encode($customer->customerID) ?></p>
                            <p><strong>Full Name:</strong> <?= Html::encode($customer->full_name) ?></p>
                            <p><strong>Gender:</strong> <?= Html::encode($customer->gender) ?></p>
                            <p><strong>Birth Date:</strong> <?= Html::encode($customer->birthDate) ?></p>
                            <p><strong>Contacts:</strong> <?= Html::encode($customer->contacts) ?></p>
                            <p><strong>Status:</strong> <?= Html::encode($customer->displayStatus()) ?></p>

                            <hr>

                            <div class="mt-3 text-center">
                                <?= Html::a('Update', ['update', 'id' => $customer->id], [
                                    'class' => 'btn btn-primary btn-sm mr-1'
                                ]) ?>
                                <?= Html::a('Delete', ['delete', 'id' => $customer->id], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Are you sure?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>

                        </div>

                        <!-- Shareholder Info (only if exists) -->
                        <?php if ($shareholder): ?>
                            <div class="statistic-right-area notika-shadow mg-tb-30 sm-res-mg-t-0 mt-3">
                                <h5 class="mb-3 text-center">Shareholder Information</h5>

                                <p><strong>Member ID:</strong> <?= Html::encode($shareholder->memberID) ?></p>
                                <p><strong>Initial Capital:</strong>
                                    <?= Yii::$app->formatter->asDecimal($shareholder->initialCapital) ?>
                                </p>
                                <p><strong>Total Deposits:</strong>
                                    <?= Yii::$app->formatter->asDecimal($totalDeposits) ?>
                                </p>
                                <p><strong>Total Interest:</strong>
                                    <?= Yii::$app->formatter->asDecimal($totalDepositInterest) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- RIGHT: LOANS + FINANCIAL SUMMARY -->
            <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                <div class="sale-statistic-inner notika-shadow mg-tb-30">

                    <!-- Loan History -->
                    <h4 class="mb-3">Loan History</h4>

                    <?php if ($customer->customerLoans): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Loan ID</th>
                                        <th>Amount</th>
                                        <th>Loan Type</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($customer->customerLoans as $loan): ?>
                                    <tr>
                                        <td><?= Html::encode($loan->loanID) ?></td>
                                        <td><?= Yii::$app->formatter->asDecimal($loan->loan_amount) ?></td>
                                        <td><?= Html::encode($loan->loanType->name ?? '-') ?></td>
                                        <td><?= Html::encode($loan->loan_duration_units) ?></td>
                                        <td><?= Html::encode($loan->displayStatus()) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No loans found.</p>
                    <?php endif; ?>

                    <!-- Financial Summary -->
                    <h4 class="mt-4 mb-2">Financial Summary</h4>
                    <p><strong>Total Loans:</strong> <?= count($customer->customerLoans) ?></p>
                    <p><strong>Active Loans:</strong>
                        <?= count(array_filter(
                            $customer->customerLoans,
                            fn($loan) => $loan->isStatusActive()
                        )) ?>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>