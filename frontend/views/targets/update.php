<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Targets */

$this->title = 'Update Targets: ' . $model->targetID;
$this->params['breadcrumbs'][] = ['label' => 'Targets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->targetID, 'url' => ['view', 'id' => $model->targetID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="targets-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
