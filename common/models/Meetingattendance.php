<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meetingattendance".
 *
 * @property int $attendanceID
 * @property int|null $meetingID
 * @property int|null $memberID
 *
 * @property Meeting $meeting
 * @property Member $member
 */
class Meetingattendance extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetingattendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetingID', 'memberID'], 'integer'],
            [['meetingID'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meetingID' => 'meetingID']],
            [['memberID'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['memberID' => 'memberID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attendanceID' => 'Attendance ID',
            'meetingID' => 'Meeting ID',
            'memberID' => 'Member ID',
        ];
    }
    public function beforeSave($insert)
    {
        $exists=$this->find()->where(['meetingID'=>$this->meetingID,'memberID'=>$this->memberID])->one();
        if($exists!=null)
        {
            $exists->delete();
        }
        return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[Meeting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(Meeting::className(), ['meetingID' => 'meetingID']);
    }

    /**
     * Gets query for [[Member]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['memberID' => 'memberID']);
    }
    public function isAttended($memberID,$meeting)
    {
        $attendance=$this->find()->where(['memberID'=>$memberID,'meetingID'=>$meeting])->one();
        return $attendance!=null;
    }
}
