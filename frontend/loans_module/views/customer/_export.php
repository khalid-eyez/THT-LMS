<?php

use kartik\export\ExportMenu;

/** @var $dataProvider yii\data\ActiveDataProvider */

$gridcolumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    'customerID',
    'full_name',
    'birthDate',
    'gender',
    'NIN',
    [
        'attribute' => 'created_at',
        'label' => 'Reg. Date',
        'value' => static fn($model) => Yii::$app->formatter->asDate($model->created_at, 'php:d M Y'),
    ],
];

echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridcolumns,
    'target' => ExportMenu::TARGET_SELF, // export in same request
]);
