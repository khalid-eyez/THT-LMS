<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Branch;
use common\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $form yii\widgets\ActiveForm */

//$branches=ArrayHelper::map(Branch::find()->where(['branchID'=>yii::$app->user->identity->getBranch()->branchID])->all(),'branchID','branchName','location');
$branches=ArrayHelper::map(Branch::find()->all(),'branchID','branchName','location');
//$roles=ArrayHelper::map(AuthItem::find()->where(['name'=>"MEMBER"])->all(),'name','name');
$roles=ArrayHelper::map(AuthItem::find()->all(),'name','name');
?>

<div class="container-fluid text-sm">
    

    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/member/create']); ?>
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
<div class="row"><div class="col-sm-12">
    <?= $form->field($model, 'gender')->dropDownList(['F'=>'Female','M'=>'Male'],['prompt'=>'--Gender--','class'=>'form-control form-control-sm']) ->label(false) ?>
</div><div class="col-sm-12">
    <?= $form->field($model, 'branch')->dropDownList($branches,['prompt'=>'--Branch--','class'=>'form-control form-control-sm'])->label(false)  ?>
</div>
<div class="col-sm-12">
    <?= $form->field($model, 'role')->dropDownList($roles,['prompt'=>'--Membership--','class'=>'form-control form-control-sm'])->label(false)  ?>
</div>
</div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-sm col-sm-4 btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
