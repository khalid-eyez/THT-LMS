<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CustomerLoans]].
 *
 * @see CustomerLoans
 */
class CustomerLoansQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CustomerLoans[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CustomerLoans|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
