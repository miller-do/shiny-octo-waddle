<?php
namespace app\index\controller;
use think\Controller;
Class Article extends Base{
	public function index(){
		// $list=model('article')->select()->order('id', 'desc');
		$cid=input('cate_id');
		$cateres= \think\Db::name('cate')->find($cid);
		
		$where = '`is_open`=1 AND `cate`='.$cid;
		$lis= \think\Db::name('article')->where($where)->order('id', 'desc')->paginate(10);
		$this->assign('list',$lis);
		$this->assign('cateres',$cateres);
		return $this->fetch();
	}
	
	public function detail(){
		$articleres=model('article')->find(input('id'));
		
		$cateres=db('cate')->find($articleres['cate']);
		$this->assign('cateres',$cateres);
		
		$articleres->setInc('clickcount');
		$member =db('admin');
		$comment =db('comment');
		//此方法不可取
		// $commentres=\think\Db::name('comment')->where($where)-> select();
		// foreach ($commentres as $key => $value) {
		// 	$where = '`id`="' . $value['from_uid'] . '"';
		// 	$user=\think\Db::name('member')->where($where)-> find();
		// 	$commentres[$key]['nickname']=$user['nickname'];
		// }
		
		//关联查询(推荐使用)
		// $commentres=$comment->alias('a')->where($where)->join('member b',' b.id = a.from_uid')->order('create_time', 'desc')-> select();
		// $commentres=$comment->order('create_time', 'desc')-> select();
		$commentres=model('comment')->with('userInfo')->where('article_id',input('id'))->order('create_time', 'desc')-> select();
		// dump($commentres);
		// die;
		$this->assign('articleres',$articleres);
		$this->assign('commentres',$commentres);
		return $this->fetch();
	}
	
}
?>