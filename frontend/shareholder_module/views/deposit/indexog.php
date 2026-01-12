<?php

use common\models\Deposit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\DepositSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Deposits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Deposit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'depositID',
            'shareholderID',
            'amount',
            'interest_rate',
            'type',
            //'deposit_date',
            //'created_at',
            //'updated_at',
            //'isDeleted',
            //'deleted_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Deposit $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'depositID' => $model->depositID]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
