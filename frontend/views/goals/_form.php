<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Goals */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goals-form">

    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/goals/create']); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true,'placeholder'=>'Code'])->label(false) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4,'placeholder'=>'Description'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
