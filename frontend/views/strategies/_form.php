<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Goals;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Strategies */
/* @var $form yii\widgets\ActiveForm */

$goals=Goals::find()->all();
$goals=ArrayHelper::map($goals,'goalID','description');
?>

<div class="strategies-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'goal')->dropDownList($goals,['prompt'=>'--choose a goal--']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
