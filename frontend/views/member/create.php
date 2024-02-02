<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Member */

$this->title = 'Create Member';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="container text-center pb-3 text-lg text-success" ><i class="fa fa-user-plus"></i> Member Registration</div>
<div class="container d-flex justify-content-center" >
 
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
