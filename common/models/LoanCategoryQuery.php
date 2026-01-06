<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[LoanCategory]].
 *
 * @see LoanCategory
 */
class LoanCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LoanCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LoanCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
