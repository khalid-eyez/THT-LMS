<?php
use buttflattery\formwizard\FormWizard;
use frontend\assets\FormWizardAsset;
?>

<style>
    .step-content{
        width:65%;

        margin: auto !important;
    }
    label{
        color:gray
    }

[class*="step"] h4,
[class*="step"] span {
    display: none !important;
}
</style>

 <div class="data-table-area" style="margin-top:0px!important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
<?=   FormWizard::widget([
       "forceBsVersion"=>true,
       'enablePreview'=>true,
       'formOptions' => [
       'id' => 'loanform',
       'options' => ['enctype' => 'multipart/form-data'],

     ],
    'steps'=>[
        [
          'model' => $model,
            //'isSkipable'=>true,
            'title'=>'Shareholder Claims',
            'description'=>'Add claims details',
        ],
     
    ]
]);

?>
          </div>
       </div>
    </div>
</div>