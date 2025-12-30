  <?php 
      use frontend\assets\DashboardAsset;
      DashboardAsset::register($this);
    ?>
    <?php $this->beginPage() ?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <?php 
    $this->registerCsrfMetaTags() ;
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/logo.png']);
    ?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Analytics | Notika - Notika Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->

    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    
    <script src="/js/vendor/modernizr-2.8.3.min.js"></script>
    <style>
      body{
  background-color: rgba(5, 125, 176)!important;
}
    </style>
    <?php $this->head() ?>
</head>

<body>
  <?php $this->beginBody() ?>
   
   <?= $this->render("@frontend/views/includes/top") ?>
  <?= $this->render("@frontend/views/includes/mobilemenu") ?>
  <?= $this->render("@frontend/views/includes/mainmenu") ?>
  <?= $content ?>
	
    
  <?= $this->render("@frontend/views/includes/loginfooter") ?>
  
   <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>