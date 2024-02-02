<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branch_annual_budget".
 *
 * @property int $bbID
 * @property int $projected_amount
 * @property int $budgetID
 * @property int $branch
 * @property int|null $authority
 *
 * @property Annualbudget $budget
 * @property Branch $branch0
 * @property Member $authority0
 * @property BranchMonthlyRevenue[] $branchMonthlyRevenues
 * @property Budgetprojections[] $budgetprojections
 */
class BranchAnnualBudget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch_annual_budget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['projected_amount', 'budgetID', 'branch'], 'required'],
            [['projected_amount', 'budgetID', 'branch', 'authority'], 'integer'],
            [['budgetID'], 'exist', 'skipOnError' => true, 'targetClass' => Annualbudget::className(), 'targetAttribute' => ['budgetID' => 'budgetID']],
            [['branch'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch' => 'branchID']],
            [['authority'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['authority' => 'memberID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bbID' => 'Bb ID',
            'projected_amount' => 'Projected Amount',
            'budgetID' => 'Budget ID',
            'branch' => 'Branch',
            'authority' => 'Authority',
        ];
    }

    /**
     * Gets query for [[Budget]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBudget()
    {
        return $this->hasOne(Annualbudget::className(), ['budgetID' => 'budgetID']);
    }

    /**
     * Gets query for [[Branch0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch0()
    {
        return $this->hasOne(Branch::className(), ['branchID' => 'branch']);
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
     * Gets query for [[BranchMonthlyRevenues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranchMonthlyRevenues()
    {
        return $this->hasMany(BranchMonthlyRevenue::className(), ['branchbudget' => 'bbID']);
    }

    /**
     * Gets query for [[Budgetprojections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBudgetprojections()
    {
        return $this->hasMany(Budgetprojections::className(), ['branchbudget' => 'bbID']);
    }

    public function getTotalExpenses()
    {
        $total=0;
        if($this->budgetprojections==null){ return 0;}
        foreach($this->budgetprojections as $proj)
        {
          $total+=$proj->getTotalExpenses();
        }

        return $total;
    }
    public function getCurrentBudget()
    {
        $currentbudget=(new Annualbudget)->getCurrentBudget()->budgetID;
        $branch=yii::$app->user->identity->member->branch;
        return $this->find()->where(['budgetID'=>$currentbudget,'branch'=>$branch])->one();
    }
    public function totalIncome()
    {
        return (new BranchMonthlyRevenue)->getTotalIncome($this->bbID);
    }
    public function deficit()
    {
        $deficit=$this->totalIncome()-$this->expectedIncome();
        if($deficit>0){return 0;}
        return $deficit;
    }
    public function getBalance()
    {
        return $this->allocated()-$this->getTotalExpenses();
    }
    public function acquireRevenue($income)
    {
        return (new BranchMonthlyRevenue)->acquireRevenue($income,$this);
        
    }
    public function projected()
    {
        $projections=$this->budgetprojections;
        $total=0;
        if($projections==null){return 0;}

        foreach($projections as $projection)
        {
            $total+=$projection->projected_amount;
        }
        return $total;

    }
    public function unallocated()
    {
        return $this->totalIncome()-$this->allocated();
    }
    public function expectedIncome()
    {
        $budget=(new Annualbudget)->getCurrentBudget();
        $contributionfactor=$budget->year->contributionfactor;
        $memberscount=$this->branch0->membersCount();

        if($this->branch0->isHQ())
        {
            $allmembers=count(Member::find()->all());
            return ($contributionfactor*$allmembers)/2;
        }

        return ($contributionfactor*$memberscount)/2;
    }
    public function allocated()
    {
        $projections=$this->budgetprojections;
        $total=0;
        if($projections==null){return 0;}

        foreach($projections as $projection)
        {
            $total+=$projection->allocated();
        }
        return $total;

    }
    public function unplanned()
    {
        return $this->expectedIncome()-$this->projected();
    }
    public function hasAuthority()
    {
        $userbranch=yii::$app->user->identity->member->branch;
        return $this->branch==$userbranch;
    }
    
 

}
