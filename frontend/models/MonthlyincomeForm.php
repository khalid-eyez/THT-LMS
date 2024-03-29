<?php

namespace frontend\models;
use yii\helpers\Html;
use Yii;
use yii\base\Model;
use common\models\Monthlyincome;
use common\models\Annualbudget;
use common\models\Monthlyspecialcontributions;
use common\models\Member;
class MonthlyincomeForm extends Model
{
    public $month;
    public $receivedAmount;
    public $contributionType;
    public $individualAmount;
    public $incomeID;
    
    public function rules()
    {
        return [
            [['month', 'receivedAmount'], 'required'],
            [['month'], 'string', 'max' => 10],
            [['contributionType'], 'string', 'max' => 100],
            [['receivedAmount'], 'integer'],
            [['individualAmount'], 'integer']
        ];
    }
    public function acquireIncome()
    {
        if(!$this->validate())
        {
            return false;
        }

        $transaction=yii::$app->db->beginTransaction();
        
        try
        {
        $monthlyincome=new Monthlyincome;
        $monthlyincome->receivedAmount=$this->receivedAmount;
        $monthlyincome->month=$this->month;
        $monthlyincome->budgetID=yii::$app->session->get("financialYear")->annualbudget->budgetID;
        
        if(!$monthlyincome->save())
        {
            throw new \Exception("Could not record monthly income !".Html::errorSummary($monthlyincome));
        }
        if($this->contributionType!=null & $this->individualAmount!=null)
        {
        $spcontrib=new Monthlyspecialcontributions;
        $spcontrib->IndividualAmount=$this->individualAmount;
        $spcontrib->contribType=$this->contributionType;
        $spcontrib->NoMembers=(new Member)->totalMembers();
        $spcontrib->income=$monthlyincome->incomeID;

        if(!$spcontrib->save())
        {
            throw new \Exception("Could not record monthly special contribution !");   
        }
        }
        $transaction->commit();
        $this->incomeID=$monthlyincome->incomeID;
        return true;
        }catch(\Exception $e)
        {
          $transaction->rollBack();
          throw $e;
        }

    }
    
}
