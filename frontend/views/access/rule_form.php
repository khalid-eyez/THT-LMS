<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="rules-form">

    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/access/add-rule']); ?>
    <?= $form->field($model, 'classname')->textInput(['placeholder' =>"Class Name [ ex: TestRule ]"])->label(false) ?>
    <?= $form->field($model, 'namespace')->textInput(['placeholder' =>"Namespace   [ ex: common\\rules\\ ]"])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-plus-circle"></i> Add', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
    $('document').ready(function(){
 
 
  
})
JS;
$this->registerJs($script);
?>
