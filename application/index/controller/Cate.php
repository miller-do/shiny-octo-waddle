<?php
namespace app\index\controller;
use think\Controller;
Class Cate extends IndexBase{
	public function index(){
		$cateid=input('cate_id');
		$cateres= \think\Db::name('menu')->find($cateid);
		// echo '<pre>';
		// print_r($cateres);
		$lis= \think\Db::name('news_article')->where(array('cateid'=>$cateid))->order('id', 'desc')->paginate(10);
		$this->assign('list',$lis);
		$this->assign('cateres',$cateres);
		return $this->fetch();
	}
}
?>