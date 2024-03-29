<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Branch */

$this->title = 'Create Branch';
$this->params['pageTitle']="New Branch";
?>
<div class="branch-create container pl-5 pr-5">
    <div class="card card-success">
        <div class="card-header"><div class="card-title text-bold"><i class="fa fa-plus-circle"></i> New Branch</div></div>
   <div class="card-body p-5">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
    </div>
</div>
