<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "loan_types".
 *
 * @property int $id
 * @property int $categoryID
 * @property string $type
 * @property float $interest_rate
 * @property float $topup_rate
 * @property float $penalty_rate
 * @property float $processing_fee_rate
 * @property int $penalty_grace_days
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LoanCategory $category
 * @property CustomerLoan[] $customerLoans
 */
class LoanType extends \yii\db\ActiveRecord
{

 public function behaviors()
    {
        return [
            TimestampBehavior::className(),
             'auditBehaviour'=>'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loan_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['processing_fee_rate'], 'default', 'value' => 0.00],
            [['penalty_grace_days'], 'default', 'value' => 1],
            [['categoryID', 'type'], 'required'],
            [['categoryID', 'penalty_grace_days'], 'integer'],
            [['interest_rate', 'topup_rate', 'penalty_rate', 'processing_fee_rate'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 50],
            [['categoryID'], 'exist', 'skipOnError' => true, 'targetClass' => LoanCategory::class, 'targetAttribute' => ['categoryID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryID' => 'Category ID',
            'type' => 'Type',
            'interest_rate' => 'Interest Rate',
            'topup_rate' => 'Topup Rate',
            'penalty_rate' => 'Penalty Rate',
            'processing_fee_rate' => 'Processing Fee Rate',
            'penalty_grace_days' => 'Penalty Grace Days',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|LoanCategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(LoanCategory::class, ['id' => 'categoryID']);
    }

    /**
     * Gets query for [[CustomerLoans]].
     *
     * @return \yii\db\ActiveQuery|CustomerLoanQuery
     */
    public function getCustomerLoans()
    {
        return $this->hasMany(CustomerLoan::class, ['loan_type_ID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LoanTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoanTypeQuery(get_called_class());
    }

}
