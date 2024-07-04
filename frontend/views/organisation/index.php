<?php
use common\models\GoalsSearch;
use common\models\ObjectivesSearch;
use common\models\StrategiesSearch;
use common\models\TargetsSearch;
$this->params['pageTitle']="Organisation Monitor";


?>



  <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#monitor"><span>Monitor</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#goals"><span>Strategic Objectives</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#strategies"><span>Strategies</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#targets"><span>Targets</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#objectives"><span>Activities</span></a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content mt-3">
  <div class="tab-pane  container active" id="monitor">
    monitor
 
  </div>
  <div class="tab-pane container fade" id="goals">
   <?php 
     $searchModel = new GoalsSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/goals/index',['dataProvider'=>$dataProvider])?>
  </div>
  <div class="tab-pane container fade" id="strategies">

  <?php 
     $searchModel = new StrategiesSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/strategies/index',['dataProvider'=>$dataProvider])?>
  </div>
  <div class="tab-pane container fade" id="targets">

  <?php 
     $searchModel = new TargetsSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/targets/index',['dataProvider'=>$dataProvider])?>
</div>
<div class="tab-pane container fade" id="objectives">

<?php 
     $searchModel = new ObjectivesSearch;
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/objectives/index',['dataProvider'=>$dataProvider])?>
</div>
</div>
</div>
<?php
$script = <<<JS
    $('.monitor').addClass('active');
JS;
$this->registerJs($script);
?>