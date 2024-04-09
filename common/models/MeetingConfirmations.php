<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meeting_confirmations".
 *
 * @property int $confID
 * @property int|null $meetingID
 * @property int|null $memberID
 * @property string|null $dateConfirmed
 *
 * @property Meeting $meeting
 * @property User $user
 */
class MeetingConfirmations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting_confirmations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetingID', 'memberID'], 'integer'],
            [['dateConfirmed'], 'safe'],
            [['meetingID'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meetingID' => 'meetingID']],
            [['memberID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['memberID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'confID' => 'Conf ID',
            'meetingID' => 'Meeting ID',
            'memberID' => 'Member ID',
            'dateConfirmed' => 'Date Confirmed',
        ];
    }
    public function beforeSave($insert)
    {
        if((new Meetingcancel)->isParticipationCancelled($this->meetingID,$this->memberID))
        {
            (new Meetingcancel)->uncancelParticipation($this->meetingID,$this->memberID); 
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'memberID']);
    }
    public function isConfirmed($memberID,$meeting)
    {
        $confirm=$this->find()->where(['memberID'=>$memberID,'meetingID'=>$meeting])->one();
        return $confirm!=null;
    }
    public function confirmParticipation($meeting)
    {
        if((Meeting::findOne($meeting))->isExpired())
        {
          throw new \Exception("Could not confirm participation of expired meeting !");
        }
        $member=yii::$app->user->identity->id;
         if(!$this->isConfirmed($member,$meeting))
         {
            $this->memberID=$member;
            $this->meetingID=$meeting;
            
            date_default_timezone_set('Africa/Dar_es_Salaam');
            $this->dateConfirmed=date("Y-m-d H:i:s");

            if($this->save()){return true;}else{return false;}
         }
         return true;
    }
    public function unConfirmParticipation($member,$meeting)
    {
      $confirmed=$this->find()->where(['meetingID'=>$meeting,'memberID'=>$member])->one();

      if($confirmed->delete())
      {
        return true;
      }
      return false;
    }
}
