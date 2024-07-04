<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "objectives".
 *
 * @property int $objID
 * @property string|null $code
 * @property string $description
 * @property int $target
 * @property string|null $createdAt
 * @property string|null $updatedAt
 *
 * @property Costcenterprojection[] $costcenterprojections
 * @property Targets $target0
 */
class Objectives extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'objectives';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string'],
            ['target','integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['code'], 'string', 'max' => 255],
            [['target'], 'exist', 'skipOnError' => true, 'targetClass' => Targets::className(), 'targetAttribute' => ['target' => 'targetID']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'objID' => 'Obj ID',
            'code' => 'Code',
            'description' => 'Description',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Costcenterprojections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCostcenterprojections()
    {
        return $this->hasMany(Costcenterprojection::className(), ['objective' => 'objID']);
    }
    public function getTarget0()
    {
        return $this->hasOne(Targets::className(),['targetID'=>'target']);
    }
    public function totalProjection()
    {
        $costcenterproj=$this->costcenterprojections;

        $total=0;

        if($costcenterproj==null)
        {
            return 0;
        }

        foreach($costcenterproj as $cproj)
        {
            $total+=$cproj->projection0->projected_amount;
        }

        return $total;
    }

    public function totalExpenses()
    {
        $costcenterproj=$this->costcenterprojections;

        $total=0;

        if($costcenterproj==null)
        {
            return 0;
        }
        foreach($costcenterproj as $cproj)
        {
            $total+=$cproj->projection0->getTotalExpenses();
        }

        return $total;
        
    }

    public function getCompletionstatus()
    {
        return ($this->totalExpenses()*100)/(($this->totalProjection()!=0)?$this->totalProjection():1);
    }
}
