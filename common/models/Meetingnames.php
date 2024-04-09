<?php

namespace common\models;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "meetingnames".
 *
 * @property int $typeID
 * @property string $name
 * @property string|null $description
 * @property Meetingreferences $referencepref
 * @property Meeting[] $meetings
 * @property Meetingparticipants[] $meetingparticipants
 * @property AuthItem[] $participants
 */
class Meetingnames extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetingnames';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'typeID' => 'Type ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Meetings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetings()
    {
        return $this->hasMany(Meeting::className(), ['type' => 'typeID']);
    }

    /**
     * Gets query for [[Meetingparticipants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingparticipants()
    {
        return $this->hasMany(Meetingparticipants::className(), ['typeID' => 'typeID']);
    }

    /**
     * Gets query for [[Participants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParticipants()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'participant'])->viaTable('meetingparticipants', ['typeID' => 'typeID']);
    }
    public function getReferencepref()
    {
        return $this->hasOne(Meetingreferences::className(), ['referenceName' => 'typeID']);
    }
    public function canCall()
    {
        $callers=[];
        switch($this->name)
        {
            case "GENERAL ASSEMBLY -HQ":
                array_push($callers,"GENERAL SECRETARY HQ");
                break;
            case "GENERAL COUNCIL MEETING -HQ":
                array_push($callers,"GENERAL SECRETARY HQ");
                break;
            case "CENTRAL COMMITTEE MEETING -HQ":
                array_push($callers,"GENERAL SECRETARY HQ");
                    break;
            case "WOMEN'S GENERAL MEETING -HQ":
                array_push($callers,"WOMEN'S COORDINATOR HQ");
                array_push($callers,"GENERAL SECRETARY HQ");
                array_push($callers,"DEPUTY WOMEN'S COORDINATOR HQ");
               
                    break;
            case "WOMEN'S CENTRAL COMMITTEE MEETING -HQ":

                array_push($callers,"DEPUTY WOMEN'S COORDINATOR HQ");
                array_push($callers,"WOMEN'S COORDINATOR HQ");
                       
                break;
            case "MEMBERS' GENERAL MEETING -BR":

                    array_push($callers,"GENERAL SECRETARY BR");
                           
                    break;
            case "BRANCH GENERAL COUNCIL MEETING -BR":

                        array_push($callers,"GENERAL SECRETARY BR");
                               
                        break;
            case "BRANCH WOMEN'S COMMITTEE MEETING -BR":

                            array_push($callers,"WOMEN'S COORDINATOR BR");
                                   
                            break;
                    

        
        }
        $userid=yii::$app->user->id;
        $user=array_keys(Yii::$app->authManager->getAssignments($userid))[0];
        return in_array($user,$callers);
    }
    public function getCallableMeetingNames()
    {
        $names=$this->find()->all();

        foreach($names as $index=>$name)
        {
            if(!$name->canCall())
            {
                unset($names[$index]);
            }
        }
        return ArrayHelper::map($names,'typeID','name');
    }
    public function getMeetingCallers()
    {
        return ["GENERAL SECRETARY HQ","WOMEN'S COORDINATOR HQ","DEPUTY WOMEN'S COORDINATOR HQ","GENERAL SECRETARY BR","WOMEN'S COORDINATOR BR"];
    }
}
