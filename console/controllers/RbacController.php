<?php

namespace console\controllers;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
class RbacController extends Controller
{

public function actionInit(){
        $this->stdout("Initializing RBAC ... \n ");
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        $roles = [
                'ADMIN'=>'A role for the administrator of the system',
                'USER'=>'A role for the normal user of the system'
        ];
        //creating the roles
        $this->stdout("Creating Initial Roles... \n ");
        foreach($roles as $role=>$description){
        $userrole = $auth->createRole($role);
        $userrole->description=$description;
        $auth->add($userrole);
        $this->stdout("created \"$userrole->name\" Role \n");
        }

        //defining initial permissions
        $permissions=[
               'view_auth_data'=>'A permission for viewing authorization data',
               'add_rule'=>'A permission for adding a rule to the RBAC system',
               'remove_children'=>'A permission for removing children (roles & permissions) from their parent role or permission',
               'delete_item'=>'A permission for deleting an item (role or permission)',
               'remove_all_rules'=>'A permission for removing all rules from the RBAC sytem',
               'remove_rule'=>'A permission for removing a single rule from the RBAC system',
               'remove_all_permissions'=>'A permission for removing all permissions from the RBAC system',
               'remove_all_auth_data'=>'A permission for removing all authorization data from the RBAC system',
               'remove_all_roles_assignments'=>'A permission for removing all roles assignments from the RBAC system',
               'remove_all_roles'=>'A permission for removing all roles from the RBAC system',
               'remove_child'=>'A permission for removing a single child (role or permission) from its parent (role or permission)',
               'add_children'=>'A permission for adding children (roles or permissions) to the parent (role or permission)',
               'discharge_user'=>'A permission for discharging a user from a role or permission',
               'item_add_users'=>'A permission for assigning users to an item (role or permission)',
               'discharge_all_users'=>'A permission for discharging all users from a role or permission',
               'item_view'=>'A permission for viewing item (role or permission) details',
               'add_perm'=>'A permission for adding a permission to the RBAC system',
               'add_role'=>'A permission for adding a role to the RBAC system',
               'view_users_list'=>'A permission for viewing users list',
               'view_audit_data'=>'A permmission for viewing audit data',
               'view_storage_info'=>'A permission for viewing storage information',
               'create_user'=>'A permission for creating a new user',
               'reset_user_password'=>'A permission for resetting the user password',
               'update_user_info'=>'A permission for updating user information',
               'delete_user'=>'A permission for deleting a user',
               'unlock_user'=>'A permission for unlocking a user',
               'lock_user'=>'A permission for locking a user',
               'view_dashboard'=>'A user level permission for viewing their dashboard'

//add user permissions

        ];
       //creating all the permissions
       $this->stdout("Creating Initial Permissions... \n ");
       foreach($permissions as $permission=>$description)
       {
        $userpermission=$auth->createPermission($permission);
        $userpermission->description=$description;
        $auth->add($userpermission);
        $this->stdout("Created \"$userpermission->name\" Permission \n");
       }

       //defining some super permissions

       $superpermissions=[
        'user_manage'=>[
                'create_user',
                'reset_user_password',
                'update_user_info',
                'delete_user',
                'unlock_user',
                'lock_user',
                'view_users_list'
        ],
                'access_control'=>[
                     'view_auth_data',
                     'add_rule',
                     'remove_children',
                     'delete_item',
                     'remove_all_rules',
               'remove_rule',
               'remove_all_permissions',
               'remove_all_auth_data',
               'remove_all_roles_assignments',
               'remove_all_roles',
               'remove_child',
               'add_children',
               'discharge_user',
               'item_add_users',
               'discharge_all_users',
               'item_view',
               'add_perm',
               'add_role'
                ]
        ];
        //adding the super permissions
       $this->stdout("Creating Super Permissions... \n ");
        foreach($superpermissions as $superpermission=>$subpermissions)
        {
                $_permission=$auth->createPermission($superpermission);
                $auth->add($_permission);
                $this->stdout("Created \"$_permission->name\" Permission \n");
                //assigning subpermissions

                foreach($subpermissions as $subpermission){

                        $_subpermission=$auth->getPermission($subpermission);
                        $auth->addChild($_permission,$_subpermission);
                        $this->stdout("Assigned \"$_subpermission->name\" Permission \n",Console::FG_BLUE);
                }
        }

        //defining admin permissions

        $admin_permissions=[
                'user_manage',
                'access_control',
                'view_audit_data',
               'view_storage_info',
        ];

        //adding permissions to the ADMIN role
        $this->stdout("Assigning ADMIN permissions... \n ");
        $adminrole=$auth->getRole('ADMIN');
        foreach($admin_permissions as $perm)
        {
                $_adminpermission=$auth->getPermission($perm);
                $auth->addChild($adminrole,$_adminpermission);
                $this->stdout("Assigned \"$_adminpermission->name\" Permission \n",Console::FG_BLUE);
        }

        //adding permission to the USER role
        $this->stdout("Assigning USER permissions... \n ");
        $userrole=$auth->getRole('USER');
        $userpermission=$auth->getPermission('view_dashboard');
        $auth->addChild($userrole,$userpermission);

        $this->stdout("Assigned \"$userpermission->name\" Permission \n",Console::FG_BLUE);
        $this->stdout("************RBAC Initialization Complete********* \n ");
  
        }

       

}
