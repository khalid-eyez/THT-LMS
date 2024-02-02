<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meetingreferences".
 *
 * @property int $mrefID
 * @property int $refID
 * @property int $referenceName
 *
 * @property Meetingnames $referenceName0
 * @property Referenceprefixes $ref
 */
class Meetingreferences extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetingreferences';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['refID', 'referenceName'], 'required'],
            [['refID', 'referenceName'], 'integer'],
            [['referenceName'], 'exist', 'skipOnError' => true, 'targetClass' => Meetingnames::className(), 'targetAttribute' => ['referenceName' => 'typeID']],
            [['refID'], 'exist', 'skipOnError' => true, 'targetClass' => Referenceprefixes::className(), 'targetAttribute' => ['refID' => 'prefID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mrefID' => 'Mref ID',
            'refID' => 'Ref ID',
            'referenceName' => 'Reference Name',
        ];
    }

    /**
     * Gets query for [[ReferenceName0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferenceName0()
    {
        return $this->hasOne(Meetingnames::className(), ['typeID' => 'referenceName']);
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
         if($name==null){return null;}
        return $name->referenceName0->name;
    }
}
