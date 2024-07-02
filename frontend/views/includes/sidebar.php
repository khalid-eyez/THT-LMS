<?php 
use yii\helpers\Url;
$user=yii::$app->user;
?>
<div class="sidebar1 d-none d-md-block text-lg" style="font-family:regulartext">
  <a href="<?=$user->can('ADMIN')?Url::to('/admin/dashboard'):Url::to('/home/dashboard')?>" class="menuitem dashboard"><i class=" nav-icon fas fa-th"></i> <span class="mn">Dashboard</span></a>
  <?php if(
    yii::$app->user->can('view_top_finance') || 
    yii::$app->user->can('view_hq_finance') ||
     yii::$app->user->can('view_costCenter_budget') ||
     yii::$app->user->can('view_branch_finance') ||
     yii::$app->user->can('view_branch_accounts')
     ){?>
  <a href="<?=Url::to('/finance/finance')?>" class="finance"> <i class="nav-icon fas fa-coins"></i> <span class="mn">Finance</span></a>
  <?php } ?>
  <a href="<?=Url::to('/costcenter/index')?>" class="costcenter"> <i class="nav-icon fa fa-money"></i> <span class="mn">Cost Centers</span></a>
  <a href="<?=Url::to('/organisation/monitor')?>" class="monitor"> <i class="nav-icon fa fa-desktop"></i> <span class="mn">Monitor</span></a>
  <?php if($user->can("MGT SECRETARY")){?>
  <a href="<?=Url::to('/filecabinet/filecabinet')?>" class="cabinet"> <i class="nav-icon fa fa-folder-open"></i> <span class="mn">File cabinet</span></a>
  <?php } ?>
  <?php if($user->can("ADMIN")){ ?>
  <a href="<?=Url::to('/admin/users-list')?>" class="menuitem users"><i class="nav-icon fa fa-user" aria-hidden="true"></i> <span class="mn">All users</span></a>
  <a href="<?=Url::to('/admin/budget-year')?>" class="menuitem byear"><i class="nav-icon fa fa-calendar" aria-hidden="true"></i> <span class="mn">Financial Year</span></a>
  <a href="<?=Url::to('/audit')?>" class="menuitem audit"><i class="nav-icon fa fa-history" aria-hidden="true"></i> <span class="mn">Audit Entries</span></a>
  <a href="<?=Url::to('/access/access-manager')?>" class="menuitem access"><i class="nav-icon fas fa-id-card-alt" aria-hidden="true"></i> <span class="mn">Access Control</span></a>
  <?php } ?>
  <?php if($user->can("CHAIRPERSON HQ") || $user->can("GENERAL SECRETARY HQ") || $user->can("CHAIRPERSON BR") || $user->can("GENERAL SECRETARY BR")){ ?>
  <a href="<?=Url::to('/member/member-list')?>" class="members"><i class="nav-icon fa fa-group"></i><span class="mn"> Members</span></a>

  <?php } ?>
  <?php if($user->can("CHAIRPERSON HQ") || $user->can("GENERAL SECRETARY HQ") || $user->can("ADMIN")){ ?>
  <a href="<?=Url::to('/branch')?>" class="branches"><i class="nav-icon fa fa-building"></i><span class="mn"> Branches</span></a>
  <?php } ?>
  <?php if(!$user->can("ADMIN")){ ?>
  <a href="<?=Url::to('/meeting/meetings')?>" class="menuitem meetings"><i class="nav-icon fas fa-comment-alt" aria-hidden="true"></i> <span class="mn">Meetings</span></a>
  <a href="<?=Url::to('/repos/docs')?>" class="menuitem repository"><i class="nav-icon fa fa-file" aria-hidden="true"></i> <span class="mn">Repository</span></a>
  <?php } ?>

</div>