<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Shareholder $model */

$this->title = 'Create Shareholder';
$this->params['breadcrumbs'][] = ['label' => 'Shareholders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shareholder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
