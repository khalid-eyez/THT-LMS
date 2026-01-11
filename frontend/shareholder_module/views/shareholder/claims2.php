<?php

use buttflattery\formwizard\FormWizard;
use frontend\assets\FormWizardAsset;
//FormWizardAsset::register($this);
$this->title="New Loan";
?>
<style>
    .step-content{
        width:65%;

        margin: auto !important;
    }
    label{
        color:gray
    }

</style>
 <div class="data-table-area" style="margin-top:0px!important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
<?=   FormWizard::widget([
     "forceBsVersion"=>true,
     'enablePreview'=>true,
        //'enableAjaxValidation' => true,
      'formOptions' => [
       'id' => 'loanform',
       'options' => ['enctype' => 'multipart/form-data'],
//        'enableClientValidation' => true, // disable client validation for multi-files
//        'enableAjaxValidation' => true,
    

     ],
    'steps'=>[
     
         [
            'model'=> $model,
            //'isSkipable'=>true,
            'title'=>'Claims Progress',
            'description'=>'Search claims',
            'fieldConfig'=>[
                'files'=>[
                    'options'=>[
                       'type'=>'file'
                    ],
             'inputOptions' => [
                        'type' => 'file',
                        'multiple' => true,
                    ],
                    'multifield' => true,
                     'labelOptions' => [
                        'label' => false,
                    ],
                    'hint' => 'Select up to 10 files (jpg, png, pdf), max 10 MB each.',
                ]
            ]
            //'formInfoText'=>'Fill all fields'
        ],
     
    ]
]);

?>
                </div></div></div></div>
               <?php $this->registerJs("
                    
                    $('document').ready(function(){
                        //$('.loans').addClass('active');
                        // $('.nav-tabs > li).each(function(){
                        //  $(this).removeClass('active')
                        // })
                        
                       //$('input').wrap('<div class='col-lg-6'></div>'); 
                    })
                    "
                    );
                ?>