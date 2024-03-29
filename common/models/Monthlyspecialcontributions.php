<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "monthlyspecialcontributions".
 *
 * @property int $contribID
 * @property string $contribType
 * @property float $IndividualAmount
 * @property int $NoMembers
 * @property int $income
 *
 * @property Monthlyincome $income0
 */
class Monthlyspecialcontributions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monthlyspecialcontributions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contribType', 'income'], 'required'],
            [['IndividualAmount'], 'number'],
            [['NoMembers', 'income'], 'integer'],
            [['contribType'], 'string', 'max' => 100],
            [['income'], 'exist', 'skipOnError' => true, 'targetClass' => Monthlyincome::className(), 'targetAttribute' => ['income' => 'incomeID']],
        ];
    }
    // public function afterSave($insert, $changedAttributes){

    //     $item=$this->contribType;
    //     $amount=$this->IndividualAmount*$this->NoMembers;
    //         $project=new Budgetprojections;
    //         $project->budgetItem=$item;
    //         $project->projected_amount=$amount;
    //         $project->branchbudget=(new Branch)->getHQ()->branchBudget->bbID;
    //         $project->save();
        
 
   

    //     return parent::afterSave($insert,$changedAttributes);
    // }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contribID' => 'Contrib ID',
            'contribType' => 'Contrib Type',
            'IndividualAmount' => 'Individual Amount',
            'NoMembers' => 'No Members',
            'income' => 'Income',
        ];
    }

    /**
     * Gets query for [[Income0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncome0()
    {
        return $this->hasOne(Monthlyincome::className(), ['incomeID' => 'income']);
    }
}
