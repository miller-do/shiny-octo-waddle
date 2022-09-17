<?php
namespace app\index\controller;
use think\Controller;
Class Article extends Base{
	public function index(){
		// $list=model('article')->select()->order('id', 'desc');
		$cate=input('cate_id');
		$cateres= \think\Db::name('cate')->find($cate);
		$lis= \think\Db::name('article')->order('id', 'desc')->paginate(10);
		$this->assign('list',$lis);
		$this->assign('cateres',$cateres);
		return $this->fetch();
	}
	public function detail(){
		$arid=input('id');
		$articleres=db('news_article')->find($arid);
		$where = '`topic_id`="' . $arid . '"';
		$member =db('member');
		$comment =db('comment');
		//此方法不可取
		// $commentres=\think\Db::name('comment')->where($where)-> select();
		// foreach ($commentres as $key => $value) {
		// 	$where = '`id`="' . $value['from_uid'] . '"';
		// 	$user=\think\Db::name('member')->where($where)-> find();
		// 	$commentres[$key]['nickname']=$user['nickname'];
		// }
		//关联查询(推荐使用)
		$commentres=$comment->alias('a')->where($where)->join('member b',' b.id = a.from_uid')->order('comment_time', 'desc')-> select();
		// echo '<pre>';
		// print_r($commentres);
		// die;
		$this->assign('articleres',$articleres);
		$this->assign('commentres',$commentres);
		return $this->fetch();
	}
}
?>