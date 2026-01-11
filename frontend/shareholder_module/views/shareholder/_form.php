<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Shareholder $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shareholder-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'customerID')->textInput() ?>

    <?= $form->field($model, 'memberID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initialCapital')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'shares')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save details', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
