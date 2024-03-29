<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "receivabletransactions".
 *
 * @property int $transID
 * @property int $projID
 * @property int $receivedamount
 * @property string|null $datereceived
 * @property int|null $authority
 *
 * @property Member $authority0
 * @property Budgetprojections $proj
 */
class Receivabletransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receivabletransactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['projID', 'receivedamount'], 'required'],
            [['projID', 'receivedamount', 'authority'], 'integer'],
            [['datereceived'], 'safe'],
            [['authority'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['authority' => 'id']],
            [['projID'], 'exist', 'skipOnError' => true, 'targetClass' => Budgetprojections::className(), 'targetAttribute' => ['projID' => 'projID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transID' => 'Trans ID',
            'projID' => 'Proj ID',
            'receivedamount' => 'Receivedamount',
            'datereceived' => 'Datereceived',
            'authority' => 'Authority',
        ];
    }

    /**
     * Gets query for [[Authority0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthority0()
    {
        return $this->hasOne(Member::className(), ['memberID' => 'authority']);
    }

    /**
     * Gets query for [[Proj]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProj()
    {
        return $this->hasOne(Budgetprojections::className(), ['projID' => 'projID']);
    }
}