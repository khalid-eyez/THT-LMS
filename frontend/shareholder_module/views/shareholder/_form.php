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
        <div class="col-md-6">
            <?= $form->field($model, 'full_name')
                ->textInput(['maxlength' => true, 'placeholder' => 'Full Name'])
                ->label(false) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'initialCapital')
                ->textInput(['placeholder' => 'Initial Capital'])
                ->label(false) ?>
        </div>
    </div>

    <div class="row">
        <!-- Birth Date + Address + Gender -->
        <div class="col-md-4">
            <?= $form->field($model, 'birthDate')
                ->input('date', ['placeholder' => 'Birth Date'])
                ->label(false) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'address')
                ->textInput(['maxlength' => true, 'placeholder' => 'Address'])
                ->label(false) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'gender')
                ->dropDownList(
                    ['male' => 'Male', 'female' => 'Female'],
                    ['prompt' => 'Gender']
                )
                ->label(false) ?>
        </div>
    </div>

    <div class="row">
        <!-- Contacts + NIN + TIN -->
        <div class="col-md-4">
            <?= $form->field($model, 'contacts')
                ->textInput(['maxlength' => true, 'placeholder' => 'Contacts'])
                ->label(false) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'NIN')
                ->textInput(['maxlength' => true, 'placeholder' => 'NIN'])
                ->label(false) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'TIN')
                ->textInput(['maxlength' => true, 'placeholder' => 'TIN'])
                ->label(false) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <div class="pull-right">
            <?= Html::submitButton('Register Shareholder', ['class' => 'btn btn-primary']) ?>
        </div>
        <div style="clear:both;"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
