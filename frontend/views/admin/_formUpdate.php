<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $form yii\widgets\ActiveForm */
$roles=yii::$app->authManager->getRoles();
$roles=ArrayHelper::map($roles,'name','name');
?>

<div class="container-fluid text-sm">
    

    <?php $form = ActiveForm::begin(['method'=>'post']); ?>

<div class="row">
<div class="col-sm-12">
    <?= $form->field($model, 'username')->textInput(['placeholder'=>'E-mail','class'=>'form-control form-control-sm'])->label(false)  ?>
</div></div>
<div class="row">
<div class="col-sm-12">
    <?= $form->field($model, 'role')->dropDownList($roles,['class'=>'form-control updpriv','multiple'=>'multiple','style'=>'width:100%'])->label("Roles")  ?>
</div>
</div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-sm btn-primary float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
