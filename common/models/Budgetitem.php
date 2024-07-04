<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "budgetitem".
 *
 * @property string $name
 * @property string $code
 * @property string|null $createdAt
 * @property string|null $updatedAt
 *
 * @property Budgetprojections[] $budgetprojections
 * @property BranchAnnualBudget[] $branchbudgets
 */
class Budgetitem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'budgetitem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'code' => 'Code',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Budgetprojections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBudgetprojections()
    {
        return $this->hasMany(Budgetprojections::className(), ['budgetItem' => 'name']);
    }

    /**
     * Gets query for [[Branchbudgets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranchbudgets()
    {
        return $this->hasMany(BranchAnnualBudget::className(), ['bbID' => 'branchbudget'])->viaTable('budgetprojections', ['budgetItem' => 'name']);
    }
    public function getLabel()
    {
        return '[ '.$this->code.' ]'.$this->name;
    }
}
