<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $fileID
 * @property string $fileName
 *
 * @property Repository $repository
 * @property Meetingdocuments $meetingdoc
 * @property Referencedocuments $referencedoc
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileName'], 'required'],
            [['fileName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fileID' => 'File ID',
            'fileName' => 'File Name',
        ];
    }

    /**
     * Gets query for [[Repositories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRepository()
    {
        return $this->hasOne(Repository::className(), ['file' => 'fileID']);
    }
    public function getMeetingdoc()
    {
        return $this->hasOne(Meetingdocuments::className(), ['fileID' => 'fileID']);
    }
    public function getReferencedoc()
    {
        return $this->hasOne(Referencedocuments::className(), ['fileID' => 'fileID']);
    }
    public function getFileSize($location)
    {
        $file=$location.$this->fileName;

       if((filesize($file)/1000000)<1)
       {
        return round(((filesize($file)/1000000)*1000),2)." KB";
       }
       else if((filesize($file)/1000000)>1000)
       {
        return round(((filesize($file)/1000000)/1000),2)." GB";
       }
       else
       {
        return round((filesize($file)/1000000),2)." MB";
       }
    }
}
