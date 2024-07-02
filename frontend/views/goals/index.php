<?php

use common\models\Goals;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\GoalsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goals';
$this->params['pageTitle']= "Organisation Goals";
?>
<div class="goals-index">
        <?= Html::a('<i class="fa fa-plus-circle"></i> Add New Goal', ['create'], ['class' => 'btn btn-success float-right mb-2','data-toggle'=>'modal','data-target'=>'#glmodal']) ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'goalID',
            'code',
            'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
    <?=$this->render('create',['model'=>new Goals()])?>

</div>
