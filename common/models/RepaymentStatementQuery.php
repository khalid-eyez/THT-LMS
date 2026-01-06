<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[RepaymentStatement]].
 *
 * @see RepaymentStatement
 */
class RepaymentStatementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RepaymentStatement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RepaymentStatement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
