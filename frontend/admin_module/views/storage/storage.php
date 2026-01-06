<?php
/* @var $this yii\web\View */

$this->params['pageTitle'] ="Storage";
$this->title = 'Storage';


?>

<div class="container-fluid">

<div class="card-body " >

<div class="container border p-3" id="accordion">
<span class="text-md text-bold pl-1 ml-2">Storage Information</span>

<pre><?=$info?></pre>



</div>



</div>






</div>
<?php
$script = <<<JS
    $('document').ready(function(){
  $('.monitor').addClass("active");
})
JS;
$this->registerJs($script);
?>









