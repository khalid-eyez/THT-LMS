<?php

namespace common\models;
use ruturajmaniyar\mod\audit\behaviors\AuditEntryBehaviors;
use Yii;
use kartik\validators\PhoneValidator;

/**
 * This is the model class for table "admin".
 *
 * @property int $adminID
 * @property int|null $userID
 * @property int|null $collegeID
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 *
 * @property College $college
 * @property User $user
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin';
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
            [['userID', 'collegeID'], 'integer'],
            [['full_name', 'email'], 'required'],
            [['full_name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 30],
            ['phone', 'k-phone','countryValue' => 'TZ'],
            [['email'], 'unique'],
            [['email'], 'email','message'=>'This E-mail seems invalid, try another one'],
            [['phone'], 'unique'],
            [['collegeID'], 'exist', 'skipOnError' => true, 'targetClass' => College::className(), 'targetAttribute' => ['collegeID' => 'collegeID']],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adminID' => 'Admin ID',
            'userID' => 'User ID',
            'collegeID' => 'College ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }

    /**
     * Gets query for [[College]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollege()
    {
        return $this->hasOne(College::className(), ['collegeID' => 'collegeID']);
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
}
