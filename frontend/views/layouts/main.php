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
use common\widgets\Page;
use yii\debug\DebugAsset;
use bedezign\yii2\audit\web\JSLoggingAsset;
use yii\widgets\Pjax;
//use Yii;

AppAsset::register($this);
//DebugAsset::register($this);
JSLoggingAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.17/mediaelementplayer.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement-plugins/2.5.1/jump-forward/jump-forward.min.css" integrity="sha512-vHovrDslh/SZPpxgZqaPdU1/wLSaS015uMYHkCn7M2Te2o6edMJ5kk1Hmjy7LPXkMQyvpkfhgaP5X7C2cyuiPQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement-plugins/2.5.1/skip-back/skip-back.min.css" integrity="sha512-sHVQCj7ahO15WmjKUqD0AAUNu8WWw2tpLM6MS79tysxdxXPqbAMZrrfI3tOreK6zcM4LxVH/asUEdQ1RnAhV6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement-plugins/2.5.1/airplay/airplay.min.css" integrity="sha512-WFZbCYRtVA0KtJDNwzADb3r3ProD/T8MWwtdYTxzLtEQOTb6imgz19kP4Lfam11En/WTTHGaJtN1I8IYPC8oFg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement-plugins/2.5.1/context-menu/context-menu.min.css" integrity="sha512-0tMNRS8a8sUxculnEHe+nBLWbSJPsiHI4YaaupqEpv7s7X6VaUxtqmqdG8WcuMvOpY1bSNSszdL8gZuJ7cGT9w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement-plugins/2.5.1/context-menu/context-menu.min.css" integrity="sha512-0tMNRS8a8sUxculnEHe+nBLWbSJPsiHI4YaaupqEpv7s7X6VaUxtqmqdG8WcuMvOpY1bSNSszdL8gZuJ7cGT9w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/emojionearea/emojionearea.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php 
    $this->registerCsrfMetaTags() ;
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/logo.png']);
    ?>
    <style>
.sidebar1 {
  margin: 0;
  padding: 0;
  width: 200px;
  background-color: #f4f6f9;
  position: fixed;
  height: 100%;
  min-height:1100px;
  overflow: auto;
}
.help-block-error{
    color: red;
}

/* Sidebar links */
.sidebar1 a {
  display: block;
  color: black;
  padding: 10px;
  text-decoration: none;
}

/* Active/current link */
.sidebar1 a.active {
  background-color: #034aa6;
  color: white;
}

/* Links on mouse-over */
.sidebar1 a:hover:not(.active) {
  background-color: #0588e6;
  color: white;
}
.mini-sidebar
{
  width:5%;

}

/* Page content. The value of the margin-left property should match the value of the sidebar's width property */
div.sidecontent {
  margin-left: 192px;
  padding-left: 15px;
  padding-right: 2px;
  height: 587px;
 
  overflow:auto;
}

/* On screens that are less than 700px wide, make the sidebar into a topbar */
@media screen and (max-width: 700px) {
  .sidebar1 {
    width: 100%;
    height: auto;
    position: relative;
  }
  .sidebar1 a {float: left;}
  div.sidecontent {margin-left: 0;}
}


/* On screens that are less than 400px, display the bar vertically, instead of horizontally */
@media screen and (max-width: 400px) {
  .sidebar1 a {
    text-align: left;
    float: none;
  }
}

