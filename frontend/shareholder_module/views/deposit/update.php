<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Deposit $model */

$this->title = 'Update Deposit: ' . $model->depositID;
$this->params['breadcrumbs'][] = ['label' => 'Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->depositID, 'url' => ['view', 'depositID' => $model->depositID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deposit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
