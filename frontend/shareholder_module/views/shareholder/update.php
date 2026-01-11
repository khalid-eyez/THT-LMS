<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Shareholder $model */

$this->title = 'Update Shareholder: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shareholders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shareholder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
