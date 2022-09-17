<?php
namespace app\home\controller;
use think\Controller;
Class Search extends IndexBase{
	public function index(){
		// echo '</pre>';
		// dump($_GET);
		$keywords=input('keywords');
		if($keywords){
			$map['title']=['like','%'.$keywords.'%'];
			$searchres=\think\Db::name('news_article')->where($map)->order('id desc')->paginate($listRow=10,$simple=false,$config=['query'=>array('keywords'=>$keywords)]);
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
		// $lis= \think\Db::name('news_article')->alias('a')->paginate(10);
		// $this->assign('list',$lis);
		return $this->fetch();
	}
}
?>