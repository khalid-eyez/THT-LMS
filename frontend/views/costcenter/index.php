<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CostcenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Costcenters';
$this->params['pageTitle']= "Cost Centers";
?>
<style>
    .grid-view table thead a{
        color:white!important;
       
    }
    </style>
<div class="costcenter-index">

    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Add Cost Center', ['create'], ['class' => 'btn btn-success float-right mb-3']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
            'attribute'=>'authority',
            'value'=>function($a)
            {
                return $a->getAuthority();
            }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php
$script = <<<JS
    $('.costcenter').addClass('active');
    $('.grid-view table thead').addClass('bg-success');
JS;
$this->registerJs($script);
?>