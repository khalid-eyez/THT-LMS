<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "budgetprojections".
 *
 * @property int $projID
 * @property string $budgetItem
 * @property int $projected_amount
 * @property int $branchbudget
 * @property string|null $status
 *
 * @property BranchAnnualBudget $branchbudget0
 * @property Itemizedprojections[] $itemizedprojections
 * @property Receivabletransactions[] $receivabletransactions
 */
class Budgetprojections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'budgetprojections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['budgetItem', 'projected_amount', 'branchbudget'], 'required'],
            [['projected_amount', 'branchbudget'], 'integer'],
            [['budgetItem'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 15],
            [['budgetItem', 'branchbudget'], 'unique', 'targetAttribute' => ['branchbudget', 'budgetItem'],'message'=>'Budget Item Already Exists'],
            [['branchbudget'], 'exist', 'skipOnError' => true, 'targetClass' => BranchAnnualBudget::className(), 'targetAttribute' => ['branchbudget' => 'bbID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'projID' => 'Proj ID',
            'budgetItem' => 'Budget Item',
            'projected_amount' => 'Projected Amount',
            'branchbudget' => 'Branchbudget',
            'status' => 'Status',
        ];
    }
    public function beforeSave($insert)
    {
        $budget=BranchAnnualBudget::findOne($this->branchbudget);

        if($budget->unplanned()<$this->projected_amount)
        {
            throw new \Exception("Current allocation goes beyond expected Income, Review your budget allocations");
        }
        return parent::beforeSave($insert);
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

    /**
     * Gets query for [[Itemizedprojections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemizedprojections()
    {
        return $this->hasMany(Itemizedprojections::className(), ['projID' => 'projID']);
    }

    /**
     * Gets query for [[Receivabletransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceivabletransactions()
    {
        return $this->hasMany(Receivabletransactions::className(), ['projID' => 'projID']);
    }
    public function getTotalExpenses()
    {
        $total=0;
        if($this->itemizedprojections==null){ return 0;}
        foreach($this->itemizedprojections as $item)
        {
          $total+=$item->getTotalExpenses();
        }

        return $total;
    }

    public function projected()
    {
        $itemprojs=$this->itemizedprojections;
        $total=0;
        if($itemprojs==null){return 0;}

        foreach($itemprojs as $itemproj)
        {
            $total+=$itemproj->totalcost;
        }

        return $total;


    }
    public function allocated()
    {
       $receivables=$this->receivabletransactions;
       $total=0;
       if($receivables==null) 
       {
        return 0;
       }

       foreach($receivables as $receivable)
       {
        $total+=$receivable->receivedamount;
       }

       return $total;
    }
    public function acquireItems($items)
    {
        try
        {
        $transaction=yii::$app->db->beginTransaction();
        if(count($items)<2){throw new \Exception("Please fill out all fields ! ");}
        foreach($items as $index=>$item)
        {
            if($index=="_csrf-frontend"){continue;}
            $itemzmodel=new Itemizedprojections;
            $itemzmodel->itemName=$item[0];
            $itemzmodel->unit=$item[1];
            $itemzmodel->unitcost=$item[2];
            $itemzmodel->numUnits=$item[3];
            $itemzmodel->totalcost=$item[4];
            $itemzmodel->projID=$this->projID;

            if(!$itemzmodel->save())
            {
               throw new \Exception("\r\n Fatal error on item '".$item[0]."'! \r\n".Html::errorSummary($itemzmodel)."\r\n You can still plan your budget later");
            }
        }
        $transaction->commit();
        return true;
    }
    catch(\Exception $i)
    {
        $transaction->rollBack();
        throw $i;

    }
    }
    public function Unallocated()
    {
        return $this->projected_amount-$this->projected();
    }

    public function deficit()
    {
        $deficit=$this->allocated()-$this->projected_amount;
        return ($deficit>0)?0:$deficit;
    }
    public function balance()
    {
        return $this->allocated()-$this->getTotalExpenses();
    }

    public function acquireBudget($budget)
    {
        try
        {
            date_default_timezone_set('Africa/Dar_es_Salaam');
            $transaction=yii::$app->db->beginTransaction();
            foreach($budget as $index=>$budge)
            {
                if($index=="_csrf-frontend")
                {
                    continue;
                }
                if($budge==null){continue;}
                $receivablemodel=new Receivabletransactions;
                $receivablemodel->projID=$index;
                $receivablemodel->receivedamount=$budge;
                $receivablemodel->authority=yii::$app->user->identity->member->memberID;
                $receivablemodel->datereceived=date("Y-m-d H:i:s");
                if(!$receivablemodel->save())
                {
                    throw new \Exception("Could not save budget allocations, try again later !");
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
}
