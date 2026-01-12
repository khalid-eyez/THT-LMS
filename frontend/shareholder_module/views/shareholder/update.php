<?php

use yii\helpers\Html;
use common\models\CustomerShareholderForm;
use common\models\Shareholder;
/** @var yii\web\View $this */
/** @var common\models\CustomerShareholderForm $model */

$this->title = 'Update Shareholder';
$this->params['breadcrumbs'][] = ['label' => 'Shareholders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-shareholder-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <!-- Render the same _form.php but with $model pre-filled -->
    <?= $this->render('_form2', [
        'model' => $model,
    ]) ?>

</div>

