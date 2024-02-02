<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\base\Exception;
use common\models\Files;
use common\models\Referencedocuments;
class ReferenceDocUploader extends Model{

    public $file;
    public $title;
    public function rules(){
        return [
           [['file','title'], 'required'],
            [['title'], 'string', 'max' => 100],
           [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, mp4, jpg, MKV, avi, csv, png, doc, docx, xlsx, xls, ppt, pptx, zip, rar','message'=>'file type not allowed'],


        ];

    }
    public function upload($label){
      
        
        $fileName =uniqid().'.'.$this->file->extension;
        $refdoc = new Referencedocuments();
        $transaction=yii::$app->db->beginTransaction();
        $refdoc->docTitle = $this->title;
        $refdoc->docType="document";
        $refdoc->referencePrefix=$label;
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $refdoc->year=date("Y");
        $refdoc->dateUploaded=date("Y-m-d H:i:s");
        try
        {
            if($this->file->saveAs('storage/cabinetRepos/'.$fileName))
            {
                $filebox=new Files;
                $filebox->fileName=$fileName;
                if(!$filebox->save())
                {
                    throw new \Exception("Could not save file, try again !"); 
                }
                $refdoc->fileID=$filebox->fileID;

            }
            else
            {
                throw new \Exception("Could not save file, try again !"); 
            }

            if(!$refdoc->save())
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