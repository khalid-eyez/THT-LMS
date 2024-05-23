<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TblAuditEntry */

$this->title = $model->audit_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Audit Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tbl-audit-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->audit_entry_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->audit_entry_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'audit_entry_id',
            'audit_entry_timestamp',
            'audit_entry_model_name',
            'audit_entry_operation',
            'audit_entry_field_name',
            'audit_entry_old_value:ntext',
            'audit_entry_new_value',
            'audit_entry_user_id',
            'audit_entry_ip',
            'audit_entry_affected_record_reference:ntext',
            'audit_entry_affected_record_reference_type:ntext',
        ],
    ]) ?>

</div>
