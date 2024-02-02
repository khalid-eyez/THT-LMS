<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "referenceprefixes".
 *
 * @property int $prefID
 * @property string $prefix
 * @property string $type
 * @property int $branch
 *
 * @property Customreferences $customreference
 * @property Meetingreferences $meetingreference
 * @property Referencedocuments[] $referencedocuments
 * @property Branch $branch0
 */
class Referenceprefixes extends \yii\db\ActiveRecord
{
    public $name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'referenceprefixes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prefix', 'type', 'branch','name'], 'required'],
            [['branch'], 'integer'],
            [['prefix'], 'string', 'max' => 30],
            [['prefix'], 'unique', 'message' => "reference prefix already exists"],
            [['name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 15],
            [['branch'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch' => 'branchID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prefID' => 'Pref ID',
            'prefix' => 'Prefix',
            'type' => 'Type',
            'branch' => 'Branch',
        ];
    }

    public function afterSave($insert,$changedAttributes)
    {
        if($insert==true)
        {
            if($this->type=="custom")
            {
                $model=new Customreferences;
                $model->refID=$this->prefID;
                $model->referenceName=$this->name;
                $model->save();
            }
            else
            {
                $model=new Meetingreferences;
                $model->refID=$this->prefID;
                $model->referenceName=$this->name;
                $model->save(); 
            }
        }
        else
        {
           
            if($this->type=="custom")
            {
                $model=$this->customreference;
                $model->referenceName=$this->name;
                $model->save();
            }
            else
            {
                $model=$this->meetingreference;
                $model->referenceName=$this->name;
                $model->save(); 
            }
        
        }
        return parent::afterSave($insert,$changedAttributes);
    }
    public function afterFind()
    {
        if($this->type=="custom")
        {
            $this->name=($this->customreference!=null)?$this->customreference->referenceName:null;
        }
        else
        {
            $this->name=($this->meetingreference!=null)?$this->meetingreference->referenceName:null;
        }
        
        return parent::afterFind();
    }

    /**
     * Gets query for [[Customreferences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomreference()
    {
        return $this->hasOne(Customreferences::className(), ['refID' => 'prefID']);
    }

    /**
     * Gets query for [[Meetingreferences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingreference()
    {
        return $this->hasOne(Meetingreferences::className(), ['refID' => 'prefID']);
    }

    /**
     * Gets query for [[Referencedocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferencedocuments()
    {
        return $this->hasMany(Referencedocuments::className(), ['referencePrefix' => 'prefID']);
    }

    /**
     * Gets query for [[Branch0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch0()
    {
        return $this->hasOne(Branch::className(), ['branchID' => 'branch']);
    }

    public function getBranchLabels()
    {
        $branch=yii::$app->user->identity->member->branch;
        $labels=$this->find()->where(['branch'=>$branch])->all();

        return $labels;
    }
    public function getTitle()
    {
        if($this->type=="meeting")
        {
            return (new Meetingreferences)->getName($this->prefID);
        }
        else
        {
            return (new Customreferences)->getName($this->prefID); 
        }
    }

    public function isMeeting()
    {
        return $this->type=="meeting";
    }
}