body{
  overflow: hidden;
  background-color: rgba(5, 125, 176)!important;
}
 
      </style>
    <title><?= Html::encode($this?->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition  layout-fixed ">
<?php $this->beginBody() ?>

<?= $this->render("@frontend/views/includes/header.php") ?>
<?= $this->render("@frontend/views/includes/sidebar")?>

<!-- Page content -->
<div class="sidecontent mt-0 pt-0" style="font-family:regulartext">
<div class="wrapper p-0 m-0">

     <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake rounded" src="<?php echo Yii::getAlias('@web/img/logo.png'); ?>" alt="LOGO" height="60" width="60">
  </div> 
     <!-- Navbar 
      //$this->render('/includes/header') -->
  <!-- /.navbar -->
  <!-- Main Sidebar Container -->
    <!-- also this you may trie these 082B45  # #0062CC
    lovely background style="background:#001832" 

  <aside class=" container bg-white main-sidebar-custom sidebar-light-primary  elevation-1 pace-primary "  >
   

    
    <div class="sidebar text-primary" >
  
     
  
  
  </aside> -->
  <!-- The sidebar -->


  


    <!-- Content Wrapper. Contains page content -->
  <div class="container-fluid bg-white mt-0 p-0">
    <!-- Content Header (Page header) -->
    <div class="content-header p-1 show-sm">
      <div class="container-fluid">
        <div class="row mb-2" style="font-size:17px; background-color:#f1f1f1">
          <div class="col-sm-12 text-secondary font-weight-bold " style="font-size:17px; background-color:#f0f0f3">
          <i class="fa fa-bars mt-2 tog" data-widget="pushmenu" href="#" role="button"></i>
           <?= Page::widget([
             'pageTitle'=>isset($this->params['pageTitle'])? $this->params['pageTitle']: ''
           ])?>
        
          
          <div class="navbar float-right navbar-expand p-0 ">
            [ <?=yii::$app->user->identity->username;?> ]
<ul class="navbar-nav ml-auto">

      <li class="nav-item dropdown">
        <a class="nav-link responsivetext" data-toggle="dropdown" href="#" id="username"><span class="fas fa-user text-primary"></span>
        </a>
<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
           

            <div class="dropdown-divider"></div>

          <a href="<?=Url::to('/auth/changepassword')?>" class="dropdown-item">
            <i class="fas fa-lock mr-2"></i> <span class="small"> Change Password</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="<?=Url::to('/auth/logout')?>" class="dropdown-item">
            <i class="fas fa-power-off"></i><span class="small"> Logout</span>
      
          </a>
</div>
</li>
</ul>

</div>
         
        </div><!-- /.row -->
        <!--Alert messages-->
    </div>
    <!-- /.content-header -->
    </div><!-- /.container-fluid -->
      <div class="container mt-2 show-sm">
      <div class="row">
      <div class="col-md-12">
      <?php if(Yii::$app->session->hasFlash('success')): ?>

          <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
                <a class="close" data-dismiss="alert">&times;</a>
              <strong><?= Yii::$app->session->getFlash('success') ?></strong>
            </div>
          </div>
      
      <?php endif ?>
       <?php if(Yii::$app->session->hasFlash('error')): ?>
          <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
              
                <span class="close" data-dismiss="alert">&times;</span>
              
              <strong><?= Yii::$app->session->getFlash('error') ?></strong>
            </div>
          </div>
        
      <?php endif ?>
      <?php if(Yii::$app->session->hasFlash('info')): ?>
          <div class="col-md-12">
            <div class="alert alert-info alert-dismissible">
              <button class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
              <strong><?= Yii::$app->session->getFlash('info') ?></strong>
            </div>
          </div>
          <?php endif ?>

      </div>
      
      </div>
      </div>

    <!-- Main content -->
    <section class="content mt-0 mycont" style="font-family:regulartext!important">
      <?= $content ?>
      </section>
    
    <!-- /.content -->
    
    <?php //$this->render('/includes/chatbox') ?>
          </div>
          <!-- /.col -->
     
    <!-- //////////////////////////////////////// -->
  </div>
  <!-- /.content-wrapper -->
</div>
      </div>
  
      </div> 
  <!-- footer -->
 <?= $this->render('@frontend/views/includes/loginfooter') ?>
  <!-- footer end -->

<?php $this->endBody() ?>


<script type="text/javascript" src="/plugins/popper/popper.min.js"></script>







<?php
$script = <<<JS
$(document).ready(function(){
  $('#sidebar-overlay').addClass("d-none");
  var bar=$('.sidebar1');
  var wid=window.innerWidth;
 
  if(wid<=992 && wid>=700)
  {
    bar.addClass('mini-sidebar');
   $('.mn').addClass("d-none");
   
   $(".sidecontent").css('transition','0.3s ease-in-out');
   var marg=bar.width();
   $(".sidecontent").css("margin-left",marg);
  }

  $('.mycont').on('click',function(e){

    if(window.innerWidth<700 )
   {
    bar.css("position","relative");
    $('.mn').addClass("d-none");
    bar.addClass("d-none");
    return true;
   }
   else if(window.innerWidth<992)
   {
    bar.addClass('mini-sidebar');
    bar.removeClass("shadow");
   $('.mn').addClass("d-none");
   $(".tog").css("margin-left","0px");
   $(".sidecontent").css('transition','0.3s ease-in-out');
   var marg=bar.width();
   $(".sidecontent").css("margin-left",marg);
   }

  })
 $('.tog').on('click',function(){
  wid=window.innerWidth;
  var bar=$('.sidebar1');
  if(bar.hasClass("mini-sidebar"))
  {
   bar.removeClass('mini-sidebar');
   bar.addClass("shadow");
   if(wid<=992 && wid>=700)
   {
    bar.css("position","fixed");
    bar.css("z-index","20");
    $('.mn').removeClass("d-none");
    $(".sidecontent").css('transition','0.1s ease-in-out');
   var marg=bar.width();
   $(".tog").css("margin-left",marg-30);
   }
   else
   {
   $('.mn').removeClass("d-none");
   
   $(".sidecontent").css('transition','0.1s ease-in-out');
   var marg=bar.width();
   
   $(".sidecontent").css("margin-left",marg);
   }
  }
  else
  {
    if(window.innerWidth<700)
   {
    $('.mn').removeClass("d-none");
    bar.removeClass("d-none");
    bar.css("position","fixed");
    bar.css("z-index","20");
    bar.addClass("shadow");
    bar.css("width",window.innerWidth-150);
    return true;
   }
  
    bar.addClass('mini-sidebar');
    bar.removeClass("shadow");
   $('.mn').addClass("d-none");
   $(".tog").css("margin-left","0px");
   $(".sidecontent").css('transition','0.3s ease-in-out');
   var marg=bar.width();
   $(".sidecontent").css("margin-left",marg);
   
  }
  
 })
})
JS;
$this->registerJs($script);
?>

<style>
  :root {
    --bs-primary: rgba(5, 125, 176, 1);
    --bs-primary-rgb: 5, 125, 176;
}

.bg-primary {
    background-color: rgba(5, 125, 176, 1) !important;
}  
.btn-primary {
    --bs-btn-color: #fff;
    --bs-btn-bg: rgba(5, 125, 176, 1);
    --bs-btn-border-color: rgba(5, 125, 176, 1);

    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: rgba(4, 110, 155, 1);
    --bs-btn-hover-border-color: rgba(4, 110, 155, 1);

    --bs-btn-active-bg: rgba(3, 95, 135, 1);
    --bs-btn-active-border-color: rgba(3, 95, 135, 1);

    --bs-btn-disabled-bg: rgba(5, 125, 176, 0.65);
    --bs-btn-disabled-border-color: rgba(5, 125, 176, 0.65);
}
</style>
</body>
</html>
<?php $this->endPage() ?>