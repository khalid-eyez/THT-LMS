<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Branch;
use common\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\models\Member */

$this->title = 'Update Member: ';
$this->params['pageTitle']="Update Member";
$roles=ArrayHelper::map(AuthItem::find()->where(['name'=>"MEMBER"])->all(),'name','name');
$branches=ArrayHelper::map(Branch::find()->where(['level'=>'BR'])->all(),'branchID','branchName');
?>
<div class="member-update pl-5 pr-5">
<div class="card">
    <div class="card-header bg-success p-2 pl-3"><i class="fa fa-edit"></i> Update Member</div>
    <div class="card-body">
<div class="container-fluid text-sm">
    

    <?php $form = ActiveForm::begin(['method'=>'post']); ?>
    <div class="row"><div class="col-sm-4">
    <?= $form->field($model, 'fname')->textInput(['placeholder'=>'First Name','class'=>'form-control form-control-sm'])->label(false) ?>
    </div><div class="col-sm-4">
    <?= $form->field($model, 'mname')->textInput(['placeholder'=>"Middle Name",'class'=>'form-control form-control-sm'])->label(false) ?>
</div><div class="col-sm-4">
    <?= $form->field($model, 'lname')->textInput(['placeholder'=>'Last Name','class'=>'form-control form-control-sm'])->label(false)  ?>
</div></div>
<div class="row">
<div class="col-sm-6">
    <?= $form->field($model, 'email')->textInput(['placeholder'=>'E-mail','class'=>'form-control form-control-sm'])->label(false)  ?>
</div><div class="col-sm-6">
    <?= $form->field($model, 'phone')->textInput(['placeholder'=>'Mobile Phone Number','class'=>'form-control form-control-sm'])->label(false)  ?>
</div></div>
<div class="row">
<div class="col-sm-4">
    <?= $form->field($model, 'branch')->dropDownList($branches,['prompt'=>'--Branch--','class'=>'form-control form-control-sm']) ->label(false) ?>
</div><div class="col-sm-4">
    <?= $form->field($model, 'gender')->dropDownList(['F'=>'Female','M'=>'Male'],['prompt'=>'--Gender--','class'=>'form-control form-control-sm']) ->label(false) ?>
</div>
<div class="col-sm-4">
    <?= $form->field($model, 'role')->dropDownList($roles,['prompt'=>'--Membership--','class'=>'form-control form-control-sm'])->label(false)  ?>
</div>
</div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    </div>
</div>
<?php
$script = <<<JS
    $('document').ready(function(){
    
    $('.members').addClass("active");
})
JS;
$this->registerJs($script);
?>
