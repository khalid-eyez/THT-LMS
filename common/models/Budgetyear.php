<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "budgetyear".
 *
 * @property int $yearID
 * @property string|null $startingyear
 * @property string $endingyear
 * @property int $contributionfactor
 * @property string $title
 * @property string $operationstatus
 *
 * @property Annualbudget $annualbudget
 */
class Budgetyear extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'budgetyear';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['startingyear', 'endingyear'], 'safe'],
            [['endingyear', 'title'], 'required'],
            [['title'], 'string', 'max' => 15],
            ['contributionfactor','default','value'=>1]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'yearID' => 'Year ID',
            'startingyear' => 'Starting year',
            'endingyear' => 'Ending year',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Annualbudgets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnnualbudget()
    {
        return $this->hasOne(Annualbudget::className(), ['yearID' => 'yearID']);
    }

  public function getBudgetYear()
  {
    return $this->find()->where(['operationstatus'=>'open'])->one();
  }
}
