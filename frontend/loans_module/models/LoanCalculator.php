<?php
namespace frontend\loans_module\models;

use yii\base\Model;
use yii;

class LoanCalculator extends Model
{
   public function generateRepaymentSchedule(float $loan_amount,float $interest_rate, string $repayment_frequency,int $loan_duration, string $starting_date,$schedule=[])
   {
      $duedate=$this->incrementDateByFrequency($this->stringtodate($starting_date),$repayment_frequency);
      $scheduleUpdated=$schedule;
      $amortized=$this->amortize($loan_amount,$interest_rate,$loan_duration);
      $amortized['payment_date']=$duedate;
      array_push($scheduleUpdated,$amortized);
      $rounds=$loan_duration-1;
      if($rounds==0)
        {
            return $scheduleUpdated;
        }

        return $this->generateRepaymentSchedule($amortized['balance'],$interest_rate, $repayment_frequency,$rounds,$duedate,$scheduleUpdated);

   }
   public function amortize($amount,$interest_rate,$duration)
   {
    $interest_rate=$interest_rate/100;
    if($interest_rate==0){
        $repayment_amount=$amount/$duration;
    }
    else{
    $gf=pow((1+$interest_rate),$duration);
    $repayment_amount=($amount*$interest_rate*$gf)/($gf-1);
    }
   
    $interest_amount=round(($amount*$interest_rate),2);
    $principal=round(($repayment_amount-$interest_amount),2);
    $balance=round(($amount-$principal),2);

    return [
        'loan_amount'=>$amount,
        'principal'=>$principal,
        'interest'=>$interest_amount,
        'installment'=>$repayment_amount,
        'balance'=>$balance
    ];


   }
    function incrementDateByFrequency(\DateTime $date, string $frequency)
    {
    $date = clone $date;
    $frequency=strtoupper($frequency);
    $daysMap = [
    'DAILY'          => 1,
    'WEEKLY'         => 7,
    'MONTHLY'        => 30,
    'QUARTERLY'      => 90,
    'SEMI_ANNUALLY'  => 180,
    'ANNUALLY'       => 360,
    ];
    $date->add(new \DateInterval('P' . $daysMap[$frequency] . 'D'));
    return $date->format('Y-m-d H:i:s'); 
    }

    public function stringtodate($datestring)
    {
        $tz = new \DateTimeZone('Africa/Dar_es_Salaam'); // use your system TZ

        $date = \DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $datestring,
        $tz
        );

        if ($date === false) {
        throw new RuntimeException('Invalid MySQL DATETIME value');
        }

        return $date;
    }
}
