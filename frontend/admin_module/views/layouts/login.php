<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
//use Yii;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    $this->registerCsrfMetaTags();
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/logo.png']);
     ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
</head>
<body class="hold-transition   p-0  normalText" style="background-color:rgba(5, 125, 176)">
 
<div class="container d-flex justify-content-center">
     <div class="row mt-5 show-sm">
      <?php if(Yii::$app->session->hasFlash('success')): ?>

          <div class="col-md-12 text-center">
            <div class="alert alert-success alert-dismissible">
              <button class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
              <strong><?= Yii::$app->session->getFlash('success') ?></strong>
            </div>
          </div>
      
      <?php endif ?>
       <?php if(Yii::$app->session->hasFlash('error')): ?>
          <div class="col-md-12 text-center">
            <div class="alert alert-danger alert-dismissible">
              <button class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
              <strong><?= Yii::$app->session->getFlash('error') ?></strong>
            </div>
          </div>
        
      <?php endif ?>
       </div>
       </div>
<div class="container-fluid   p-0 d-flex justify-content-center text-center mt-5 pt-2" style="border-radius:7px 7px 7px 7px!important; min-height:440px;margin-top:6%!important">
  <div class="row shadow-lg bg-white login" style="width:60%; border-radius:6px 6px 6px 6px !important">
  <div class="col-sm-6  bg-white p-5 m-0 mt-0 d-none d-md-block">
    <img src="/img/logo.png" class="img-responsive m-2 " style="width:65%;height:67%;aspect-ratio: 4 / 3;object-fit:contain;border:none;"/>
  </div>
  <div class="col-sm-6 " style="border:none">
<?= $content ?>
       </div>
</div>
</div>
<?= $this->render('@frontend/views/includes/loginfooter') ?>
<!-- /.login-box -->
<?php $this->endBody() ?>
    <style>
      :root {
    --bs-primary: rgba(5, 125, 176, 1);
    --bs-primary-rgb: 5, 125, 176;
}

.bg-primary {
    background-color: rgba(5, 125, 176, 1) !important;
}
    </style>
</body>
</html>

<?php $this->endPage() ?>
