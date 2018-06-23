<?php
namespace app\admin\controller;  //定义控制器的命名空间
use app\admin\model\Type;
use app\admin\model\Attribute;
class AttributeController extends CommonController {
    
	//添加属性
	public function add(){
		if(request()->isPost()){
			$postData=input('post.');
			if($postData['attr_input_type']==0){
				$result=$this->validate($postData,'Attribute.add',[],true);
			}else{
				$result=$this->validate($postData,'Attribute.liebiao',[],true);
			}
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$attributeModel=new Attribute();
			if($attributeModel->allowField(true)->save($postData)){
				$this->success("添加成功",url("admin/attribute/index"));
			}else{
				$this->error("添加失败");
			}
		}
		$types=Type::select();
		return $this->fetch('',['types'=>$types]);
	}
	//分类列表
	public function index(){
		$attrModel=new Attribute();
		$lists=$attrModel->alias('t1')
						 ->field('t1.*,t2.type_name')
						 ->join('sh_type t2','t1.type_id=t2.type_id','left')
						 ->select();
		return $this->fetch('',['lists'=>$lists]);
	}
	//修改属性
	public function upd(){
		if(request()->isPost()){
			$postData=input('post.');
			if($postData['attr_input_type']==0){
				$result=$this->validate($postData,'Attribute.upd',[],true);
			}else{
				$result=$this->validate($postData,'Attribute.liebiao',[],true);
			}
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$attrModel=new Attribute();
			if($attrModel->allowField(true)->update($postData)){
				$this->success("修改成功",url("admin/attribute/index"));
			}else{
				$this->error("修改失败");
			}
		}
		$attr_id=input('attr_id');
		$data=Attribute::find($attr_id);
		$types=Type::select();
		return $this->fetch('',['data'=>$data,'types'=>$types]);
	}
	//删除
	public function attrDel(){
		if(request()->isAjax()){
			$attr_id=input('attr_id');
			if(Attribute::destroy($attr_id)){
				return json(['code'=>200,'message'=>'删除成功']);
			}else{
				return json(['code'=>-1,'message'=>'删除失败']);
			}
		}
	}
}