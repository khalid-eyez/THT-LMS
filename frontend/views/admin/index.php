<?php
use yii\bootstrap4\Breadcrumbs;
use common\models\Session;
use common\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
$this->params['pageTitle']='Dashboard';
?>
<div class="site-index">

    

    <div class="body-content">
            <!-- Content Wrapper. Contains page content -->
   
       <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon elevation-0"><i class="fa fa-user-circle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number">
                <?=$users?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
         

          <div class="col-12 col-sm-6 col-md-3">
          <a href="<?= Url::toRoute('student-crud/index') ?>" class="text-dark">
            <div class="info-box mb-3">
            <span class="info-box-icon elevation-0"><i class="fa fa-group"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Members</span>
                <span class="info-box-number">
                  <?=$members?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

       
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
          <a href="<?= Url::toRoute('/branch') ?>" class="text-dark">
            <div class="info-box mb-3">
            <span class="info-box-icon elevation-0"><i class="fas fa-building"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Branches</span>
                <span class="info-box-number">
                  <?=$branches?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <div class="col-12 col-sm-6 col-md-3">
          <a href="<?= Url::toRoute('admin/courses') ?>" class="text-dark">
            <div class="info-box mb-3">
            <span class="info-box-icon elevation-0"><i class="fa fa-calendar"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Financial Year</span>
                <span class="info-box-number">
                  <?=$year->title?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
        

         
 
          </div>
             
         
      
   
       

      </div><!--/. container-fluid -->

    </div>
</div>
<?php
$script = <<<JS
    $('.dashboard').addClass('active');
JS;
$this->registerJs($script);
?>