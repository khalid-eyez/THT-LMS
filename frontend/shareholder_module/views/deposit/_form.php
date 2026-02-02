<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ShareholderDepositForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="container-fluid">

    <?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
    
    <?=   $form->field($model, 'amount')->textInput() ?>
    
    <?= $form->field($model, 'type')->dropDownList([
    'capital' => 'Initial Capital',
    'monthly' => 'Monthly Deposit',
    ], [
    'prompt' => '-- Select Deposit Type --',
    ]) ?>

   
   <?=    $form->field($model, 'deposit_date')->input('date') ?>

      <?= $form->field($model, 'payment_document',)->fileInput() ?>

    
        <?= Html::submitButton('<i class="fa fa-save"></i> Record Deposit', ['class' => 'btn btn-primary pull-right','style'=>'margin-bottom:20px']) ?>
  

    <?php  ActiveForm::end(); ?>

</div>
