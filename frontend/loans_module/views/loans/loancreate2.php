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
            'fieldConfig'=>[
            'birthDate'=>[
            'inputOptions'=>[
            'type'=>'date',
            ],
            ],
             'NIN'=>[
            'labelOptions' => [
            'label' => "National ID",
            ],
            ],
            'TIN'=>[
            'labelOptions' => [
            'label' => "Tax ID",
            ],
            ],
             'address'=>[
             'inputOptions' => [
              'placeholder' => 'Ex: Camp St, Goba RD, Goba, Dar Es Salaam, Tanzania',
               ],
            ],
              'contacts'=>[
             'inputOptions' => [
              'placeholder' => 'Ex: email: tht01@gmail.com, phone: 0744009988',
               ],
            ],
             'gender'=>[
             'options'=>[
                'itemsList'=>["Male"=>"Male","Female"=>"Female","Other"=>"Other"],
                'type'=>'dropdown',
                'prompt' => '--Select Gender--',
             ],
             
            // 'inputOptions'=>[
            // 'type'=>'sele'
            // ]
            ]
            ],
            'model'=>$customerinfo,
            'title'=>'Customer Information',
            'description'=>'Add customer details',
            'formInfoText'=>''
            //'formInfoText'=>$this->render('loans'),
        ],
         [
            'model'=> $loaninfo,
             'fieldConfig'=>[
                 'loan_type_ID'=>[
                        'options'=>[
                        'itemsList'=>$loaninfo->loantypes(),
                        'type'=>'dropdown',
                        'prompt' => '--Loan Type--',
                        ],
                        'labelOptions'=>[
                            'label'=>'Loan Type'
                        ]
                        ],
                         'repayment_frequency'=>[
                        'options'=>[
                        'itemsList'=>$loaninfo->repayment_frequencies(),
                        'type'=>'dropdown',
                        'prompt' => '--Choose Frequency--',
                        ],
                        'labelOptions'=>[
                            'label'=>'Repayment Frequency'
                        ]
                        ],
                        'loan_duration_units'=>[
                        'options'=>[
                        'placeholder' => 'X (frequency)',
                        ],
                        'labelOptions'=>[
                            'label'=>'Loan Duration'
                        ]
                        ]
             ],
            'title'=>'Loan Information',
            'description'=>'Add customer loan information',
            'formInfoText'=>''
        ],
         [
            'model'=> $attachments,
            //'isSkipable'=>true,
            'title'=>'Loan Attachments',
            'description'=>'Add Loan application attachments',
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