<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\base\Exception;
use common\models\Files;
use common\models\Repository;
use common\models\Meeting;
use common\models\Meetingdocuments;
class MeetingDocUploader extends Model{

    public $file;
    public $title;
    public $meetingID;

    public function rules(){
        return [
           [['file','title'], 'required'],
           ['meetingID','integer'],
            [['title'], 'string', 'max' => 100],
           [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, mp4, jpg, MKV, avi, png, doc, docx, xlsx, xls, ppt, pptx, zip, rar','message'=>'file type not allowed'],


        ];

    }
    public function upload(){
      
        
        $fileName =uniqid().'.'.$this->file->extension;
        $meetingdoc = new Meetingdocuments();
        $transaction=yii::$app->db->beginTransaction();
        $meetingdoc->title = $this->title;
        $meetingdoc->meetingID=$this->meetingID;
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $meetingdoc->dateUploaded=date("Y-m-d H:i:s");
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
                $meetingdoc->fileID=$filebox->fileID;

            }
            else
            {
                throw new \Exception("Could not save file, try again !"); 
            }

            if(!$meetingdoc->save())
            {
               throw new \Exception("Could not save document, try again !");  
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
?>