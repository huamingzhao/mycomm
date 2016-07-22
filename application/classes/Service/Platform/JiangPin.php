<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 活动前期宣传 Service
 * @author 郁政
 */

class Service_Platform_JiangPin{
	/**
 	* 获取往期中实物奖的名单
 	* @author 郁政
 	*/
	public function getBigPrizeBefore($game_id){
		$res = array();
		$arr = array();
		if($game_id == 1){
			$arr = common::getPrizeName1();
			$om = DB::select('user_person.per_realname','user.mobile','roulette.prize_type')->from('roulette')->join('user','left')->on('roulette.user_id','=','user.user_id')->join('user_person','left')->on('roulette.user_id','=','user_person.per_user_id')->where('roulette.prize_type','in',array(1,2,3))->where('roulette.game_id','=',$game_id)->order_by('roulette.lucky_time','desc')->order_by('roulette.prize_type','asc')->limit(75)->execute()->as_array();
		}elseif($game_id == 2){
			$arr = common::getPrizeName2();
			$om = DB::select('user_person.per_realname','user.mobile','roulette.prize_type')->from('roulette')->join('user','left')->on('roulette.user_id','=','user.user_id')->join('user_person','left')->on('roulette.user_id','=','user_person.per_user_id')->where('roulette.prize_type','in',array(1,2,3,4))->where('roulette.game_id','=',$game_id)->order_by('roulette.lucky_time','desc')->order_by('roulette.prize_type','asc')->limit(75)->execute()->as_array();
		}		
		if(!empty($om)){
			foreach($om as $k => $v){
				$res[$k]['name'] =  $v['per_realname'];
				$res[$k]['mobile'] = common::decodeUserMobile(common::decodeMoible($v['mobile']));
				$res[$k]['prize'] = $arr[$v['prize_type']];
			}
		}	
		return $res;	
	}
}
?>