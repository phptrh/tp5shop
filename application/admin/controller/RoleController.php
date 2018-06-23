<?php
namespace app\admin\controller;  //定义控制器的命名空间
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;
class RoleController extends CommonController {
    //添加角色
    public function add(){
    	if(request()->isPost()){
    		$postData=input('post.');
    		$result=$this->validate($postData,'Role.add',[],true);
    		if($result!==true){
    			$this->error(implode(',', $result));
    		}
    		$roleModel=new Role();
    		if($roleModel->save($postData)){
    			$this->success("添加成功",url("admin/role/index"));
    		}else{
    			$this->error("添加失败");
    		}
    	}
    	//获取以auth_id为下标的权限列表树
    	$authModel=new Auth();
    	$auths=$authModel->getAuthsSon();
    	$children=[];
    	foreach($auths as $auth){
    		$children[$auth['pid']][]=$auth['auth_id'];
    	}
    	return $this->fetch('',['auths'=>$auths,'children'=>$children]);
    }

    //展示列表
    public function index(){
    	$lists=Db::query('select t1.*,GROUP_CONCAT(t2.auth_name) as all_auth from sh_role as t1 LEFT JOIN sh_auth as t2 on FIND_IN_SET(t2.auth_id,t1.auth_id_list) GROUP BY role_id');
    	return $this->fetch('',['lists'=>$lists]);
    }
    //角色编辑
    public function upd(){
    	if(request()->isPost()){
    		$postData=input('post.');
    		$result=$this->validate($postData,'Role.upd',[],true);
    		if($result!==true){
    			$this->error(implode(',', $result));
    		}
    		$roleModel=new Role();
    		if($roleModel->isUpdate(true)->save($postData)!==false){
    			$this->success("编辑成功",url("admin/role/index"));
    		}else{
    			$this->error("编辑失败");
    		}
    	}
    	$role_id=input('role_id');
    	$roleModel=new Role();
    	$data=$roleModel->find($role_id);
    	//获取以auth_id为下标的权限列表树
    	$authModel=new Auth();
    	$auths=$authModel->getAuthsSon();
    	$children=[];
    	foreach($auths as $auth){
    		$children[$auth['pid']][]=$auth['auth_id'];
    	}
    	return $this->fetch('',['auths'=>$auths,'children'=>$children,'data'=>$data]);
    }
    //ajax删除
    public function ajaxDel(){
    	$role_id=input('role_id');
    	if($role_id==1){
  			return json(['code'=>'-1','message'=>'超级管理员无法删除']);
    	}else{
    		$roleModel=new Role();
    		if($roleModel->destroy($role_id)){
  				return json(['code'=>'200','message'=>'删除成功']);
    		}else{
  				return json(['code'=>'-1','message'=>'删除失败']);
    			
    		}
    	}
    }
}