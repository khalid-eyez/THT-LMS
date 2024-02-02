<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meetinginvitees".
 *
 * @property int $MI_ID
 * @property int|null $meetingID
 * @property int|null $memberID
 * @property string|null $dateInvited
 *
 * @property Meeting $meeting
 * @property Member $member
 */
class Meetinginvitees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetinginvitees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetingID', 'memberID'], 'integer'],
            [['dateInvited'], 'safe'],
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
            'MI_ID' => 'Mi ID',
            'meetingID' => 'Meeting ID',
            'memberID' => 'Member ID',
            'dateInvited' => 'Date Invited',
        ];
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
    public function getMemberDetails()
    {
        return $this->member->fullName()." -".$this->member->getRank();
    }
    public function getBranchName()
    {
        return $this->member->branch0->branchName;
    }
    public function inviteeExists()
    {
        $invitee=$this->find()->where(['meetingID'=>$this->meetingID,'memberID'=>$this->memberID])->one();
        return $invitee!=null;
    }
}
