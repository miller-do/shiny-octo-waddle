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
}
?>