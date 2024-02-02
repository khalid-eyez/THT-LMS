<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Branch;
use common\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $form yii\widgets\ActiveForm */
$this->params['pageTitle']="Update Document";
?>

<div class="container-fluid text-sm">
    
 <div class="container">
    <?php $form = ActiveForm::begin(['method'=>'post']); ?>
    <div class="row">
<div class="col-sm-12">
    <?= $form->field($model, 'docTitle')->textInput(['placeholder'=>'Document Title','class'=>'form-control form-control-sm'])->label(false)  ?>
</div><div class="col-sm-12">
    <?= $form->field($model, 'docDescription')->textarea(['placeholder'=>'Document Description','class'=>'form-control form-control-sm'])->label(false)  ?>
</div></div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-sm col-sm-4 btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<?php
$script = <<<JS
    $('document').ready(function(){
    
    $('.repository').addClass("active");
})
JS;
$this->registerJs($script);
?>