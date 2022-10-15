<?php
namespace app\index\controller;
use think\Controller;
Class Luckdraw extends Base{
	
	//不考虑库存
	public function index(){
		// 数组$prize_arr，
		// id用来标识不同的奖项，
		// min表示圆盘中各奖项区间对应的最小角度，
		// max表示最大角度，如一等奖对应的最小角度：0，最大角度30，这里我们设置max值为1、max值为29，是为了避免抽奖后指针指向两个相邻奖项的中线。
		// 由于圆盘中设置了多个七等奖，所以我们在数组中设置每个七等奖对应的角度范围。
		// prize表示奖项内容，
		// v表示中奖几率，我们会发现，数组中七个奖项的v的总和为100，如果v的值为1，则代表中奖几率为1%，依此类推。
		$prize_arr = array(
		'0' => array('id'=>1,'min'=>1,'max'=>29,'prize'=>'一等奖','v'=>1), 
		'1' => array('id'=>2,'min'=>302,'max'=>328,'prize'=>'二等奖','v'=>2), 
		'2' => array('id'=>3,'min'=>242,'max'=>268,'prize'=>'三等奖','v'=>5), 
		'3' => array('id'=>4,'min'=>182,'max'=>208,'prize'=>'四等奖','v'=>7), 
		'4' => array('id'=>5,'min'=>122,'max'=>148,'prize'=>'五等奖','v'=>10), 
		'5' => array('id'=>6,'min'=>62,'max'=>88,'prize'=>'六等奖','v'=>25), 
		'6' => array('id'=>7,'min'=>array(32,92,152,212,272,332), 
		'max'=>array(58,118,178,238,298,358),'prize'=>'七等奖','v'=>50) 
		);
		
		foreach ($prize_arr as $key => $val) { 
		    $arr[$val['id']] = $val['v']; 
		} 
		 
		$rid = $this->getRand($arr); //根据概率获取奖项id 
		 
		$res = $prize_arr[$rid-1]; //中奖项 
		$min = $res['min']; 
		$max = $res['max']; 
		if($res['id']==7){ //七等奖 
		    $i = mt_rand(0,5); 
		    $result['angle'] = mt_rand($min[$i],$max[$i]); //一个介于$min[$i]和$max[$i]之间（包括 $min[$i] 和 $max[$i]）的随机整数
		}else{ 
		    $result['angle'] = mt_rand($min,$max); //随机生成一个角度 
		} 
		$result['prize'] = $res['prize']; 
		 
		echo json_encode($result);
		
		// $this->assign('cateres',$cateres);
		// return $this->fetch();
	}
	
	public function getRand($proArr){
		$result = ''; 
		//概率数组的总概率精度 
		$proSum = array_sum($proArr); 
		//概率数组循环 
		foreach ($proArr as $key => $proCur) { 
			$randNum = mt_rand(1, $proSum); 
			if ($randNum <= $proCur) { 
				$result = $key; 
				break; 
			} else { 
				$proSum -= $proCur; 
			} 
		} 
		unset ($proArr); 
		return $result;
	}
}
?>