<?php
use common\models\Member;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Branch;
use common\models\AuthItem;
/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $form yii\widgets\ActiveForm */

$branches=ArrayHelper::map($branch,'branchID','branchName','location');
$roles=['CHAIRPERSON BR'=>'BRANCH CHAIRPERSON'];
?>
<div class="container">
<div class="card shadow-sm">
     <div class="card-header bg-success pl-4 p-1"><div class="card-title ml-1"><i class='fa fa-plus-circle'></i> Register Branch Chairperson</div></div>
      <div class="card-body pl-4 pr-4">
         <?php $model=new Member();?>
         <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/member/create','enableClientValidation' => true,
                                  'validateOnSubmit' => true,
                                  'options' => ['data-pjax' => true ]]); ?>
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
</div>
</div>