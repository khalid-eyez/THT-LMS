<?php

namespace frontend\admin_module\models;
use yii\base\Model;
use yii\helpers\Url;
use yii;


 class AccessManager extends Model
 {
    public $manager;

    public function __construct($config = [])
    {
        $this->manager=yii::$app->authManager;
        parent::__construct($config);
    }

    public function allRoles()
    {
        $roles=$this->purify($this->manager->getRoles());
   
       // print_r($this->childrolestree($this->item2extended($roles)));
       return $this->rolesTreeWidget($this->childrolestree($this->item2extended($roles)));
    }
    public function allPermissions()
    {
        $permissions=$this->manager->getPermissions();
        return $this->permissionsTreeWidget($this->childpermissionstree($this->item2extended($permissions)));
    }

    public function childrolestree($roles)
    {
     
      foreach($roles as $index=>$role)
      {
        $children=$this->item2extended($this->manager->getChildren($role->name));
        $role->node=$children;
        $this->childrolestree($children);
      }
       
       return $this->purify($roles);

    }
    public function childrentree($roles)
    {
     
      foreach($roles as $index=>$role)
      {
        $children=$this->item2extended($this->manager->getChildren($role->name));
        $role->node=$children;
        $this->childrolestree($children);
      }
       
       return $roles;

    }
    public function childpermissionstree($permissions)
    {
     
      foreach($permissions as $index=>$perm)
      {
        $children=$this->item2extended($this->manager->getChildren($perm->name));
        $perm->node=$children;
        $this->childpermissionstree($children);
      }
       
       return $permissions;

    }
    public function purify($roles)
    {
        foreach($roles as $index=>$role)
        {
            if($role->type==2)
            {
                unset($roles[$index]);
            }
        }
        return $roles;
    }
    public function rolesTreeWidget($roles)
    {
      static $html='';
      foreach($roles as $index=>$role)
      {
        $href=str_replace(" ","",$role->name);
        $type=($role->type==1)?'Role':'Permission';
        $html.='<div class="row shadow-sm p-3 pr " data-toggle="tooltip" data-title="'.$role->description.'">
        <div class="col" href="#'.$href.'" data-toggle="collapse"><a class="card-link" data-toggle="collapse" href="#'.$href.'">'.$role->name.'  </a>
        <span class="text-muted text-sm">['.$type.']</span><a class="ml-1 text-success text-sm text-bold">['.count($this->manager->getUserIdsByRole($role->name)).' Users]</a>
        <a class="ml-1 float-right itemdel" name="'.$role->name.'" type="'.$role->type.'" data-toggle="tooltip" data-title="Delete Item"><i class="fa fa-trash btn btn-sm  btn-danger"></i></a>
        <a href="'.Url::toRoute(['/access/item-view','item'=>urlencode(base64_encode($role->name)),'type'=>urlencode(base64_encode($role->type))]).'" class="ml-3 float-right" data-toggle="tooltip" data-title="Go To Item"><i class="fas fa-arrow-right btn btn-sm  btn-primary"></i></a>
        <span class="text-muted text-sm float-right">'.(($role->ruleName!=null)?"[Rule: ".$role->ruleName."]":"").'</span></div>';
        $html.='<div id="'.$href.'" class="collapse container" >';
        $html.='<div class="col">';
        $html.=$this->children2accordion($role->node,$role->name);
         $html.='</div></div></div>';
        
      }
  
      
       return $html;
    }

    public function childrenTreeWidget($roles)
    {
      static $html='';
      foreach($roles as $index=>$role)
      {
        $href=str_replace(" ","",$role->name);
        $type=($role->type==1)?'Role':'Permission';
        $html.='<div class="row shadow-sm p-3 pr " data-toggle="tooltip" data-title="'.$role->description.'">
        <div class="col" href="#'.$href.'" data-toggle="collapse"><a class="card-link" data-toggle="collapse" href="#'.$href.'">'.$role->name.'  </a>
        <span class="text-muted text-sm">['.$type.']</span><a class="ml-1 text-success text-sm text-bold">['.count($this->manager->getUserIdsByRole($role->name)).' Users]</a>
        <a class="ml-1 float-right itemdel" name="'.$role->name.'" type="'.$role->type.'" data-toggle="tooltip" data-title="Remove Child"><i class="fa fa-times btn btn-sm  btn-danger"></i></a>
        <a href="'.Url::toRoute(['/access/item-view','item'=>urlencode(base64_encode($role->name)),'type'=>urlencode(base64_encode($role->type))]).'" class="ml-3 float-right" data-toggle="tooltip" data-title="Go To Item"><i class="fas fa-arrow-right btn btn-sm  btn-primary"></i></a>
        <span class="text-muted text-sm float-right">'.(($role->ruleName!=null)?"[Rule: ".$role->ruleName."]":"").'</span></div>';
        $html.='<div id="'.$href.'" class="collapse container" >';
        $html.='<div class="col">';
        $html.=$this->children2accordion($role->node,$role->name);
         $html.='</div></div></div>';
        
      }
  
      
       return $html;
    }
    public function permissionsTreeWidget($roles)
    {
      static $html='';
      foreach($roles as $index=>$role)
      {
        $href=str_replace(" ","",$role->name);
        $type=($role->type==1)?'Role':'Permission';
        $html.='<div class="row shadow-sm p-3 pr " data-toggle="tooltip" data-title="'.$role->description.'">
        <div class="col" href="#'.$href.'" data-toggle="collapse"><a class="card-link" data-toggle="collapse" href="#'.$href.'">'.$role->name.'  </a>
        <span class="text-muted text-sm">['.$type.']</span><a class="ml-1 text-success text-sm text-bold">['.count($this->manager->getUserIdsByRole($role->name)).' Users]</a>
        <a class="ml-1 float-right itemdel" name="'.$role->name.'" type="'.$role->type.'" data-toggle="tooltip" data-title="Delete Item"><i class="fa fa-trash btn btn-sm  btn-danger"></i></a>
        <a href="'.Url::toRoute(['/access/item-view','item'=>urlencode(base64_encode($role->name)),'type'=>urlencode(base64_encode($role->type))]).'" class="ml-3 float-right" data-toggle="tooltip" data-title="Go To Item"><i class="fas fa-arrow-right btn btn-sm  btn-primary"></i></a>
        <span class="text-muted text-sm float-right">'.(($role->ruleName!=null)?"[Rule: ".$role->ruleName."]":"").'</span></div>';
        $html.='<div id="'.$href.'" class="collapse container" >';
        $html.='<div class="col">';
        $html.=$this->children2accordion($role->node,$role->name);
         $html.='</div></div></div>';
        
      }
  
      
       return $html;
    }
    public function children2accordion($children,$parent)
    {
       
        $childrenHTML='';

        foreach($children as $index=>$child)
        {
          $id=str_replace(" ","",$child->name);
          $type=($child->type==1)?'Role':'Permission';
          $childrenHTML.='<div class="row p-2 text-sm bg-white child border-top" data-toggle="tooltip" data-title="'.$child->description.'">
          <div class="col-sm-12 p-2" data-toggle="collapse" href="#'.$id.'">'.$child->name.' <span class="text-muted text-sm">['.$type.']</span><a class="ml-1 text-success text-sm text-bold">['.count($this->manager->getUserIdsByRole($child->name)).' Users]</a>
          <a href="'.Url::toRoute(['/access/item-view','item'=>urlencode(base64_encode($child->name)),'type'=>urlencode(base64_encode($child->type))]).'"class="ml-3 float-right" data-toggle="tooltip" data-title="Go To Item"><i class="fas fa-arrow-right text-primary border border-primary p-1"></i></a>
          <span class="text-muted text-sm float-right">'.(($child->ruleName!=null)?"[Rule: ".$child->ruleName."]":"").'</span></div>';
          $childrenHTML.='<div id="'.$id.'" class="collapse col-sm-12 container-fluid">';
          
          $childrenHTML.=$this->children2accordion($child->node,$child->name);
         
          $childrenHTML.='</div></div>';
        }
        //$childrenHTML.='</div>';
        return  $childrenHTML;
    }
    public function item2extended($itemsbuffer)
    {
        foreach($itemsbuffer as $index=>$item)
        {
           $itemsbuffer[$index]=new ExtendedItem($item);
        }

        return $itemsbuffer;
    }

    public function removeRule($rulename)
    {
      $rule=$this->manager->getRule($rulename);
      return $this->manager->remove($rule);
    }
    public function deleteItem($name,$type)
    {
      $item=($type==1)?$this->manager->getRole($name):$this->manager->getPermission($name);

      return $this->manager->remove($item);
    }
    public function removeChildren($parent,$type)
    {
      $parent=($type==1)?$this->manager->getRole($parent):$this->manager->getPermission($parent);
      return $this->manager->removeChildren($parent);
    }
    public function removeChild($parent,$type,$child)
    {
      $parent=($type==1)?$this->manager->getRole($parent):$this->manager->getPermission($parent);
      $child=($this->manager->getRole($child))?$this->manager->getRole($child):$this->manager->getPermission($child);
      return $this->manager->removeChild($parent,$child);
    }
    public function getChildren($name)
    {
      return $this->childrenTreeWidget($this->childrentree($this->item2extended($this->manager->getChildren($name))));
    }
    public function getAssignments($name)
    {
      return $this->manager->getUserIdsByRole($name);
    }
    public function deassignAllUsers($itemname)
    {
      $item=$this->manager->getRole($itemname);
      $users=$this->manager->getUserIdsByRole($itemname);

      foreach($users as $user)
      {
        if(!$this->manager->revoke($item,$user))
        {
          continue;
        }
      }

      return true;
    }
 }








?>