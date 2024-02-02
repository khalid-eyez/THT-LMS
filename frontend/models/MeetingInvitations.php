<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use Mpdf\Mpdf;
use common\models\Referencedocuments;
use common\models\Files;

class MeetingInvitations extends Model{

    
   public $orgName="CHAMA CHA WAFANYAKAZI WA TAASISI ZA ELIMU YA JUU TANZANIA ";
   public $SLP;
   public $tel;
   public $fax;
   public $website;
   public $email;
   public $ref;
   public $meeting;
   public $ending1="Tunaomba utumie barua hii kufuatilia upatikanaji wa ruhusa kutoka kwa mwajiri wako.";
   public $ending2="Ni matumaini yetu kuwa tutaendelea kupata ushirikiano wako endelevu.";
   public $moto='"NIA NA MWELEKEO WETU DAIMA NI KUJENGA, TAIFA KWANZA"';
   public $headerline="Tafadhali husika na somo tajwa hapo juu.";


   public function loadMeeting($meeting)
   {

    $this->SLP=$meeting->announcedFrom0->pobox;
    $this->tel=$meeting->announcedFrom0->telphone;
    $this->fax=$meeting->announcedFrom0->fax;
    $this->website=$meeting->announcedFrom0->website;
    $this->email=$meeting->announcedFrom0->email;
    $this->loadRef($meeting);
    $this->meeting=$meeting;

   }
    public function generatePdfletter()
    {
      $filename=$this->meeting->meetingTitle."_Invitation.pdf";
      $filename=str_replace(" ","-",$filename);
      $filesaveName=uniqid().".pdf";
      $filesave="storage/cabinetRepos/".$filesaveName;
      $document=new Referencedocuments;
      if($document->isInvitationOffered($this->meeting->meetingID))
      {
        $invitation=$document->getInvitation($this->meeting->meetingID);
        $filename=$invitation->file->fileName;
        return $this->downloadExisting($filename);
      }
      $document->docTitle="Meeting Invitation";
      $document->docType="letter";
      $document->offeredTo=yii::$app->user->identity->member->memberID;
      $document->meetingID=$this->meeting->meetingID;
      $document->year=date("Y");
      date_default_timezone_set('Africa/Dar_es_Salaam');
      $document->dateUploaded=date("Y-m-d H:i:s");
      $document->referencePrefix=$this->meeting->type0->referencepref->ref->prefID;
      $file=new Files;
      $file->fileName=$filesaveName;
      $file->save();
      $document->fileID=$file->fileID;
      $document->save();

        $mpdf = new Mpdf(['orientation' => 'P']);
        $mpdf->setFooter('{PAGENO}');
        $stylesheet = file_get_contents('css/capdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetWatermarkText('THTU - THTU',0.09);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML('<div class="float-left heading"><img class="logo" src="img/logo.png" width="85px" height="85px"/>
        <div class="org">
        <font size=4><b>'.$this->orgName.'</b></font><br>
        <font size=3>SLP:<b>'.$this->SLP.'</b></font><br>
        <font size=3>SIMU:<b>'.$this->tel.'</b></font>
        <font size=3>NUKUSHI:<b>'.$this->fax.'</b></font><br>
        <font size=3>TOVUTI:<b>'.$this->website.'</b></font>
        <font size=3>Barua Pepe:<b>'.$this->email.'</b></font>

        </div></div>',2);

        $mpdf->WriteHTML('<div class="ref"><div class="left"><font size=4><b>Kumb. Na. </b>'.$this->ref.str_pad($document->docID, 4, "0", STR_PAD_LEFT).'.'.date("Y").'</font></div><div class="right"><font size=4><b>Tarehe: </b>'.date("d/m/Y").'</font></div></div>',3);
        $mpdf->WriteHTML('<div class="receiver">Ndg <b>'.yii::$app->user->identity->member->fullName().'</b>,<br>'.$this->receiverBuilder().',<br><b>THTU.</b></div>',2);

        $mpdf->WriteHTML("<p class='obj'><font size=5>YAH: <b>".$this->headingBuilder()."</b></font></p>",3);
        $mpdf->WriteHTML("<p><font>".$this->headerline."</font></p>",3);
        $mpdf->WriteHTML("<p><font>".$this->introBuilder()."</font></p>",3);
        $mpdf->WriteHTML("<p><font>".$this->descBuilder()."</font></p>",3);

        if($this->meeting->type0->name!="CENTRAL COMMITTEE MEETING -HQ")
        {
        $mpdf->WriteHTML("<p><font>".$this->ending1."</font></p>",3);
        }
        $mpdf->WriteHTML("<p><font>".$this->ending2."</font></p>",3);
        $mpdf->WriteHTML("<p class='moto'><font size=3><b>".$this->moto."</b></font></p>",3);
        $mpdf->WriteHTML("<p class='sign'><font >".$this->meeting->announcedBy0->fullName()."<br>Kny:<b>".$this->signatureRank()."</font></b></p>",3);
       
          
       
        $mpdf->Output($filesave,"F");
        
        //saving reference details

        
        $mpdf->Output($filename,"D");

        return null;
    
  }

  private function loadRef($meeting)
  {

    $this->ref=$meeting->type0->referencepref->ref->prefix;

  }
  public function downloadExisting($filename)
  {
    $fileloc="storage/cabinetRepos/".$filename;
    $name=$this->meeting->meetingTitle;
    return yii::$app->response->sendFile($fileloc,$name.".".pathinfo($filename,PATHINFO_EXTENSION));
  }
  private function headingBuilder()
  {
    $meetingtitle=strtoupper($this->meeting->meetingTitle);
      $heading="MWALIKO WA KUHUDHURIA {$meetingtitle}";
      return $heading;
  }
  private function receiverBuilder()
  {
    $receiver="";
    $meetingName=$this->meeting->type0->name;
    if($meetingName=="CENTRAL COMMITTEE MEETING -HQ")
    {
      $receiver="Mjumbe wa Kamati Kuu ya Chama";
    }
    else if($meetingName=="GENERAL ASSEMBLY -HQ")
    {

    }
    else if($meetingName=="GENERAL COUNCIL MEETING -HQ")
    {
      
    }
    else
    {
      $receiver="Mwanachama";
    }
    return $receiver;
  }
 

  private function introBuilder()
  {
    $intro="";
    $meetingName=$this->meeting->type0->name;
    if($meetingName=="CENTRAL COMMITTEE MEETING -HQ")
    {
      $intro="Ukiwa mjumbe wa kamati tajwa hapo juu, unaarifiwa kuwa kutakuwa na {$this->meeting->meetingTitle} tarehe {$this->meeting->meetingTime} {$this->meeting->venue} na kitadumu muda wa masaa {$this->meeting->duration}.";
    }
    else if($meetingName=="GENERAL ASSEMBLY -HQ")
    {
      $intro="Unataarifiwa kwamba chama kitafanya {$this->meeting->meetingTitle} {$this->meeting->description} utakaofanyika tarehe {$this->meeting->date}, {$this->meeting->venue} kuanzia saa {$this->meeting->time} na utadumu muda wa masaa {$this->meeting->duration}.";
    }
    else if($meetingName=="GENERAL COUNCIL MEETING -HQ")
    {
      $intro="Chama cha Wafanyakazi wa Taasisi za Elimu ya Juu Tanzania (THTU) kinatarajia kufanya {$this->meeting->meetingTitle} {$this->meeting->description} utakaofanyika tarehe {$this->meeting->meetingTime} katika {$this->meeting->venue} kuanzia saa {$this->meeting->time} na utadumu muda wa masaa {$this->meeting->duration}.";
    }
    else
    {

    }
    return $intro;
  }
  private function descBuilder()
  {
    $desc="";
    $meetingName=$this->meeting->type0->name;
    if($meetingName=="CENTRAL COMMITTEE MEETING -HQ")
    {
      $desc="Hivyo kwa barua hii unaombwa kufuatilia ruhusa kwa Mwajiri wako ili kuweza kuhudhuria kikao hicho, kwani ushiriki wako ni muhimu kwa utendaji bora wa chama na Taifa kwa ujumla. Aidha, endapo utakuwa na ajenda ya ziada katika ajenda ya mengineyo unaombwa kuwasilisha siku tatu kabla.";
    }
    else if($meetingName=="GENERAL ASSEMBLY -HQ")
    {
      $desc="Kwa barua hii tunayo heshima kukualika uhudhurie mkutano huo muhimu kwa maendeleo ya Chama.";
    }
    else if($meetingName=="GENERAL COUNCIL MEETING -HQ")
    {
      $desc="Kwa barua hii tunayo heshima kukualika uhudhurie mkutano huo muhimu kwa maendeleo ya Chama.";
    }
    else
    {
      $desc="Kwa barua hii tunayo heshima kukualika uhudhurie mkutano huo muhimu kwa maendeleo ya Chama.";
    }
    return $desc;
  }
  
  
  private function signatureRank()
  {
    $rank=$this->meeting->announcedBy0->getRank();
    if($rank="GENERAL SECRETARY HQ")
    {
      return "KATIBU MKUU CHAMA";
    }
    else if($rank="WOMEN'S COORDINATOR HQ")
    {
      return "MRATIBU MKUU WANAWAKE CHAMA";
    }
    else if($rank="DEPUTY WOMEN'S COORDINATOR HQ")
    {
      return "MRATIBU MSAIDIZI WANAWAKE CHAMA";
    }
    else if($rank="GENERAL SECRETARY BR")
    {
      return "KATIBU MKUU TAWI";
    }
    else
    {
       return "MRATIBU WANAWAKE TAWI";
    }
  }
  

  
}
?>