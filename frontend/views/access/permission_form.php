<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
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
<div class="perm-form">

    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/access/add-perm']); ?>
    <?= $form->field($model, 'name')->textInput(['placeholder' =>"Permission Name"])->label(false) ?>
    <?= $form->field($model, 'description')->textarea(['placeholder' =>"Description"])->label(false) ?>
    <?= $form->field($model, 'ruleName')->dropDownList($model->getRules(),['prompt' =>"--Rule--"])->label(false) ?>
    <?= $form->field($model, 'permissions[]')->dropDownList($model->getPermissions(),['data-placeholder' =>"--Child Permission--",'multiple'=>'multiple','class'=>'permis form-control','style'=>'width:100%'])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-plus-circle"></i> Add', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
    $('document').ready(function(){
  $('.permis').select2();
})
JS;
$this->registerJs($script);
?>
