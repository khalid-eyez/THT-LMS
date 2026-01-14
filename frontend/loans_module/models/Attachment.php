<?php
namespace frontend\loans_module\models;

use yii\base\Model;
use yii;

class Attachment extends Model
{
    public $file;
    public function rules()
    {
        return [
                ['file', 'required', 'message' => 'Please upload at least one file.'],
                [
                'file',
                'file',
                'skipOnEmpty' => false,                   
                'maxSize' => 10 * 1024 * 1024,  
                'tooBig' => 'The file must be smaller than 10 MB.',
                'extensions' => 'jpg, png, pdf',
                'wrongExtension' => 'Only files with these extensions are allowed: {extensions}.',
                ]
        ];
    }
    public function saveFile()
    {
            $file=$this->file;
            $filename='/uploads/'.uniqid() . '.' . $file->extension;
            $path= Yii::getAlias('@webroot').$filename;
            if($file->saveAs($path))
            {
                return $filename;
            }

      
    }


}