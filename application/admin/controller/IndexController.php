<?php
namespace app\Admin\Controller;  //定义控制器的命名空间
class IndexController extends CommonController {
    //首页展示
	public function index(){
		return $this->fetch();
	}
	public function top(){
		return $this->fetch();
	}
	public function left(){
		return $this->fetch();
	}
	public function main(){
		return $this->fetch();
	}
}