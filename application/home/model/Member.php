<?php
namespace app\home\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class Member extends Model {
     
    //表主键 
    protected $pk="member_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;
    //注册入库前钩子
    protected static function init(){
    	Member::event('before_insert',function($member){
    		$member['password']=md5($member['password'].config('pwd_salt'));
    	});
        Member::event('before_update',function($member){
            $member['password']=md5($member['password'].config('pwd_salt'));
        });
    }
    //登录验证
    public function checkUser($username,$pwd){
    	$condition=[
    		'username'=>$username,
    		'password'=>md5($pwd.config('pwd_salt'))
    	];
    	$userinfo=Member::where($condition)->find();
    	if($userinfo){
    		session('home_username',$userinfo['username']);
    		session('member_id',$userinfo['member_id']);
    		return true;
    	}else{
    		return false;
    	}
    }
}