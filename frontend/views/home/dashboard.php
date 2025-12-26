<?php

$this->params['pageTitle']='Dashboard';


?>

<p class="container border border-primary text-bold text-center p-5 mt-3 text-lg">
    Welcome <br><span class="text-primary"><?=yii::$app->user->identity->username?></span>
    <br><i class="fas fa-smile fa-2x text-info"></i>
</p>
<?php
$script = <<<JS

    $('.dash').addClass("active");
JS;
$this->registerJs($script);
?>