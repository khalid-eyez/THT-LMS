<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "loan_requirements".
 *
 * @property int $id
 * @property int $loan_type_ID
 * @property string $document
 * @property string $optional
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LoanAttachments[] $loanAttachments
 * @property LoanTypes $loanType
 */
class LoanRequirements extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const OPTIONAL_TRUE = 'true';
    const OPTIONAL_FALSE = 'false';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loan_requirements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['optional'], 'default', 'value' => 'false'],
            [['loan_type_ID', 'document'], 'required'],
            [['loan_type_ID'], 'integer'],
            [['optional'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['document'], 'string', 'max' => 50],
            ['optional', 'in', 'range' => array_keys(self::optsOptional())],
            [['loan_type_ID'], 'exist', 'skipOnError' => true, 'targetClass' => LoanTypes::class, 'targetAttribute' => ['loan_type_ID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loan_type_ID' => 'Loan Type ID',
            'document' => 'Document',
            'optional' => 'Optional',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[LoanAttachments]].
     *
     * @return \yii\db\ActiveQuery|LoanAttachmentsQuery
     */
    public function getLoanAttachments()
    {
        return $this->hasMany(LoanAttachments::class, ['requirementID' => 'id']);
    }

    /**
     * Gets query for [[LoanType]].
     *
     * @return \yii\db\ActiveQuery|LoanTypesQuery
     */
    public function getLoanType()
    {
        return $this->hasOne(LoanTypes::class, ['id' => 'loan_type_ID']);
    }

    /**
     * {@inheritdoc}
     * @return LoanRequirementsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoanRequirementsQuery(get_called_class());
    }


    /**
     * column optional ENUM value labels
     * @return string[]
     */
    public static function optsOptional()
    {
        return [
            self::OPTIONAL_TRUE => 'true',
            self::OPTIONAL_FALSE => 'false',
        ];
    }

    /**
     * @return string
     */
    public function displayOptional()
    {
        return self::optsOptional()[$this->optional];
    }

    /**
     * @return bool
     */
    public function isOptionalTrue()
    {
        return $this->optional === self::OPTIONAL_TRUE;
    }

    public function setOptionalToTrue()
    {
        $this->optional = self::OPTIONAL_TRUE;
    }

    /**
     * @return bool
     */
    public function isOptionalFalse()
    {
        return $this->optional === self::OPTIONAL_FALSE;
    }

    public function setOptionalToFalse()
    {
        $this->optional = self::OPTIONAL_FALSE;
    }
}
