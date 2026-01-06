<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Deposit]].
 *
 * @see Deposit
 */
class DepositQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Deposit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Deposit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
