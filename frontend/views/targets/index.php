<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TargetsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="targets-index">


    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Add Target', ['/targets/create'], ['class' => 'btn btn-success float-right mb-3']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'description:ntext',
            [
                'attribute'=>'strategy',
                'value'=>function($a)
                {
                    return $a->strategy0->description;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'update'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-edit" data-toggle="tooltip" data-title="Update Item"></i> ', Url::toRoute(['/targets/update','id'=>$model->targetID]));
                    },
                    'view'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-eye" data-toggle="tooltip" data-title="View Item"></i> ', Url::toRoute(['/targets/view','id'=>$model->targetID]));
                    },
                    'delete'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-trash" data-toggle="tooltip" data-title="Delete Item"></i>', ['/targets/delete', 'id' => $model->targetID], [
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
