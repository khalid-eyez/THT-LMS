<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Branch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-form container">

    <?php $form = ActiveForm::begin(['method'=>'POST','action'=>'/branch/create']); ?>

    <?= $form->field($model, 'branchName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_short')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->input('email',['maxlength' => true]) ?>

    <?= $form->field($model, 'telphone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pobox')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
