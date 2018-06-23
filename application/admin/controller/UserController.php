<?php
namespace app\admin\controller;  //定义控制器的命名空间
use think\Controller;  // 引入命名空间类
use app\admin\model\User;
use app\admin\model\Role;
class UserController extends CommonController {
    //添加后台用户
    public function add(){
    	if(request()->isPost()){
    		$postData=input('post.');
    		$result=$this->validate($postData,'User.add',[],true);
    		if($result!==true){
    			$this->error(implode(',', $result));
    		}
    		$userModel=new User();
    		if($userModel->allowField(true)->save($postData)){
    			$this->success("添加成功",url("admin/user/index"));
    		}else{
    			$this->error("添加失败");
    		}
    	}
        $roles=Role::select();
    	return $this->fetch('',['roles'=>$roles]);
    }
    //用户列表
    public function index(){
    	$lists=User::paginate('3');
    	return $this->fetch('',['lists'=>$lists]);
    }
    public function upd(){
    	if(request()->isPost()){
    		$postData=input('post.');
    		$result=$this->validate($postData,'User.upd',[],true);
    		if($result!==true){
    			$this->error(implode(',', $result));
    		}
    		$userModel=new User();
    		if($userModel->allowField(true)->isUpdate(true)->save($postData)!==false){
    			$this->success("修改成功",url("admin/user/index"));
    		}else{
    			$this->error("修改失败");
    		}
    	}
    	$user_id=input('user_id');
    	$data=User::find($user_id);
    	return $this->fetch('',['data'=>$data]);
    }
    //ajax修改用户状态(可用?禁用)
    public function ajaxChangeActive(){
        $user_id=input('user_id');
        $is_active=input('is_active')?0:1;
        $data=['user_id'=>$user_id,'is_active'=>$is_active];
        $userModel=new User();
        if($userModel->update($data)!==false){
            return json(['code'=>200,'is_active'=>$is_active]);
        }else{
            return json(['code'=>'-1','is_active'=>$is_active]);
        }
    }
}