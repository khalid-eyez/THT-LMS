
<?php
use yii;
use yii\helpers\Url;
use yii\helpers\Html;
[$start, $end] = explode(' - ', $model->date_range);
?>

<style>
    #cashtable td
    {
        border:none;
        margin-bottom: 20px;
        padding-top:6px;
        padding-bottom:6px
    }
    #cashtable tr{
        border:none;
        
    }
    #cashtable{
        border:none;
    }
    #heading td{
        font-weight: bold;
        background-color: rgba(2,106,189,0.05);
        padding-top:6px;
        padding-bottom:6px;
    }
</style>
<?php
  
     $records=$model->getCashFlows();
     if($records!=null){
?>
<div class="card-box">
<div class="row mb-3">
    <div class="col-sm-6" style="text-align:left">
    Start Date: <?=date_format(new \DateTime($start),'d M y')?> <br> End Date: <?=date_format(new \DateTime($end),'d M y')?>
    
    </div>
    <div class="col-sm-6" style="text-align:right">
       <?php
    $obalance=$records[0]->openingBalance();
    $balancedisp=($obalance<0)?yii::$app->formatter->asDecimal(abs($obalance),2).' C':yii::$app->formatter->asDecimal(abs($obalance),2).' D';
    ?>
    <?=$balancedisp?><br>
    </div>
</div>


<div class="table-responsive">
<table id="cashtable" class="table table-striped nowrap">
    
<tr id="heading" class="bg-primary text-white nowrap"><td>DATE</td><td>REFERENCE</td><td>DESCRIPTION</td><td>DEBIT</td><td>CREDIT</td><td>BALANCE</td><td></td></tr>
    <?php
    $totaldebit=0;
    $totalcredit=0;
    $finalcumul=0;
    foreach($records as $index=>$record)
    {
        $totaldebit+=$record->debit;
        $totalcredit+=$record->credit;
        $finalcumul=$record->balance;

    ?>
    <tr>
        <td><?=date_format((new DateTime($record->created_at)),'d M y')?></td>
        <td><?= Html::encode($record->reference_no) ?>

        <?php if ($record->status === 'reversed'): ?>
        <span class="badge bg-danger ms-1">REV</span>
        <?php endif; ?></td>
        <td><?=$record->description?></td>
        <td><?=yii::$app->formatter->asDecimal(abs($record->debit),2)?></td>
        <td><?=yii::$app->formatter->asDecimal(abs($record->credit),2)?></td>
        
        <td><?=($record->balance<0)?yii::$app->formatter->asDecimal(abs($record->balance),2).' C':yii::$app->formatter->asDecimal(abs($record->balance),2).' D'?></td>
        <td>
            <a href="<?=$record->payment_document?>" target="_blank" data-toggle="tooltip" title="Reference Document"><i class="fa fa-file-o"></i></a>
            <a href="<?=Url::to(['/cashbook/cashbook/receipt-pdf','cashbookID'=>$record->id])?>" target="_blank" data-toggle="tooltip" title="Download Receipt"><i class="fa fa-download"></i></a>
            <a href="<?=Url::to(['/cashbook/cashbook/reverse','cashbookID'=>$record->id])?>" data-toggle="tooltip" title="Reverse Transaction"><i class="fa fa-refresh"></i></a>
        </td>
    </tr>
    <?php
    }
    ?>
    <tr style="font-weight:bold">
        <td colspan="3" style="text-align:right;padding-right:5px">TOTAL: </td>
    <td><?=yii::$app->formatter->asDecimal(abs($totaldebit),2)?></td>
    <td><?=yii::$app->formatter->asDecimal(abs($totalcredit),2)?></td>
    <td><?=($finalcumul<0)?yii::$app->formatter->asDecimal(abs($finalcumul),2).' Cr':yii::$app->formatter->asDecimal(abs($finalcumul),2).' Dr'?></td>
    <td></td>
    
</tr>
</table>
</div>
<?php } ?>
</div>