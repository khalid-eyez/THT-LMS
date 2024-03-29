<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branchotherincomes".
 *
 * @property int $incomeID
 * @property string $incomeType
 * @property float $amount
 * @property string $month
 * @property int $budget
 *
 * @property BranchAnnualBudget $budget0
 */
class Branchotherincomes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branchotherincomes';
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
            [['budget'], 'exist', 'skipOnError' => true, 'targetClass' => BranchAnnualBudget::className(), 'targetAttribute' => ['budget' => 'bbID']],
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
        return $this->hasOne(BranchAnnualBudget::className(), ['bbID' => 'budget']);
    }
}
