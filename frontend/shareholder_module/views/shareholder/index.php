<?php

use common\models\Shareholder;
use common\models\CustomerShareholderForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ShareholderSearch $searchModel */
/** @var common\models\CustomerShareholderForm $sCustomerShareholderForm */
/** @var yii\data\ActiveDataProvider $dataProvider */

//$this->title = 'Shareholders';
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="breadcomb-area" style="margin-top:0px!important">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="shareholder-index">

    <h1><?= Html::encode($this->title) ?></h1>

   <div class="d-flex justify-content-end">
    <?= Html::a('+ New Shareholder', ['create'], ['class' => 'btn btn-primary']) ?>
  </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => [
        'class' => 'table table-striped table-hover table-bordered align-middle', 
    ],
    'headerRowOptions' => [
        'class' => 'table-primary text-center', 
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn',
         'header' => 'No',
         'headerOptions' => ['class' => 'text-primary text-center'],
         'contentOptions' => ['class' => 'text-center'],
        ],
        [
    'attribute' => 'customerFullName',
    'label' => 'Full Name',
    'value' => function($model) {
        return $model->customer ? $model->customer->full_name : '(not set)';
        }
      ],

        'memberID',
        [
            'attribute' => 'initialCapital',
            'format' => ['decimal', 2],
            'contentOptions' => ['class' => 'text-end'], 
        ],
        [
            'attribute' => 'shares',
            'contentOptions' => ['class' => 'text-center'],
        ],

        [
            'class' => ActionColumn::class,
            'header' => 'Action',
            'headerOptions' => ['class' => 'text-primary text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function($url, $model, $key) {
                    return Html::a('<i class="fa fa-eye"></i>', $url, [
                        'class' => 'btn btn-sm btn-info me-1',
                        'title' => 'View'
                    ]);
                },
                'update' => function($url, $model, $key) {
                    return Html::a('<i class="fa fa-edit"></i>', $url, [
                        'class' => 'btn btn-sm btn-warning me-1',
                        'title' => 'Update'
                    ]);
                },
                'delete' => function($url, $model, $key) {
                    return Html::a('<i class="fa fa-trash"></i>', $url, [
                        'class' => 'btn btn-sm btn-danger',
                        'title' => 'Delete',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this shareholder?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ],
        ],
    ],
]); ?>




</div></div></div></div></div>
