<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $loans common\models\CustomerLoan[] */
//print_r($loans); return null;
?>

<?php if (empty($loans)): ?>
    <div class="alert alert-warning mb-0 mt-3" style="margin-top: 20px;">
        No loans found matching your search.
    </div>
<?php return; endif; ?>

<div class="list-group loan-search-results mt-3" style="margin-top: 20px;">
    <?php foreach ($loans as $loan): ?>
        <div class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                
                <!-- LOAN DETAILS -->
                <div class="col-md-9">
                    <div class="font-weight-bold">
                        Loan ID: <?= Html::encode($loan->loanID) ?>
                    </div>

                    <div class="text-muted small">
                        Amount: <?= Yii::$app->formatter->asDecimal($loan->loan_amount,2) ?>
                        &nbsp;|&nbsp;
                        Repayment: <?= Html::encode($loan->repayment_frequency) ?>
                        &nbsp;|&nbsp;
                        Duration: <?= Html::encode($loan->loan_duration_units) ?>
                         &nbsp;|&nbsp;
                        Customer: <?= Html::encode($loan->customer->customerID) ?>
                    </div>
                </div>

                <!-- ACTION ICONS -->
                <div class="col-md-3 text-right">
                    <?= Html::a(
                        '<i class="fa fa-eye"></i>',
                        Url::to(['/loans/loans/loan-view', 'loanID' => $loan->id]),
                        [
                            'class' => 'btn btn-sm btn-outline-primary mr-1',
                            'data-toggle' => 'tooltip',
                            'title' => 'View Loan Schedule',
                        ]
                    ) ?>

                    <?= Html::a(
                        '<i class="fa fa-file-pdf-o"></i>',
                        Url::to(['/loans/loans/download-repayment-statement-report-pdf', 'loanID' => $loan->id]),
                        [
                            'class' => 'btn btn-sm btn-outline-success',
                            'data-toggle' => 'tooltip',
                            'title' => 'Download Repayment Statement PDF',
                        ]
                    ) ?>
                      <?= Html::a(
                        '<i class="fa fa-file-excel-o"></i>',
                        Url::to(['/loans/loans/download-repayment-statement-report-excel', 'loanID' => $loan->id]),
                        [
                            'class' => 'btn btn-sm btn-outline-success',
                            'data-toggle' => 'tooltip',
                            'title' => 'Download Repayment Statement Excel',
                        ]
                    ) ?>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
$this->registerJs(<<<JS
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
JS
);
?>
