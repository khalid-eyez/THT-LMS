<?php

namespace common\models;

use Yii;
use ruturajmaniyar\mod\audit\behaviors\AuditEntryBehaviors;
use kartik\validators\PhoneValidator;
/**
 * This is the model class for table "member".
 *
 * @property int $memberID
 * @property int|null $userID
 * @property string $IndividualNumber
 * @property string $fname
 * @property string|null $mname
 * @property string $lname
 * @property string $email
 * @property string $phone
 * @property string $gender
 * @property int|null $branch
 * @property Branch $branch0
 * @property User $user
 * @property Meetingattendance[] $attendances
 */
class Member extends \yii\db\ActiveRecord
{
public $role;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }
    // public function behaviors()
    // {
    //     return [
    //         'auditEntryBehaviors' => [
    //             'class' => AuditEntryBehaviors::class
    //          ],
    //     ];
    // }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userID', 'branch'], 'integer'],
            [['fname', 'lname', 'email', 'phone', 'gender','role','branch'], 'required'],
            [['IndividualNumber'], 'string', 'max' => 100],
            [['fname', 'mname', 'lname', 'email'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 15],
            [['gender'], 'string', 'max' => 10],
            [['IndividualNumber'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email','message'=>'This E-mail seems invalid, try another one'],
            [['phone'], 'unique'],
            [['branch'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch' => 'branchID']],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'memberID' => 'Member ID',
            'userID' => 'User ID',
            'IndividualNumber' => 'Individual Number',
            'fname' => 'Fname',
            'mname' => 'Mname',
            'lname' => 'Lname',
            'email' => 'Email',
            'phone' => 'Phone',
            'gender' => 'Gender',
            'branch' => 'Branch',
        ];
    }
    public function beforeSave($insert)
    {

      if($insert==false && $this->isAttributeChanged('email'))
      {
          $userID=$this->userID;
          $user=User::findOne($userID);
          $user->username=$this->email;
          $user->save();
      }
      else if($insert==true){
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
        $user=new User();
        $user->username = $this->email;
        $user->setPassword($this->phone);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        if(!$user->save())
        {
          throw new \Exception("could not save user data, try again !");
          return false;
        }
        $this->userID=$user->id;
        
        $auth=Yii::$app->authManager;
        $userRole =$auth->getRole($this->role);
        $auth->assign($userRole, $user->getId());

        $branch=Branch::findOne($this->branch)->branch_short;
        $prefix="THTU-".date('Y')."/".$branch;
        $usernumber=$prefix."/".rand(0,15000000);
        $this->IndividualNumber=$usernumber;
        $transaction->commit();
    }
    catch(\Exception $e)
    {
        $transaction->rollBack();
        throw $e; 
    }
      }
      else
      {
        
      }


        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        if(!$this->user->setDeleted())
        {
            throw new \Exception("User Deleting Failed !");
            return false;
        }
        return parent::beforeDelete();
    }

    /**
     * Gets query for [[Branch0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch0()
    {
        return $this->hasOne(Branch::className(), ['branchID' => 'branch']);
    }
    public function getAttendances()
    {
        return $this->hasMany(Meetingattendance::className(), ['memberID' => 'memberID']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userID']);
    }
    public function fullName()
    {
        return $this->fname." ".$this->mname." ".$this->lname;
    }

    public function branch()
    {
        return $this->branch0->branch_short;
    }
    public function isMemberOf($branch)
    {
        return $this->branch==$branch;
    }
    public function hasConfirmed($meeting)
    {
        return (new MeetingConfirmations)->isConfirmed($this->memberID,$meeting);
    }
    public function hasCancelledParticipation($meeting)
    {
        return (new Meetingcancel)->isParticipationCancelled($meeting,$this->memberID);
    }
    public function hasAttended($meeting)
    {
        return (new Meetingattendance)->isAttended($this->memberID,$meeting); 
    }
    public function getParticipantStatus($meeting)
    {
   
      if($this->hasAttended($meeting) && $this->hasConfirmed($meeting))
      {
         return "Attended";
      }
      else if($this->hasConfirmed($meeting))
      {
           return "Confirmed";
      }
      else if($this->hasAttended($meeting))
      {
        return "Attended";
      }
      else if($this->hasCancelledParticipation($meeting))
      {
        return "Cancelled";
      }
      else
      {
        
      
        if($this->canView($meeting))
        {
            return "Invited";
        }
        else
        {
            return "Not invited";
        }
       
      }
    }
    public function canView($meeting)
    {
        $meeting=Meeting::findOne($meeting);
        $userid=$this->userID;
        $user=User::findIdentity($userid);
        $role=array_keys(Yii::$app->authManager->getAssignments($userid))[0];
        if(($meeting->isParticipant($role) && $meeting->canParticipate($userid)) || $meeting->isInvited($this->memberID))
        {
            return true;
        }

        return false;
    }
    public function getRank()
    {
        $userid=$this->userID;
        $role=array_keys(Yii::$app->authManager->getAssignments($userid))[0];

        return $role;
    }
    public function getMemberDetails()
    {
        return $this->fullName()." -".$this->getRank();
    }
    public function getBranchName()
    {
        return $this->branch0->branchName;
    }

   
    
  

}
