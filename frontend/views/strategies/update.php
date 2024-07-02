<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Strategies */

$this->title = 'Update Strategies: ' . $model->strID;
$this->params['breadcrumbs'][] = ['label' => 'Strategies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->strID, 'url' => ['view', 'id' => $model->strID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="strategies-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
