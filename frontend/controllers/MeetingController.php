<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\MeetingDocUploader;
use yii\web\UploadedFile;
use common\models\Repository;
use common\models\Files;
use yii\helpers\Html;
use common\models\Meeting;
use common\models\Meetingnames;
use common\models\Meetingdocuments;
use common\models\Meetingcancel;
use frontend\models\DocBuilder;
use frontend\models\Participationcancel;
use frontend\models\MeetingInvitations;

class MeetingController extends Controller
{
    public function behaviors()
    {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
              
                [
                    'actions' => [
                        'download',
                        'meetings',
                        'confirm-participation',
                        'cancel-reason',
                        'sign-attendance',
                        'cancel-participation',
                        'invitation-downloader'
                    ],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => [
                        'update',
                        
                       
                    ],
                    'allow' => true,
                    'roles' => ['CHAIRPERSON BR','GENERAL SECRETARY HQ','TREASURER HQ','CHAIRPERSON HQ','GENERAL SECRETARY BR','TREASURER BR','LABOUR OFFICER','ACCOUNTS ASSISTANT','ACCOUNTS','MGT SECRETARY'],
                ],
                [
                  'actions' => [
                      'create-meeting',
                      'update-meeting',
                      'upload',
                      'attendance',
                      'delete',
                      'delete-doc',
                      'meeting-cancel',
                      'meeting-uncancel',
                      'download-excel-attendance',
                      'download-pdf-attendance',
                      'participation-cancel-reason',
                      'disapprove-participation-cancel', 
                      'approve-participation-cancel'
                  ],
                  'allow' => true,
                  'roles' =>(new Meetingnames)->getMeetingCallers()
              ]
            ],
        ],
    ];
    }

    public function actionMeetings()
    {
        $meetings=(new Meeting)->getViewableMeetings();
        return $this->render('meetings',['meetings'=>$meetings]);
    }
    public function actionCreateMeeting()
    {
    $model = new Meeting();
    try
    {
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Meeting Called Successfully !");
      return $this->redirect(yii::$app->request->referrer);
    }
    else
    {
      yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Meeting Calling Failed ! ".Html::errorSummary($model));
      return $this->redirect(yii::$app->request->referrer);
    }
  }
  catch(\Exception $m)
  {
    yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Meeting Calling Failed ! ".$m->getMessage());
    return $this->redirect(yii::$app->request->referrer);
  }
   }
   public function actionUpdateMeeting($id)
    {
      $id=base64_decode(urldecode($id));
      $model = Meeting::findOne($id);
      $model->status="Updated";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Meeting Updated Successfully !");
          return $this->redirect(yii::$app->request->referrer);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpload($meeting)
    {
       $meeting=base64_decode(urldecode($meeting));
       $model=new MeetingDocUploader;
       if(yii::$app->request->isPost)
       {
       if($model->load(yii::$app->request->post()))
       {
         $model->meetingID=$meeting;
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
  public function actionAttendance($meeting)
  {
    $meeting=base64_decode(urldecode($meeting));
    $meeting=Meeting::findOne($meeting);
    if(!$meeting->isExpired())
    {
      yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Could not load attendance! Attendance is not accessible before the meeting date !");
      return $this->redirect(yii::$app->request->referrer);
    }
    if(yii::$app->request->isPost)
    {
        
        try
        {
        if($meeting->load(yii::$app->request->post()))
        {
        if($meeting->updateAttendance())
        {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Attendance Updated Successfully !");
          return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
          yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Attendance Updating Failed !".Html::errorSummary($meeting));
            return $this->redirect(yii::$app->request->referrer);
        }
      }
      else
      {
        if($meeting->deleteAttendance())
        {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Attendance Updated Successfully !");
          return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
          yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Attendance Updating Failed !".Html::errorSummary($meeting));
            return $this->redirect(yii::$app->request->referrer);
        }
      }
      }
      catch(\Exception $e)
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Attendance Updating Failed !".$e->getMessage());
        return $this->redirect(yii::$app->request->referrer);
      }
        
      
    
    }
    return $this->render("attendance",['meeting'=>$meeting]);
  }
  public function actionSignAttendance($meeting)
  {
    $meeting=base64_decode(urldecode($meeting));
    $meeting=Meeting::findOne($meeting);

    try
    {
      if($meeting->signAttendance())
      {
        yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Attendance Signed Successfully !");
        return $this->redirect(yii::$app->request->referrer);
      }
      else
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Attendance signing Failed !".Html::errorSummary($meeting));
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    catch(\Exception $s)
    {
      yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> ".$s->getMessage());
      return $this->redirect(yii::$app->request->referrer);
    }
  }
    public function actionDownload($file)
    {
      $file=base64_decode(urldecode($file));

      $file=(Files::findOne($file));
      $filename=$file->fileName;

      if(file_exists("storage/meetingRepos/".$filename))
      {
        return yii::$app->response->sendFile("storage/meetingRepos/".$filename,$file->meetingdoc->title.".".pathinfo($filename,PATHINFO_EXTENSION));
      }
      else
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> File Not Found");
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
      $meeting=yii::$app->request->post("meet");
      $meeting=Meeting::findOne($meeting);
      try
      {
      if($meeting!=null && $meeting->delete())
      {
        return $this->asJson(['deleted'=>'Meeting Deleted Successfully !']);
      }
      else
      {
        return $this->asJson(['failure'=>'Meeting  Deleting Failed ! '.Html::errorSummary($meeting)]);
      }
    }
    catch(\Exception $d)
    {
      return $this->asJson(['failure'=>'Meeting  Deleting Failed ! '.$d->getMessage()]);
    }
    }

    public function actionDeleteDoc()
    {
      $meetingdoc=yii::$app->request->post("doc");
      $meetingdoc=Meetingdocuments::findOne($meetingdoc);
      try
      {
      if($meetingdoc!=null && $meetingdoc->delete())
      {
        return $this->asJson(['deleted'=>'Document Deleted Successfully !']);
      }
      else
      {
        return $this->asJson(['failure'=>'Document  Deleting Failed ! '.Html::errorSummary($meetingdoc)]);
      }
    }
    catch(\Exception $d)
    {
      return $this->asJson(['failure'=>'Document Deleting Failed ! '.$d->getMessage()]);
    }
    }
    public function actionConfirmParticipation($meeting)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);

      try
      {
        if($meeting->confirmParticipation())
        {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Confirmation Successfully !");
          return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
          yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Confirmation Failed !".Html::errorSummary($meeting));
          return $this->redirect(yii::$app->request->referrer);
        }
      }
      catch(\Exception $w)
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Confirmation Failed !".$w->getMessage());
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    public function actionMeetingCancel($meeting)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meetingcancel=new Meetingcancel();

      if(yii::$app->request->isPost)
      {
        try
        {
        if($meetingcancel->load(yii::$app->request->post()))
        {
          $meetingcancel->meetingID=$meeting;
          $meetingcancel->type="meetingCancel";
          $meetingcancel->status="Approved";
          $meetingcancel->memberID=yii::$app->user->identity->id;
         
          if($meetingcancel->save())
          {
            yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Meeting Cancelled Successfully !");
            return $this->redirect(yii::$app->request->referrer);
          }
          else
          {
            yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Meeting Cancellation Failed !".Html::errorSummary($meetingcancel));
            return $this->redirect(yii::$app->request->referrer);
          }
        }
      }
        catch(\Exception $m)
        {
          yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Meeting Cancellation Failed !".$m->getMessage());
          return $this->redirect(yii::$app->request->referrer);
        }
       
      }
      return $this->render('meetingcancel',['model'=>$meetingcancel]);
    }
    public function actionMeetingUncancel($meeting)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meetingcancel=Meeting::findOne($meeting);
      try
      {
        if($meetingcancel->uncancel())
        {
          yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Meeting Uncancelled Successfully !");
          return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
          yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Meeting Uncancel Failed !".Html::errorSummary($meetingcancel));
          return $this->redirect(yii::$app->request->referrer);
        }
      }
       catch(\Exception $d)
       {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Meeting Uncancel Failed !".$d->getMessage());
        return $this->redirect(yii::$app->request->referrer);
       }
    }
    public function actionCancelReason($meeting)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);

      return $this->render('cancelreason',['meeting'=>$meeting]);
    }
    public function actionDownloadExcelAttendance($meeting)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);
      
      try
      {
      (new DocBuilder)->downloadExcelAttendance($meeting);
      }
      catch(\Exception $ex)
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Download Failed !".$ex->getMessage());
        return $this->redirect(yii::$app->request->referrer);
      }

    }
    public function actionDownloadPdfAttendance($meeting)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);
      
      try
      {
      (new DocBuilder)->attendancePdfdownloader($meeting);
      }
      catch(\Exception $ex)
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Download Failed !".$ex->getMessage());
        return $this->redirect(yii::$app->request->referrer);
      }

    }
    public function actionCancelParticipation($meeting)
    {
      $model=new Participationcancel;
      $meeting=base64_decode(urldecode($meeting));
      if((Meeting::findOne($meeting))->isExpired())
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Participation Cancellation Failed ! Could not cancel expired meeting !");
        return $this->redirect(yii::$app->request->referrer);
      }
       if(yii::$app->request->isPost)
       {
       if($model->load(yii::$app->request->post()))
       {
         $file=UploadedFile::getInstance($model,'file');
         $model->file=$file;
         try
         {
           if($model->upload($meeting))
           {
            yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Participation Cancelled Successfully !");
            return $this->redirect("/meeting/meetings");
           }
           else
           {
            yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Participation Cancellation Failed !".Html::errorSummary($model));
            return $this->redirect(yii::$app->request->referrer);
           }
         }
         catch(\Exception $f)
         {
            yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Participation Cancellation Failed !".$f->getMessage());
            return $this->redirect(yii::$app->request->referrer);
         }
       }

      
    }
      return $this->render('participationcancel',['model'=>$model]);
    }
    public function actionInvitationDownloader($meeting)
    {
      try{

      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);

      $invitation=(new MeetingInvitations);
      $invitation->loadMeeting($meeting);
      $invitation->generatePdfletter();
      }
      catch(\Exception $l)
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Download Failed ! try again".$l->getMessage());
        return $this->redirect(yii::$app->request->referrer);
      }

    }
    public function actionParticipationCancelReason($meeting,$member)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);
      $member=base64_decode(urldecode($member));
      
      return $this->render('participation_cancelreason',['meeting'=>$meeting,'member'=>$member]);
    }
    public function actionApproveParticipationCancel($meeting,$member)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);
      $member=base64_decode(urldecode($member));
      if($meeting->approveParticipationCancel($member))
      {
        yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Reason approved Successfully !");
        return $this->redirect(yii::$app->request->referrer);
      }
      else
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> An Error occurred, try again !");
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    public function actionDisapproveParticipationCancel($meeting,$member)
    {
      $meeting=base64_decode(urldecode($meeting));
      $meeting=Meeting::findOne($meeting);
      $member=base64_decode(urldecode($member));

      if($meeting->disapproveParticipationCancel($member))
      {
        yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Reason disapproved Successfully !");
        return $this->redirect(yii::$app->request->referrer);
      }
      else
      {
        yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> An Error occurred, try again !");
        return $this->redirect(yii::$app->request->referrer);
      }
    }







}










?>