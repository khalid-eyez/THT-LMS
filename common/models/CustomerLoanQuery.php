<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CustomerLoan]].
 *
 * @see CustomerLoan
 */
class CustomerLoanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CustomerLoan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CustomerLoan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
