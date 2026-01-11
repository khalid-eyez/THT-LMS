<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CustomerShareholderForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="customer-shareholder-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4 class="text-primary">Customer Information</h4>
    <hr>

    <?php //echo $form->field($model, 'customerID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthDate')->input('date') ?>

    <?= $form->field($model, 'gender')->dropDownList([
        'male' => 'Male',
        'female' => 'Female',
    ], ['prompt' => 'Select Gender']) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacts')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NIN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TIN')->textInput(['maxlength' => true]) ?>


    <h4 class="mt-4 text-primary">Shareholder Information</h4>
    <hr>

    <?= $form->field($model, 'memberID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initialCapital')->textInput() ?>

    <?php //echo $form->field($model, 'shares')->textInput() ?>


    <div class="form-group mt-3 ">
        <?= Html::submitButton(
            'Register Shareholder',
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

