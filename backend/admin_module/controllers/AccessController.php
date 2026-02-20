<?php

namespace frontend\admin_module\controllers;

use frontend\admin_module\models\AddPerm;
use frontend\admin_module\models\AddRole;
use frontend\admin_module\models\AddRule;
use frontend\admin_module\models\AddUser;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\admin_module\models\AccessManager;
use yii\helpers\Html;
use frontend\admin_module\models\AddChildren;


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
                         'access-manager'
                        ],
                        'allow' => true,
                        'roles' => ['view_auth_data'],
                    ],
                    [
                        'actions' => [
                         'add-rule'
                        ],
                        'allow' => true,
                        'roles' => ['add_rule'],
                    ],
                    [
                        'actions' => [
                         'remove-children'
                        ],
                        'allow' => true,
                        'roles' => ['remove_children'],
                    ],
                    [
                        'actions' => [
                         'delete-item'
                        ],
                        'allow' => true,
                        'roles' => ['delete_item'],
                    ],
                    [
                        'actions' => [
                         'remove-all-rules'
                        ],
                        'allow' => true,
                        'roles' => ['remove_all_rules'],
                    ],
                    [
                        'actions' => [
                         'remove-rule'
                        ],
                        'allow' => true,
                        'roles' => ['remove_rule'],
                    ],
                    [
                        'actions' => [
                         'remove-all-permissions'
                        ],
                        'allow' => true,
                        'roles' => ['remove_all_permissions'],
                    ],
                    [
                        'actions' => [
                         'remove-all-auth-data'
                        ],
                        'allow' => true,
                        'roles' => ['remove_all_auth_data'],
                    ],
                    [
                        'actions' => [
                         'remove-all-roles-assignments'
                        ],
                        'allow' => true,
                        'roles' => ['remove_all_roles_assignments'],
                    ],
                    [
                        'actions' => [
                         'remove-all-roles'
                        ],
                        'allow' => true,
                        'roles' => ['remove_all_roles'],
                    ],
                    [
                        'actions' => [
                         'remove-child'
                        ],
                        'allow' => true,
                        'roles' => ['remove_child'],
                    ],
                    [
                        'actions' => [
                         'add-children'
                        ],
                        'allow' => true,
                        'roles' => ['add_children'],
                    ],
                    [
                        'actions' => [
                         'discharge-user'
                        ],
                        'allow' => true,
                        'roles' => ['discharge_user'],
                    ],
                    [
                        'actions' => [
                         'add-users'
                        ],
                        'allow' => true,
                        'roles' => ['item_add_users'],
                    ],
                    [
                        'actions' => [
                         'deassign-all-users'
                        ],
                        'allow' => true,
                        'roles' => ['discharge_all_users'],
                    ],
                    [
                        'actions' => [
                         'item-view'
                        ],
                        'allow' => true,
                        'roles' => ['item_view'],
                    ],
                    [
                        'actions' => [
                         'add-perm'
                        ],
                        'allow' => true,
                        'roles' => ['add_perm'],
                    ],
                    [
                        'actions' => [
                         'add-role'
                        ],
                        'allow' => true,
                        'roles' => ['add_role'],
                    ],
                   
                ],
            ],
        ];
    }
/**
 * Renders the access control  interface
 * @return string
 * @author khalid <thewinner016@gmail.com>
 */
