<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "targets".
 *
 * @property int $targetID
 * @property string|null $code
 * @property string $description
 * @property int $strategy
 * @property string|null $createdAt
 * @property string|null $updatedAt
 *
 * @property Objectives[] $objectives
 * @property Strategies $strategy0
 */
class Targets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'targets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'strategy'], 'required'],
            [['description'], 'string'],
            [['strategy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['code'], 'string', 'max' => 100],
            [['strategy'], 'exist', 'skipOnError' => true, 'targetClass' => Strategies::className(), 'targetAttribute' => ['strategy' => 'strID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'targetID' => 'Target ID',
            'code' => 'Code',
            'description' => 'Description',
            'strategy' => 'Strategy',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Objectives]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjectives()
    {
        return $this->hasMany(Objectives::className(), ['target' => 'targetID']);
    }

    /**
     * Gets query for [[Strategy0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStrategy0()
    {
        return $this->hasOne(Strategies::className(), ['strID' => 'strategy']);
    }
}
