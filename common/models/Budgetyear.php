<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "budgetyear".
 *
 * @property int $yearID
 * @property string|null $startingdate
 * @property string $endingdate
 * @property string $title
 * @property int $contributionfactor
 * @property string $status
 * @property string $operationstatus
 *
 * @property Annualbudget[] $annualbudgets
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
            [['startingdate', 'endingdate'], 'safe'],
            [['endingdate', 'title', 'contributionfactor'], 'required'],
            [['contributionfactor'], 'integer'],
            [['title'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'yearID' => 'Year ID',
            'startingdate' => 'Startingdate',
            'endingdate' => 'Endingdate',
            'title' => 'Title',
            'contributionfactor' => 'Contributionfactor',
        ];
    }

    /**
     * Gets query for [[Annualbudgets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnnualbudgets()
    {
        return $this->hasMany(Annualbudget::className(), ['yearID' => 'yearID']);
    }

  public function getBudgetYear()
  {
    return $this->find()->where(['status'=>'ongoing'])->one();
  }
}
