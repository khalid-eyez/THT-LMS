<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CustomerLoan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="customer-loan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'customerID')->textInput() ?>

    <?= $form->field($model, 'loan_type_ID')->textInput() ?>

    <?= $form->field($model, 'loan_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'topup_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'repayment_frequency')->dropDownList([ 'daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'semi-annually' => 'Semi-annually', 'annually' => 'Annually', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'loan_duration_units')->textInput() ?>

    <?= $form->field($model, 'duration_extended')->textInput() ?>

    <?= $form->field($model, 'deposit_account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_account_names')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'processing_fee_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'processing_fee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'new' => 'New', 'approved' => 'Approved', 'active' => 'Active', 'finished' => 'Finished', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'interest_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'penalty_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'penalty_grace_days')->textInput() ?>

    <?= $form->field($model, 'topup_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approvedby')->textInput() ?>

    <?= $form->field($model, 'initializedby')->textInput() ?>

    <?= $form->field($model, 'paidby')->textInput() ?>

    <?= $form->field($model, 'approved_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'isDeleted')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <?= $form->field($model, 'loanID')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
