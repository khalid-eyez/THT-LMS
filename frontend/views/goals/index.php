<?php

use common\models\Goals;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\GoalsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="goals-index">
        <?= Html::a('<i class="fa fa-plus-circle"></i> Add New Goal', ['create'], ['class' => 'btn btn-success float-right mb-2','data-toggle'=>'modal','data-target'=>'#glmodal']) ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'description:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'update'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-edit" data-toggle="tooltip" data-title="Update Item"></i> ', Url::toRoute(['/goals/update','id'=>$model->goalID]));
                    },
                    'view'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-eye" data-toggle="tooltip" data-title="View Item"></i> ', Url::toRoute(['/goals/view','id'=>$model->goalID]));
                    },
                    'delete'=>function($url,$model,$key)
                    {
                        return Html::a('<i class="fa fa-trash" data-toggle="tooltip" data-title="Delete Item"></i>', ['/goals/delete', 'id' => $model->goalID], [
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
    <?=$this->render('create',['model'=>new Goals()])?>

</div>
