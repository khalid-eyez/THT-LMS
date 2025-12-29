<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "loan_attachments".
 *
 * @property int $id
 * @property int $loanID
 * @property int $requirementID
 * @property string $uploaded_doc
 * @property string|null $timevalidated
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property CustomerLoans $loan
 * @property LoanRequirements $requirement
 */
class LoanAttachments extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loan_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timevalidated', 'deleted_at'], 'default', 'value' => null],
            [['isDeleted'], 'default', 'value' => 0],
            [['loanID', 'requirementID', 'uploaded_doc'], 'required'],
            [['loanID', 'requirementID', 'isDeleted'], 'integer'],
            [['timevalidated', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['uploaded_doc'], 'string', 'max' => 255],
            [['loanID'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerLoans::class, 'targetAttribute' => ['loanID' => 'id']],
            [['requirementID'], 'exist', 'skipOnError' => true, 'targetClass' => LoanRequirements::class, 'targetAttribute' => ['requirementID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loanID' => 'Loan ID',
            'requirementID' => 'Requirement ID',
            'uploaded_doc' => 'Uploaded Doc',
            'timevalidated' => 'Timevalidated',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Loan]].
     *
     * @return \yii\db\ActiveQuery|CustomerLoansQuery
     */
    public function getLoan()
    {
        return $this->hasOne(CustomerLoans::class, ['id' => 'loanID']);
    }

    /**
     * Gets query for [[Requirement]].
     *
     * @return \yii\db\ActiveQuery|LoanRequirementsQuery
     */
    public function getRequirement()
    {
        return $this->hasOne(LoanRequirements::class, ['id' => 'requirementID']);
    }

    /**
     * {@inheritdoc}
     * @return LoanAttachmentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoanAttachmentsQuery(get_called_class());
    }

}
