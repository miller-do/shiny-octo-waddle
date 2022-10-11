<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Token;

class Admin extends Controller{
	
	public function getlist(){
		// $data=[
		// 	'pageIndex'=>input('post.pageIndex'),
		// 	'pageSize'=>input('post.pageSize'),
		// 	'total'=>input('post.total')
		// ];
		// $pageIndex=$data['pageIndex'];
		// $pageSize=$data['pageSize'];
		$list=model('admin')->order('create_time', 'asc')->select();
		//包含软删除
		//$cates=model('Cate')->withTrashed()->order('sort', 'asc')->page($pageIndex,$pageSize)->select();
		//仅软删除数据
		//$cates=model('Cate')->onlyTrashed()->order('sort', 'asc')->page($pageIndex,$pageSize)->select();
		if($list){
			return json(['code'=>200,'data'=>$list,'msg'=>'栏目获取成功']);
		}else{
			return json(['code'=>500,'data'=> $list,'msg'=>'列表获取失败']);
		}
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
		
		$isHide=input('post.is_hide')==true?'1':'0';
		$data=[
			'catename'=>input('post.catename'),
			'pid'=>input('post.pid'),
			'url'=>input('post.url'),
			'is_hide'=>$isHide,
			'sort'=>input('post.sort')
		];
		// allowField 过滤post数组中的非数据表字段数据
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
			$result=$cateInfo->delete(true);
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
