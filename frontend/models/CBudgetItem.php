<?php

namespace frontend\models;

use common\models\Budgetprojections;
use common\models\Costcenterprojection;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\BranchAnnualBudget;

class CBudgetItem extends Model
{
   public $projID;  
   public  $budgetItem;
   public  $projected_amount;
   public $objective;
    public function rules()
    {
        return [
            [['budgetItem', 'projected_amount','objective'], 'required'],
            [['projected_amount','objective'], 'integer'],
            [['budgetItem'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'budgetItem' => 'Budget Item',
            'projected_amount' => 'Projected Amount',
        ];
    }

    public function saveItem($center)
    {
        if(!$this->validate())
        {
            return false;
        }

        $transaction=yii::$app->db->beginTransaction();

        try{

            $projection=new Budgetprojections();
            $ccprojection=new Costcenterprojection();
            $projection->budgetItem=$this->budgetItem;
            $projection->projected_amount=$this->projected_amount;
            $projection->branchbudget=(new BranchAnnualBudget)->getCurrentBudget()->bbID;

            if(!$projection->save())
            {
                throw new Exception('Unable to save Budget Item '.Html::errorSummary($projection));
            }

            $ccprojection->costcenter=$center;
            $ccprojection->projection=$projection->projID;
            $ccprojection->objective=$this->objective;

            if(!$ccprojection->save())
            {
                throw new Exception('Could not save budget item as a cost center projection '.Html::errorSummary($ccprojection));
            }

            $transaction->commit();
            $this->projID=$projection->projID;
            return $this;

        }
        catch(Exception $t)
        {
         $transaction->rollBack();
         throw $t;
        }
    }


}
