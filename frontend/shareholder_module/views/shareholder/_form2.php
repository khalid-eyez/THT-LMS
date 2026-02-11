<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CustomerShareholderForm;
use common\models\Shareholder;

/** @var yii\web\View $this */
/** @var common\models\CustomerShareholderForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="customer-shareholder-form">

    <?php $form = ActiveForm::begin(); ?>

    <h5 class="text-primary"><i class="fa fa-edit"></i> Shareholder Update</h5>

    <!-- Row 1: 3 columns -->
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model->customer, 'full_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model->customer, 'birthDate')->input('date') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model->customer, 'gender')->dropDownList([
                'Male' => 'Male',
                'Female' => 'Female',
            ], ['prompt' => 'Select Gender']) ?>
        </div>
    </div>

    <!-- Row 2: 2 columns -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model->customer, 'contacts')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model->customer, 'address')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <!-- Row 3: 3 columns -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model->customer, 'NIN')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model->customer, 'TIN')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <!-- Submit button: right aligned -->
    <div class="form-group mt-3 text-right">
        <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
