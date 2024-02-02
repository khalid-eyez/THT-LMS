<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\base\Exception;
use common\models\Files;
use common\models\Repository;
class ReposUpload extends Model{

    public $file;
    public $title;
    public $description;

    public function rules(){
        return [
           [['file','title',], 'required'],
            [['title'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
           [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, mp4, jpg, MKV, avi, png, doc, docx, xlsx, xls, ppt, pptx, zip, rar','message'=>'file type not allowed'],


        ];

    }
    public function upload(){
      
        
        $fileName =uniqid().'.'.$this->file->extension;
        $repos = new Repository();
        $transaction=yii::$app->db->beginTransaction();
        $repos->docTitle = $this->title;
        $repos->docDescription=$this->description;
        $repos->userID=Yii::$app->user->id;
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $repos->uploadTime=date("Y-m-d H:i:s");
        try
        {
            if($this->file->saveAs('storage/repos/'.$fileName))
            {
                $filebox=new Files;
                $filebox->fileName=$fileName;
                if(!$filebox->save())
                {
                    throw new \Exception("Could not save file, try again !"); 
                }
                $repos->file=$filebox->fileID;

            }
            else
            {
                throw new \Exception("Could not save file, try again !"); 
            }

            if(!$repos->save())
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