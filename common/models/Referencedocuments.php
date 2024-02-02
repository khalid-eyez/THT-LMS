<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "referencedocuments".
 *
 * @property int $docID
 * @property string $docTitle
 * @property string|null $docType
 * @property int $referencePrefix
 * @property int $fileID
 * @property int $year
 * @property int|null $meetingID
 * @property int|null $offeredTo
 * @property string dateUploaded
 * @property string reference
 * @property Referenceprefixes $referencePrefix0
 * @property Files $file
 * @property Meeting $meeting
 * @property Member $offeredTo0
 */
class Referencedocuments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'referencedocuments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['docTitle', 'referencePrefix', 'fileID', 'year'], 'required'],
            [['referencePrefix', 'fileID', 'year', 'meetingID', 'offeredTo'], 'integer'],
            [['docTitle'], 'string', 'max' => 100],
            [['reference'], 'string', 'max' => 40],
            [['dateUploaded'], 'string'],
            [['docType'], 'string', 'max' => 20],
            [['referencePrefix'], 'exist', 'skipOnError' => true, 'targetClass' => Referenceprefixes::className(), 'targetAttribute' => ['referencePrefix' => 'prefID']],
            [['fileID'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['fileID' => 'fileID']],
            [['meetingID'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meetingID' => 'meetingID']],
            [['offeredTo'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['offeredTo' => 'memberID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'docID' => 'Doc ID',
            'docTitle' => 'Doc Title',
            'docType' => 'Doc Type',
            'referencePrefix' => 'Reference Prefix',
            'fileID' => 'File ID',
            'year' => 'Year',
            'meetingID' => 'Meeting ID',
            'offeredTo' => 'Offered To',
        ];
    }
public function afterSave($insert,$changedAttributes)
{
    if($insert==true)
    {
        $this->reference=$this->getReference();
        $this->save();
    }
}
    /**
     * Gets query for [[ReferencePrefix0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferencePrefix0()
    {
        return $this->hasOne(Referenceprefixes::className(), ['prefID' => 'referencePrefix']);
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
     * Gets query for [[OfferedTo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOfferedTo0()
    {
        return $this->hasOne(Member::className(), ['memberID' => 'offeredTo']);
    }

    public function isInvitationOffered($meeting)
    {
        $member=yii::$app->user->identity->member->memberID;
        $invitation=$this->find()->where(['meetingID'=>$meeting,'offeredTo'=>$member])->one();
        return $invitation!=null;
    }
    public function getInvitation($meeting)
    {
        $member=yii::$app->user->identity->member->memberID;
        $invitation=$this->find()->where(['meetingID'=>$meeting,'offeredTo'=>$member])->one();
        if($invitation!=null)
        {
           return $invitation; 
        }
        return null;
    }
    public function getReference()
    {
        return $this->referencePrefix0->prefix.str_pad($this->docID, 4, "0", STR_PAD_LEFT).".".$this->year;
    }
    public function fileSize()
    {
        try
        {
        return $this->file->getFileSize('storage/cabinetRepos/');
        }
        catch(\Exception $f)
        {
            return null;
        }
    }
    public function getUploadDate()
    {
        return date_format(date_create($this->dateUploaded),'d/m/Y');
    }

    public function findByKeyword($keyword)
    {
        $result=$this->findBySql("select * from referencedocuments where reference like '%{$keyword}%' or docTitle like '%{$keyword}%' ")->all();
        if($result==null){return null;}
        return $result;
    }
    public function getLabel()
    {
        $reference=$this->referencePrefix0;
       
        return $reference->getTitle();
    }
}
