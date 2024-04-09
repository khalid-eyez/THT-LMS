<?php

namespace common\models;
use yii\helpers\Html;
use Yii;

/**
 * This is the model class for table "meetingcancel".
 *
 * @property int $cancelID
 * @property int $meetingID
 * @property string $type
 * @property int $memberID
 * @property string|null $reason
 * @property int|null $fileID
 * @property string|null $canceltime
 * @property string $status
 *
 * @property Meeting $meeting
 * @property User $user
 * @property Files $file
 */
class Meetingcancel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetingcancel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetingID', 'type', 'memberID', 'status'], 'required'],
            [['meetingID', 'memberID', 'fileID'], 'integer'],
            [['canceltime'], 'safe'],
            [['type', 'status'], 'string', 'max' => 25],
            [['reason'], 'string', 'max' => 255],
            [['meetingID'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meetingID' => 'meetingID']],
            [['memberID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['memberID' => 'id']],
            [['fileID'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['fileID' => 'fileID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cancelID' => 'Cancel ID',
            'meetingID' => 'Meeting ID',
            'type' => 'Type',
            'memberID' => 'Member ID',
            'reason' => 'Reason',
            'fileID' => 'File ID',
            'canceltime' => 'Canceltime',
            'status' => 'Status',
        ];
    }

    public function beforeSave($insert)
    {

        date_default_timezone_set('Africa/Dar_es_Salaam');
        $this->canceltime=date("Y-m-d H:i:s");

        //check if confirmation exists
        if($this->type=="participationCancel")
        {
         if((new MeetingConfirmations)->isConfirmed($this->memberID,$this->meetingID))
         {
            $uncof=(new MeetingConfirmations)->unConfirmParticipation($this->memberID,$this->meetingID); 
         }
        }

      
        return parent::beforeSave($insert);
    }
    public function afterSave($insert,$changedAttributes)
    {
        if($this->type=="meetingCancel")
        {
        $meeting=$this->meeting;
        $meeting->status="cancelled";
        $meeting->save();
        }
        
        
        return parent::afterSave($insert,$changedAttributes);
    }
    public function beforeDelete()
    {
        $meeting=$this->meeting;
        if($this->type=="meetingCancel")
        {
            $meeting->status="Upcoming";
            $meeting->save(); 
        }
        return parent::beforeDelete();
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

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Files::className(), ['fileID' => 'fileID']);
    }
    public function isCancelled($meeting)
    {
        $cancel=$this->find()->where(['meetingID'=>$meeting,'type'=>'meetingCancel'])->one();
        
        return $cancel!=null;
    }
    public function isParticipationCancelled($meeting,$member)
    {
        $cancel=$this->find()->where(['meetingID'=>$meeting,'memberID'=>$member,'type'=>'participationCancel'])->one();
        
        return $cancel!=null;
    }
    public function uncancel($meeting)
    {
        $cancel=$this->find()->where(['meetingID'=>$meeting,'type'=>'meetingCancel'])->one();
        if($cancel->delete())
        {
            return true;
        }
        return false;
    }
    public function uncancelParticipation($meeting,$member)
    {
        $member=yii::$app->user->identity->id;
        $cancel=$this->find()->where(['meetingID'=>$meeting,'memberID'=>$member,'type'=>'participationCancel'])->one();

        if($cancel->delete())
        {
            return true;
        }
        return false;
    }
    public function getCancelReason($meeting)
    {
      $cancel=$this->find()->where(['meetingID'=>$meeting,'type'=>'meetingCancel'])->one();
      if($cancel->reason!=null)
      {
        return $cancel->reason;
      }

      return null;
    }
    public function getParticipationCancel($meeting,$member)
    {
        $cancel=$this->find()->where(['meetingID'=>$meeting,'memberID'=>$member,'type'=>'participationCancel'])->one();

        return $cancel;
    }
  
}
