<?php

use buttflattery\formwizard\FormWizard;
use frontend\assets\FormWizardAsset;
//FormWizardAsset::register($this);
?>
 <div class="data-table-area" style="margin-top:0px!important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<?=   FormWizard::widget([
     "forceBsVersion"=>true,
    'steps'=>[
       
        [
            'model'=>$model,
            'title'=>'User Info',
            'description'=>'Add you account details',
            'formInfoText'=>'Fill all fields'
        ],
        [
            'model'=> $model,
            //'isSkipable'=>true,
            'title'=>'My Business Profile.',
            'description'=>'Add your business profile details.',
            'formInfoText'=>'Fill all fields'
        ],
    ]
]);

?>
                </div></div></div></div>