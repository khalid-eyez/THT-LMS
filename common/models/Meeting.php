<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;

/**
 * This is the model class for table "meeting".
 *
 * @property int $meetingID
 * @property string $meetingTitle
 * @property string|null $description
 * @property int $type
 * @property string $meetingTime
 * @property string $venue
 * @property int|null $announcedBy
 * @property string|null $dateAnnounced
 * @property int $announcedFrom
 * @property int|null $duration
 * @property string|null $status
 *
 * @property User $announcedBy0
 * @property Meetingnames $type0
 * @property Branch $announcedFrom0
 * @property MeetingConfirmations[] $meetingConfirmations
 * @property Meetingattendance[] $meetingattendances
 * @property Meetingdocuments[] $meetingdocuments
 * @property Meetinginvitees[] $meetinginvitees
 */
class Meeting extends \yii\db\ActiveRecord
{

    public $date;
    public $time;
    public $invited=[];
    public $attendances=[];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetingTitle', 'type','date','time', 'venue'], 'required'],
            [['type', 'announcedBy', 'announcedFrom','duration'], 'integer'],
            ['invited', 'each', 'rule' => ['integer']],
            ['attendances', 'each', 'rule' => ['integer']],
            [['meetingTime', 'dateAnnounced'], 'safe'],
            [['meetingTitle'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 255],
            [['venue'], 'string', 'max' => 100],
            [['announcedBy'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['announcedBy' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => Meetingnames::className(), 'targetAttribute' => ['type' => 'typeID']],
            [['announcedFrom'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['announcedFrom' => 'branchID']],
        ];
    }
  
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'meetingID' => 'Meeting ID',
            'meetingTitle' => 'Meeting Title',
            'description' => 'Description',
            'type' => 'Type',
            'meetingTime' => 'Meeting Time',
            'venue' => 'Venue',
            'announcedBy' => 'Announced By',
            'dateAnnounced' => 'Date Announced',
            'announcedFrom' => 'Announced From',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert==true)
        {
        $this->meetingTime=$this->date." ".$this->time;
        $this->announcedBy=yii::$app->user->identity->id;
        $this->announcedFrom=yii::$app->user->identity->getBranch()->branchID;
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $this->dateAnnounced=date('Y-m-d H:i:s');
        $this->status="Upcoming"; 
        }
        else
        {
            $this->meetingTime=$this->date." ".$this->time;
           
            
            
        }
        return parent::beforeSave($insert);
    }
    public function afterSave($insert,$changedAttributes)
    {
       $invitees=$this->invited;
       date_default_timezone_set('Africa/Dar_es_Salaam');
       if($invitees!=null)
       {
       foreach($invitees as $index=>$invitee)
       {
        $invitesheet=new Meetinginvitees;
        $invitesheet->meetingID=$this->meetingID;
        $invitesheet->memberID=$invitee;
        $invitesheet->dateInvited=date('Y-m-d H:i:s');
        
        try{
        if(!$invitesheet->inviteeExists())
        {
        if(!$invitesheet->save())
        {
            $this->addError('invited',Member::findOne($invitee)->fullName()." Not Invited".Html::errorSummary($invitesheet));
            continue;
        }
        }
        else
        {
            continue;
        }
        }
        catch(\Exception $m)
        {
            continue;
        }

       }
    }
       return parent::afterSave($insert,$changedAttributes);
    }
    public function afterFind()
    {
        $invited=Meetinginvitees::find()->where(['meetingID'=>$this->meetingID])->all();
        if(!empty($invited) || $invited!=null)
        {
            $this->invited=array_keys(ArrayHelper::map($invited,'memberID','memberID'));
        }
        //loading attendaces

        if($this->meetingattendances!=null)
        {
            $this->attendances=array_keys(ArrayHelper::map($this->meetingattendances,'memberID','memberID'));
        }

        //loading date and time

        $meetingtime=explode(" ",$this->meetingTime);
        $this->date=$meetingtime[0];
        $this->time=$meetingtime[1];

        return parent::afterFind();
    }

