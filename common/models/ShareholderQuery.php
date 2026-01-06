<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Shareholder]].
 *
 * @see Shareholder
 */
class ShareholderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Shareholder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Shareholder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
