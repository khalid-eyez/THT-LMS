<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "itemizedprojections".
 *
 * @property int $ipID
 * @property string $itemName
 * @property string $unit
 * @property int $unitcost
 * @property int $numUnits
 * @property int $totalcost
 * @property int $projID
 *
 * @property Budgetprojections $proj
 * @property Payabletransactions[] $payabletransactions
 */
class Itemizedprojections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'itemizedprojections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemName', 'unitcost', 'numUnits','unit', 'totalcost', 'projID'], 'required'],
            [['unitcost', 'numUnits', 'totalcost', 'projID'], 'integer'],
            [['itemName'], 'string', 'max' => 200],
            [['itemName', 'projID'], 'unique', 'targetAttribute' => ['itemName', 'projID'],'message'=>'Item Already Exists'],
            [['unit'], 'string', 'max' => 50],
            [['projID'], 'exist', 'skipOnError' => true, 'targetClass' => Budgetprojections::className(), 'targetAttribute' => ['projID' => 'projID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ipID' => 'Ip ID',
            'itemName' => 'Item Name',
            'unitcost' => 'Unitcost',
            'numUnits' => 'Num Units',
            'totalcost' => 'Totalcost',
            'projID' => 'Proj ID',
        ];
    }

    public function beforeDelete()
    {
        if($this->payabletransactions!=null)
        {
            throw new \Exception("Payments exist for this item ! Consider reviewing the budget item structure instead.");
        }
        return parent::beforeDelete();
    }
    public function beforeSave($insert)
    {
        if($insert==true)
        {
        $unallocated=Budgetprojections::findOne($this->projID)->Unallocated();
        if($this->totalcost>$unallocated){throw new \Exception("Allocation greater than projected budget");}
        }

        return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[Proj]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProj()
    {
        return $this->hasOne(Budgetprojections::className(), ['projID' => 'projID']);
    }

    /**
     * Gets query for [[Payabletransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayabletransactions()
    {
        return $this->hasMany(Payabletransactions::className(), ['item' => 'ipID']);
    }

    public function getTotalExpenses()
    {
        $total=0;
        if($this->payabletransactions==null){ return 0;}
        foreach($this->payabletransactions as $trans)
        {
          $total+=$trans->Amount;
        }

        return $total;
    }
    public function balance()
    {
        return $this->totalcost-$this->getTotalExpenses();
    }

    public function payedunits()
    {
        $total=0;
        $payables=$this->payabletransactions;
        if($payables==null){return 0;}

        foreach($payables as $payable)
        {
            $total+=$payable->quantity;
        }
        return $total;
    }

    public function availableunits()
    {
        return $this->numUnits-$this->payedunits();
    }
    
    

   
}
