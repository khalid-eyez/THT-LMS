<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Objectives */

$this->title = 'Update Objectives: ' . $model->objID;
$this->params['breadcrumbs'][] = ['label' => 'Objectives', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->objID, 'url' => ['view', 'id' => $model->objID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="objectives-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
