<?php
namespace app\home\controller;
use think\Controller;
Class Comment extends IndexBase{
	public function index(){
		$topic_id=input('article_id');
		$topic_type=input('article_cateid');
		$Content=input('comment','trim');
		if($Content==''){
			return	$this -> error('评论失败！评论内容不能为空', url('article/index'), 3);
		}
		$member_id = session('member.id');
		$member_info = db('member') -> find($member_id);
		$data=array();
		$data['topic_id']=$topic_id;
		$data['topic_type']=$topic_type;
		$data['content']=$Content;
		$data['from_uid']=$member_info['id'];
		$data['comment_time']=date('Y-m-d H:i:s');
		if(db("comment")->insert($data)){
			return 	$this -> success('评论成功！', url('article/index'), 3);
			// return json(['code'->200,'msg'->'添加文章成功']);
		}else{
			return	$this -> error('评论失败！', url('article/index'), 3);
			//return json(['code'->301,'msg'->'添加文章失败']);
		}
		// echo '<pre>';
		// print_r($comment);
		return $this->fetch();
	}
}
?>