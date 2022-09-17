<?php
namespace app\home\controller;
use think\Controller;
Class Tags extends IndexBase{
	public function index(){
		$tags=input('tags');
		$map['keywords']=['like','%'.$tags.'%'];
		$lis= \think\Db::name('news_article')->alias('a')->where($map)->paginate(5);
		$this->assign('list',$lis);
		return $this->fetch();
	}
	public function detail(){
		$arid=input('id');
		$artLis=db('news_article')->find($arid);
		
		// $artLis= \think\Db::name('news_article')->alias('a')->paginate(5);
		// $this->assign('server',$service);
		$this->assign('artLis',$artLis);
		return $this->fetch();
	}
}
?>