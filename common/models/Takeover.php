<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "takeover".
 *
 * @property int $tvID
 * @property int $budget
 * @property int $amount
 *
 * @property BranchAnnualBudget $budget0
 */
class Takeover extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'takeover';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['budget', 'amount'], 'required'],
            [['budget', 'amount'], 'integer'],
            [['budget'], 'exist', 'skipOnError' => true, 'targetClass' => BranchAnnualBudget::className(), 'targetAttribute' => ['budget' => 'bbID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tvID' => 'Tv ID',
            'budget' => 'Budget',
            'amount' => 'Amount',
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
