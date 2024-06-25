<?php

namespace frontend\controllers;

use frontend\Models\AddPerm;
use frontend\Models\AddRole;
use frontend\Models\AddRule;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AccessManager;
use yii\helpers\Html;
use common\rules\TestRule;
use frontend\models\AddChildren;

class AccessController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                  
                    [
                        'actions' => [
                         
                        ],
                        'allow' => true,
                        'roles' => ['ADMIN'],
                    ],
                ],
            ],
        ];
    }

public function actionAccessManager()
{
    $roles=(new AccessManager())->allRoles();

    //print($roles);
  
    // return null;
    $permissions=(new AccessManager())->allPermissions();
    $rolemodel=new AddRole();
    $permmodel=new AddPerm();
    $rulemodel=new AddRule();
    $rules=yii::$app->authManager->getRules();

    return $this->render("accesscontrol",[
        'roles'=>$roles,
        'permissions'=>$permissions,
        'rolemodel'=>$rolemodel,
        'permmodel'=>$permmodel,
        'rules'=>$rules,
        'rulemodel'=>$rulemodel
     ]);
}
public function actionAddRule()
{
   $rulemodel=new AddRule();
   try
   {
    if($rulemodel->load(yii::$app->request->post()) && $rulemodel->addrule())
    {
        yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Rule added successfully !');
        return $this->redirect(yii::$app->request->referrer); 
    }
    else
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Rule adding failed ! '.Html::errorSummary($rulemodel));
        return $this->redirect(yii::$app->request->referrer); 
    }
}
catch(\Exception $e)
{
    yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Rule adding failed ! '.$e->getMessage());
    return $this->redirect(yii::$app->request->referrer); 
}
}

public function actionRemoveRule()
{
    $rulename=yii::$app->request->post('name');

    if((new AccessManager())->removeRule($rulename))
    {
        return $this->asJson(['removed'=>'Rule removed successfully']);
    }
    else
    {
        return $this->asJson(['failure'=>'Rule removing failed']);  
    }
}

public function actionRemoveAllRules()
{
    yii::$app->authManager->removeAllRules();

    return $this->asJson(['removed'=>'All Rules Removed']);
}
public function actionDeleteItem()
{
    $name=yii::$app->request->post('name');
    $type=yii::$app->request->post('type');   
    if((new AccessManager())->deleteItem($name,$type))
    {
        return $this->asJson(['deleted'=>'Item Deleted successfully']);  
    }
    else
    {
        return $this->asJson(['failure'=>'Item deleting failed']);    
    }
}
public function actionRemoveChildren()
{
    $parentname=yii::$app->request->post('parent'); 
    $type=yii::$app->request->post('type'); 
    if((new AccessManager())->removeChildren($parentname,$type))
    {
        return $this->asJson(['removed'=>'Children Removed Successfully']);  
    }
    else
    {
        return $this->asJson(['failure'=>'Children Removing failed']);    
    }
}
public function actionRemoveChild()
{
    $parentname=yii::$app->request->post('parent'); 
    $type=yii::$app->request->post('type'); 
    $child=yii::$app->request->post('item'); 
    $type=base64_decode(urldecode($type));
    $parentname=base64_decode(urldecode($parentname));
    try
    {
    if((new AccessManager())->removeChild($parentname,$type,$child))
    {
        return $this->asJson(['removed'=>'Child Removed Successfully']);  
    }
    else
    {
        return $this->asJson(['failure'=>'Child Removing failed']);    
    }
}
catch(\Exception $m)
{
    return $this->asJson(['failure'=>'Child Removing failed '.$m->getMessage()]);    
}
}
public function actionAddChildren($name,$type)
{
    $model=new AddChildren;
    $name=base64_decode(urldecode($name));
    $type=base64_decode(urldecode($type));
   if(yii::$app->request->isPost)
   {
    try
    {
      if($model->load(yii::$app->request->post()) && $model->addTo($name,$type))
      {
        yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Children added successfully !');
        return $this->redirect(yii::$app->request->referrer);
      }
      else
      {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Children adding failed ! '.Html::errorSummary($model));
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    catch(\Exception $t)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Children adding failed ! '.$t->getMessage());
        return $this->redirect(yii::$app->request->referrer);
    }
   }

   return $this->render('addchildren',['model'=>$model]);
}
public function actionItemView($item,$type)
{
    $name=base64_decode(urldecode($item));
    $type=base64_decode(urldecode($type));
    $item=($type==1)?yii::$app->authManager->getRole($name):yii::$app->authManager->getPermission($name);
    $children=(new AccessManager())->getChildren($name);
    $users=(new AccessManager())->getAssignments($name);

    return $this->render('itemView',['children'=>$children,'users'=>$users,'item'=>$item]);
}

public function actionDeassignAllUsers($item)
{
    $item=base64_decode(urldecode($item));
    if((new AccessManager())->deassignAllUsers($item))
    {
        yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> All Users Removed Successfully !');
        return $this->redirect(yii::$app->request->referrer);  
    }
    else
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Users Removing Failed ! ');
        return $this->redirect(yii::$app->request->referrer);  
    }
}
public function actionAddRole()
{
    $model=new AddRole();
    //print_r(yii::$app->request->post());return null;
    try
    {
        if($model->load(yii::$app->request->post()) && $model->addRole())
        {
            yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Role added successfully !');
            return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Role adding failed ! '.Html::errorSummary($model));
            return $this->redirect(yii::$app->request->referrer); 
        }
    }
    catch(\Exception $r)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Role adding failed ! '.$r->getMessage());
        return $this->redirect(yii::$app->request->referrer); 
    }
}

public function actionAddPerm()
{
    $model=new AddPerm();
    try
    {
        if($model->load(yii::$app->request->post()) && $model->addPerm())
        {
            yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Permission added successfully !');
            return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Permission adding failed ! '.Html::errorSummary($model));
            return $this->redirect(yii::$app->request->referrer); 
        }
    }
    catch(\Exception $r)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Permission adding failed ! '.$r->getMessage());
        return $this->redirect(yii::$app->request->referrer); 
    }
}
   
}
