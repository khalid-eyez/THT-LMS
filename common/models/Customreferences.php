<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customreferences".
 *
 * @property int $crefID
 * @property int $refID
 * @property string $referenceName
 *
 * @property Referenceprefixes $ref
 */
class Customreferences extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customreferences';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['refID', 'referenceName'], 'required'],
            [['refID'], 'integer'],
            [['referenceName'], 'string', 'max' => 30],
            [['refID'], 'exist', 'skipOnError' => true, 'targetClass' => Referenceprefixes::className(), 'targetAttribute' => ['refID' => 'prefID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'crefID' => 'Cref ID',
            'refID' => 'Ref ID',
            'referenceName' => 'Reference Name',
        ];
    }

    /**
     * Gets query for [[Ref]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRef()
    {
        return $this->hasOne(Referenceprefixes::className(), ['prefID' => 'refID']);
    }
    public function getName($refpref)
    {
        $name=$this->find()->where(['refID'=>$refpref])->one();

        return $name->referenceName;
    }
}
