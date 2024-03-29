<?php

use common\models\Course;
use common\models\GroupAssignment;
use common\models\GroupGenerationAssignment;
use common\models\Groups;
use common\models\Student;
use common\models\StudentGroup;
use frontend\models\ClassRoomSecurity;
use frontend\models\GroupAssSubmit;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use common\models\Instructor;


/* @var $this yii\web\View */

$this->params['courseTitle'] ="<i class='fa fa-hdd-o'></i> Storage Management";
$this->title = 'Storage';
$this->params['breadcrumbs'] = [
    ['label'=>'Dashboard', 'url'=>Url::to('/home/dashboard')],
    ['label'=>$this->title]
];

?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->

        <div class="container-fluid">
           
            <div class="row">

                <section class="col-lg-12 p-4 ">
            
                           
                                        <div class="tab-content" id="custom-tabs-four-tabContent">


                                            <!-- ########################################### group by  instructor ######################################## -->

                                            <!-- Left col -->
                                            <section class="col-lg-12">
                                                   

                                                <div class="card-body " >
                                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                                    <div class="container border p-3 mb-3">
                                                            <span class="text-md text-bold pl-1 ml-2 mb-3">Boost Storage</span>
                                                                <form class="form-group mt-2" id="form" method="post" >
                                                                    <div class="row">
                                                                        <div class="col-sm-3">
                                                                    <select class="form-control filetype" name="filetype" required>
                                                                        <option value="" disabled selected>--File type--</option>
                                                                        <option value="material">Material</option>
                                                                        <option value="submits">Assignments Submits</option>


                                                                    </select>
                                                                            </div><div class="col-sm-3">
                                                                    <select class="form-control amount" name="amount" required>
                                                                        <option value="all">All</option>
                                                                        <option value="interval" selected>Within Time Interval (Inclusive)</option>
                                                                        <option value="interval_out" >Out of Time Interval (Exclusive)</option>


                                                                    </select></div><div class="col-sm-2">
                                                                    <input type="text" class="form-control from" name="from" placeholder="From" onmouseover="(this.type='date')"  onfocus="(this.type='date')" onblur="(this.type='text')" required></input>
                                                                    </div><div class="col-sm-2">
                                                                    <input type="text" class="form-control to" name="to" placeholder="To" onmouseover="(this.type='date')"  onfocus="(this.type='date')" onblur="(this.type='text')" required></input>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                                                                    <button type="submit" class="btn btn-default text-danger del"><i class="fa fa-trash "></i> Delete Files</button>
                                                                </div>
                                                                  </form>
                                                                        </div></div>
                                                    <div class="container border p-3" id="accordion">
                                                    <span class="text-md text-bold pl-1 ml-2">Storage Information</span>
                                                  
                                                         <pre><?=$info?></pre>
                 
                                                        

                                                            </div>
                                                           
                                

                                                              </div>

                                                            


                                                      

                                                    </div>
                                                    <!-- ########################################### GROUPS END ######################################## -->
                                              

                                              

                                            </section>
                                            <!-- ########################################### group by instructor end ######################################## -->
                                    
                             
                             

                                                           



                               

                                                                           <?php $script = <<<JS
                                                                            $(document).ready(function(){

                                                                                $(document).on('submit', '#form', function(z){
                                                                                         z.preventDefault();
                                                                                         
                                                                                                Swal.fire({
                                                                                            title: 'Delete Files? This is a terrible task,You won\'t be able to revert and deleted files are unrecoverable !',
                                                                                            text: "you might need to take appropriate precautions, make sure files being deleted are no longer in use unless you know what you are doing!",
                                                                                            icon: 'question',
                                                                                            showCancelButton: true,
                                                                                            confirmButtonColor: '#3085d6',
                                                                                            cancelButtonColor: '#d33',
                                                                                            confirmButtonText: 'Delete Anyways'
                                                                                            }).then((result) => {
                                                                                            if (result.isConfirmed) {
                                                                                               
                                                                                            
                                                                                                
                                                                                                var formdata=new FormData(this);
                                          
                                                                                            $.ajax({
                                                                                                url:'boost-storage',
                                                                                                method:'post',
                                                                                                async:false,
                                                                                                dataType:'JSON',
                                                                                                processData: false,
                                                                                                contentType: false,
                                                                                                data:formdata,
                                                                                                success:function(data){

                                                
                                                            
                                                                                                if(data.deleted){
                                                                                                    Swal.fire(
                                                                                                        'File(s) Deleted successfully !',
                                                                                                         data.deleted,
                                                                                                        'success'
                                                                                            )
                                                                                            


                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                    Swal.fire(
                                                                                                        'An error occured!',
                                                                                                        "Some files might have not been deleted, "+data.failure,
                                                                                                        'error'
                                                                                            )
                                                                                        

                                                                                                }
                                                                                                }
                                                                                            })

                                                                                            }
                                                                                            })

                                                                                            })  
                                                                                            
                                                                                            ////////

                                                                                            $('body').on('change','.amount',function(){

                                                                                            if($(this).val()=="all")
                                                                                            {
                                                                                           
                                                                                            $('.from').prop('disabled','disabled');
                                                                                            $('.to').prop('disabled','disabled');
                                                                
                                                                                            }
                                                                                            else
                                                                                            {
                                                        
                                                                                            $('.from').prop('disabled','');
                                                                                            $('.to').prop('disabled','');
                                                                                           

                                                                                            }

                                                                                            });
                                                                            });
                                                                            JS;
                                                                            $this->registerJs($script);

                                                                            ?>

                                                                        </div>

                                                                </div>



                                                <!-- ########################################### group by student end ######################################## -->
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            </section>



            </div>
        </div>
    </div><!--/. container-fluid -->
</div>


<?php
$script = <<<JS
$(document).ready(function(){
  $("#CourseList").DataTable({
    responsive:true,
  });
//Remember active tab
$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

localStorage.setItem('activeTab', $(e.target).attr('href'));

});

var activeTab = localStorage.getItem('activeTab');

if(activeTab){

$('#custom-tabs-four-tab a[href="' + activeTab + '"]').tab('show');

}
  
});

JS;
$this->registerJs($script);
?>
<?php 
$this->registerCssFile('@web/plugins/select2/css/select2.min.css');
$this->registerJsFile(
  '@web/plugins/select2/js/select2.full.js',
  ['depends' => 'yii\web\JqueryAsset']
);
$this->registerJsFile(
  '@web/js/create-assignment.js',
  ['depends' => 'yii\web\JqueryAsset'],

);



?>




