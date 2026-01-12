<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Deposit $model */

$this->title = $model->depositID;
$this->params['breadcrumbs'][] = ['label' => 'Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="deposit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'depositID' => $model->depositID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'depositID' => $model->depositID], [
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
            'depositID',
            'shareholderID',
            'amount',
            'interest_rate',
            'type',
            'deposit_date',
            'created_at',
            'updated_at',
            'isDeleted',
            'deleted_at',
        ],
    ]) ?>

</div>
