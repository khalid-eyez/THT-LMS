<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meetingdocuments".
 *
 * @property int $docID
 * @property int|null $meetingID
 * @property int|null $fileID
 * @property string|null $title
 * @property string|null $dateUploaded
 *
 * @property Meeting $meeting
 * @property Files $file
 */
class Meetingdocuments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meetingdocuments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetingID', 'fileID'], 'integer'],
            [['dateUploaded'], 'safe'],
            ['title','string','max'=>100],
            [['meetingID'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meetingID' => 'meetingID']],
            [['fileID'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['fileID' => 'fileID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'docID' => 'Doc ID',
            'meetingID' => 'Meeting ID',
            'fileID' => 'File ID',
            'dateUploaded' => 'Date Uploaded',
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
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Files::className(), ['fileID' => 'fileID']);
    }
    public function fileSize()
    {
        return $this->file->getFileSize('storage/meetingRepos/');
    }
}
