<?php

namespace common\models;
use Exception;
use yii\base\UserException;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 */
class Setting extends \yii\db\ActiveRecord
{

 public function behaviors()
    {
        return [
             'auditBehaviour'=>'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }
     public function beforeSave($insert)
     {
        if(!$insert && $this->isAttributeChanged("name"))
            {
                throw new UserException("The setting name cannot be updated ! ");
            }
        return parent::beforeSave($insert);
     }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            ['name','unique'],
            ['name','trim'],
            [['value'], 'safe'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    /**
     * {@inheritdoc}
     * @return SettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingQuery(get_called_class());
    }

    public function getSettingValue($name)
    {
        $setting=$this->find()->where(['name'=>$name])->one();
        if($setting==null)
            {
                throw new Exception($name." not found");
            }
        return $setting->value;
    }

}
