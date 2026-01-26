<?php

use common\models\Customer;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\dynagrid\DynaGrid;
use kartik\mpdf\Pdf;
use kartik\daterange\DateRangePicker;
use kartik\export\ExportMenu;
/** @var yii\web\View $this */
/** @var frontend\loans_module\models\CustomerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<style> 
    .modal.modal-fluid .modal-dialog {
    width: 70%;
    max-width: 70%;
    margin: 30px auto;
}
</style>
<div class="breadcomb-area bg-white">
		<div class="container bg-white">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
    <?php Pjax::begin(); ?>
    <?php

   
        $gridcolumns=[
            ['class'=>'kartik\grid\SerialColumn'],
            'customerID',
            'full_name',
            'birthDate',
            'gender',
            //'address',
            //'contacts',
            'NIN',
            //'TIN',
            //'status',
            //'isDeleted',
            //'deleted_at',
            [
            'attribute'=>'created_at',
            'label'=>'Reg. Date',
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
           [
          'class' => 'yii\grid\ActionColumn',
          'template' => '{view}',  // the default buttons + your custom button
          'buttons' => [
              'view' => function($url, $model, $key) { 
                  
                  return Html::a('<i class="fa fa-eye "></i>', ['/loans/customer/view','customerID'=>$model->id], ['data-pjax' => '0','class'=>'ml-1 ','title'=>'View Loan']);// render your custom button
                 
              },
              
          ]
           ]
        ];

        //////////dynagrid

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
                'filename' => 'Customers',
                'contentBefore' => '',
                'contentAfter' => '',
                'pdfConfig' => [
                    'mode' => Pdf::MODE_CORE,
                    'destination' => Pdf::DEST_DOWNLOAD,
                    'methods' => [
                        'SetHeader' => [''], 
                        'SetFooter' => [''], 
                    ],
                    'options' => ['title' => 'Customers'],
                ],
            ],
            GridView::EXCEL => [
                'label' => 'Excel',
                'filename' => 'Customers',
                'contentBefore' => '',
                'contentAfter' => '',
            ],
            GridView::CSV => [
                'label' => 'CSV',
                'filename' => 'Customers',
                'contentBefore' => '',
                'contentAfter' => '',
            ],
        ],
        'panel'=>['heading'=>'<h3 class="panel-title">Customers</h3>'],
    ],  
    'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
]);
     ?>

    <?php Pjax::end(); ?>

<!--
    ############## modal
-->
           
</div></div></div></div>

