<?php
namespace app\admin\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class Auth extends Model {
     
    //表主键 
    protected $pk="auth_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;
    //获取权限列表树
    public function getAuthsSon($pid=0){
    	$auths=$this->select()->toArray();
    	return $this->_getAuthsSon($auths,$pid);
    }
    public function _getAuthsSon($data,$pid=0,$deep=1){
    	static $lists=[];
    	foreach($data as $auth){
    		if($auth['pid']==$pid){
    			$auth['deep']=$deep;
    			$lists[$auth['auth_id']]=$auth;
    			$this->_getAuthsSon($data,$auth['auth_id'],$deep+1);
    		}
    	}
    	return $lists;
    }
}