<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\Files;
use yii\helpers\Html;
use common\models\Referenceprefixes;
use frontend\models\ReferenceDocUploader;
use common\models\Referencedocuments;

class FilecabinetController extends Controller
{
    public function behaviors()
    {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
              
                [
                    'actions' => [
                        'filecabinet',
                        'new-label',
                        'update-label',
                        'delete-label',
                        'upload-document',
                        'view',
                        'download',
                        'delete-doc',
                        'update-doc',
                        'find-document'
                    ],
                    'allow' => true,
                    'roles' => ['CHAIRPERSON BR','GENERAL SECRETARY HQ','TREASURER HQ','CHAIRPERSON HQ','GENERAL SECRETARY BR','TREASURER BR','LABOUR OFFICER','ACCOUNTS ASSISTANT','ACCOUNTS','MGT SECRETARY'],
                ],
            ],
        ],
    ];
    }

    public function actionFilecabinet()
    {
        $labels=(new Referenceprefixes)->getBranchLabels();
        $model=new Referenceprefixes;
        return $this->render('filecabinet',['labels'=>$labels,'model'=>$model]);
    }
    public function actionNewLabel()
    {
      try
      {
      $model=new Referenceprefixes();
      $model->branch=yii::$app->user->identity->member->branch;
      $model->type="custom";
      if($model->load(yii::$app->request->post()) && $model->save())
      {
        yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Label Added Successfully !");
        return $this->redirect(yii::$app->request->referrer);
      }
      else
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Label Adding Failed ! ".Html::errorSummary($model));
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    catch(\Exception $l)
    {
      yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Label Adding Failed ! ".$l->getMessage());
      return $this->redirect(yii::$app->request->referrer);
    }
    }
    public function actionUpdateLabel($label)
    {
      $label=base64_decode(urldecode($label));

      $model = Referenceprefixes::findOne($label);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Label Updated Successfully !");
          return $this->redirect(yii::$app->request->referrer);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDeleteLabel()
    {
      $label=yii::$app->request->post("label");
      $label=Referenceprefixes::findOne($label);
      try
      {
      if($label!=null && $label->delete())
      {
        return $this->asJson(['deleted'=>'Label Deleted Successfully !']);
      }
      else
      {
        return $this->asJson(['failure'=>'Label  Deleting Failed ! '.Html::errorSummary($label)]);
      }
    }
    catch(\Exception $l)
    {
      return $this->asJson(['failure'=>'Label  Deleting Failed ! '.$l->getMessage()]);
    }
    }
    public function actionUploadDocument($label)
    {
      $label=base64_decode(urldecode($label));
      $model=new ReferenceDocUploader;
       if(yii::$app->request->isPost)
       {
       if($model->load(yii::$app->request->post()))
       {
         $file=UploadedFile::getInstance($model,'file');
         $model->file=$file;
         try
         {
           if($model->upload($label))
           {
            yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Document Uploaded Successfully !");
            return $this->redirect(yii::$app->request->referrer);
           }
           else
           {
            yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Document Uploading Failed !".Html::errorSummary($model));
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
    return $this->render('uploadDoc',['model'=>$model]);
    }
    public function actionView($label)
    {
      $label=base64_decode(urldecode($label));
      $label=Referenceprefixes::findOne($label);

      return $this->render('labelfiles',['label'=>$label]);
    }
    public function actionDownload($file)
    {
      try
      {
      $file=base64_decode(urldecode($file));

      $file=(Files::findOne($file));
      if($file==null){throw new \Exception("File Not Found");}
      $filename=$file->fileName;

      if(file_exists("storage/cabinetRepos/".$filename))
      {
        return yii::$app->response->sendFile("storage/cabinetRepos/".$filename,$file->referencedoc->docTitle.".".pathinfo($filename,PATHINFO_EXTENSION));
      }
      else
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> File Not Found");
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    catch(\Exception $f)
    {
      yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Downloading Failed !".$f->getMessage());
        return $this->redirect(yii::$app->request->referrer);
    }


    }

    public function actionDeleteDoc()
    {
      $doc=yii::$app->request->post("doc");
      $doc=Referencedocuments::findOne($doc);
      try
      {
      if($doc!=null && $doc->delete())
      {
        return $this->asJson(['deleted'=>'Document Deleted Successfully !']);
      }
      else
      {
        return $this->asJson(['failure'=>'Document  Deleting Failed ! '.Html::errorSummary($doc)]);
      }
    }
    catch(\Exception $d)
    {
      return $this->asJson(['failure'=>'Document Deleting Failed ! '.$d->getMessage()]);
    }
    }

    public function actionUpdateDoc($doc)
    {
      $doc=base64_decode(urldecode($doc));

      $model=Referencedocuments::findOne($doc);

      if(yii::$app->request->isPost)
      {
        if($model->load(yii::$app->request->post()) && $model->save())
        {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Document Updated Successfully !");
          return $this->redirect(['/filecabinet/view','label'=>urlencode(base64_encode($model->referencePrefix0->prefID))]);
        }
        else
        {
          yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Document Updating Failed !".Html::errorSummary($model));
          return $this->redirect(yii::$app->request->referrer);
        }
      }

      return $this->render('updateDoc',['model'=>$model]);
    }

   public function actionFindDocument()
   {
    $key=yii::$app->request->post('keyword');
    $result=(new Referencedocuments)->findByKeyword($key);

    return $this->render('searchResults',['results'=>$result]);

   }


    
   


}










?>