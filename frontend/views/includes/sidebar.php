<?php 
use yii\helpers\Url;
$user=yii::$app->user;
?>
<div class="sidebar1 d-none d-md-block text-lg" style="font-family:regulartext">
  <?php if($user->can('view_dashboard')){ ?>
    <a href="<?=Url::to('/home/dashboard')?>" class="menuitem dash"><i class="nav-icon fa fa-th" aria-hidden="true"></i> <span class="mn">Dashboard</span></a>
  <?php } ?>
  <?php if($user->can("view_users_list")){ ?>
  <a href="<?=Url::to('/admin/users-list')?>" class="menuitem users"><i class="nav-icon fa fa-user" aria-hidden="true"></i> <span class="mn">Users List</span></a>
  <?php } ?>
  <?php if($user->can("view_audit_data")){ ?>
  <a href="<?=Url::to('/audit-index')?>" class="menuitem audit"><i class="nav-icon fa fa-history" aria-hidden="true"></i> <span class="mn">Audit Entries</span></a>
  <?php } ?>
  <?php if($user->can("view_auth_data")){ ?>
  <a href="<?=Url::to('/access/access-manager')?>" class="menuitem access"><i class="nav-icon fas fa-id-card-alt" aria-hidden="true"></i> <span class="mn">Access Control</span></a>
  <?php } ?>
  <?php if($user->can("view_storage_info")){ ?>
  <a href="<?=Url::to('/storage/monitor')?>" class="menuitem monitor"><i class="nav-icon fa fa-hdd" aria-hidden="true"></i> <span class="mn">Storage Monitor</span></a>
  <?php } ?>
  <!-- SHAREHOLDERS MODULE TESTING-->
  <?php if(!Yii::$app->user->isGuest){ ?>
  <a href="<?= Url::toRoute(['/shareholder/shareholder/create']) ?>" class="menuitem monitor"><i class="nav-icon fa fa-university" aria-hidden="true"></i><span class="mn"> New Shareholder</span></a>
  <?php } ?>
  <?php if(!Yii::$app->user->isGuest){ ?>
  <a href="<?= Url::toRoute(['/shareholder/shareholder/index']) ?>" class="menuitem monitor"><i class="nav-icon fa fa-users" aria-hidden="true"></i><span class="mn"> Manage Shareholders</span></a>
  <?php } ?>
  <?php if(!Yii::$app->user->isGuest){ ?>
  <a href="<?= Url::toRoute(['/shareholder/shareholder/claims']) ?>" class="menuitem monitor"><i class="nav-icon fa fa-money-bill-wave" aria-hidden="true"></i><span class="mn">Shareholder Claims</span></a>
  <?php } ?>
  <?php if(!Yii::$app->user->isGuest){ ?>
  <a href="<?= Url::toRoute(['/shareholder/shareholder/claims2']) ?>" class="menuitem monitor"><i class="nav-icon fa fa-tasks" aria-hidden="true"></i><span class="mn">Claims Progress</span></a>
  <?php } ?>
  </div>