<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "deposit_interests".
 *
 * @property int $id
 * @property int $depositID
 * @property float $interest_amount
 * @property string|null $payment_date
 * @property string|null $claim_date
 * @property int $claim_months
 * @property string|null $approved_at
 * @property int|null $approved_by
 *
 * @property User $approvedBy
 * @property Deposit $deposit
 */
class DepositInterest extends \yii\db\ActiveRecord
{

 public function behaviors()
    {
        return [
        
             'auditBehaviour'=>'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposit_interests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_date', 'claim_date', 'approved_at', 'approved_by'], 'default', 'value' => null],
            [['depositID', 'interest_amount', 'claim_months'], 'required'],
            [['depositID', 'claim_months', 'approved_by'], 'integer'],
            [['interest_amount'], 'number'],
            [['payment_date', 'claim_date', 'approved_at'], 'safe'],
            [['depositID'], 'exist', 'skipOnError' => true, 'targetClass' => Deposit::class, 'targetAttribute' => ['depositID' => 'depositID']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['approved_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'depositID' => 'Deposit ID',
            'interest_amount' => 'Interest Amount',
            'payment_date' => 'Payment Date',
            'claim_date' => 'Claim Date',
            'claim_months' => 'Claim Months',
            'approved_at' => 'Approved At',
            'approved_by' => 'Approved By',
        ];
    }

    /**
     * Gets query for [[ApprovedBy]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(User::class, ['id' => 'approved_by']);
    }

    /**
     * Gets query for [[Deposit]].
     *
     * @return \yii\db\ActiveQuery|DepositQuery
     */
    public function getDeposit()
    {
        return $this->hasOne(Deposit::class, ['depositID' => 'depositID']);
    }

    /**
     * {@inheritdoc}
     * @return DepositInterestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DepositInterestQuery(get_called_class());
    }
    public function approve()
    {
    $this->approved_by=yii::$app->user->identity->id;
    $this->approved_at=date('Y-m-d H:i:s');

    return $this->save();
    }

    public static function getPendingClaims()
    {
    return self::find()
    ->where([
    'approved_at' => null,
    'approved_by' => null,
    'payment_date' => null,
    ])
    ->all();
    }


}
