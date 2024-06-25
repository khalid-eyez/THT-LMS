<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "costcenterrevenue".
 *
 * @property int $ccrID
 * @property int $budget
 * @property int $center
 * @property int $amount
 * @property string|null $datereceived
 *
 * @property BranchAnnualBudget $budget0
 * @property Costcenter $center0
 * @property Receivabletransactions[] $receivabletransactions
 */
class Costcenterrevenue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'costcenterrevenue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['budget', 'center', 'amount'], 'required'],
            [['budget', 'center', 'amount'], 'integer'],
            [['datereceived'], 'safe'],
            [['budget'], 'exist', 'skipOnError' => true, 'targetClass' => BranchAnnualBudget::className(), 'targetAttribute' => ['budget' => 'bbID']],
            [['center'], 'exist', 'skipOnError' => true, 'targetClass' => Costcenter::className(), 'targetAttribute' => ['center' => 'centerID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ccrID' => 'Ccr ID',
            'budget' => 'Budget',
            'center' => 'Center',
            'amount' => 'Amount',
            'datereceived' => 'Datereceived',
        ];
    }

    /**
     * Gets query for [[Budget0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBudget0()
    {
        return $this->hasOne(BranchAnnualBudget::className(), ['bbID' => 'budget']);
    }

    /**
     * Gets query for [[Center0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCenter0()
    {
        return $this->hasOne(Costcenter::className(), ['centerID' => 'center']);
    }

    /**
     * Gets query for [[Receivabletransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceivabletransactions()
    {
        return $this->hasMany(Receivabletransactions::className(), ['centerrevenue' => 'ccrID']);
    }
}
