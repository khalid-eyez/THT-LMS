<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ShareholderSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shareholder-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customerID') ?>

    <?= $form->field($model, 'memberID') ?>

    <?= $form->field($model, 'initialCapital') ?>

    <?= $form->field($model, 'shares') ?>

    <?php // echo $form->field($model, 'isDeleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
