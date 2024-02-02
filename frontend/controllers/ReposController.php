<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\ReposUpload;
use yii\web\UploadedFile;
use common\models\Repository;
use common\models\Files;
use yii\helpers\Html;

class ReposController extends Controller
{
    public function behaviors()
    {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
              
                [
                    'actions' => [
                        'docs',
                        'download'
                    ],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => [
                        'upload',
                        'update',
                        'delete'
                    ],
                    'allow' => true,
                    'roles' => ['CHAIRPERSON HQ','GENERAL SECRETARY HQ','TREASURER HQ','LABOUR OFFICER HQ' ,'ACCOUNTS ASSISTANT','ACCOUNTS','MGT SECRETARY','CHAIRPERSON BR','GENERAL SECRETARY BR','TREASURER BR','LABOUR OFFICER BR' ,'ACCOUNTS ASSISTANT','ACCOUNTS','MGT SECRETARY'],
                ],
            ],
        ],
    ];
    }

    public function actionDocs()
    {
        $docs=Repository::find()->orderBy(['docID'=>'SORT_DESC'])->all();
        return $this->render('docs',['docs'=>$docs]);
    }

    public function actionUpload()
    {
       $model=new ReposUpload;
       if($model->load(yii::$app->request->post()))
       {
         $file=UploadedFile::getInstance($model,'file');
         $model->file=$file;
         try
         {
           if($model->upload())
           {
            yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Document Uploaded Successfully !");
            return $this->redirect(yii::$app->request->referrer);
           }
           else
           {
            yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Document Uploading Failed !".Html::errorSummary());
            return $this->redirect(yii::$app->request->referrer);
           }
         }
         catch(\Exception $f)
         {
            yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Document Uploading Failed !".$f->getMessage());
            return $this->redirect(yii::$app->request->referrer);
         }
       }
    }
    public function actionDownload($file)
    {
      try
      {
      $file=base64_decode(urldecode($file));

      $file=(Files::findOne($file));

      if($file==null){throw new \Exception("File Not Found");}
      $filename=$file->fileName;
       
      if(file_exists("storage/repos/".$filename))
      {
        return yii::$app->response->sendFile("storage/repos/".$filename,$file->repository->docTitle);
      }
      else
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> File Not Found");
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    catch(\Exception $f)
    {
      yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> ".$f->getMessage());
      return $this->redirect(yii::$app->request->referrer);
    }

      
    }

    public function actionUpdate($doc)
    {
      $doc=base64_decode(urldecode($doc));

      $model=Repository::findOne($doc);

      if(yii::$app->request->isPost)
      {
        if($model->load(yii::$app->request->post()) && $model->save())
        {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Document Updated Successfully !");
          return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
          yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Document Updating Failed !".Html::errorSummary($model));
          return $this->redirect(yii::$app->request->referrer);
        }
      }

      return $this->render('_form',['model'=>$model]);
    }
    public function actionDelete()
    {
      $doc=yii::$app->request->post("doc");
      $doc=Repository::findOne($doc);
      try
      {
      if($doc!=null && $doc->delete())
      {
        return $this->asJson(['deleted'=>'Document Deleted Successfully !']);
      }
      else
      {
        return $this->asJson(['failure'=>'Document Deleting Failed ! '.Html::errorSummary($doc)]);
      }
    }
    catch(\Exception $d)
    {
      return $this->asJson(['failure'=>'Document Deleting Failed ! '.$d->getMessage()]);
    }
    }







}










?>