  <?php 
      use frontend\assets\DashboardAsset;
      use yii\helpers\Html;
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
    <title><?= Html::encode($this->title) ?></title>
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
      .toast-center {
          top: 55% !important;
          left: 50% !important;
          transform: translate(-50%, -50%);
          position: fixed;
          z-index: 1080;
      }
      .tooltip .tooltip-inner {
    background-color: #0f6eb1 !important; /* green background */
    color: #fff;                           /* white text */
}

/* Tooltip arrow to match background */
.tooltip.bs-tooltip-left .arrow::before {
    border-left-color: #046cbc !important;
}
    </style>
    <?php $this->head() ?>
</head>

<body>
  <?php $this->beginBody() ?>
   
   <?= $this->render("@frontend/views/includes/top") ?>
  <?= $this->render("@frontend/views/includes/mobilemenu") ?>
  <?= $this->render("@frontend/views/includes/mainmenu") ?>
  <div class="content">
      <div class="row" style="border:none">
          <div class="col-md-12">
            <?php
            $session = Yii::$app->session;
            $this->registerJs("
            toastr.options = {
            positionClass: 'toast-center',
            closeButton: true,
            progressBar: true,
            timeOut: 5000
            };
            ");

            foreach (['success', 'error', 'info'] as $type) {
            if ($session->hasFlash($type)) {
            $msg = addslashes($session->getFlash($type));
            $this->registerJs("toastr.$type('$msg');");
            }
            }
            ?>

          </div></div>

    

  <?= $content ?>
  </div>
	<div id="global-loader" class="data-table-area" style="display: none; position: absolute; z-index:10; top:51%;left:43%; width:150px;height:150px"><img src="/img/spinner.gif" /></div>
    
  <?= $this->render("@frontend/views/includes/loginfooter") ?>
  
   <?php $this->endBody() ?>

   <?php $this->registerJs("
                    
                    $('document').ready(function(){
                      
                      $(document).ajaxStart(function () {
                      $('#global-loader').show();
                      }).ajaxStop(function () {
                      $('#global-loader').hide();
                      });
                     $('body').on('click','.notika-main-menu-dropdown li a',function(e){
                        e.preventDefault();
                         $('#global-loader').show();
                        let url=$(this).attr('href');
                        $('.content').load(url, function () {
                        history.pushState({ url: url }, '', url);
                        $('#global-loader').hide();
                        });
                        
                        
                     })
                
                        
                    })
                    "
                    );
                ?>
</body>

</html>
<?php $this->endPage() ?>
    