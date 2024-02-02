<?php

namespace common\models;
use ruturajmaniyar\mod\audit\behaviors\AuditEntryBehaviors;
use Yii;

/**
 * This is the model class for table "repository".
 *
 * @property int $docID
 * @property int|null $userID
 * @property string $docTitle
 * @property string|null $docDescription
 * @property string|null $uploadTime
 * @property int $file
 *
 * @property Files $file0
 * @property User $user
 */
class Repository extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repository';
    }
    public function behaviors()
    {
        return [
            'auditEntryBehaviors' => [
                'class' => AuditEntryBehaviors::class
             ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userID', 'file'], 'integer'],
            [['docTitle', 'file'], 'required'],
            [['uploadTime'], 'safe'],
            [['docTitle'], 'string', 'max' => 150],
            [['docDescription'], 'string', 'max' => 255],
            [['file'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['file' => 'fileID']],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'docID' => 'Doc ID',
            'userID' => 'User ID',
            'docTitle' => 'Doc Title',
            'docDescription' => 'Doc Description',
            'uploadTime' => 'Upload Time',
            'file' => 'File',
        ];
    }
    public function afterDelete()
    {
        $fileName=$this->file0->fileName;

        try
        {
          if(file_exists("storage/repos/".$fileName))
          {
            unlink("storage/repos/".$fileName);
            $this->file0->delete();
          }
          else
          {
            $this->file0->delete();
            throw new \Exception("file not found");
          }

          $this->file0->delete();
        }
        catch(\Exception $d)
        {
            return parent::afterDelete();
        }
        return parent::afterDelete();
    }
    /**
     * Gets query for [[File0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile0()
    {
        return $this->hasOne(Files::className(), ['fileID' => 'file']);
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

    public function isUploader($user)
    {
        return $user==$this->userID;
    }
}
