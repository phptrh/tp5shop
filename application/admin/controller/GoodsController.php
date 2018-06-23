<?php
namespace app\admin\controller;  //定义控制器的命名空间
use app\admin\model\Category;
use app\admin\model\Goods;
use app\admin\model\Type;
use app\admin\model\Attribute;
class GoodsController extends CommonController {

	//列表展示
	public function index(){
		$lists=Goods::where('is_delete',0)->select();
		return $this->fetch('',['lists'=>$lists]);
	}

	public function ajaxGetAttr(){
		if(request()->isAjax()){
			$type_id=input('type_id');
			$attrModel=new Attribute();
			$attrs=$attrModel->where('type_id',$type_id)->select();
			return json($attrs);
		}
	}
    
	//添加
	public function add(){
		if(request()->isPost()){
			$postData=input('post.');
			$result=$this->validate($postData,'Goods.add',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$goodsModel=new Goods();
			$goods_img=$goodsModel->uploadImg();
			if($goods_img){
				$postData['goods_img']=json_encode($goods_img);
				//调用缩略图
				$thumb_path=$goodsModel->thumbImg($goods_img);
				if($thumb_path){
					$postData['goods_middle']=json_encode($thumb_path['middle']);
					$postData['goods_thumb']=json_encode($thumb_path['small']);
				}
			}
			if($goodsModel->allowField(true)->save($postData)){
				$this->success("添加成功",url("admin/goods/index"));
			}else{
				$this->error("添加失败");
			}
		}
		$types=Type::select();
		$catModel=new Category();
		$cats=$catModel->getCatsSon();
		return $this->fetch('',['cats'=>$cats,'types'=>$types]);
	}
	//ajax获取商品详情
	public function ajaxGetContent(){
		if(request()->isAjax()){
			$goods_id=input('goods_id');
			$goods=Goods::find($goods_id);
			return json($goods);
		}
	}
	//ajax改变商品状态
	public function ajaxChange(){
		if(request()->isAjax()){
			$data=input('get.');
			$goodsModel=new Goods();
			if($goodsModel->allowField(true)->update($data)){
				return json(['code'=>200,'message'=>'修改成功']);
			}else{
				return json(['code'=>-1,'message'=>'修改失败']);
			}
		}
	}
	//ajax加入回收站
	public function ajaxAddRecycle(){
		if(request()->isAjax()){
			$data=input('get.');
			$goodsModel=new Goods();
			if($goodsModel->allowField(true)->update($data)){
				return json(['code'=>200,'message'=>'更新成功']);
			}else{
				return json(['code'=>-1,'message'=>'更新失败']);
			}
		}
		$lists=Goods::where('is_delete',1)->select();
		return $this->fetch('',['lists'=>$lists]);
	}

}