<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "cashbook".
 *
 * @property int $id
 * @property string $reference_no
 * @property string $description
 * @property string $category
 * @property float $debit
 * @property float $credit
 * @property float $balance
 * @property string $created_at
 * @property string $updated_at
 * @property string $payment_document
 *
 * @property Customer $customer
 */
class Cashbook extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
             [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()'),
             ],
             'auditBehaviour'=>'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cashbook';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit','debit'], 'default', 'value' => 0.00],
            [['reference_no', 'description', 'category', 'balance', 'payment_document'], 'required'],
            [['created_at','updated_at'],'safe'],
            [['debit', 'credit', 'balance'], 'number'],
            [['reference_no', 'description', 'category', 'payment_document'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reference_no' => 'Reference No',
            'description' => 'Description',
            'category' => 'Category',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'balance' => 'Balance',
            'payment_document' => 'Payment Document',
        ];
    }
   
   

    /**
     * {@inheritdoc}
     * @return CashbookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CashbookQuery(get_called_class());
    }
    public function getLast()
    {
        $last = $this->find()->orderBy(['id' => SORT_DESC])->one();

        $lastBalance = $last ? $last->balance : 0;
        return $lastBalance;
    }
    public function updatedBalance()
    {
        $last=$this->getLast();
        $credit=-($this->credit);
        $debit=$this->debit;
        $last+=$credit;
        $last+=$debit;
        return $last;
    }
    public function openingBalance()
    {
        $prev=$this->find()->where(['<','id',$this->id])->orderBy(['id'=>SORT_DESC])->one();

        return ($prev==null)?0:$prev->balance;
    }

}
