<?php
namespace app\index\model;
use think\Model;
use think\model\concern\SoftDelete;

class Article extends Model{
	protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $createTime = false;
	public function index(){
		echo "111111111";
	}
}