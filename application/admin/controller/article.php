<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Token;

class Article extends Controller{
	
	public function getlist(){
		$data=[
			'pageIndex'=>input('post.pageIndex'),
			'pageSize'=>input('post.pageSize'),
			'total'=>input('post.total')
		];
		$cates=model('Cate')->order('sort', 'asc')->page($data['pageIndex'],$data['pageSize'])->select();
		// if($result){
		// 	return json(['code'=>200,'data'=>$result,'msg'=>'栏目修改成功']);
		// }else{
		// 	return json(['code'=>500,'data'=>$result,'msg'=>'栏目修改失败']);
		// }
	}
	
	public function edit(){
		//先查询后编辑
		$data=input('post.');
		$data['is_hide']=input('post.is_hide')?'1':'0';
		// dump($data);die;
		$cateInfo=model('Cate')->find($data['id']);
		$result=$cateInfo->save($data,['id' => $data['id']]);
		if($result){
			return json(['code'=>200,'data'=>$result,'msg'=>'栏目修改成功']);
		}else{
			return json(['code'=>500,'data'=>$result,'msg'=>'栏目修改失败']);
		}
	}
	
	public function add(){
		
		$isHide=is_bool(input('post.is_hide'))?'1':'0';
		$data=[
			'catename'=>input('post.catename'),
			'pid'=>input('post.pid'),
			'url'=>input('post.url'),
			'is_hide'=>$isHide,
			'sort'=>input('post.sort')
		];
		$result=model('Cate')->allowField(true)->cateAdd($data);
		
		if($result==1){
			return json(['code'=>200,'data'=>$result,'msg'=>'栏目添加成功']);
		}else{
			return json(['code'=>500,'data'=>$result,'msg'=>'栏目添加失败']);
		}
		
	}
	
	public function del(){
		$data=input('post.');
		//真实删除
		// $result=$cateInfo->delete(true);
		//先查询后删除
		if(count($data)>1){
			//批量删除
			$cateInfo=model('Cate');
			$result=$cateInfo::destroy($data);
		}else{
			//单个删除
			$cateInfo=model('Cate')->find($data);
			$result=$cateInfo->delete();
		}
		$total=model('Cate')->count();
		$res=[
			'total'=>$total
		];
		if($result==1){
			return json(['code'=>200,'data'=>$res,'msg'=>'栏目删除成功']);
		}else{
			return json(['code'=>500,'data'=>$result,'msg'=>'栏目删除失败']);
		}
	}
}
