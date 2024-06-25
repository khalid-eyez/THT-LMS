<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "objectives".
 *
 * @property int $objID
 * @property string|null $code
 * @property string $description
 * @property string|null $createdAt
 * @property string|null $updatedAt
 *
 * @property Costcenterprojection[] $costcenterprojections
 */
class Objectives extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'objectives';
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
            'objID' => 'Obj ID',
            'code' => 'Code',
            'description' => 'Description',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Costcenterprojections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCostcenterprojections()
    {
        return $this->hasMany(Costcenterprojection::className(), ['objective' => 'objID']);
    }
}
