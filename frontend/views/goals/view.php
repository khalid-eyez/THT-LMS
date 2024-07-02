<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Goals */

$this->title = "View Goal";
$this->params['pageTitle']="View Goal";

\yii\web\YiiAsset::register($this);
?>
<div class="goals-view">
    <p>
        <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->goalID], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->goalID], [
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
            'code',
            'description:ntext',
            'createdAt',
            'updatedAt',
        ],
    ]) ?>

</div>
