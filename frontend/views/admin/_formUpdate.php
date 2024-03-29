<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $form yii\widgets\ActiveForm */
$updating=$user;
$roles=ArrayHelper::map(AuthItem::find()->all(),'name','name');
$promoting_roles=array_filter($roles,function($value)use($updating){

    $not_allowed_roles=[];

    if(!$updating->isMember())
    {
        $not_allowed_roles=[
            'ADMIN',
            'CHAIRPERSON BR',
            'CHAIRPERSON HQ',
            'COORDINATOR BR',
            'DEPUTY COORDINATOR BR',
            'DEPUTY WOMEN\'S COORDINATOR BR',
            'GENERAL SECRETARY BR',
            'LABOUR OFFICER',
            'MEMBER',
            'TREASURER BR',
            'WOMEN\'S COORDINATOR BR',
            'COORDINATOR HQ',
            'WOMEN\'S COORDINATOR HQ',
            'TREASURER HQ',
            'DEPUTY COORDINATOR HQ',
            'DEPUTY WOMEN\'S COORDINATOR HQ',
        ];
    }
    else
    {
        $not_allowed_roles=[
            'ADMIN',
            'GENERAL SECRETARY HQ',
            'MGT SECRETARY',
            'SECRETARY',
            'DEPUTY GENERAL SECRETARY HQ',
            'ACCOUNTS',
            'ACCOUNTS ASSISTANT'
        ];  
    }
  

    return !in_array($value,$not_allowed_roles);
});
?>

<div class="container-fluid text-sm">
    

    <?php $form = ActiveForm::begin(['method'=>'post']); ?>

<div class="row">
<div class="col-sm-12">
    <?= $form->field($model, 'username')->textInput(['placeholder'=>'E-mail','class'=>'form-control form-control-sm'])->label(false)  ?>
</div></div>
<div class="row">
<div class="col-sm-12">
    <?= $form->field($model, 'role')->dropDownList($promoting_roles,['class'=>'form-control updpriv','multiple'=>'multiple','style'=>'width:100%'])->label("Positions")  ?>
</div>
</div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-sm col-sm-4 btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
