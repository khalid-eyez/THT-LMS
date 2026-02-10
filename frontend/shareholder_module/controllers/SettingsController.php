<?php

namespace frontend\shareholder_module\controllers;

use common\models\Setting;
use yii\web\Controller;
use yii;
use yii\helpers\Html;

/**
 * Default controller for the `shareholder` module
 */
class SettingsController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionSettings()
    {
        return $this->renderAjax('settings');
    }
    public function actionAddSetting()
    {
        $model=new Setting();
        try
        {
        if($model->load(yii::$app->request->post()) && $model->save())
            {
               return $this->asJson(['success'=>'Setting added successfully!']);
            }
            else
                {
                   return $this->asJson(['error'=>'Setting adding failed! '.Html::errorSummary($model)]); 
                }
        }
        catch(\Exception $r)
        {
           return $this->asJson(['error'=>'Setting adding failed! An unknown error occurred !'.$r->getMessage()]);  
        }
    }

     public function actionUpdateSetting($settingID)
    {
        $model=Setting::findOne($settingID);
        try
        {
        if($model->load(yii::$app->request->post()) && $model->save())
            {
               return $this->asJson(['success'=>'Setting updated successfully!']);
            }
            else
                {
                   return $this->asJson(['error'=>'Setting updating failed! '.Html::errorSummary($model)]); 
                }
        }
        catch(\Exception $r)
        {
           return $this->asJson(['error'=>'Setting updating failed! An unknown error occurred !'.$r->getMessage()]);  
        }
    }
}
