<?php 
use yii\helpers\Url;
$user=yii::$app->user;
?>
<div class="sidebar1 mt-2 d-none d-md-block">
  <div class="bg-success row bk" style="height:0.5%"></div>
  <a href="<?=$user->can('ADMIN')?Url::to('/admin/dashboard'):Url::to('/member/dashboard')?>" class="menuitem dashboard"><i class=" nav-icon fas fa-th"></i> <span class="mn">Dashboard</span></a>
  <?php if($user->can("TREASURER HQ") || $user->can("CHAIRPERSON HQ") || $user->can("GENERAL SECRETARY HQ") || $user->can("TREASURER BR") || $user->can("CHAIRPERSON BR") || $user->can("GENERAL SECRETARY BR")){?>
  <a href="<?=Url::to('/finance/finance')?>" class="finance"> <i class="nav-icon fas fa-coins"></i> <span class="mn">Finance</span></a>
  <?php } ?>
  <?php if($user->can("SECRETARY HQ") || $user->can("CHAIRPERSON HQ") || $user->can("GENERAL SECRETARY HQ") || $user->can("SECRETARY BR") || $user->can("CHAIRPERSON BR") || $user->can("GENERAL SECRETARY BR")  ){?>
  <a href="<?=Url::to('/filecabinet/filecabinet')?>" class="cabinet"> <i class="nav-icon fa fa-folder-open"></i> <span class="mn">File cabinet</span></a>
  <?php } ?>
  <?php if($user->can("ADMIN")){ ?>
  <a href="<?=Url::to('/admin/users-list')?>" class="menuitem users"><i class="nav-icon fa fa-user" aria-hidden="true"></i> <span class="mn">All users</span></a>
  <a href="<?=Url::to('/admin/budget-year')?>" class="menuitem byear"><i class="nav-icon fa fa-calendar" aria-hidden="true"></i> <span class="mn">Financial Year</span></a>
  <a href="<?=Url::to('/repos/docs')?>" class="menuitem repository"><i class="nav-icon fa fa-calendar" aria-hidden="true"></i> <span class="mn">Audit Entries</span></a>
  <?php } ?>
  <?php if($user->can("CHAIRPERSON HQ") || $user->can("GENERAL SECRETARY HQ") || $user->can("CHAIRPERSON BR") || $user->can("GENERAL SECRETARY BR")){ ?>
  <a href="<?=Url::to('/member/member-list')?>" class="members"><i class="nav-icon fa fa-group"></i><span class="mn"> Members</span></a>

  <?php } ?>
  <?php if($user->can("CHAIRPERSON HQ") || $user->can("GENERAL SECRETARY HQ") || $user->can("ADMIN")){ ?>
  <a href="<?=Url::to('/branch')?>" class="branches"><i class="nav-icon fa fa-building"></i><span class="mn"> Branches</span></a>
  <?php } ?>
  <?php if(!$user->can("ADMIN")){ ?>
  <a href="<?=Url::to('/meeting/meetings')?>" class="menuitem meetings"><i class="nav-icon fas fa-comments" aria-hidden="true"></i> <span class="mn">Meetings</span></a>
  <a href="<?=Url::to('/repos/docs')?>" class="menuitem repository"><i class="nav-icon fa fa-file" aria-hidden="true"></i> <span class="mn">Repository</span></a>
  <?php } ?>

</div>