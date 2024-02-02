<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meetingparticipants".
 *
 * @property int $participantID
 * @property int|null $typeID
 * @property string $participant
 *
 * @property Meetingnames $type
 * @property AuthItem $participant0
 */
class Meetingparticipants extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetingparticipants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['typeID'], 'integer'],
            [['participant'], 'required'],
            [['participant'], 'string', 'max' => 64],
            [['typeID', 'participant'], 'unique', 'targetAttribute' => ['typeID', 'participant']],
            [['typeID'], 'exist', 'skipOnError' => true, 'targetClass' => Meetingnames::className(), 'targetAttribute' => ['typeID' => 'typeID']],
            [['participant'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['participant' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'participantID' => 'Participant ID',
            'typeID' => 'Type ID',
            'participant' => 'Participant',
        ];
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Meetingnames::className(), ['typeID' => 'typeID']);
    }

    /**
     * Gets query for [[Participant0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParticipant0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'participant']);
    }
    public function isParticipant($type,$participant)
    {
        $find=$this->find()->where(['typeID'=>$type,'participant'=>$participant])->one();

        return $find!=null;
    }
}
