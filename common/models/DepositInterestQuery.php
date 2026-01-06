<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[DepositInterest]].
 *
 * @see DepositInterest
 */
class DepositInterestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DepositInterest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DepositInterest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
