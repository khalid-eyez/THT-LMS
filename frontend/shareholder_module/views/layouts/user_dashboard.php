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
    </style>
    <?php $this->head() ?>
</head>

<body>
  <?php $this->beginBody() ?>
   
   <?= $this->render("@frontend/views/includes/top") ?>
  <?= $this->render("@frontend/views/includes/mobilemenu") ?>
  <?= $this->render("@frontend/views/includes/mainmenu") ?>
  <div class="content">
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

          </div></div>
      <div class="preloader flex-column justify-content-center align-items-center">
          <img class="animation__shake rounded" src="<?php echo Yii::getAlias('@web/img/logo.png'); ?>" alt="LOGO" height="60" width="60">
      </div>
    <div id="global-loader" class="data-table-area" style="display: none; position: absolute; z-index:10; top:51%;left:43%; width:150px;height:150px"><img src="/img/spinner.gif" /></div>
  <?= $content ?>
  </div>
	
    
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
                        let url=$(this).attr('href');
                        $('.content').load(url, function () {
                        history.pushState({ url: url }, '', url);
                        });
                     })
                
                        
                    })
                    "
                    );
                ?>
</body>

</html>
<?php $this->endPage() ?>
    