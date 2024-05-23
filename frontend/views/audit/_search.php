<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TblAuditEntrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-audit-entry-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'audit_entry_id') ?>

    <?= $form->field($model, 'audit_entry_timestamp') ?>

    <?= $form->field($model, 'audit_entry_model_name') ?>

    <?= $form->field($model, 'audit_entry_operation') ?>

    <?= $form->field($model, 'audit_entry_field_name') ?>

    <?php // echo $form->field($model, 'audit_entry_old_value') ?>

    <?php // echo $form->field($model, 'audit_entry_new_value') ?>

    <?php // echo $form->field($model, 'audit_entry_user_id') ?>

    <?php // echo $form->field($model, 'audit_entry_ip') ?>

    <?php // echo $form->field($model, 'audit_entry_affected_record_reference') ?>

    <?php // echo $form->field($model, 'audit_entry_affected_record_reference_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
