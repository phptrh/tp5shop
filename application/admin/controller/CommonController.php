<?php
namespace app\admin\controller;  //定义控制器的命名空间
use think\Controller;  // 引入命名空间类
class CommonController extends Controller {
    //防翻墙验证
    public function _initialize(){
    	if(!session('username')){
    		$this->error('请先登录',url('admin/public/login'));
    	}
    	$now_ca=strtolower(request()->controller().'/'.request()->action());
    	$visitorAuth=session('visitorAuth');
    	//超级管理员
    	if($visitorAuth=="*"||strtolower(request()->controller())=='index'){
    		return;
    	}else{
    		if(!in_array($now_ca, $visitorAuth)){
    			exit('访问错误');
    		}
    	}
    }

}