<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "otherincomes".
 *
 * @property int $incomeID
 * @property string $incomeType
 * @property float $amount
 * @property string $month
 * @property int $budget
 *
 * @property Annualbudget $budget0
 */
class Otherincomes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'otherincomes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['incomeType', 'amount', 'month', 'budget'], 'required'],
            [['amount'], 'number'],
            [['budget'], 'integer'],
            [['incomeType'], 'string', 'max' => 200],
            [['month'], 'string', 'max' => 10],
            [['budget'], 'exist', 'skipOnError' => true, 'targetClass' => Annualbudget::className(), 'targetAttribute' => ['budget' => 'budgetID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'incomeID' => 'Income ID',
            'incomeType' => 'Income Type',
            'amount' => 'Amount',
            'month' => 'Month',
            'budget' => 'Budget',
        ];
    }

    /**
     * Gets query for [[Budget0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBudget0()
    {
        return $this->hasOne(Annualbudget::className(), ['budgetID' => 'budget']);
    }
}
