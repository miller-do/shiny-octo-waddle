<?php
namespace app\index\controller;
use think\Controller;
Class Cate extends Base{
	public function index(){
		$cateid=input('cate_id');
		$cateres= \think\Db::name('cate')->find($cateid);
		// echo '<pre>';
		// print_r($cateres);
		$lis= \think\Db::name('article')->where(array('cate'=>$cateid))->order('id', 'desc')->paginate(10);
		$this->assign('list',$lis);
		$this->assign('cateres',$cateres);
		return $this->fetch();
	}
}
?>