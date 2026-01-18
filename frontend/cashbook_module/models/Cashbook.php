<?php
namespace frontend\cashbook_module\models;
use yii\base\Model;
use yii\base\UserException;
use common\models\Cashbook as Book;
use common\helpers\UniqueCodeHelper;

class Cashbook extends Model{

public $record=[];

public function __construct($data,$config = [])
{
    $this->record=$data;
    return parent::__construct($config);
}
public function save($reference_prefix,$suffix_no)
{
    if($this->record==null)
        {
            throw new UserException('Cashbook record empty');
        }
                $cashbook=new Book();
                $cashbook->credit=$this->record['credit'];
                $cashbook->debit=$this->record['debit'];
                $cashbook->reference_no=UniqueCodeHelper::generate($reference_prefix).'-'.$suffix_no.date("Y");
                $cashbook->description=$this->record['description'];
                $cashbook->payment_document=$this->record['payment_doc'];
                $cashbook->category=$this->record['category'];
                $cashbook->balance=$cashbook->updatedBalance();

                if(!$cashbook->save())
                    {
                        throw new \Exception("unable to save cashbook record.". json_encode($cashbook->getErrors()));
                    }

                    return $cashbook;
}


}