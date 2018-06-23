<?php
namespace app\admin\controller;  //定义控制器的命名空间
use app\admin\model\Category;
class CategoryController extends CommonController {
    
	//添加
	public function add(){
		if(request()->isPost()){
			$postData=input('post.');
			$result=$this->validate($postData,'Category.add',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$catModel=new Category();
			if($catModel->allowField(true)->save($postData)){
				$this->success("添加成功",url("admin/category/index"));
			}else{
				$this->error("添加失败");
			}
		}
		$catModel=new Category();
		$lists=$catModel->getCatsSon();
		return $this->fetch('',['lists'=>$lists]);
	}
	//分类列表
	public function index(){
		$catModel=new Category();
		$lists=$catModel->getCatsSon();
		return $this->fetch('',['lists'=>$lists]);
	}
	//修改分类
	public function upd(){
		if(request()->isPost()){
			$postData=input('post.');
			$result=$this->validate($postData,'Category.upd',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$categoryModel=new Category();
			if($categoryModel->allowField(true)->update($postData)){
				$this->success("修改成功",url("admin/category/index"));
			}else{
				$this->error("修改失败");
			}
		}
		$cat_id=input('cat_id');
		$data=Category::find($cat_id);
		$catModel=new Category();
		$lists=$catModel->getCatsSon();
		return $this->fetch('',['lists'=>$lists,'data'=>$data]);
	}
	//删除分类
	public function catDel(){
		if(request()->isAjax()){
  			$cat_id=input('cat_id');
			$catModel=new Category();
			if($catModel->where('pid',$cat_id)->find()){
				return json(['code'=>-1,'message'=>'还有子类,无法删除']);
			}else{
				if($catModel->destroy($cat_id)){
					return json(['code'=>200,'message'=>'删除成功']);
				}else{
					return json(['code'=>-2,'message'=>'删除失败']);
				}
			}
		}
	}	
}