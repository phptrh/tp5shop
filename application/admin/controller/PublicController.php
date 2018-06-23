<?php
namespace app\admin\controller;  //定义控制器的命名空间
use think\Controller;  // 引入命名空间类
use app\admin\model\User;
class PublicController extends Controller {
    //用户登录
    public function login(){
    	if(request()->isPost()){
    		$postData=input('post.');
    		$result=$this->validate($postData,'User.login',[],true);
    		if($result!==true){
    			$this->error(implode(',', $result));
    		}
    		$userModel=new User();
    		if($userModel->checkUser($postData['username'],$postData['password'])){
    			$this->redirect('admin/index/index');
    		}else{
    			$this->error('用户名或密码错误');
    		}
    	}
    	return $this->fetch();
    }
    //退出登录
    public function logout(){
    	session('username',null);
    	$this->redirect('admin/public/login');
    }
}