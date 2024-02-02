<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branch_monthly_revenue".
 *
 * @property int $revenueID
 * @property int $received_amount
 * @property int $incomeID
 * @property string $month
 * @property int $branchbudget
 *
 * @property Monthlyincome $income
 * @property BranchAnnualBudget $branchbudget0
 */
class BranchMonthlyRevenue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch_monthly_revenue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['received_amount', 'incomeID', 'month', 'branchbudget'], 'required'],
            [['incomeID', 'branchbudget'], 'integer'],
            [['received_amount'], 'number'],
            [['month'], 'string', 'max' => 10],
            [['incomeID'], 'exist', 'skipOnError' => true, 'targetClass' => Monthlyincome::className(), 'targetAttribute' => ['incomeID' => 'incomeID']],
            [['branchbudget'], 'exist', 'skipOnError' => true, 'targetClass' => BranchAnnualBudget::className(), 'targetAttribute' => ['branchbudget' => 'bbID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'revenueID' => 'Revenue ID',
            'received_amount' => 'Received Amount',
            'incomeID' => 'Income ID',
            'month' => 'Month',
            'branchbudget' => 'Branchbudget',
        ];
    }

    /**
     * Gets query for [[Income]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncome()
    {
        return $this->hasOne(Monthlyincome::className(), ['incomeID' => 'incomeID']);
    }

    /**
     * Gets query for [[Branchbudget0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranchbudget0()
    {
        return $this->hasOne(BranchAnnualBudget::className(), ['bbID' => 'branchbudget']);
    }
    public function getTotalIncome($budget)
    {
       $income=$this->find()->where(['branchbudget'=>$budget])->sum('received_amount');

       if($income==null){return 0;}

       return $income;
    }
    public function acquireRevenue($income,$budget)
    {
        $total=$income->receivedAmount;
        $branchmember=count($budget->branch0->members);
        $allmembers=count(Member::find()->all());
        $allmembers=($allmembers!=0)?$allmembers:1;
        $hqshare=$total/2; 
        if($budget->branch0->level=="HQ")
        {
            $share=$hqshare;
        }
        else
        {
            $share=(($total-$hqshare)/$allmembers)*$branchmember;
        }
     
      

        $this->received_amount=$share;
        $this->incomeID=$income->incomeID;
        $this->month=$income->month;
        $this->branchbudget=$budget->bbID;

        return $this->save();
    }
    public function updateRevenues($revbuffer)
    {
        $newtotal=0;
        foreach($revbuffer as $key=>$revenue)
        {
            if($key=="_csrf-frontend"){continue;}
            $newtotal+=$revenue;
           
        }

        
        foreach($revbuffer as $key=>$revenue)
        {
            if($key=="_csrf-frontend"){continue;}
            $rev=$this->findOne($key);
            if($newtotal>$rev->income->receivedAmount){
                throw new \Exception("Updated Income greater than actual monthly income");
            }
            if($rev==null){continue;}
            $rev->received_amount=$revenue;

            if(!$rev->save()){continue;}
        }

        return true;
    }
}
