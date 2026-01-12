<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Deposit $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="deposit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shareholderID')->textInput() ?>
    
    <?= $form->field($model, 'amount')->textInput() ?>
    
    <?= $form->field($model, 'type')->dropDownList([
        'capital' => 'Capital',
        'monthly' => 'Monthly',
    ]) ?>
   
   <?= $form->field($model, 'deposit_date')->input('date') ?>
   
   <hr>
   
   <?= $form->field($model, 'payment_document')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Record Deposit', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
