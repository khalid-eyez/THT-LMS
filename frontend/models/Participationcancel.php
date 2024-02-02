<?php

namespace frontend\models;
use yii\helpers\Html;
use Yii;
use yii\base\Model;
use common\models\Files;
use common\models\Meetingcancel;
use common\models\Meeting;

class Participationcancel extends Model
{
    public $reason;
    public $file;
    public $type="participationCancel";
   
    public function rules()
    {
        return [
            [['reason','type'], 'required'],
            [['reason'], 'string', 'max' => 255],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, jpg, png, doc, docx, xlsx, xls, ppt, pptx','message'=>'file type not allowed'],
        ];
    }

    public function upload($meeting){
      
        if((Meeting::findOne($meeting))->isExpired())
        {
            throw new \Exception("Could not cancel expired meeting !"); 
        }
        $fileName =uniqid().'.'.$this->file->extension;
        $meetingcancel = new Meetingcancel();
        $transaction=yii::$app->db->beginTransaction();
        $meetingcancel->meetingID = $meeting;
        $meetingcancel->type=$this->type;
        $meetingcancel->memberID=yii::$app->user->identity->member->memberID;
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $meetingcancel->canceltime=date("Y-m-d H:i:s");
        $meetingcancel->reason=$this->reason;
        $meetingcancel->status="pending";
        try
        {
            if($this->file->saveAs('storage/meetingRepos/'.$fileName))
            {
                $filebox=new Files;
                $filebox->fileName=$fileName;
                if(!$filebox->save())
                {
                    throw new \Exception("Could not save file, try again !"); 
                }
                $meetingcancel->fileID=$filebox->fileID;

            }
            else
            {
                throw new \Exception("Could not save file, try again !"); 
            }

            if(!$meetingcancel->save())
            {
               throw new \Exception("Could not save cancel data, try again !");  
            }

            $transaction->commit();

            return true;
        }
        catch(\Exception $f)
        {
            $transaction->rollBack();
            throw $f;

        }
        
     
      
        
       

        
    }


    
}
