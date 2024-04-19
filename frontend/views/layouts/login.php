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
<body class="hold-transition   p-0 normalText" style="background-color:rgba(6, 92, 27,.9)">
  <?=$this->render('/includes/loginheader2')?>
<div class="container d-flex justify-content-center">
     <div class="row mt-2 show-sm">
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
<div class="container-fluid   p-0 d-flex justify-content-center text-center mt-4" style="border-radius:7px 7px 7px 7px!important">
  <div class="row shadow-lg bg-white login" style="width:60%; border-radius:6px 6px 6px 6px !important">
  <div class="col-sm-6  bg-white p-0 m-0 mt-2 d-none d-md-block">
    <img src="/img/flag.gif" class="img-responsive m-0 shadow-sm" style="width:100%;height:400px;border:none;"/>
  </div>
  <div class="col-sm-6 " style="border:none">
<?= $content ?>
       </div>
</div>
</div>
<?= $this->render('/includes/loginfooter') ?>
<!-- /.login-box -->
<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>
