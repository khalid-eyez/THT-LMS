<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Cashbook]].
 *
 * @see Cashbook
 */
class CashbookQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Cashbook[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Cashbook|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
