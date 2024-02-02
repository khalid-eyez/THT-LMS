<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
$this->params['pageTitle']="Update Meeting"
?>
<style>
    .select2-container--default .select2-results__option--highlighted[aria-selected] 
    {
        background-color:green;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected]:hover 
    {
        background-color:green;
    }
    </style>

<div class="container text-success   text-center border">
               <div class="row bg-success p-1"></div>
               <div class="row ">
               <div class="col pl-5 pr-5 pb-5">
               <div class="meeting-form p-5 pt-3 ">

<?php $form = ActiveForm::begin(['method'=>'post']); ?>

<?= $form->field($model, 'meetingTitle')->textInput(['placeholder' =>"Meeting title"])->label(false) ?>

<?= $form->field($model, 'description')->textarea(['placeholder' =>"Description"])->label(false) ?>

<?= $form->field($model, 'type')->dropDownList($model->getCallableMeetingNames(),['prompt' =>"--Meeting Type--"])->label(false) ?>
<div class="row">
    <div class="col-sm-4">
<?= $form->field($model, 'date')->textInput(['placeholder'=>'Meeting Date','class'=>"meedate form-control",'onmouseenter'=>"this.type='date'",'onmouseover'=>"this.type='date'"])->label(false) ?>
 </div><div class="col-sm-4">
<?= $form->field($model, 'time')->textInput(['placeholder'=>'Meeting Time','class'=>"meetime form-control",'onmouseenter'=>"this.type='time'",'onmouseover'=>"this.type='time'"])->label(false) ?>
</div>
<div class="col-sm-4">
<?= $form->field($model, 'duration')->input('number',['placeholder'=>'Meeting duration(hours)','class'=>"form-control"])->label(false) ?>
</div>
</div>

<?= $form->field($model, 'venue')->textInput(['placeholder' => "Meeting Venue"])->label(false) ?>
<?= $form->field($model, 'invited[]')->dropDownList($model->getMembers(),['data-placeholder' =>"--Invitees | Representatives | Etc--",'multiple'=>'multiple','class'=>'invited form-control','style'=>'width:100%'])->label(false) ?>
<div class="form-group">
    <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
               </div>
                 
                

            </div>

            <?php
$script = <<<JS
    $('document').ready(function(){
    $('.meetings').addClass("active");
    $('.invited').select2();
   })
JS;
$this->registerJs($script);
?>
