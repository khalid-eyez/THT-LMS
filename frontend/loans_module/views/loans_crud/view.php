<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CustomerLoan $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Customer Loans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-loan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'customerID',
            'loan_type_ID',
            'loan_amount',
            'topup_amount',
            'deposit_amount',
            'repayment_frequency',
            'loan_duration_units',
            'duration_extended',
            'deposit_account',
            'deposit_account_names',
            'processing_fee_rate',
            'processing_fee',
            'status',
            'interest_rate',
            'penalty_rate',
            'penalty_grace_days',
            'topup_rate',
            'approvedby',
            'initializedby',
            'paidby',
            'approved_at',
            'created_at',
            'updated_at',
            'isDeleted',
            'deleted_at',
            'loanID',
        ],
    ]) ?>

</div>
