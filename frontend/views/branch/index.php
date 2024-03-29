<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branches';
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageTitle']="Branches";
?>
<style>
    .grid-view table thead a{
        color:white!important;
       
    }
    </style>
<div class="branch-index text-sm">

        <?= Html::a('<i class="fa fa-plus-circle"></i> Create Branch', ['create'], ['class' => 'btn btn-success float-right mb-2']) ?>
   

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'branchName',
            'branch_short',
            'location',
            'email:email',
            'telphone',
            //'fax',
            //'website',
            //'pobox',
            //'level',

            ['class' => 'yii\grid\ActionColumn'],
      
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php
$script = <<<JS
    $('.branches').addClass('active');
    $('.grid-view table thead').addClass('bg-success');
JS;
$this->registerJs($script);
?>