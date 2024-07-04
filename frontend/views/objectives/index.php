<?php

use common\models\Objectives;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ObjectivesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="objectives-index">

        <?= Html::a('<i class="fa fa-plus-circle"></i> Add Objective', ['/objectives/create'], ['class' => 'btn btn-success float-right mb-2']) ?>


    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'description:ntext',
            [
                'attribute'=>'target',
                'value'=>function($a)
                {
                    return $a->target0->description;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'update'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-edit" data-toggle="tooltip" data-title="Update Item"></i> ', Url::toRoute(['/objectives/update','id'=>$model->objID]));
                    },
                    'view'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-eye" data-toggle="tooltip" data-title="View Item"></i> ', Url::toRoute(['/objectives/view','id'=>$model->objID]));
                    },
                    'delete'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-trash" data-toggle="tooltip" data-title="Delete Item"></i>', ['/objectives/delete', 'id' => $model->objID], [
                            'class' => 'text-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                    ]
             
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
