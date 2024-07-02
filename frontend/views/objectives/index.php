<?php

use common\models\Objectives;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ObjectivesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Objectives';
$this->params['pageTitle']='Organisation Objectives'
?>
<div class="objectives-index">

        <?= Html::a('<i class="fa fa-plus-circle"></i> Add New Objective', ['create'], ['class' => 'btn btn-success float-right mb-2','data-toggle'=>'modal','data-target'=>'#objmodal']) ?>


    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'description:ntext',
            'createdAt',
            'updatedAt',
            'target',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
<?=$this->render('create',['model'=>new Objectives])?>
</div>
