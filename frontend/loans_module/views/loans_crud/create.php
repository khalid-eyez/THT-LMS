<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerLoan $model */

$this->title = 'Create Customer Loan';
$this->params['breadcrumbs'][] = ['label' => 'Customer Loans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-loan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
