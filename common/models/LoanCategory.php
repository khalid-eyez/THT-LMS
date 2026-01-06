<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "loan_categories".
 *
 * @property int $id
 * @property string $categoryName
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LoanType[] $loanTypes
 */
class LoanCategory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loan_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryName'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['categoryName'], 'string', 'max' => 100],
            [['categoryName'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryName' => 'Category Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[LoanTypes]].
     *
     * @return \yii\db\ActiveQuery|LoanTypeQuery
     */
    public function getLoanTypes()
    {
        return $this->hasMany(LoanType::class, ['categoryID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LoanCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoanCategoryQuery(get_called_class());
    }

}