public function actionAccessManager()
{
    $roles=(new AccessManager())->allRoles();
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
/**
 * Adds a rule to the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
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
/**
 * Removes all roles from the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionRemoveAllRoles()
{
    if(yii::$app->request->isPost)
    {
        yii::$app->authManager->removeAllRoles();
    }
    yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> All Roles Removed Successfully !');
    return $this->asJson(['res'=>null]);


}
/**
 * Removes all roles assignments from the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionRemoveAllRolesAssignments()
{
    if(yii::$app->request->isPost)
    {
        yii::$app->authManager->removeAllAssignments();
    }
    yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> All Roles Assignments Removed Successfully !');
    return $this->asJson(['res'=>null]);


}
/**
 * removes all authorization data from the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionRemoveAllAuthData()
{
    if(yii::$app->request->isPost)
    {
        yii::$app->authManager->removeAll();
    }
    yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> All Authorization Data Removed Successfully !');
    return $this->asJson(['res'=>null]);


}
/**
 * removes all permissions from the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionRemoveAllPermissions()
{
    if(yii::$app->request->isPost)
    {
        yii::$app->authManager->removeAllPermissions();
    }
    return $this->asJson(['res'=>null]);
}
/**
 * removes a rule from the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
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
/**
 * removes all rules from the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionRemoveAllRules()
{
    yii::$app->authManager->removeAllRules();

    return $this->asJson(['removed'=>'All Rules Removed']);
}
/**
 * deletes an item (role/permission) from the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
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
/**
 * removes children from its parent
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
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
/**
 * removes a single child from its parent
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
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
/**
 * Assigns a user to a role/permission
 * @param mixed $name the name of the item (role or permission)
 * @return string|Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionAddUsers($name)
{
    $model=new AddUser();
    $name=base64_decode(urldecode($name));
   if(yii::$app->request->isPost)
   {
    try
    {
      if($model->load(yii::$app->request->post()) && $model->addTo($name))
      {
        yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> User(s) added successfully !');
        return $this->redirect(yii::$app->request->referrer);
      }
      else
      {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> User(s) adding failed ! '.Html::errorSummary($model));
        return $this->redirect(yii::$app->request->referrer);
      }
    }
    catch(\Exception $t)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> User(s) adding failed ! '.$t->getMessage());
        return $this->redirect(yii::$app->request->referrer);
    }
   }

   return $this->render('adduser',['model'=>$model]);
}
/**
 * discharges a user from a role/permission
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionDischargeUser()
{
    $manager=yii::$app->authManager;
    $item=yii::$app->request->post('item');
    $item=base64_decode(urldecode($item));
    $item=($manager->getRole($item))?$manager->getRole($item):$manager->getPermission($item);
    $userid=yii::$app->request->post('user');

    
    if($manager->revoke($item,$userid))
    {
        return $this->asJson(['removed'=>'revoking successful']);
    }
    else
    {
        return $this->asJson(['failure'=>'revoking failed']); 
    }
}
/**
 * Adds children(roles or permissions) to an item (role or permission)
 * @param mixed $name the name of the item (role or permission) to be assigned children
 * @param mixed $type the type of the item (role or permission) to be assigned children
 * @return string|Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
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
/**
 * Displays item (role or permission) details
 * @param mixed $item the name of the item (role or permission)
 * @param mixed $type the type of the item 
 * @return string the rendered page
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionItemView($item,$type)
{
    $name=base64_decode(urldecode($item));
    $type=base64_decode(urldecode($type));
    $item=($type==1)?yii::$app->authManager->getRole($name):yii::$app->authManager->getPermission($name);
    $children=(new AccessManager())->getChildren($name);
    $users=(new AccessManager())->getAssignments($name);

    return $this->render('itemView',['children'=>$children,'users'=>$users,'item'=>$item]);
}
/**
 * Discharges all users from an item (role or permission)
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionDeassignAllUsers()
{
    $item=yii::$app->request->post('item');
    $item=base64_decode(urldecode($item));
    if((new AccessManager())->deassignAllUsers($item))
    {
        yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> All Users Discharged Successfully !');
        return $this->redirect(yii::$app->request->referrer);  
    }
    else
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Users Discharging Failed ! ');
        return $this->redirect(yii::$app->request->referrer);  
    }
}
/**
 * Adds a role to the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
public function actionAddRole()
{
    $model=new AddRole();
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
/**
 * Adds a permission to the RBAC system
 * @return Yii\web\Response
 * @author khalid <thewinner016@gmail.com>
 * @since 1.0.0
 */
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
