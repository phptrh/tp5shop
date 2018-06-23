<?php
namespace app\admin\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
use think\Db;
class Goods extends Model {
     
    //表主键 
    protected $pk="goods_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;
    //获取原图的上传路径
	public function uploadImg(){
		$goods_img=[];
		$files=request()->file('img');
		if($files){
 			$condition=['size'=>1024*1024*3,'ext'=>'png,jpg,jepg,gif'];
 			foreach($files as $file){
 				$fileinfo=$file->validate($condition)->move('./upload');
 				if($fileinfo){
 					$goods_img[]=str_replace('\\','/',$fileinfo->getSaveName());
 				}
 			}
		}
		return $goods_img;
	}
	//生成缩略图
	public function thumbImg($goods_img){
		$middle=[];
		$small=[];
		//中等缩略图
		foreach($goods_img as $path){
			$path_arr=explode('/',$path);
			$middle_path=$path_arr[0].'/middle_'.$path_arr[1];
			$image=\think\Image::open('./upload/'.$path);
			$image->thumb(350,350,2)->save('./upload/'.$middle_path);
			$middle[]=$middle_path;
		}
		//小图
		foreach($goods_img as $path){
			$path_arr=explode('/',$path);
			$small_path=$path_arr[0].'/small_'.$path_arr[1];
			$image=\think\Image::open('./upload/'.$path);
			$image->thumb(150,150,2)->save('./upload/'.$small_path);
			$small[]=$small_path;
		}
		return ['middle'=>$middle,'small'=>$small];
	}
	protected static function init(){
		//生成唯一的货号
		Goods::event('before_insert',function($goods){
			$goods['goods_sn']=date('ymdhis').uniqid();
		});
		//商品入库的后钩子
		Goods::event('after_insert',function($goods){
			$goods_id=$goods['goods_id'];
			$getAttrValue=$goods['getAttrValue'];
			$getAttrPrice=$goods['getAttrPrice'];
			foreach($getAttrValue as $attr_id=>$attrValue){
				if(is_array($attrValue)){
					foreach($attrValue as $k=>$single_attr_value){
						$data=[
							'goods_id'=>$goods_id,
							'attr_id'=>$attr_id,
							'attr_value'=>$single_attr_value,
							'attr_price'=>$getAttrPrice[$attr_id][$k],
							'create_time'=>time(),
							'update_time'=>time()
						];
						Db::name('goods_attr')->insert($data);
					}
				}else{
					$data=[
							'goods_id'=>$goods_id,
							'attr_id'=>$attr_id,
							'attr_value'=>$attrValue,
							'create_time'=>time(),
							'update_time'=>time()
						];
					Db::name('goods_attr')->insert($data);
				}
			}
		});
	}
}