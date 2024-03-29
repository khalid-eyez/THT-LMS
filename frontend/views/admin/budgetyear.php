<?php


use common\models\Annualbudget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use common\models\Budgetyear;


/* @var $this yii\web\View */

$this->params['pageTitle'] ="Budget Year";


?>


<div class="site-index">
<div class="body-content">
<!-- Content Wrapper. Contains page content -->

<div class="container-fluid">

<div class="row">

<section class="col-lg-12 p-4 ">
<div class="row  border d-flex text-center justify-content-center border-muted shadow-sm">
<div class="col-sm-4  text-bold  m-1 d-flex  p-3  text-center justify-content-center" >
<a href="migrate-back" value=""  class="btn btn-success shadow">
<i class="fa fa-arrow-left "> </i> Migrate Backwards
</a>
</div>
<div class="col-sm-2   p-3 m-1  " >
    <span>Current Year</span><br>
    <span class="text-bold"><?=(new Budgetyear)->getBudgetYear()->title?></span>
</div>
<div class="col-sm-4 text-bold m-1 d-flex  p-3  text-center justify-content-center" >
<a href="/admin/migrate"  value="" class="btn btn-success shadow"" >
Migrate Forwards <i class="fa fa-arrow-right" aria-hidden="true"></i>
</a></div>

</div>

<div class="tab-content" id="custom-tabs-four-tabContent">


<!-- ########################################### group by  instructor ######################################## -->

<!-- Left col -->
<section class="col-lg-12 mt-3 border shadow-sm">


<div class="container-fluid " >
<div class="tab-content" id="custom-tabs-four-tabContent">
<div class="container   p-1 pl-3" id="accordion">
<div class="row text-sm text-muted text-bold p-2 border-bottom border-muted">
<div class="col-sm">Title</div>
<div class="col-sm">Start Year</div>
<div class="col-sm">End Year</div>
<div class="col-sm">Status</div>

</div>
<?php

if(empty($budgetyears)){

echo '<div style="width:91%"  class="container  p-2  d-flex justify-content-center p-5"><span class="text-center text-muted "><i class="fa fa-info-circle"></i> No Budget Years Found</span></div>';

}

?>

<?php foreach($budgetyears as $budgetyear){ ?>
<div class="row text-sm border-bottom border-muted <?=($budgetyear->operationstatus=='open')?'text-bold bg-success':'text-muted'?>">
<div class="col-sm"><?=$budgetyear->title?></div>
<div class="col-sm"><?=$budgetyear->startingyear?></div>
<div class="col-sm"><?=$budgetyear->endingyear?></div>
<div class="col-sm"><?=$budgetyear->operationstatus?></div>
</div>
<?php } ?>





</div>

</div>




</div>
<!-- ########################################### GROUPS END ######################################## -->



</section>
<!-- ########################################### group by instructor end ######################################## -->










<?php $script = <<<JS
$(document).ready(function(){
$('.byear').addClass('active');                                                                   
});
JS;
$this->registerJs($script);

?>

</div>

</div>



<!-- ########################################### group by student end ######################################## -->
</div>

</div>
</div>
</div>
</div>

</section>



</div>
</div>
</div><!--/. container-fluid -->
</div>


<?php
$script = <<<JS
$(document).ready(function(){
$("#CourseList").DataTable({
responsive:true,
});
//Remember active tab
$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

localStorage.setItem('activeTab', $(e.target).attr('href'));

});

var activeTab = localStorage.getItem('activeTab');

if(activeTab){

$('#custom-tabs-four-tab a[href="' + activeTab + '"]').tab('show');

}

});

JS;
$this->registerJs($script);
?>
<?php 
$this->registerCssFile('@web/plugins/select2/css/select2.min.css');
$this->registerJsFile(
'@web/plugins/select2/js/select2.full.js',
['depends' => 'yii\web\JqueryAsset']
);
$this->registerJsFile(
'@web/js/create-assignment.js',
['depends' => 'yii\web\JqueryAsset'],

);



?>




