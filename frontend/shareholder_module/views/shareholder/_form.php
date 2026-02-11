<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CustomerShareholderForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="customer-shareholder-form">

    <?php $form = ActiveForm::begin([
         'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    ]); ?>

    <div class="row">
        <!-- Full Name + Initial Capital -->
        <div class="col-md-12">
            <?= $form->field($model, 'full_name')
                ->textInput(['maxlength' => true, 'placeholder' => 'Full Name'])
                ->label(true) ?>
        </div>
    </div>

    <div class="row">
        <!-- Birth Date + Address + Gender -->
        <div class="col-md-4">
            <?= $form->field($model, 'birthDate')
                ->input('date', ['placeholder' => 'Birth Date'])
                ->label(true) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'address')
                ->textInput(['maxlength' => true, 'placeholder' => 'Address'])
                ->label(true) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'gender')
                ->dropDownList(
                    ['Male' => 'Male', 'Female' => 'Female'],
                    ['prompt' => 'Gender']
                )
                ->label(true) ?>
        </div>
    </div>

    <div class="row">
        <!-- Contacts + NIN + TIN -->
        <div class="col-md-4">
            <?= $form->field($model, 'contacts')
                ->textInput(['maxlength' => true, 'placeholder' => 'Contacts'])
                ->label(true) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'NIN')
                ->textInput(['maxlength' => true, 'placeholder' => 'NIN'])
                ->label(true) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'TIN')
                ->textInput(['maxlength' => true, 'placeholder' => 'TIN'])
                ->label(true) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <div class="pull-right">
            <?= Html::submitButton('<i class="fa fa-save"></i> Register Shareholder', ['class' => 'btn btn-primary']) ?>
        </div>
        <div style="clear:both;"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
