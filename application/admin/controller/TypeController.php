<?php
namespace app\admin\controller;  //定义控制器的命名空间
use app\admin\model\Type;
use app\admin\model\Attribute;
class TypeController extends CommonController {
    
	//添加商品类型
	public function add(){
		if(request()->isPost()){
			$postData=input('post.');
			$postData['mark']=trim($postData['mark']);
			$result=$this->validate($postData,'Type.add',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$typeModel=new Type();
			if($typeModel->allowField(true)->save($postData)){
				$this->success("添加成功",url("admin/type/index"));
			}else{
				$this->error("添加失败");
			}
		}
		return $this->fetch();
	}
	//商品类型列表
	public function index(){
		$lists=Type::select();
		return $this->fetch('',['lists'=>$lists]);
	}
	public function upd(){
		if(request()->isPost()){
			$postData=input('post.');
			$postData['mark']=trim($postData['mark']);
			$result=$this->validate($postData,'Type.upd',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$typeModel=new Type();
			if($typeModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success("编辑成功",url("admin/type/index"));
			}else{
				$this->error("编辑失败");
			}
		}
		$type_id=input('type_id');
		$data=Type::find($type_id);
		return $this->fetch('',['data'=>$data]);
	}
	//ajax删除类型
	public function ajaxTypeDel(){
		if(request()->isAjax()){
 			$type_id=input('type_id');
 			if(Type::destroy($type_id)){
 				return json(['code'=>200,'message'=>'删除成功']);
 			}else{
 				return json(['code'=>'-1','message'=>'删除失败']);
 			}
		}
	}
	//展示属性列表
	public function attrList(){
		$type_id=input('type_id');
		$attrModel=new Attribute();
		$lists=$attrModel->alias('t1')
						 ->field('t1.*,t2.type_name')
						 ->join('sh_type t2','t1.type_id=t2.type_id','left')
						 ->where('t1.type_id',$type_id)
						 ->select();
		return $this->fetch('',['lists'=>$lists]);
	}
}