<?php

use common\models\Shareholder;
use common\models\CustomerShareholderForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
//from deposit
//
use common\models\Deposit;
use common\models\DepositSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


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
            'template' => '{update} {view} {delete}', 
            'buttons' => [

                  'update' => function($url, $model, $key) {
                    return Html::a('<i class="fa fa-coins"></i>', 
                    Url::to(['/shareholder/deposit/create','shareholder_id' => $model->id]),
                    [
                        'class' => 'btn btn-sm btn-success me-1',
                        'title' => 'Deposit'
                    ]);
                },
                'view' => function($url, $model, $key) {
                    return Html::a('<i class="fa fa-book-open"></i>', 
                     Url::to(['/shareholder/deposit/create','shareholder_id' => $model->id]),
                    [
                        'class' => 'btn btn-sm btn-warning me-1',
                        'title' => 'View History'
                    ]);
                },
              
                'delete' => function($url, $model, $key) {
                    return Html::a('<i class="fa fa-download"></i>',
                    Url::to(['/shareholder/deposit/create','shareholder_id' => $model->id]),
                     [
                        'class' => 'btn btn-sm btn-primary',
                        'title' => 'Download History',
                    ]);
                },
            ],
        ],
    ],
]); ?>

</div></div></div></div></div>
