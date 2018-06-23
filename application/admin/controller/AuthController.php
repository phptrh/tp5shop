<?php
namespace app\admin\controller;  //定义控制器的命名空间
use app\admin\model\Auth;
class AuthController extends CommonController {
    //添加权限
    public function add(){
    	if(request()->isPost()){
    		$postData=input('post.');
            if($postData['pid']==0){
                $result=$this->validate($postData,'Auth.ding',[],true);
            }else{
                $result=$this->validate($postData,'Auth.add',[],true);
            }
    		if($result!==true){
    			$this->error(implode(',', $result));
    		}
    		$AuthModel=new Auth();
    		if($AuthModel->allowField(true)->save($postData)){
    			$this->success("添加成功",url("admin/auth/index"));
    		}else{
    			$this->error("添加失败");
    		}
    	}
        $authModel=new Auth();
        $lists=$authModel->getAuthsSon();
    	return $this->fetch('',['lists'=>$lists]);
    }
    //权限列表
    public function index(){
        $authModel=new Auth();
        $lists=$authModel->getAuthsSon();
        return $this->fetch('',['lists'=>$lists]);
    }

    //编辑权限
    public function upd(){
        if(request()->isPost()){
            $postData=input('post.');
            if($postData['pid']==0){
                $result=$this->validate($postData,'Auth.ding',[],true);
            }else{
                $result=$this->validate($postData,'Auth.add',[],true);
            }
            if($result!==true){
                $this->error(implode(',', $result));
            }
            $AuthModel=new Auth();
            if($AuthModel->allowField(true)->update($postData)){
                $this->success("编辑成功",url("admin/auth/index"));
            }else{
                $this->error("编辑失败");
            }
        }
        $auth_id=input('auth_id');
        $authModel=new Auth();
        $data=Auth::find($auth_id);
        $lists=$authModel->getAuthsSon();
        return $this->fetch('',['lists'=>$lists,'data'=>$data]);
    }
    //ajax权限删除
    public function ajaxDel(){
        $auth_id=input('auth_id');
        $authModel=new Auth();
        $auths=$authModel->getAuthsSon($auth_id);
        if($auths){
            return json(['code'=>'-1','message'=>'还有子级,无法删除']);
        }else{
            if($authModel->destroy($auth_id)){
                return json(['code'=>200,'message'=>'删除成功']);
            }else{
                return json(['code'=>200,'message'=>'删除失败']);
            }
        }
    }   
}