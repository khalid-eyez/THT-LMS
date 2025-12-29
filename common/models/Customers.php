<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property int $userID
 * @property string $full_name
 * @property string $birthDate
 * @property string $gender
 * @property string $address
 * @property string $contacts
 * @property string $NIN
 * @property string|null $TIN
 * @property string $status
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CustomerLoans[] $customerLoans
 * @property Shareholders $shareholders
 * @property User $user
 */
class Customers extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TIN', 'deleted_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'active'],
            [['isDeleted'], 'default', 'value' => 0],
            [['userID', 'full_name', 'birthDate', 'gender', 'address', 'contacts', 'NIN'], 'required'],
            [['userID', 'isDeleted'], 'integer'],
            [['birthDate', 'address', 'contacts', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'string'],
            [['full_name', 'NIN', 'TIN'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 8],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['userID'], 'unique'],
            [['NIN'], 'unique'],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userID' => 'User ID',
            'full_name' => 'Full Name',
            'birthDate' => 'Birth Date',
            'gender' => 'Gender',
            'address' => 'Address',
            'contacts' => 'Contacts',
            'NIN' => 'Nin',
            'TIN' => 'Tin',
            'status' => 'Status',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CustomerLoans]].
     *
     * @return \yii\db\ActiveQuery|CustomerLoansQuery
     */
    public function getCustomerLoans()
    {
        return $this->hasMany(CustomerLoans::class, ['customerID' => 'id']);
    }

    /**
     * Gets query for [[Shareholders]].
     *
     * @return \yii\db\ActiveQuery|ShareholdersQuery
     */
    public function getShareholders()
    {
        return $this->hasOne(Shareholders::class, ['customerID' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userID']);
    }

    /**
     * {@inheritdoc}
     * @return CustomersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomersQuery(get_called_class());
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function setStatusToActive()
    {
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isStatusInactive()
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function setStatusToInactive()
    {
        $this->status = self::STATUS_INACTIVE;
    }
}
