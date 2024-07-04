<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\StrategiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="strategies-index">

    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Add Strategy', ['/strategies/create'], ['class' => 'btn btn-success float-right mb-3']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'description:ntext',
            [
                'attribute'=>'goal',
                'value'=>function($a)
                {
                    return $a->goal0->description;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'update'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-edit" data-toggle="tooltip" data-title="Update Item"></i> ', Url::toRoute(['/strategies/update','id'=>$model->strID]));
                    },
                    'view'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-eye" data-toggle="tooltip" data-title="View Item"></i> ', Url::toRoute(['/strategies/view','id'=>$model->strID]));
                    },
                    'delete'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-trash" data-toggle="tooltip" data-title="Delete Item"></i>', ['/strategies/delete', 'id' => $model->strID], [
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
