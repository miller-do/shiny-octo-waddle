<?php
namespace app\index\controller;
use think\Controller;
Class Search extends Base{
	public function index(){
		// echo '</pre>';
		// dump($_GET);
		$keywords=input('keywords');
		if($keywords){
			//快捷查询方式是一种多字段相同查询条件的简化写法https://www.kancloud.cn/manual/thinkphp5_1/354030
			// where('title|content','like','%'.$keywords.'%')
			$searchres=db('article')->where('title','like','%'.$keywords.'%')->order('id desc')->paginate($listRow=10,$simple=false,$config=['query'=>array('keywords'=>$keywords)]);
			$this->assign(array(
				'searchres'=>$searchres,
				'keywords'=>$keywords
			));
		}else{
			$this->assign(array(
				'searchres'=>null,
				'keywords'=>$keywords
			));
		}
		// $this->assign('list',$lis);
		return $this->fetch();
	}
}
?>