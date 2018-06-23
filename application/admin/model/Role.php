<?php
namespace app\admin\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class Role extends Model {
     
    //表主键 
    protected $pk="role_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;
    //前钩子将角色权限id转为字符串
    protected static function init(){
    	Role::event('before_insert',function($role){
    		$role['auth_id_list']=implode(',',$role['auth_id_list']);
    	});
    	Role::event('before_update',function($role){
    		$role['auth_id_list']=implode(',',$role['auth_id_list']);
    	});
    }
}