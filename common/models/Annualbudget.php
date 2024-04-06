<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "annualbudget".
 *
 * @property int $budgetID
 * @property int $projected_amount
 * @property int $yearID
 * @property int|null $authority
 * @property string $status
 *
 * @property Budgetyear $year
 * @property Member $authority0
 * @property BranchAnnualBudget[] $branchAnnualBudgets
 * @property Monthlyincome[] $monthlyincomes
 * @property Otherincomes[] $otherincomes
 */
class Annualbudget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'annualbudget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['projected_amount', 'yearID'], 'required'],
            [['projected_amount', 'yearID', 'authority'], 'integer'],
            ['status','string','max'=>15],
            [['yearID'], 'exist', 'skipOnError' => true, 'targetClass' => Budgetyear::className(), 'targetAttribute' => ['yearID' => 'yearID']],
            [['authority'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['authority' => 'memberID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'budgetID' => 'Budget ID',
            'projected_amount' => 'Projected Amount',
            'yearID' => 'Year ID',
            'authority' => 'Authority',
        ];
    }

    /**
     * Gets query for [[Year]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getYear()
    {
        return $this->hasOne(Budgetyear::className(), ['yearID' => 'yearID']);
    }

    /**
     * Gets query for [[Authority0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthority0()
    {
        return $this->hasOne(Member::className(), ['memberID' => 'authority']);
    }

    /**
     * Gets query for [[BranchAnnualBudgets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranchAnnualBudgets()
    {
        return $this->hasMany(BranchAnnualBudget::className(), ['budgetID' => 'budgetID']);
    }

    /**
     * Gets query for [[Monthlyincomes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMonthlyincomes()
    {
        return $this->hasMany(Monthlyincome::className(), ['budgetID' => 'budgetID']);
    }
    public function getOtherincomes()
    {
        return $this->hasMany(Otherincomes::className(), ['budget' => 'budgetID']);
    }

    public function getCurrentBudget()
    {
        //return $this->find()->where(['yearID'=>(yii::$app->session->get("financialYear"))->yearID])->one();
    }
    public function totalIncome()
    {
        return (new Monthlyincome)->getTotalIncome($this->budgetID);
    }
    public function totalRevenue()
    {
        return $this->totalIncome()+$this->otherIncomeTotal();
    }
    public function HQrevenue()
    {
        
        $branchbudgets=$this->branchAnnualBudgets;

        if($branchbudgets==null){return 0;}

        foreach($branchbudgets as $branchbudget)
        {
            if($branchbudget->branch0->isHQ())
            {
                $takeover=($branchbudget->takeover!=null)?$branchbudget->takeover->amount:0;
                return $branchbudget->totalIncome()+$this->otherIncomeTotal()+$this->totalspcontributions()+$takeover;
            }
        }
    }
    public function otherIncomeTotal()
    {
       $otherincomes=$this->otherincomes;
  
       if($otherincomes==null)
       {
         return 0;
       }
       $otherincometotal=0;
       foreach($otherincomes as $otherincome)
       {
        $otherincometotal+=$otherincome->amount;
       }

       return $otherincometotal;
    }
    public function totalReturns()
    {
        $incomes=$this->monthlyincomes;
        $totalreturns=0;

        if($incomes==null){return 0;}

        foreach($incomes as $income)
        {
            $totalreturns+=$income->getTotalReturns();
        }
      return $totalreturns;
    }
    public function deficit()
    {
        $deficit=$this->totalIncome()-$this->expectedIncome();
        if($deficit>0){return 0;}
        return $deficit;
    }
    public function unallocated()
    {
      return $this->totalIncome()-$this->allocated();
    }
    public function allocated()
    {
        $brachbudgets=$this->branchAnnualBudgets;
        $allocated=0;

        foreach($brachbudgets as $brachbudget)
        {
            $allocated+=$brachbudget->totalIncome();
        }
        return $allocated+$this->totalspcontributions();
    }
    public function getTotalExpenses()
    {
        $total=0;
        if($this->branchAnnualBudgets==null){ return 0;}
        foreach($this->branchAnnualBudgets as $budget)
        {
          $total+=$budget->getTotalExpenses();
        }

        return $total;
    }
    public function getBalance()
    {
        return $this->totalRevenue()-$this->getTotalExpenses()-$this->totalReturns();
    }
    public function isOpen()
    {
        return $this->status=="open";
    }
    public function totalspcontributions()
    {
        $incomes=$this->monthlyincomes;
        $total=0;

        if($incomes==null){return 0;}

        foreach($incomes as $income)
        {
            $total+=$income->getSpcontribTotal();
        }

        return $total;
    }

    public function HQbudget()
    {
        $budgets=$this->branchAnnualBudgets;

        foreach($budgets as $budget)
        {
            if($budget->branch0->isHQ())
            {
                return $budget;
            }
        }
    }
}
