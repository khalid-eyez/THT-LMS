<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payabletransactions".
 *
 * @property int $transID
 * @property int $item
 * @property int|null $quantity
 * @property int $Amount
 * @property string|null $dateapplied
 * @property int|null $authority
 * @property string $reference
 *
 * @property Itemizedprojections $item0
 * @property Member $authority0
 */
class Payabletransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payabletransactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item'], 'required'],
            [['item', 'quantity', 'Amount', 'authority'], 'integer'],
            [['dateapplied'], 'safe'],
            ['reference','string','max'=>20],
            [['item'], 'exist', 'skipOnError' => true, 'targetClass' => Itemizedprojections::className(), 'targetAttribute' => ['item' => 'ipID']],
            [['authority'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['authority' => 'memberID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transID' => 'Trans ID',
            'item' => 'Item',
            'quantity' => 'Quantity',
            'Amount' => 'Amount',
            'dateapplied' => 'Dateapplied',
            'authority' => 'Authority',
        ];
    }
    public function beforeSave($insert)
    {
      if($insert==true)
      {
      try
      {
     
      date_default_timezone_set('Africa/Dar_es_Salaam');
      $this->dateapplied=date("Y-m-d H:i:s");
      $item=Itemizedprojections::findOne($this->item);
      $this->Amount=$this->quantity*$item->unitcost;
      if($item->available()<$this->Amount)
      {
        throw new \Exception("Budget not sufficient for this payment");
      }
      if($item->availableunits()<$this->quantity)
      {
        throw new \Exception("Quantity greater than available number of units");
      }
      
      $suffix=rand(1,999).$this->transID;
      $suffix=str_pad($suffix, 4, "0", STR_PAD_LEFT);
      $this->reference="THTU".$suffix;
      return parent::beforeSave($insert);
    }
      catch(\Exception $p)
      {
        throw $p;
      }
    }

    return parent::beforeSave($insert);
      
    }
    

    /**
     * Gets query for [[Item0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem0()
    {
        return $this->hasOne(Itemizedprojections::className(), ['ipID' => 'item']);
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
    public function status()
    {
        /* closed for the time being
        if($this->authority==null)
        {
            return "pending";
        }*/ 
        return "Authorized";
    }
    public function reference()
    {
      return $this->reference.$this->transID;
    }
   
}
