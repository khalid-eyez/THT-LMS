<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\loans_module\models\CustomerLoanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="customer-loan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customerID') ?>

    <?= $form->field($model, 'loan_type_ID') ?>

    <?= $form->field($model, 'loan_amount') ?>

    <?= $form->field($model, 'topup_amount') ?>

    <?php // echo $form->field($model, 'deposit_amount') ?>

    <?php // echo $form->field($model, 'repayment_frequency') ?>

    <?php // echo $form->field($model, 'loan_duration_units') ?>

    <?php // echo $form->field($model, 'duration_extended') ?>

    <?php // echo $form->field($model, 'deposit_account') ?>

    <?php // echo $form->field($model, 'deposit_account_names') ?>

    <?php // echo $form->field($model, 'processing_fee_rate') ?>

    <?php // echo $form->field($model, 'processing_fee') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'interest_rate') ?>

    <?php // echo $form->field($model, 'penalty_rate') ?>

    <?php // echo $form->field($model, 'penalty_grace_days') ?>

    <?php // echo $form->field($model, 'topup_rate') ?>

    <?php // echo $form->field($model, 'approvedby') ?>

    <?php // echo $form->field($model, 'initializedby') ?>

    <?php // echo $form->field($model, 'paidby') ?>

    <?php // echo $form->field($model, 'approved_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'isDeleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'loanID') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
