<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goals".
 *
 * @property int $goalID
 * @property string|null $code
 * @property string $description
 * @property string|null $createdAt
 * @property string|null $updatedAt
 *
 * @property Strategies[] $strategies
 */
class Goals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'goalID' => 'Goal ID',
            'code' => 'Code',
            'description' => 'Description',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Strategies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStrategies()
    {
        return $this->hasMany(Strategies::className(), ['goal' => 'goalID']);
    }
}
