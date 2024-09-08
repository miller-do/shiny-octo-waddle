<?php
namespace app\api\controller;
use think\Controller;
use think\facade\Request;

Class Cate extends Controller{
	public function getCateList(){
		//->with('cate')
		//->where('pid','in',[43,25])
		$dataRes=model('cate')->order('sort', 'asc')->order('create_time', 'desc')->select();
		// dump($dataRes);die;
		$dataReturn = [
			'total'     => model('cate')->where(`empty('delete_time')`)->count(),
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
}
?>