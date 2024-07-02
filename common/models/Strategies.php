<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "strategies".
 *
 * @property int $strID
 * @property string|null $code
 * @property string $description
 * @property int $goal
 *
 * @property Goals $goal0
 * @property Targets[] $targets
 */
class Strategies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'strategies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'goal'], 'required'],
            [['description'], 'string'],
            [['goal'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['goal'], 'exist', 'skipOnError' => true, 'targetClass' => Goals::className(), 'targetAttribute' => ['goal' => 'goalID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'strID' => 'Str ID',
            'code' => 'Code',
            'description' => 'Description',
            'goal' => 'Goal',
        ];
    }

    /**
     * Gets query for [[Goal0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGoal0()
    {
        return $this->hasOne(Goals::className(), ['goalID' => 'goal']);
    }

    /**
     * Gets query for [[Targets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTargets()
    {
        return $this->hasMany(Targets::className(), ['strategy' => 'strID']);
    }
}
