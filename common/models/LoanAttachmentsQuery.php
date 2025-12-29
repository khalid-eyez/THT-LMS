<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[LoanAttachments]].
 *
 * @see LoanAttachments
 */
class LoanAttachmentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LoanAttachments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LoanAttachments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
