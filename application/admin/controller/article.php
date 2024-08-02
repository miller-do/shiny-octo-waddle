<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Token;
use think\facade\Request;

class Article extends Controller{
	
	public function upload(){
	    // 获取表单上传文件 例如上传了001.jpg
	    // $file = request()->file('image');
		$file = $this->request->file('file');
		dump(input('post.'));
		die;
	    // 移动到框架应用根目录/uploads/ 目录下
	    $info = $file->move( '../uploads');
	    if($info){
	        // 成功上传后 获取上传信息
			$imgUrl = $info->getSaveName();
			return $imgUrl;
	    }else{
	        // 上传失败获取错误信息
	        return $file->getError();
	    }
	}
	
	//查询分类列表
	public function getcates(){
		$cateInfo=model('cate')->order('id', 'asc')->select();
		foreach ($cateInfo as $key => $value){
			$dataRes[$key]['value']=$value['id'];
			$dataRes[$key]['label']=$value['catename'];
		}
		
		if($dataRes){
			return json(['code'=>200,'data'=>$dataRes,'msg'=>'列表获取成功']);
		}else{
			return json(['code'=>500,'data'=>$dataRes,'msg'=>'列表获取失败']);
		}
	}
	
	public function getlist(){
		$data=[
			'pageIndex'=>input('post.pageIndex'),
			'pageSize'=>input('post.pageSize'),
			'total'=>input('post.total')
		];
		//with('cate,cate.catename') 第一个是模型方法、第二个表示要关联的模型方法
		$dataRes=model('article')->with('cate')->order('id', 'desc')->page($data['pageIndex'],$data['pageSize'])->select();
		// dump($dataRes);
		// die;
		$domain=Request::domain(true);//env('ROOT_PATH');
		foreach ($dataRes as $value){
			$value['thumb']=$domain.'\/upload/'.$value['thumb'];
		}
		$dataReturn = [
			'total'     =>model('article')->where(`empty('delete_time')`)->count(),
			'cur'       => $data['pageIndex'],
			'size'      => $data['pageSize'],
			'list'      => $dataRes
		];
		if($dataRes){
			return json(['code'=>200,'data'=>$dataReturn,'msg'=>'列表获取成功']);
		}else{
			return json(['code'=>500,'data'=>$dataRes,'msg'=>'列表获取失败']);
		}
	}
	
	public function edit(){
		//先查询后编辑
		$data=input('post.');
		$file = $this->request->file('file');
		// 移动到框架应用根目录/uploads/ 目录下
		if($file){
			$path=env('ROOT_PATH')."public/upload";
			$info = $file->move($path);//'../uploads'
			if($info){
			    // 成功上传后 获取上传信息
				$data['thumb'] = $info->getSaveName();
				// http://localhost/tp-yycms/uploads/20220921/93c04acb867db32bdbc4da7756152b7b.jpg
			}else{
			    // 上传失败获取错误信息
			    return $file->getError();
			}
		}
		// $data['tags']=str_relace('，',',',input('post.tags'));
		$cateInfo=model('article')->find($data['id']);
		$result=$cateInfo->allowField(true)->save($data,['id' => $data['id']]);
		if($result){
			return json(['code'=>200,'data'=>$result,'msg'=>'文章修改成功']);
		}else{
			return json(['code'=>500,'data'=>$result,'msg'=>'文章修改失败']);
		}
	}
	
	public function add(){
		// dump(input('post.'));
		try{
			$file = $this->request->file('file');
		}catch(\Throwable $err){
			$this->error('请上传封面图');
		}
		// dump($file);
		// die;
		$data=[
			'title'=>input('post.title'),
			'author'=>input('post.author'),
			'desc'=>input('post.desc'),
			'tags'=>input('post.tags'),//str_relace('，',',',input('post.tags')),
			'cate'=>input('post.cate'),
			'is_top'=>input('post.is_top'),
			'is_open'=>input('post.is_open'),
			'content'=>input('post.content')
		];
		// 移动到框架应用根目录/uploads/ 目录下
		$path=env('ROOT_PATH')."public/upload";
		$info = $file->move($path);//'../uploads'
		if($info){
		    // 成功上传后 获取上传信息
			$data['thumb'] = $info->getSaveName();
		}else{
		    // 上传失败获取错误信息
		    return $file->getError();
		}
		//allowField(true):过滤非表中字段
		$result=model('article')->allowField(true)->articleAdd($data);
		
		if($result==1){
			return json(['code'=>200,'data'=>$result,'msg'=>'文章添加成功']);
		}else{
			return json(['code'=>500,'data'=>$result,'msg'=>'文章添加失败']);
		}
		
	}
	
	public function del(){
		$data=input('post.');
		//真实删除
		// $result=$cateInfo->delete(true);
		//先查询后删除
		if(count($data)>1){
			//批量删除
			$cateInfo=model('article');
			$result=$cateInfo::destroy($data);
		}else{
			//单个删除
			$cateInfo=model('article')->find($data);
			//删除缩略图文件
			$res=unlink(env('ROOT_PATH')."public/upload/".$cateInfo['thumb']);
			//真实删除
			$result=$cateInfo->delete(true);
		}
		$total=model('article')->count();
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
