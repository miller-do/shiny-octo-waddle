<?php
namespace app\index\model;
use think\Model;
use think\model\concern\SoftDelete;

class User extends Model{
	use SoftDelete;
	
	protected $deleteTime = 'delete_time';
	protected $defoultSoftDelete = 0;
	
	public function index(){
		echo "111111111";
	}
}