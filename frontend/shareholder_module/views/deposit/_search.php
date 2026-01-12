<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DepositSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="deposit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'depositID') ?>

    <?= $form->field($model, 'shareholderID') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'interest_rate') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'deposit_date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'isDeleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
