<?php
namespace app\index\controller;
use think\Controller;
Class Comment extends Base{
	public function index(){
		$data=input('post.');
		$userid = session('member.id');
		if(!$userid){
			$this -> error('请先登录！', url('Common/login'));
		}
		if(empty($data['content'])){
			return	$this -> error('评论失败！评论内容不能为空', url('article/detail',array('id'=>$data['article_id'])), 3);
		}else{
			$data['user_id']=$userid;
			$commentInfo=model("comment")->save($data);
			if($commentInfo){
				return 	$this -> success('评论成功！', url('article/detail',array('id'=>$data['article_id'])), 3);
			}else{
				return	$this -> error('评论失败！', url('article/detail',array('id'=>$data['article_id'])), 3);
			}
		}
		
	}
	public function del(){
		$cid=input('cid');
		$commonts=model("comment")->select();
		//子评论处理
		$subComs=[];
		foreach ($commonts as $k => $commont) {
			if($commont['pid']==$cid){
				$subComs[]=$commont['id'];
			}
		}
		array_push($subComs,$cid);
		// echo json_encode($subComs);
		// dump($subComs);die;
		// $result=model("comment")->where('id',$cid)->find();//
		// $res= $result->delete(true)
		// dump($res);die;
		$result=model("comment")->destroy($subComs,true);
		
		
		if($result){
			return 	$this -> success('删除成功');
		}else{
			return 	$this -> error('删除成功');
		}
	}
}
?>