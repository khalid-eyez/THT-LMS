<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "costcenter".
 *
 * @property int $centerID
 * @property string $name
 * @property int $branch
 * @property int $authority
 *
 * @property Branch $branch0
 * @property User $authority0
 * @property Costcenterprojection[] $costcenterprojections
 * @property Costcenterrevenue[] $costcenterrevenues
 */
class Costcenter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'costcenter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'branch', 'authority'], 'required'],
            [['branch', 'authority'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['branch'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch' => 'branchID']],
            [['authority'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['authority' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'centerID' => 'Center ID',
            'name' => 'Name',
            'branch' => 'Branch',
            'authority' => 'Authority',
        ];
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
        return $this->hasOne(User::className(), ['id' => 'authority']);
    }

    /**
     * Gets query for [[Costcenterprojections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCostcenterprojections()
    {
        return $this->hasMany(Costcenterprojection::className(), ['costcenter' => 'centerID']);
    }
      /**
     * Gets query for [[Costcenterrevenues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCostcenterrevenues()
    {
        return $this->hasMany(Costcenterrevenue::className(), ['center' => 'centerID']);
    }
    public function getCurrentProjections()
    {
        $projections=$this->costcenterprojections;

        return array_filter($projections,function($projection){

            return $projection->projection0->branchbudget0->isCurrent();
        });
    }
    public function getYearProjections()
    {
        $projections=$this->costcenterprojections;
        $budgetyear=yii::$app->session->get("financialYear")->yearID; 
        return array_filter($projections,function($projection) use($budgetyear){
            
            return $projection->projection0->branchbudget0->budget->yearID==$budgetyear;
        });
    }

    public function getCenterBudget()
    {
        $budgetyear=yii::$app->session->get("financialYear");
        $branchbudgets=$budgetyear->annualbudget->branchAnnualBudgets;
        foreach($branchbudgets as $branchbudget)
        {
            if($branchbudget->branch==$this->branch)
            {
                return $branchbudget;
            }
        }
    }
    public function getYearRevenues()
    {
        $revenues=$this->costcenterrevenues;
        $budgetyear=yii::$app->session->get("financialYear")->yearID; 
        return array_filter($revenues,function($revenue) use($budgetyear){
            
            return $revenue->budget0->budget->yearID==$budgetyear;
        });
    }
    public function totalProjection()
    {
        $total=0;
        $centerprojections=$this->getYearProjections();
        if($centerprojections==null)
        {
            return 0;
        }

        foreach($centerprojections as $proj)
        {
            $total+=$proj->projection0->projected_amount;
        }

        return $total;
    }
    public function allocatedBudget()
    {
        $total=0;
        $centerprojections=$this->getYearProjections();
        if($centerprojections==null)
        {
            return 0;
        }

        foreach($centerprojections as $proj)
        {
            $total+=$proj->projection0->allocated();
        }

        return $total;
    }

    public function currentBudget()
    {
        $total=0;
        $revenues=$this->getYearRevenues();
        if($revenues==null){return 0;}

        foreach($revenues as $revenue)
        {
            $total+=$revenue->amount;
        }

        return $total;
    }
    public function deficit()
    {
        return $this->totalProjection()-$this->currentBudget();
    }

    public function totalexpenses()
    {
        $projections=$this->getYearProjections();
        $total=0;
        if($projections==null){return 0;}

        foreach($projections as $projection)
        {
           $total+=$projection->projection0->getTotalExpenses();
        }
        return $total;
    }
    public function balance()
    {
        return $this->currentBudget()-$this->totalexpenses();
    }

    public function acquireBudget($buffer,$budget)
    {
        try
        {
            date_default_timezone_set('Africa/Dar_es_Salaam');
            $transaction=yii::$app->db->beginTransaction();
            foreach($buffer as $index=>$budge)
            {
                if($index=="_csrf-frontend")
                {
                    continue;
                }
                if($budge==null){continue;}
                $receivablemodel=new Costcenterrevenue();
                $receivablemodel->center=$index;
                $center=$this->find()->where(['centerID'=>$index])->one();
                if(($center->currentBudget()+$budge)>$center->totalProjection())
                {
                    throw new \Exception("Total allocation greater than projection on \"".$center->name." Center\" You might need to review projections first");  
                }
                $receivablemodel->amount=$budge;
                $receivablemodel->center=$index;
                $receivablemodel->budget=$budget;
                $receivablemodel->datereceived=date("Y-m-d H:i:s");
                if(!$receivablemodel->save())
                {
                    throw new \Exception("Could not save budget allocations, try again later ! ".Html::errorSummary($receivablemodel));
                }

            }
            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function updateBudget($buffer,$budget)
    {
        try
        {
            date_default_timezone_set('Africa/Dar_es_Salaam');
            $transaction=yii::$app->db->beginTransaction();
            foreach($buffer as $index=>$budge)
            {
                if($index=="_csrf-frontend")
                {
                    continue;
                }
                if($budge==null){continue;}
                $receivablemodel=new Costcenterrevenue();
                $receivablemodel->center=$index;
                $center=$this->find()->where(['centerID'=>$index])->one();
                if(($center->currentBudget()+$budge)>$center->totalProjection())
                {
                    throw new \Exception("Total allocation greater than projection on \"".$center->name." Center\" You might need to review projections first");  
                }
                $receivablemodel->amount=$budge;
                $receivablemodel->center=$index;
                $receivablemodel->budget=$budget;
                $receivablemodel->datereceived=date("Y-m-d H:i:s");
                if(!$receivablemodel->save())
                {
                    throw new \Exception("Could not save budget allocations, try again later ! ".Html::errorSummary($receivablemodel));
                }

            }
            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function unallocated()
    {
        return $this->currentBudget()-$this->allocatedBudget();
    }
}
