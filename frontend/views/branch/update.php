<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Branch */

$this->title = 'Update Branch: ' . $model->branch_short;
$this->params['pageTitle']="Update Branch";
?>
<div class="branch-update container pl-5 pr-5">
    <div class="card card-success">
        <div class="card-header"><div class="card-title text-bold"><i class="fa fa-edit"></i> <?= Html::encode($this->title) ?></div></div>
   <div class="card-body p-5">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
    </div>
    </div>
