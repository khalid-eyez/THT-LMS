<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerLoan $model */

$this->title = 'Update Customer Loan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Customer Loans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="customer-loan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
