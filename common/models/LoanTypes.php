<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "loan_types".
 *
 * @property int $id
 * @property int $categoryID
 * @property string $type
 * @property float $interestrate
 * @property float $penaltyrate
 * @property int $penalty_grace_days
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LoanCategories $category
 * @property CustomerLoans[] $customerLoans
 * @property LoanRequirements[] $loanRequirements
 */
class LoanTypes extends \yii\db\ActiveRecord
{


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
            [['categoryID', 'type', 'interestrate', 'penaltyrate', 'penalty_grace_days'], 'required'],
            [['categoryID', 'penalty_grace_days'], 'integer'],
            [['interestrate', 'penaltyrate'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 50],
            [['categoryID'], 'exist', 'skipOnError' => true, 'targetClass' => LoanCategories::class, 'targetAttribute' => ['categoryID' => 'id']],
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
            'interestrate' => 'Interestrate',
            'penaltyrate' => 'Penaltyrate',
            'penalty_grace_days' => 'Penalty Grace Days',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|LoanCategoriesQuery
     */
    public function getCategory()
    {
        return $this->hasOne(LoanCategories::class, ['id' => 'categoryID']);
    }

    /**
     * Gets query for [[CustomerLoans]].
     *
     * @return \yii\db\ActiveQuery|CustomerLoansQuery
     */
    public function getCustomerLoans()
    {
        return $this->hasMany(CustomerLoans::class, ['loan_type_ID' => 'id']);
    }

    /**
     * Gets query for [[LoanRequirements]].
     *
     * @return \yii\db\ActiveQuery|LoanRequirementsQuery
     */
    public function getLoanRequirements()
    {
        return $this->hasMany(LoanRequirements::class, ['loan_type_ID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LoanTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoanTypesQuery(get_called_class());
    }

}
