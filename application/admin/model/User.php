<?php
namespace app\admin\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class User extends Model{
     
    //表主键 
    protected $pk="user_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;

    protected static function init(){
    	User::event('before_insert',function($user){
    		$user['password']=md5($user['password'].config('pwd_salt'));
    	});
        User::event('before_update',function($user){
            if(isset($user['password'])){
                if($user['password']==''){
                    unset($user['password']);
                }else{
                    $user['password']=md5($user['password'].config('pwd_salt'));
                }
            }
            
        });
    }
    //登录验证
    public function checkUser($username,$pwd){
        $data=[
            'username'=>$username,
            'password'=>md5($pwd.config('pwd_salt'))
        ];
        $userinfo=User::where($data)->find();
        if($userinfo){
            //研制成功保存用户名到session
            session('username',$username);
            $this->writeAuthToSession($userinfo['role_id']);
            return true;
        }else{
            return false;
        }
    }
    public function writeAuthToSession($role_id){
        $role=Role::find($role_id);
        $auth_id_list=$role['auth_id_list'];
        //取出超级管理员权限
        if($auth_id_list=='*'){
            //存入超级管理员权限
            session('visitorAuth','*');
            $oneAuth=Auth::where('pid',0)->select()->toArray();
            foreach($oneAuth as $k=>$auth){
                $oneAuth[$k]['sonAuth']=Auth::where('pid',$auth['auth_id'])->select()->toArray();
            }
        }else{
            $visitorAuth=[];
            //非超级管理员权限
            $auths=Auth::where('auth_id','in',$auth_id_list)->select()->toArray();
            $oneAuth=[];
            //遍历数组取出一级权限
            foreach($auths as $k=>$auth){
                if($auth['pid']==0){
                    $oneAuth[]=$auth;
                }
                $visitorAuth[]=strtolower($auth['auth_c'].'/'.$auth['auth_a']);
            }
            //取出访问者权限
            session('visitorAuth',$visitorAuth);
            //二级循环取出二级权限
            foreach($oneAuth as $k=>$auth){
                foreach($auths as $kk=>$s_auth){
                    if($s_auth['pid']==$auth['auth_id']){
                        $oneAuth[$k]['sonAuth'][]=$s_auth;
                    }
                }
            }
        }
        session('menuAuth',$oneAuth);
    }

}