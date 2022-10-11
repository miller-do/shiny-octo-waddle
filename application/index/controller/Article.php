<?php
namespace app\index\controller;
use think\Controller;
Class Article extends Base{
	public function index(){
		// $list=model('article')->select()->order('id', 'desc');
		$cid=input('cate_id');
		$cateres= \think\Db::name('cate')->find($cid);
		
		$where = '`is_open`=1 AND `cate`='.$cid;
		$lis= model('article')->where($where)->order('id', 'desc')->paginate(10);
		// dump($lis);die;
		// dump(json_encode($lis));die;
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
		$comments=model('comment')->with('userInfo')->where('article_id',input('id'))->order('create_time', 'desc')-> select();
		$coms=[];
		//组装顶级评论集合
		foreach ($comments as $k => $comment) {
			if($comment['pid']==0){
				$item=$comments[$k];
				// array_push($coms,$item);
				$coms[$k]=$item->toArray();
			}
		}
		//返回的是数据集而不是可以直接操作的数组
		foreach ($coms as $k => $com) {
			foreach ($comments as $kk => $comment) {
				//一级菜单的id等于菜单数据的pid
				if($com['id']==$comment['pid']){
					// array_push($coms[$k]['subComments'],$subs[$kk]);
					$coms[$k]['subComments'][$kk]=$comment->toArray();
				}
			}
		}
		// dump($coms);
		// die;
		$this->assign('articleres',$articleres);
		$this->assign('commentres',$coms);
		return $this->fetch();
	}
	
}
?>