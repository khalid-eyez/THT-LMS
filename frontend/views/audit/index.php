<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TblAuditEntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Audit Entries';
$this->params['pageTitle']= $this->title;
?>
<div class="tbl-audit-entry-index">

<a class="btn btn-sm btn-danger float-right mb-2 " href="/audit/delete-all"><i class='fa fa-trash'></i> Clear All</a>

  

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'audit_entry_timestamp',
            'audit_entry_model_name',
            'audit_entry_operation',
            'audit_entry_field_name',
            //'audit_entry_old_value:ntext',
            //'audit_entry_new_value',
            'audit_entry_user_id',
            //'audit_entry_ip',
            //'audit_entry_affected_record_reference:ntext',
            //'audit_entry_affected_record_reference_type:ntext',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php
$script = <<<JS
    $('.audit').addClass('active');
JS;
$this->registerJs($script);
?>