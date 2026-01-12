<?php

use common\models\CustomerLoan;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;



?>
<style> 
    table , .summary{
         background-color: white!important;
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
            ['class' => 'yii\grid\SerialColumn'],
            'loanID',
            //'loan_type_ID',
            'loan_amount',
            //'topup_amount',
            //'deposit_amount',
            'repayment_frequency',
            'loan_duration_units',
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
            'approvedby',
            //'initializedby',
            //'paidby',
            //'approved_at',
            //'created_at',
            //'updated_at',
            //'isDeleted',
            //'deleted_at',
            //'loanID',
           [
          'class' => 'yii\grid\ActionColumn',
          'template' => '{view}{update}{reset}{block}{delete_stud}',  // the default buttons + your custom button
          'buttons' => [
              'update' => function($url, $model, $key) { 
                  
                  return Html::a('<i class="fas fa-edit"></i>', ['/loans/loan-view','loanID'=>$model->id], ['data-pjax' => '0','class'=>'ml-1','data-toggle'=>'tooltip','data-title'=>'Update User']);// render your custom button
                 
              },
          ]
           ]
        ]; 
    ?>
      <?= ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridcolumns,
    'pjax' => false,
    'showConfirmAlert'=>false,
    'container'=>['class'=>'btn-group float-right mb-2', 'role'=>'group'],
    'dropdownOptions'=>[
      'icon'=>"<i class='fas fa-file-export'></i>",
      'label'=>"Export List",
      'class' => 'btn btn-outline-secondary btn-default'
    ],
    'columnSelectorOptions'=>[
      'icon'=>"<i class='fa fa-list'></i>",
      'label'=>'Select Columns'
    ],
    'timeout'=>240,
    'fontAwesome'=>true,
    'exportConfig'=>[
      'Html'=>false,
      'Txt'=>false,
      'Xls'=>false,
      'Xlsx'=>[
        'label' =>'Excel',
        'icon'=>'fa fa-file-excel-o ml-2'
      ],
      'Pdf'=>[
        'icon'=>'fa fa-file-pdf-o ml-2'
      ],
      'Csv'=>[
        'icon'=>'fa fa-file ml-2'
      ]
      
      ],
      'filename'=>'Loans'
])."\n"; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' =>$gridcolumns 
    ]); ?>
    <?php Pjax::end(); ?>

</div></div></div></div>
