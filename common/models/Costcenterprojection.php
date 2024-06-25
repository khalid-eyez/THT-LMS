<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "costcenterprojection".
 *
 * @property int $projID
 * @property int $projection
 * @property int $costcenter
 * @property int $objective
 *
 * @property Budgetprojections $projection0
 * @property Costcenter $costcenter0
 * @property Objectives $objective0
 */
class Costcenterprojection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'costcenterprojection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['projection', 'costcenter', 'objective'], 'required'],
            [['projection', 'costcenter', 'objective'], 'integer'],
            [['projection'], 'exist', 'skipOnError' => true, 'targetClass' => Budgetprojections::className(), 'targetAttribute' => ['projection' => 'projID']],
            [['costcenter'], 'exist', 'skipOnError' => true, 'targetClass' => Costcenter::className(), 'targetAttribute' => ['costcenter' => 'centerID']],
            [['objective'], 'exist', 'skipOnError' => true, 'targetClass' => Objectives::className(), 'targetAttribute' => ['objective' => 'objID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'projID' => 'Proj ID',
            'projection' => 'Projection',
            'costcenter' => 'Costcenter',
            'objective' => 'Objective',
        ];
    }

    /**
     * Gets query for [[Projection0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjection0()
    {
        return $this->hasOne(Budgetprojections::className(), ['projID' => 'projection']);
    }

    /**
     * Gets query for [[Costcenter0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCostcenter0()
    {
        return $this->hasOne(Costcenter::className(), ['centerID' => 'costcenter']);
    }

    /**
     * Gets query for [[Objective0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjective0()
    {
        return $this->hasOne(Objectives::className(), ['objID' => 'objective']);
    }
}
