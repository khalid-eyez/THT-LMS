<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Targets */

$this->title = 'Create Targets';
$this->params['breadcrumbs'][] = ['label' => 'Targets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="targets-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
