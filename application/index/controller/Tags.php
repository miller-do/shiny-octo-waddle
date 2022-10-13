<?php
namespace app\index\controller;
use think\Controller;
Class Tags extends Base{
	public function index(){
		$cateid=input('cate_id');
		$tags=input('tags');
		// $map['tags']=['like','%'.$tags.'%'];
		$lis= db('article')->alias('a')->where('title|tags','like','%'.$tags.'%')->paginate(5);
		$cateres= db('cate')->find($cateid);
		$this->assign('list',$lis);
		$this->assign('cateres',$cateres);
		return $this->fetch();
	}
}
?>