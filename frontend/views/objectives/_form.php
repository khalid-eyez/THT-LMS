<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Targets;
/* @var $this yii\web\View */
/* @var $model common\models\Objectives */
/* @var $form yii\widgets\ActiveForm */

$targets=Targets::find()->all();
$targets=ArrayHelper::map($targets,'targetID','description');
?>

<div class="objectives-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true,'placeholder'=>'Code'])->label(false) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4,'placeholder'=>'Description'])->label(false) ?>
    <?= $form->field($model, 'target')->dropDownList($targets,['prompt' =>'--Target--'])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
