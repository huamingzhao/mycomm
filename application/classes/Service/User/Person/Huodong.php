<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人中心抽奖管理
 * @author 郁政
 *
 */
class Service_User_Person_Huodong{
	/**
	 * 个人中心获取抽奖记录
	 * @author 郁政
	 *
	 */
	public function getRouletteList($user_id,$game_id){
		$res = array();
		$return = array();
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$arr = common::getPrizeName();
		$arr = $arr[$game_id];
		$count = ORM::factory('Roulette')->where('user_id','=',$user_id)->where('game_id','=',$game_id)->reset ( FALSE )->count_all();
		$page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' => 10
        ) );
        $om = ORM::factory('Roulette')->where('user_id','=',$user_id)->where('game_id','=',$game_id)->limit ( $page->items_per_page )->offset ( $page->offset )->order_by('lucky_time','desc')->find_all()->as_array();
        if(count($om) > 0){
        	foreach($om as $k => $v){
        		$res[$k]['lucky_time'] = date('Y.m.d H:i:s',$v->lucky_time);
        		$res[$k]['prize_id'] = $v->prize_type;
        		$res[$k]['prize'] = $arr[$v->prize_type];
        	}
        }
        $return['list'] = $res;
        $return['page'] = $page;
        
        return $return;
	}
	
	/**
	 * 个人用户活跃度积分获取最近6个月的月份
	 * @author 郁政
	 */
	public function getRencentMonth(){
		$month = array();
		//得到系统的年月
		$tmp_date=date("Ym");	
		//切割出年份
		$tmp_year=substr($tmp_date,0,4);
		//切割出月份
		$tmp_mon =substr($tmp_date,4,2);
		$tmp_thismonth = mktime(0,0,0,$tmp_mon,1,$tmp_year);	
		for($i=0;$i<=5;$i++){
			$tmp_nextmonth = mktime ( 0, 0, 0, $tmp_mon - $i, 1, $tmp_year );
			$month[$tmp_nextmonth]= date ( "Y", $tmp_nextmonth ).'年'.date ( "m", $tmp_nextmonth ).'月';
		}
		return $month;		
	}
	
	/**
	 * 获取创业币列表
	 * @author 郁政
	 */
	public function getChuangYeBiList($user_id,$date){
		$return = array();
		$res = array();
		$arr = array();
		$arr = common::getChuangYeBiSource();
		$start_time = $date;
		$end_time = strtotime("+1 month",$date);
		$count = ORM::factory('Usercurrency')->where('user_id','=',$user_id)->where('date','>=',$start_time)->where('date','<',$end_time)->reset ( FALSE )->count_all();
		$page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' => 10
        ) );
		$currency = ORM::factory('Usercurrency')->where('user_id','=',$user_id)->where('date','>=',$start_time)->where('date','<',$end_time)->limit ( $page->items_per_page )->offset ( $page->offset )->order_by('date','desc')->find_all()->as_array();
		if(count($currency) > 0){
			foreach($currency as $k => $v){
				$res[$k]['date'] = date('Y-m-d H:i',$v->date);
				//$res[$k]['source'] = $arr[$v->score_type];
				$res[$k]['score_type'] = $v->score_type;
				$res[$k]['score'] = $v->score;
				$res[$k]['score_operating'] = $v->score_operating;
			}
		}
		$return['list'] = $res;
		$return['page'] = $page;
		return $return;
	}
	
	/**
	 * 获取创业币总数
	 * @author 郁政
	 */
	public function getChuangYeBiCount($user_id){
		$count = 0;
		$count_1 = 0;
		$count_2 = 0;
		$currency_1 = DB::select(array(DB::expr('sum(score)'),'total'))->from('user_currency')->where('user_id','=',$user_id)->where('score_operating','=',1)->execute();
		if(isset($currency_1[0]['total']) && $currency_1[0]['total']){
			$count_1 = $currency_1[0]['total'];
		}
		$currency_2 = DB::select(array(DB::expr('sum(score)'),'total'))->from('user_currency')->where('user_id','=',$user_id)->where('score_operating','=',0)->execute();
		if(isset($currency_2[0]['total']) && $currency_2[0]['total']){
			$count_2 = $currency_2[0]['total'];
		}
		$count = ($count_1 - $count_2) > 0 ? ($count_1 - $count_2) : 0;
		return $count;
	}
	
	/**
	 * 获取累计已兑换的创业币总数
	 * @author 郁政
	 */
	public function getHasUsedCount($user_id){
		$count = 0;
		$currency = DB::select(array(DB::expr('sum(score)'),'total'))->from('user_currency')->where('user_id','=',$user_id)->where('score_operating','=',1)->where('score_type','=',2)->execute();
		if(isset($currency[0]['total']) && $currency[0]['total']){
			$count = $currency[0]['total'];
		}
		return $count;
	}
	
	/**
	 * 兑换创业币 
	 * @author 郁政
	 */
	public function goExchange($user_id,$amount){
		$msg = array();
		$amount = intval($amount);
		$service = new Service_Platform_SearchInvestor();
		$point = $service->getAllScore($user_id);
		$hasusedCount = $this->getHasUsedCount($user_id);
		if(($hasusedCount + $amount) <= $point){
			$currency = ORM::factory('Usercurrency');
			$currency->user_id = $user_id;
			$currency->score_operating = 1;
			$currency->score_type = 2;
			$currency->score = $amount;
			$currency->date = time();
			$currency->create();
			$msg['status'] = 1;
		}else{
			$msg['status'] = 2;
		}
		return $msg;
	}
	
	/**
	 * 创业币入库
	 * @author 郁政
	 */
	public function addChuangYeBi($user_id,$score_operating = 1,$score_type = 1,$score){
		$currency = ORM::factory('Usercurrency');
		$currency->user_id = $user_id;
		$currency->score_operating = $score_operating;
		$currency->score_type = $score_type;
		$currency->score = $score;
		$currency->date = time();
		$res = $currency->create();
		if(isset($res->id) && $res->id > 0){
			return true;
		}
		return false;
	}
	
	/**
	 * 返回当前活动或者还未开始的活动id
	 * @author 郁政
	 */
	public function getGameId(){
		$res = 0;
		$game = ORM::factory('Game')->where('status','=',1)->find();
		if($game->loaded()){
			$res = $game->game_id;
		}else{
			$count = 0;
			$count = ORM::factory('Game')->where('status','=',0)->count_all();
			if($count == 0){
				$game = ORM::factory('Game')->where('status','=',2)->order_by('id','desc')->limit(1)->find();
				if($game->loaded()){
					$res = $game->game_id;
				}
			}else{
				$game = ORM::factory('Game')->where('status','=',0)->order_by('id','asc')->limit(1)->find();
				if($game->loaded()){
					$res = $game->game_id;
				}
			}	
		}		
		return $res;
	}
}
?>