<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Strategies */

$this->title = 'Create Strategies';
$this->params['breadcrumbs'][] = ['label' => 'Strategies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="strategies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
