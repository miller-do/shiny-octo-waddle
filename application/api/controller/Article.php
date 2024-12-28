<?php
namespace app\api\controller;
use think\Controller;
use think\facade\Request;

Class Article extends Controller{
	public function index( $pageIndex = 1 , $pageSize = 10 ){
		//echo 不能打印复合型和资源型数据
		// print 不能打印复合型和资源型数据
		// print_r 只能打印一些易于理解的信息,可以打印复合型和资源型数据；var_dump()更加详细
		// dump 和var_dump 两者打印内容一样，dump打印出来的结构更好看
		$tabId=input('post.tabId');
		$cateId=input('post.cateId');
		
		$pageIndex=input('post.pageIndex')?input('post.pageIndex'):$pageIndex;
		$pageSize =input('post.pageSize')?input('post.pageSize'):$pageSize;
		$data=[
			'pageIndex'=>$pageIndex,
			'pageSize'=>$pageSize,
		];
		
		if($cateId){
			$condition=model('article')->hasWhere('cate',['id'=>$cateId]);
			$dataRes=model('article')->hasWhere('cate',['id'=>$cateId])->order('create_time', 'desc')->page($pageIndex,$pageSize)->select();
		}else{
			$condition=model('article')->hasWhere('cate',['pid'=>$tabId]);
			$dataRes=model('article')->hasWhere('cate',['pid'=>$tabId])->order('create_time', 'desc')->page($pageIndex,$pageSize)->select();
		}
		
		
		$domain=Request::domain(true);//env('ROOT_PATH');
		foreach ($dataRes as $value){
			$value['thumb']=$domain.'\/upload/'.$value['thumb'];
		}
		$dataReturn = [
			'total'     => $condition->count(),
			'pageIndex'       => $pageIndex,
			'pageSize'      => $pageSize,
			'list'      => $dataRes
		];
		if($dataRes){
			return json(['code'=>200,'data'=>$dataReturn,'msg'=>'列表获取成功']);
		}else{
			return json(['code'=>500,'data'=>$dataRes,'msg'=>'列表获取失败']);
		}
		// // $list=model('article')->select()->order('id', 'desc');
		// $cid=input('cate_id');
		// $cateres= \think\Db::name('cate')->find($cid);
		
		// $where = '`is_open`=1 AND `cate`='.$cid;
		// $lis= model('article')->where($where)->order('id', 'desc')->paginate(10);
		// // dump($lis);die;
		// // dump(json_encode($lis));die;
		// $this->assign('list',$lis);
		// $this->assign('cateres',$cateres);
		// return $this->fetch();
	}
	
	public function detail(){
		$id=input('id');
		$articleres=model('article')->find($id);
		// $articleres1=model('article')->comments()->where('id',$id)->select();
		// $articleres->setInc('clickcount');
		
		$comments=model('article')->hasMany('Comment','article_id','id')->where('article_id',$id)->order('create_time', 'desc')->select();
		// dump($comments);
		// die;
		$domain=Request::domain(true);//env('ROOT_PATH');
		$articleres['thumb']=$domain.'\/upload/'.$articleres['thumb'];
		$articleres['comments']=$comments;
		if($articleres){
			return json(['code'=>200,'data'=>$articleres,'msg'=>'文章获取成功']);
		}else{
			return json(['code'=>500,'data'=>$articleres,'msg'=>'文章获取失败']);
		}
		
		$comment =model('comment');
		
		
		//关联查询(推荐使用)
		// $commentres=$comment->alias('a')->where($where)->join('member b',' b.id = a.from_uid')->order('create_time', 'desc')-> select();
		// $commentres=$comment->order('create_time', 'desc')-> select();
		$comments=model('comment')->with('userInfo')->with('article')->where('article_id',input('id'))->order('create_time', 'desc')-> select();
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
		$this->assign('cateres',$cateres);
		$this->assign('articleres',$articleres);
		$this->assign('commentres',$coms);
		return $this->fetch();
	}
	
	public function recommendList(){
		$where = '`is_top`=1';
		$datas=model('article')->where($where)->order('create_time', 'desc')->paginate(6);
		if($datas){
			return json(['code'=>200,'data'=>$datas,'msg'=>'文章获取成功']);
		}else{
			return json(['code'=>500,'data'=>$datas,'msg'=>'文章获取失败']);
		}
		//->page($data['pageIndex'],$data['pageSize'])->select();
	}
	
	public function latestList(){
		$where = '`is_open`=1';
		$datas=model('article')->where($where)->order('create_time', 'desc')->paginate(6);
		if($datas){
			return json(['code'=>200,'data'=>$datas,'msg'=>'文章获取成功']);
		}else{
			return json(['code'=>500,'data'=>$datas,'msg'=>'文章获取失败']);
		}
	}
	public function hotList(){
		$where = '`is_open`=1';
		$datas=model('article')->where($where)->order('clickcount', 'desc')->paginate(6);
		
		if($datas){
			return json(['code'=>200,'data'=>$datas,'msg'=>'文章获取成功']);
		}else{
			return json(['code'=>500,'data'=>$datas,'msg'=>'文章获取失败']);
		}
	}
}
?>