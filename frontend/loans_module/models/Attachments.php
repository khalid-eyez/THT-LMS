<?php
namespace frontend\loans_module\models;

use yii\base\Model;
use yii;

class Attachments extends Model
{
    public $files=[];
    public function rules()
    {
        return [
                ['files', 'required', 'message' => 'Please upload at least one file.'],
                [
                'files',
                'file',
                'skipOnEmpty' => false,       
                'maxFiles' => 5,             
                'maxSize' => 10 * 1024 * 1024,  
                'tooBig' => 'Each file must be smaller than 10 MB.',
                'extensions' => 'jpg, png, pdf',
                'wrongExtension' => 'Only files with these extensions are allowed: {extensions}.',
                ]
        ];
    }
    public function saveFiles()
    {
        $uploaded=[];
        foreach($this->files as $file)
        {
            $filename='/uploads/'.uniqid() . '.' . $file->extension;
            $path= Yii::getAlias('@webroot').$filename;
            if($file->saveAs($path))
            {
                array_push($uploaded,$filename);
            }

        }
        return $uploaded;
    }


}