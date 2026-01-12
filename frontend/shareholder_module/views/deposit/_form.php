<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Deposit $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="deposit-form">

    <?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
    
    <?=   $form->field($model, 'amount')->textInput() ?>
    
    <?=   $form->field($model, 'type')->dropDownList([
        'capital' => 'Capital',
        'monthly' => 'Monthly',
    ]) ?>
   
   <?=    $form->field($model, 'deposit_date')->input('date') ?>
      <hr>

      <?= $form->field($model, 'payment_document')->fileInput() ?>

      <p class="text-danger">Allowed Extensions => pdf, jpg, jpeg and png</p>

    <div   class="form-group">
        <?= Html::submitButton('Record Deposit', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php  ActiveForm::end(); ?>

</div>
