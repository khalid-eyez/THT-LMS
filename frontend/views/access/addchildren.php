<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->params['pageTitle']='Add Children';
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
<div class="container-fluid p-5">
    <span class="text-center text-bold "><?=base64_decode(urldecode($_GET['name']))?></span>
    <div class="container p-5">
    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'']); ?>
    <?= $form->field($model, 'childroles[]')->dropDownList($model->getRoles(),['data-placeholder' =>"--Child Roles--",'multiple'=>'multiple','class'=>'cr form-control','style'=>'width:100%'])->label(false) ?>
    <?= $form->field($model, 'permissions[]')->dropDownList($model->getPermissions(),['data-placeholder' =>"--Permission--",'multiple'=>'multiple','class'=>'permi form-control','style'=>'width:100%'])->label(false) ?>
 
    <?= Html::submitButton('<i class="fa fa-plus-circle"></i> Add', ['class' => 'btn btn-success btn-sm float-right']) ?>
   

    <?php ActiveForm::end(); ?>

</div></div>
<?php
$script = <<<JS
    $('document').ready(function(){
 
    $('.cr').select2();
    $('.permi').select2();
 
  
})
JS;
$this->registerJs($script);
?>