    /**
     * Gets query for [[AnnouncedBy0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnnouncedBy0()
    {
        return $this->hasOne(User::className(), ['id' => 'announcedBy']);
    }

    /**
     * Gets query for [[Type0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(Meetingnames::className(), ['typeID' => 'type']);
    }

    /**
     * Gets query for [[AnnouncedFrom0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnnouncedFrom0()
    {
        return $this->hasOne(Branch::className(), ['branchID' => 'announcedFrom']);
    }

    /**
     * Gets query for [[MeetingConfirmations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingConfirmations()
    {
        return $this->hasMany(MeetingConfirmations::className(), ['meetingID' => 'meetingID']);
    }

    /**
     * Gets query for [[Meetingattendances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingattendances()
    {
        return $this->hasMany(Meetingattendance::className(), ['meetingID' => 'meetingID']);
    }

    /**
     * Gets query for [[Meetingdocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingdocuments()
    {
        return $this->hasMany(Meetingdocuments::className(), ['meetingID' => 'meetingID']);
    }

    /**
     * Gets query for [[Meetinginvitees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetinginvitees()
    {
        return $this->hasMany(Meetinginvitees::className(), ['meetingID' => 'meetingID']);
    }

    public function isCaller($member)
    {
        return $this->announcedBy==$member;
    }
    public function isCallable()
    {
        return $this->type0->canCall();
    }
   public function getCallableMeetingNames()
   {
    return (new Meetingnames)->getCallableMeetingNames();
   }
    public function isParticipant($role)
    {
      
      $participant=Meetingparticipants::find()->where(['typeID'=>$this->type0->typeID,'participant'=>$role])->one();
      

      return $participant!=null;
    }
    public function canParticipate($user)
    {
        $name=$this->type0->name;
        if($name=="MEMBERS' GENERAL MEETING -BR" || $name=="BRANCH GENERAL COUNCIL MEETING -BR" || $name=="BRANCH WOMEN'S COMMITTEE MEETING -BR")
        {
            return (User::findIdentity($user))->isMemberOf($this->announcedFrom0->branchID);
        }
        return true;
    }
    public function isInvited($user)
    {
        $invited=Meetinginvitees::find()->where(['meetingID'=>$this->meetingID,'memberID'=>$user])->one();
        

        return $invited!=null;
    }
    public function isViewable()
    {
        $userid=yii::$app->user->id;
        $user=User::findIdentity($userid);
        $role=array_keys(Yii::$app->authManager->getAssignments($userid))[0];
        if(($this->isParticipant($role) && $this->canParticipate($userid)) || $this->isInvited($user->id))
        {
            return true;
        }

        return false;
    }
    public function getViewableMeetings()
    {
        $meetings=$this->find()->orderBy(["meetingID"=>SORT_DESC])->all();
        if($meetings==null || empty($meetings))
        {
            return [];
        }
        foreach($meetings as $index=>$meeting)
        {
            if(!$meeting->isViewable())
            {
                unset($meetings[$index]);
            }
        }
        return $meetings;
    }
    public function getPartcipants()
    {
        $participants=[];
        $official_participants=$this->type0->meetingparticipants;
        $invited=$this->meetinginvitees;
        
        foreach($official_participants as $index=>$official_participant)
        {
            $auth=$official_participant->participant;
            $assignedpart=AuthAssignmnet::find()->where(['item_name'=>$auth])->all();
            if($assignedpart==null){continue;}

            foreach($assignedpart as $part)
            {
                if($part==null){continue;}
                if(User::findIdentity($part->user_id)==null)
                {
                   continue;
                }
              
                array_push($participants,(User::findIdentity($part->user_id)));
            }
            
        }
        foreach($invited as $index2=>$invitee)
        {
            array_push($participants,$invitee->user);
        }

      return $participants;
    }

    public function getParticipantStatus($member)
    {
      $member=User::findOne($member);
      $meeting=$this->meetingID;
      if($member->hasAttended($meeting) && $member->hasConfirmed($meeting))
      {
         return "Attended";
      }
      else if($member->hasConfirmed($meeting))
      {
           return "Confirmed";
      }
      else if($member->hasAttended($meeting))
      {
         return "Attended";
      }
      else
      {
        if($member->canView($meeting))
        {
            return "Invited";
        }
        else
        {
            return "Not invited";
        }
       
      }
    }

    public function getMembers()
    {
        $members=Member::find()->all();

        //removing the caller

        foreach($members as $index=>$member)
        {
          if($member->userID==yii::$app->user->identity->id)
          {
            unset($members[$index]);
          }
        }

        return ArrayHelper::map($members,'memberID','memberDetails','branchName');
    }
    public function getCaller()
    {
        return array_keys(Yii::$app->authManager->getAssignments($this->announcedBy0->id))[0];
    }
    public function updateAttendance()
    {
    
      
            try
            {
            $attendees=$this->attendances;
            $currentattendances=$this->meetingattendances;
            foreach($currentattendances as $ind=>$currentattendance)
            {
                if(!$currentattendance->delete())
                {
                    continue;
                }
            }
            if($attendees!=null)
            {
            
            foreach($attendees as $index=>$attendee)
            {
                $attendancesheet=new Meetingattendance;
                $attendancesheet->memberID=$attendee;
                $attendancesheet->meetingID=$this->meetingID;

                if(!$attendancesheet->save())
                {
                    continue;
                }
            }
          

            return true;
        }
        return true;
      
    }
        catch(\Exception $w)
        {
            
            throw $w;
            
        }
       
    }
    public function signAttendance()
    {
        if(!$this->isExpired())
        {
            throw new \Exception("Could not sign attendance! Attendance is not accessible before the meeting date !");
        }
        $attendancesheet=new Meetingattendance;
        $attendancesheet->memberID=yii::$app->user->identity->id;
        $attendancesheet->meetingID=$this->meetingID;

        if($attendancesheet->save())
        {
            return true;
        }
        return false;
    }
    public function deleteAttendance()
    {
        $currentattendances=$this->meetingattendances;
        if($currentattendances!=null)
        {
        foreach($currentattendances as $ind=>$currentattendance)
        {
            if(!$currentattendance->delete())
            {
                continue;
            }
        }
       }
       return true;
    }
    public function confirmParticipation()
    {
        return (new MeetingConfirmations)->confirmParticipation($this->meetingID);
    }
    public function isCancelled()
    {
        return (new Meetingcancel)->isCancelled($this->meetingID);
    } 
    public function uncancel()
    {
        return (new Meetingcancel)->uncancel($this->meetingID); 
    }
    public function isExpired()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $meetingtime=$this->meetingTime;
        $meetingtime=strtotime($meetingtime);
        $now=strtotime(date('Y-m-d H:i:s'));

        return $meetingtime<$now;
    }
    
    public function meetingStatus()
    {
        if($this->isExpired())
        {
            return "Expired";
        }
        else if($this->isCancelled())
        {
            return "Cancelled";
        }
        else if($this->status=="Updated")
        {
            return "Updated";
        }
        else
        {
            return "Upcoming";
        }
    }
    public function getCancelReason()
    {
        return (new Meetingcancel)->getCancelReason($this->meetingID);  
    }

    public function isAttended()
    {
        $memberID=yii::$app->user->identity->id;
        return (new Meetingattendance)->isAttended($memberID,$this->meetingID);
    }
    public function isUserCancelled()
    {
        $memberID=yii::$app->user->identity->id;
        return (new Meetingcancel)->isParticipationCancelled($this->meetingID,$memberID);
    }
    public function unConfirmParticipation()
    {
        $member=yii::$app->user->identity->id;
        return (new MeetingConfirmations)->unConfirmParticipation($member,$this->meetingID);
    }
    public function isConfirmed()
    {
        $member=yii::$app->user->identity->id;
        return (new MeetingConfirmations)->isConfirmed($member,$this->meetingID);
    }
    public function getParticipationCancel($member)
    {
        $meeting=$this->meetingID;
        return (new Meetingcancel)->getParticipationCancel($meeting,$member);
    }
    public function approveParticipationCancel($member)
    {
        $cancel=$this->getParticipationCancel($member);
        $cancel->status="approved";

        if($cancel->save())
        {
            return true;
        }

        return false;
    }
    public function disapproveParticipationCancel($member)
    {
        $cancel=$this->getParticipationCancel($member);
        $cancel->status="disapproved";

        if($cancel->save())
        {
            return true;
        }

        return false;
    }
    public function isCancelApproved($member)
    {
        $cancel=$this->getParticipationCancel($member);

        return $cancel->status=="approved";
    }
    public function getCancelStatus($member)
    {
        $cancel=$this->getParticipationCancel($member);
        if($cancel==null){return null;}
        return $cancel->status;
    }

   
}
