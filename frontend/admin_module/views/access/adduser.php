<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->params['pageTitle']='Assign Users';
?>

<div class="container-fluid p-5">
    <span class="text-center text-bold "><?=base64_decode(urldecode($_GET['name']))?></span>
    <div class="container p-5">
    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'']); ?>
    <?= $form->field($model, 'users[]')->dropDownList($model->getUsers(),['data-placeholder' =>"--User--",'multiple'=>'multiple','class'=>'user form-control','style'=>'width:100%'])->label(false) ?>
 
    <?= Html::submitButton('<i class="fa fa-plus-circle"></i> Assign', ['class' => 'btn btn-primary btn-sm float-right']) ?>
   

    <?php ActiveForm::end(); ?>

</div></div>
<?php
$script = <<<JS
    $('document').ready(function(){
    $('.access').addClass('active');
    $('.user').select2();
 
  
})
JS;
$this->registerJs($script);
?>
