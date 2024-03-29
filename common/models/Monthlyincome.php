<?php

namespace common\models;
use yii\helpers\Html;
use Yii;

/**
 * This is the model class for table "monthlyincome".
 *
 * @property int $incomeID
 * @property int $budgetID
 * @property string $month
 * @property int $receivedAmount
 * @property string|null $datereceived
 *
 * @property BranchMonthlyRevenue[] $branchMonthlyRevenues
 * @property Annualbudget $budget
 * @property Monthlyspecialcontributions $spcontribution
 */
class Monthlyincome extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monthlyincome';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['budgetID', 'month', 'receivedAmount'], 'required'],
            [['budgetID', 'receivedAmount'], 'integer'],
            [['datereceived'], 'safe'],
            [['month'], 'string', 'max' => 10],
            [['budgetID', 'month'], 'unique', 'targetAttribute' => ['budgetID', 'month'],'message'=>'Income already acquired for this month, choose a different month or update the existing one'],
            [['budgetID'], 'exist', 'skipOnError' => true, 'targetClass' => Annualbudget::className(), 'targetAttribute' => ['budgetID' => 'budgetID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'incomeID' => 'Income ID',
            'budgetID' => 'Budget ID',
            'month' => 'Month',
            'receivedAmount' => 'Received Amount',
            'datereceived' => 'Datereceived',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert==true)
        {
            date_default_timezone_set('Africa/Dar_es_Salaam');
            $this->datereceived=date('Y-m-d H:i:s');

            

        }
        return parent::beforeSave($insert);
    }
    public function afterSave($insert,$changedAttributes)
    {
        if($insert==true)
        {
            $branchbudgets=yii::$app->session->get("financialYear")->annualbudget->branchAnnualBudgets;
            if($branchbudgets==null){throw new \Exception('No branch budgets found !');}
            foreach($branchbudgets as $branchbudget)
            {
                if($branchbudget==null){continue;}
                if($branchbudget->acquireRevenue($this))
                {
                    continue;
                }
                else
                {
                    $this->delete();
                    throw new \Exception("could not distribute income".Html::errorSummary($branchbudget));
                 
                }
            }
        }

        return parent::afterSave($insert,$changedAttributes);
    }

    /**
     * Gets query for [[BranchMonthlyRevenues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranchMonthlyRevenues()
    {
        return $this->hasMany(BranchMonthlyRevenue::className(), ['incomeID' => 'incomeID']);
    }
    public function getSpcontribution()
    {
        return $this->hasOne(Monthlyspecialcontributions::className(), ['income' => 'incomeID']);
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

    public function getTotalIncome($budget)
    {
       $income=$this->find()->where(['budgetID'=>$budget])->sum('receivedAmount');

       if($income==null){return 0;}

       return $income;
    }
    public function getSpcontribTotal()
    {
        $contrib=$this->spcontribution;
        if($contrib==null){return 0.00;}
    
        $total=$contrib->IndividualAmount*$contrib->NoMembers;
        

        return $total;


    }
    public function branchReturnsTotal()
    {
      return ($this->receivedAmount/2)-$this->getSpcontribTotal();
    }
    public function getTotalReturns()
    {
        $branchrevenues=$this->branchMonthlyRevenues;
        $totalrevenues=0;
        if($branchrevenues==null){return 0;}

        foreach($branchrevenues as  $branchrevenue)
        {
            if($branchrevenue->branchbudget0->branch0->isHQ()){continue;}
            $totalrevenues+=$branchrevenue->received_amount;
        }

        return $totalrevenues;
    }

    
}
