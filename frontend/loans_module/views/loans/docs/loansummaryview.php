<?php 
use yii\helpers\Html;
use yii\helpers\Url;
?>
 <div class="wizard-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="wizard-wrap-int">
<p class="text-center mb-3" style="font-size:20px"><b>Loan Summary</b><a href="<?=Url::to(["/loans/loans/download-summary","loanID"=>$loan->id])?>" class="pull-right"><i class="fa fa-file-pdf-o"></i> Download</a></p>

<div class="row">

    <!-- Loan Summary -->
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <strong>Loan Information</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <th class="w-25">Loan ID</th>
                            <td><?= Html::encode($loan->loanID) ?></td>
                        </tr>

                        <tr>
                            <th>Loan Amount</th>
                            <td>
                                <?= Yii::$app->formatter->asDecimal($loan->loan_amount) ?>
                            </td>
                        </tr>

                        <?php if ($loan->topup_amount > 0): ?>
                        <tr>
                            <th>Top-Up Amount</th>
                            <td>
                                <?= Yii::$app->formatter->asDecimal($loan->topup_amount) ?>
                            </td>
                        </tr>
                        <?php endif; ?>

    

                        <tr>
                            <th>Repayment Frequency</th>
                            <td><?= Html::encode(ucwords(strtolower($loan->repayment_frequency))) ?></td>
                        </tr>

                        <tr>
                            <th>Loan Duration</th>
                            <td>
                                <?= Html::encode($loan->loan_duration_units) ?>
                                <?= $loan->loan_duration_units == 1 ? 'Period' : 'Periods' ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Interest Rate</th>
                            <td><?= Yii::$app->formatter->asPercent($loan->interest_rate / 100, 2) ?></td>
                        </tr>

                        <tr>
                            <th>Penalty Rate</th>
                            <td><?= Yii::$app->formatter->asPercent($loan->penalty_rate / 100, 2) ?></td>
                        </tr>
                        <tr>
                        <th>Penalty Grace Days</th>
                        <td><?= Html::encode($loan->penalty_grace_days) ?> Days</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-<?= $loan->status === 'active' ? 'success' : 'secondary' ?>">
                                    <?= Html::encode(ucfirst($loan->status)) ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Approved At</th>
                            <td>
                                <?= $loan->approved_at
                                    ? Yii::$app->formatter->asDatetime($loan->approved_at)
                                    : '<span class="text-muted">Not Approved</span>' ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <strong class="ml-2">Customer Information</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <th class="w-25">Customer ID</th>
                            <td><?= Html::encode($loan->customer->customerID) ?></td>
                        </tr>

                        <tr>
                            <th>Full Name</th>
                            <td><?= Html::encode($loan->customer->full_name) ?></td>
                        </tr>

                        <tr>
                            <th>Birth Date</th>
                            <td>
                                <?= Yii::$app->formatter->asDate(
                                    $loan->customer->birthDate,
                                    'php:d M Y'
                                ) ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Gender</th>
                            <td><?= Html::encode(ucfirst($loan->customer->gender)) ?></td>
                        </tr>

                        <tr>
                            <th>Contacts</th>
                            <td><?= Html::encode($loan->customer->contacts) ?></td>
                        </tr>

                        <tr>
                            <th>Address</th>
                            <td><?= Html::encode($loan->customer->address) ?></td>
                        </tr>

                        <tr>
                            <th>NIN</th>
                            <td><?= Html::encode($loan->customer->NIN) ?></td>
                        </tr>

                        <?php if (!empty($loan->customer->TIN)): ?>
                        <tr>
                            <th>TIN</th>
                            <td><?= Html::encode($loan->customer->TIN) ?></td>
                        </tr>
                        <?php endif; ?>

                  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="bg-primary text-white">
<strong>Repayment Schedule</strong>
</div>
<table class="table table-striped">
    <tr><th>#</th><th>Repayment Date</th><th class="">Loan Amount</th><th>Principal</th><th>Interest</th><th>Installment</th><th>Loan Balance</th></tr>
    <?php
      $count=1;
      foreach($loan->repaymentSchedules as $due)
        {
    ?>
     <tr>
        <td><?=$count++ ?></td>
        <td><?=Yii::$app->formatter->asDatetime($due->repayment_date,'php:d M Y') ?></td>
        <td><?=Yii::$app->formatter->asDecimal($due->loan_amount,2)?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->principle_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->interest_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->installment_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->loan_balance,2) ?></td></tr>

    <?php
        }
    ?>
</table>
</div></div></div></div></div>
