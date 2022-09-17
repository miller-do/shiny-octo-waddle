<?php
	namespace app\index\controller;
	use think\Controller;
	
// 终端输入可创建控制器
// php think make:controller dmin/index --plain

//控制器文件首字母必须为大写
class Index extends Base{
	public function Index(){
		
		// $cate=Db("menu")->select();
		// // echo "<pre>";
		// // print_r($cate);
		return $this -> fetch();
	}
}

?>