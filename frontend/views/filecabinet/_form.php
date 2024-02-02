<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="meeting-form">

    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/filecabinet/new-label']); ?>

    <?= $form->field($model, 'prefix')->textInput(['placeholder' =>"Reference prefix (ex. THTU/HQ/AD.01/ )"])->label(false) ?>

    <?= $form->field($model, 'name')->textInput(['placeholder' =>"Reference name (ex. general affairs)"])->label(false) ?>
   
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
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
