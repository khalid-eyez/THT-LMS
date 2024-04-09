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
<div class="container pl-5 pr-5 p-2">
<div class="card text-sm shadow">
    <div class="card-header p-1 pl-3 bg-success"><i class="fa fa-edit"></i> Update Document</div>
 <div class="card-body">
    <?php $form = ActiveForm::begin(['method'=>'post']); ?>
    <div class="row">
<div class="col-sm-12">
    <?= $form->field($model, 'docTitle')->textInput(['placeholder'=>'Document Title','class'=>'form-control form-control-sm'])->label(false)  ?>
</div><div class="col-sm-12">
    <?= $form->field($model, 'docDescription')->textarea(['placeholder'=>'Document Description','class'=>'form-control form-control-sm'])->label(false)  ?>
</div></div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save Changes', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div></div>
<?php
$script = <<<JS
    $('document').ready(function(){
    
    $('.repository').addClass("active");
})
JS;
$this->registerJs($script);
?>