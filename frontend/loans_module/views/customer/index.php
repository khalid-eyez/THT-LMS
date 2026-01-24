<?php

use common\models\CustomerLoan;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dynagrid\DynaGrid;
use kartik\mpdf\Pdf;
use kartik\daterange\DateRangePicker;



?>
<style> 
    table , .summary{
         background-color: white!important;

    }
    .kv-grid-table th {
    color: #058aba !important;
    }
</style>
<div class="breadcomb-area bg-white">
		<div class="container bg-white">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">

    <?php Pjax::begin(); ?>
    <?php 
    // echo $this->render('_search', ['model' => $searchModel]); 
    

    $gridcolumns= [
            ['class'=>'kartik\grid\SerialColumn'],
            'loanID',
              [
                'attribute' => 'loanTypeName',
                'label' => 'Loan Type',
                'value' => function ($model) {
                return $model->loanType->type ?? '';
                },
              ],
            'loan_amount',
            //'topup_amount',
            //'deposit_amount',
            [
              'attribute'=>'repayment_frequency',
              'label'=>'Repayment',
             
            ],
            [
              'attribute'=>'loan_duration_units',
              'label'=>'Duration',
              
            ],
            //'duration_extended',
            //'deposit_account',
            //'deposit_account_names',
            //'processing_fee_rate',
            //'processing_fee',
            'status',
            //'interest_rate',
            //'penalty_rate',
            //'penalty_grace_days',
            //'topup_rate',
            //'approvedby',
            //'initializedby',
            //'paidby',
            //'approved_at',
            //'created_at',
            [
            'attribute'=>'created_at',
            'label'=>'Loan Date',
            'value' => function ($model) {
                return Yii::$app->formatter->asDate($model->created_at, 'php:d M Y');
                },
            //'filterType'=>GridView::FILTER_DATE,
            'filter'=> DateRangePicker::widget([
        'model' => $searchModel,
        'attribute' => 'date_range',
        'convertFormat' => true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d',
                'separator' => ' - ',
            ],
            'opens' => 'left'
        ],
        'options' => [
            'class' => 'form-control',
            'id'=>'loan_range',
            'placeholder' => 'Select date range',
        ],
    ]),
            'format'=>'raw',
            //'width'=>'170px',
            'filterWidgetOptions'=>[
            //'pluginOptions'=>['format'=>'yyyy-mm-dd']
            ],
            ],
            //'updated_at',
            //'isDeleted',
            //'deleted_at',
            //'loanID',
           [
          'class' => 'yii\grid\ActionColumn',
          'template' => '{view}',  // the default buttons + your custom button
          'buttons' => [
              'view' => function($url, $model, $key) { 
                  
                  return Html::a('<i class="fa fa-eye"></i>', ['/loans/loan-view','loanID'=>$model->id], ['data-pjax' => '0','class'=>'ml-1','data-toggle'=>'tooltip','data-title'=>'View Loan']);// render your custom button
                 
              },
              
          ]
           ]
        ]; 
    ?>
<?php
    echo DynaGrid::widget([
    'columns'=>$gridcolumns,
    'storage'=>DynaGrid::TYPE_COOKIE,
    'theme'=>'panel-info',
    'gridOptions'=>[
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'pjax'=> true,
          'export' => [
            'fontAwesome' => true,
            'showConfirmAlert' => false, // no confirmation
        ],
          'exportConfig' => [
            GridView::PDF => [
                'label' => 'PDF',
                'filename' => 'Customer_Loans',
                'contentBefore' => '',
                'contentAfter' => '',
                'pdfConfig' => [
                    'mode' => Pdf::MODE_CORE,
                    'destination' => Pdf::DEST_DOWNLOAD,
                    'methods' => [
                        'SetHeader' => [''], 
                        'SetFooter' => [''], 
                    ],
                    'options' => ['title' => 'Customer Loans'],
                ],
            ],
            GridView::EXCEL => [
                'label' => 'Excel',
                'filename' => 'Customer_Loans',
                'contentBefore' => '',
                'contentAfter' => '',
            ],
            GridView::CSV => [
                'label' => 'CSV',
                'filename' => 'Customer_Loans',
                'contentBefore' => '',
                'contentAfter' => '',
            ],
        ],
        'panel'=>['heading'=>'<h3 class="panel-title">Loans</h3>'],
    ],  
    'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
]);
?>
    <?php Pjax::end(); ?>

</div></div></div></div>
<?php
$this->registerJs(<<<JS
$(document).ready(function(){


});
JS);
?>

