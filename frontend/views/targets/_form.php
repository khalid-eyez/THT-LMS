<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Strategies;

/* @var $this yii\web\View */
/* @var $model common\models\Targets */
/* @var $form yii\widgets\ActiveForm */

$strategies=Strategies::find()->all();
$strategies=ArrayHelper::map($strategies,'strID','description');
?>

<div class="targets-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'strategy')->dropDownList($strategies,['prompt'=>'--Choose Strategy--']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
