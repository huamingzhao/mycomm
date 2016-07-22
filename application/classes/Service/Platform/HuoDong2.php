<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 活动 Service
 * @author 郁政
 */

class Service_Platform_HuoDong2{
	/**
 	* 根据用户id返回该用户当天是否已经抽过奖
 	* @author 郁政
 	*/
	public function isAlreadyDraw($user_id){
		$user_id = intval($user_id);
		$start_time = strtotime(date('Y-m-d 00:00:00', time()));
		$end_time = strtotime(date('Y-m-d 00:00:00', time()+86400));
		$roulette = ORM::factory('Roulette');
		$count = $roulette->where('user_id','=',$user_id)->where('lucky_time','>=',$start_time)->where('lucky_time','<',$end_time)->count_all();
		if($count > 0){
			return true;
		}
		return false;
	}
	
	/**
 	* 根据用户id返回该用户是否中过实物奖或者是否在该活动内注册的用户
 	* @author 郁政
 	*/
	public function isAreadyBigJiang($user_id){
		$date = common::chouJiangTime2();
		$game_id = $date['game_id'];
		$user_id = intval($user_id);
		$roulette = ORM::factory('Roulette');
		$count = $roulette->where('user_id','=',$user_id)->where('prize_type','in',array(1,2,3,4))->where('game_id','=',$game_id)->count_all();
		if($count > 0){
			return true;
		}
		$count2 = ORM::factory('UserHuoDong')->where('user_id','=',$user_id)->where('game_id','=',$game_id)->count_all();
		if($count2 == 0){
			return true;
		}
		return false;
	}
	
	/**
 	* 获得该次抽奖在奖池中返回的数字
 	* @author 郁政
 	*/
	public function getNumFromJiangChi(){
		$res = array();		
		$num = 1;		
		$date = date('Y-m-d',time());
		
		//$date = '2013-10-12';
		
		$om = ORM::factory('JiangChi');
		$jiangchi = $om->where('date','=',$date)->find();
		if(isset($jiangchi->id) && $jiangchi->id != ''){
			$prize_key = $jiangchi->prize_key;
			$now = $jiangchi->now_site;
			if(strlen($prize_key) > $now){
				$num = $prize_key[$now];
			}else{
				$num = 1;
			}			
		}
		return $num;		
	}
	
	/**
 	* 根据数字返回获得的奖品编号
 	* @author 郁政
 	*/	
	public function getPrizeByNum($num){
		$prize_type = 9;
		$num = intval($num);
		if($num == 2){
			$prize_type = 1;
		}
		if($num == 3){
			$prize_type = 2;
		}
		if($num == 4){
			$prize_type = 3;
		}
		if($num == 5){
			$prize_type = 4;
		}
		if($num == 1){
			$prize_type = $this->_getChuangYeBi();
		}
		return $prize_type;
	}
	
	/**
 	* 返回创业币的小奖
 	* @author 郁政
 	*/	
	public function _getChuangYeBi(){
		$res = 9;
		$rand = rand(1, 100);
		if($rand >= 1 && $rand <=12){
			$res = 5;
		}elseif($rand >= 13 && $rand <= 27){
			$res = 6;
		}elseif($rand >= 28 && $rand <= 45){
			$res = 7;
		}elseif($rand >= 46 && $rand <= 70){
			$res = 8;		
		}else{
			$res = 9;
		}
		return $res;
	}
	
	/**
 	* 生成奖池
 	* @author 郁政
 	*/
	public function buildJiangChi(){
		$jiangchi = new jiangchi2();
		$arr = array();
		$arr = $jiangchi->begin();
		$date = common::chouJiangTime2();
		$om = ORM::factory('JiangChi');			
		foreach($arr as $k => $v){	
			$om = ORM::factory('JiangChi');		
			$om->date = $v['date'];
			$om->prize_key = $v['jiangchi'];
			$om->game_id = $date['game_id'];
			$om->create();				
		}
		return true;
	}
	
	/**
 	* 抽奖时添加相应记录入库并发送短信和冲创业币
 	* @author 郁政
 	*/
	public function addDrawRecord($user_id,$prize_type,$mobile,$type = 0){
		$arr1 = array();
		$arr2 = array();
		$user_id = intval($user_id);
		$con = common::chouJiangTime2();
		$game_id = $con['game_id'];
		$date = date('Y-m-d',time());
		
		//$date = '2013-10-12';
		
		$prize_type = intval($prize_type);
		$service = new Service_User_Person_Points();
		//$service = new Service_User_Person_Huodong();
		$user_service = new Service_User();
		try {
			$roulette = ORM::factory('Roulette');
			$roulette->user_id = $user_id;
			$roulette->prize_type = $prize_type;
			$roulette->lucky_time = time();
			$roulette->game_id = $game_id;
			$roulette->save();
			if($type != 1){
				$jiangchi = ORM::factory('JiangChi')->where('date','=',$date)->find();
				if($jiangchi->loaded()){	
					$old_site = $jiangchi->now_site;
					$jiangchi->now_site = $old_site + 1;
					$cg = $jiangchi->update();	
				}
			}			
			if($prize_type == 1){				
				$msg = "恭喜您获得Ipod一个，我们将尽快为您发送奖品，请将此消息分享给您的好友，每天都能抽奖一次，祝您明天好运来。";
				$msg2 = common::send_message($mobile, $msg, 'online');
				//消息发送成功
	            if($msg2->retCode === 0){
	                $user_service->messageLog($mobile,$user_id,1,$msg,1);	                
	            }else{//发送失败
	                $user_service->messageLog($mobile,$user_id,1,$msg,0);
	            }
			}elseif($prize_type == 2){
				$msg = "恭喜您获得移动充电器一个，我们将尽快为您发送奖品，请将此消息分享给您的好友，每天都能抽奖一次，祝您明天好运来。";
				$msg2 = common::send_message($mobile, $msg, 'online');
				//消息发送成功
	            if($msg2->retCode === 0){
	                $user_service->messageLog($mobile,$user_id,1,$msg,1);	                
	            }else{//发送失败
	                $user_service->messageLog($mobile,$user_id,1,$msg,0);
	            }
			}elseif($prize_type == 3){
				$msg = "恭喜您获得U盘一个，我们将尽快为您发送奖品，请将此消息分享给您的好友，每天都能抽奖一次，祝您明天好运来。";
				$msg2 = common::send_message($mobile, $msg, 'online');
				//消息发送成功
	            if($msg2->retCode === 0){
	                $user_service->messageLog($mobile,$user_id,1,$msg,1);	                
	            }else{//发送失败
	                $user_service->messageLog($mobile,$user_id,1,$msg,0);
	            }
			}elseif($prize_type == 4){
				$msg = "恭喜您获得T恤一件，我们将尽快为您发送奖品，请将此消息分享给您的好友，每天都能抽奖一次，祝您明天好运来。";
				$msg2 = common::send_message($mobile, $msg, 'online');
				//消息发送成功
	            if($msg2->retCode === 0){
	                $user_service->messageLog($mobile,$user_id,1,$msg,1);	                
	            }else{//发送失败
	                $user_service->messageLog($mobile,$user_id,1,$msg,0);
	            }
			}elseif($prize_type == 5){
				$service->addPoints($user_id, 'chuanyebi',100);
				//$service->addChuangYeBi($user_id, 1, 1, 100);
			}elseif($prize_type == 6){
				$service->addPoints($user_id, 'chuanyebi',80);
				//$service->addChuangYeBi($user_id, 1, 1, 80);
			}elseif($prize_type == 7){
				$service->addPoints($user_id, 'chuanyebi',50);
				//$service->addChuangYeBi($user_id, 1, 1, 50);
			}elseif($prize_type == 8){
				$service->addPoints($user_id, 'chuanyebi',20);
				//$service->addChuangYeBi($user_id, 1, 1, 20);
			}elseif($prize_type == 9){
				$service->addPoints($user_id, 'chuanyebi',10);
				//$service->addChuangYeBi($user_id, 1, 1, 10);
			}		
			return true;
		} catch (Exception $e) {
			return false;
		}
		
	} 
	
	/**
 	* 活动期间用户注册时往czzs_user_huodong表中添加记录
 	* @author 郁政
 	*/
	public function addUserTeamp($user_id,$time){
		$date = common::chouJiangTime2();
		$start_time = strtotime($date['start_time']);
		
		//$start_time = strtotime(date('Y-m-d 00:00:00', time()-86400));
		
		$end_time = strtotime($date['end_time']);
		if($time > $start_time && $time < $end_time){
			$count= ORM::factory('UserHuoDong')->where('game_id','=',$date['game_id'])->where('user_id','=',$user_id)->count_all();
			if($count > 0){
				return false;
			}
			$om = ORM::factory('UserHuoDong')->where('game_id','=',$date['game_id'])->order_by('datetime','desc')->limit(1)->find()->as_array();
			if(isset($om['temp_id']) && $om['temp_id'] != ""){
				$user_huodong = ORM::factory('UserHuoDong');
				$user_huodong->user_id = $user_id;
				$user_huodong->game_id = $date['game_id'];
				$user_huodong->datetime = time();
				$user_huodong->temp_id = intval($om['temp_id'])+1;	
				$user_huodong->create();
			}else{
				$user_huodong = ORM::factory('UserHuoDong');
				$user_huodong->user_id = $user_id;
				$user_huodong->game_id = $date['game_id'];
				$user_huodong->datetime = time();
				$user_huodong->temp_id = 1;	
				$user_huodong->create();						
			}
			return true;
			
		}else{
			return false;	
		}	
	}
	
	/**
 	* 获取获奖名单列表
 	* @author 郁政
 	*/
	public function getWinners(){
		$return = array();
		$res = array();
		$res1 = array();
		$res2 = array();
		$arr = common::getPrizeName2();
		$person_service = new Service_User_Person_User();
		$client = Service_Sso_Client::instance();		
		//$om1 = DB::select('user_person.per_realname','user.mobile','roulette.prize_type')->from('roulette')->join('user','left')->on('roulette.user_id','=','user.user_id')->join('user_person','left')->on('roulette.user_id','=','user_person.per_user_id')->where('roulette.prize_type','in',array(1,2,3,4))->where('roulette.game_id','=',2)->order_by('roulette.lucky_time','desc')->limit(20)->execute()->as_array();
		$om1 = DB::select('user_id','prize_type')->from('roulette')->where('prize_type','in',array(1,2,3,4))->where('game_id','=',2)->order_by('lucky_time','desc')->limit(20)->execute()->as_array();
		$user_ids = array();
		$prize_types = array();
		$userInfo = array();
		if(!empty($om1)){
			foreach($om1 as $k => $v){				
				$user_ids[] = $v['user_id'];
				$prize_types[] = $v['prize_type'];
			}			
			$userInfo = $client->getUserInfoByMoreUserId($user_ids);
		}		
		if(!empty($userInfo)){
			foreach($userInfo as $k => $v){	
				$userperson = $person_service->getPerson($v['id']);			
				$res1[$k]['name'] =  $userperson->per_realname ? $userperson->per_realname : '佚名';
				$res1[$k]['mobile'] = common::decodeUserMobile($v['mobile']);
				$res1[$k]['prize'] = $arr[$prize_types[$k]];
			}
		}			
		$user_ids = array();
		$prize_types = array();
		$userInfo = array();
		$om2 = DB::select('user_id','prize_type')->from('roulette')->where('prize_type','not in',array(1,2,3,4))->where('game_id','=',2)->order_by('lucky_time','desc')->limit(80)->execute()->as_array();		
		if(!empty($om2)){
			foreach($om2 as $k => $v){
				$user_ids[] = $v['user_id'];
				$prize_types[] = $v['prize_type'];
			}
			$userInfo = $client->getUserInfoByMoreUserId($user_ids);
		}
		if(!empty($userInfo)){
			foreach($userInfo as $k => $v){	
				$userperson = $person_service->getPerson($v['id']);			
				$res2[$k]['name'] =  $userperson->per_realname ? $userperson->per_realname : '佚名';
				$res2[$k]['mobile'] = common::decodeUserMobile($v['mobile']);
				$res2[$k]['prize'] = $arr[$prize_types[$k]];
			}
		}	
		$res = array_merge($res1,$res2);
		shuffle($res);		
		$return['result'] = $res;		
		return $return;
	}
	
	/**
 	* 判断现在是否是活动时间
 	* @author 郁政
 	*/
	public function isHuoDongTime(){
		$res = 0;
		$date = common::chouJiangTime2();
		$start_time = strtotime($date['start_time']);
		$end_time = strtotime($date['end_time']);
		$now_time = time();
		if($now_time < $start_time){
			$res = 1;
		}
		if($now_time > $end_time){
			$res = 2;
		}
		return $res;
	}	
	
	/**
 	* 根据用户id获得参加活动的序号
 	* @author 郁政
 	*/
	public function getTempUserId($user_id){
		$res = array();
		$huodong = ORM::factory('UserHuoDong')->where('user_id','=',$user_id)->where('game_id','=',2)->find()->as_array();
		if(isset($huodong['temp_id']) && $huodong['temp_id'] != ""){			
			$res['temp_id'] = $huodong['temp_id'];						
		}
		return $res;
	}
	
	/**
 	* 查看奖池（调试用）
 	* @author 郁政
 	*/
	public function getJiangChi($user_id){	
		$res = array();	
		$service = new Service_User(); 
		$userinfo = $service->getUserInfoById($user_id);
	    $email = $userinfo->email; 
	    $arr = array(
	    	'350405445@qq.com',
	    );  
	    if(in_array($email, $arr)){	    	
	    	$om = ORM::factory('JiangChi')->where('game_id','=',2)->find_all()->as_array();
	    	foreach($om as $k => $v){
	    		$res[$k]['date'] = $v->date;
	    		$res[$k]['prize_key'] = $v->prize_key;
	    		$res[$k]['now_site'] = $v->now_site;
	    	}
	    }
	    return $res;	    	     
	}
	
		
	/**
 	* 获取抽奖人数
 	* @author 郁政
 	*/
	public function getRoulettePeopleNum($game_id){
		$res = 0;		
		$res = ORM::factory('UserHuoDong')->where('game_id','=',$game_id)->count_all();
		return $res;
	}
	
	/**
 	* 获取抽奖总次数
 	* @author 郁政
 	*/
	public function getRouletteCount($game_id){
		$res = 0;		
		$res = ORM::factory('Roulette')->where('game_id','=',$game_id)->count_all();
		return $res;		
	}
	
	/**
 	* 获取上一期中实物奖的名单
 	* @author 郁政
 	*/
	public function getBigPrizeBefore($game_id,$type = 1){
		$res = array();				
		$person_service = new Service_User_Person_User();
		$client = Service_Sso_Client::instance();	
		if($game_id == 1){
			$arr = common::getPrizeName1();
		}elseif($game_id == 2){
			$arr = common::getPrizeName2();
		}					
		if($type == 1){
			$om = DB::select('user_id','prize_type')->from('roulette')->where('prize_type','in',array(1,2,3))->where('game_id','=',$game_id)->order_by('lucky_time','desc')->order_by('prize_type','asc')->execute()->as_array();
			//$om = DB::select('user_person.per_realname','user.mobile','roulette.prize_type')->from('roulette')->join('user','left')->on('roulette.user_id','=','user.user_id')->join('user_person','left')->on('roulette.user_id','=','user_person.per_user_id')->where('roulette.prize_type','in',array(1,2,3))->where('roulette.game_id','=',$game_id)->order_by('roulette.lucky_time','desc')->order_by('roulette.prize_type','asc')->execute()->as_array();
		}else{
			$om = DB::select('user_id','prize_type')->from('roulette')->where('prize_type','in',array(1,2,3))->where('game_id','=',$game_id)->order_by('lucky_time','desc')->order_by('prize_type','asc')->limit(75)->execute()->as_array();
			//$om = DB::select('user_person.per_realname','user.mobile','roulette.prize_type')->from('roulette')->join('user','left')->on('roulette.user_id','=','user.user_id')->join('user_person','left')->on('roulette.user_id','=','user_person.per_user_id')->where('roulette.prize_type','in',array(1,2,3))->where('roulette.game_id','=',$game_id)->order_by('roulette.lucky_time','desc')->order_by('roulette.prize_type','asc')->limit(75)->execute()->as_array();
		}	
		$user_ids = array();
		$prize_types = array();
		$userInfo = array();
		if(!empty($om)){
			foreach($om as $k => $v){				
				$user_ids[] = $v['user_id'];
				$prize_types[] = $v['prize_type'];
			}			
			$userInfo = $client->getUserInfoByMoreUserId($user_ids);
		}		
		if(!empty($userInfo)){
			foreach($userInfo as $k => $v){
				$userperson = $person_service->getPerson($v['id']);			
				$res[$k]['name'] =  $userperson->per_realname ? $userperson->per_realname : '佚名';
				$res[$k]['mobile'] = common::decodeUserMobile($v['mobile']);
				$res[$k]['prize'] = $arr[$prize_types[$k]];
			}
		}	
		return $res;	
	}
	
	/**
 	* 获取本期参加抽奖人数的基数
 	* @author 郁政
 	*/
	public function getBaseNumber($game_id_1,$game_id_2){
		$res = 0;
		$base_num_1 = 0;
		$base_num_2 = 0;
		$base_num_1 = $this->getRouletteCount($game_id_1);
		$base_num_2 = $this->getRouletteCount($game_id_2);
		if($base_num_1 > $base_num_2){
			$res = $base_num_1;
		}else{
			$res = $base_num_2;
		}
		return $res;
	}
	
	/**
 	* 获得奖品编号（新）
 	* @author 郁政
 	*/
	public function getPrizeNumberNum($game_id_1,$game_id_2,$multiple){		
		$res = 9;
		$res1 = 1;
		$memcache = Cache::instance ('memcache');
		$cache_time = 86400;
		$base_num = $memcache->get('choujiang_basenum_'.$game_id_2);
		if(!$base_num){
			$base_num = $this->getBaseNumber($game_id_1, $game_id_2);
			$memcache->set('choujiang_basenum_'.$game_id_2, $base_num,$cache_time);
		}
		$num = 0;
		$num = $base_num * $multiple;		
		$res1 = $this->getNumberByProbability($num, $game_id_2);
		$res = $this->getPrizeByNum($res1);		
		//大奖种类个数
		$count = $memcache->get('prize_count_'.$game_id_2);
		if(!$count){
			$count = ORM::factory('PrizeAmount')->where('game_id','=',$game_id_2)->count_all();
			$memcache->set('prize_count_'.$game_id_2,$count,$cache_time);
		}		
		if($res >= 1 && $res <= $count){
			$isEnough = $this->isEnoughPrize($res, $game_id_2);
			if(!$isEnough){
				$res = $this->_getChuangYeBi();
			}
		}
		return $res;
	}
	
	/**
 	* 获得奖品按概率（新）
 	* @author 郁政
 	*/
	public function getNumberByProbability($num,$game_id){
		$res = 1;
		$num = intval($num);		
		$memcache = Cache::instance('memcache');
		$cache_time = 604800;
		$jiangpin_detail = $memcache->get('jiangpin_multiple_'.$game_id);
		if(!$jiangpin_detail){
			$jiangpin_detail = array();
			$prize_amount = ORM::factory('PrizeAmount')->where('game_id','=',$game_id)->find_all()->as_array();
			if(count($prize_amount) > 0){
				foreach($prize_amount as $k => $v){
					$jiangpin_detail[$v->p_id]['amount'] = $v->amount;
					$jiangpin_detail[$v->p_id]['mutiple'] = $v->mutiple;
				}
			$memcache->set('jiangpin_multiple_'.$game_id,$jiangpin_detail,$cache_time);
			}			
		}	
		$amount_1 = isset($jiangpin_detail[1]['amount']) ? $jiangpin_detail[1]['amount'] : 5;
		$amount_2 = isset($jiangpin_detail[2]['amount']) ? $jiangpin_detail[2]['amount'] : 10;
		$amount_3 = isset($jiangpin_detail[3]['amount']) ? $jiangpin_detail[3]['amount'] : 32;
		$amount_4 = isset($jiangpin_detail[4]['amount']) ? $jiangpin_detail[4]['amount'] : 60;
		$multiple_1 = isset($jiangpin_detail[1]['mutiple']) ? $jiangpin_detail[1]['mutiple'] : 1;
		$multiple_2 = isset($jiangpin_detail[2]['mutiple']) ? $jiangpin_detail[2]['mutiple'] : 1;
		$multiple_3 = isset($jiangpin_detail[3]['mutiple']) ? $jiangpin_detail[3]['mutiple'] : 1;
		$multiple_4 = isset($jiangpin_detail[4]['mutiple']) ? $jiangpin_detail[4]['mutiple'] : 1;		
		$rand = rand(1, $num);
		$begin_1 = 1;
		$last_1 = intval($amount_1*$multiple_1);
		$begin_2 = $last_1 + 1;
		$last_2 = $begin_2 + intval($amount_2*$multiple_2);
		$begin_3 = $last_2 + 1;
		$last_3 = $begin_3 + intval($amount_3*$multiple_3);
		$begin_4 = $last_3 + 1;
		$last_4 = $begin_4 + intval($amount_4*$multiple_4);		
		if($rand >= $begin_1 && $rand <= $last_1){
			$res = 2;
		}elseif($rand >= $begin_2 && $rand <= $last_2){
			$res = 3;
		}elseif($rand >= $begin_3 && $rand <= $last_3){
			$res = 4;
		}elseif($rand >= $begin_4 && $rand <= $last_4){
			$res = 5;
		}else{
			$res = 1;
		}
		return $res;
	}
	
	/**
 	* 判断该奖品数量是否充足
 	* @author 郁政
 	*/
	public function isEnoughPrize($p_id,$game_id){	
		$om = ORM::factory('PrizeAmount')->where('p_id','=',$p_id)->where('game_id','=',$game_id)->find();	
		if(isset($om->now_amount) && $om->now_amount > 0){			
			$om->now_amount = intval($om->now_amount - 1);
			$om->update();
			return true;
		}
		return false;
	}
	
	/**
 	* 根据上证指数收盘价获取大奖用户
 	* @author 郁政
 	*/
	public function getUserBySz($game_id){
		$res = array();
		$date = common::chouJiangTime2();
		$start_time = strtotime($date['end_time']);
		$end_time = $start_time + 43200;
		$now_date = time();
		if($now_date >= $start_time && $now_date <= $end_time){
			//获取参与抽奖总人数
			$count_people = $this->getRoulettePeopleNum($game_id);			
			//调用新浪api获取上证指数
			$url = "http://hq.sinajs.cn/list=s_sh000001";
			$str = file_get_contents($url); 
			$arr = array();
			$arr = explode(',', $str);
			$sz = isset($arr[1]) ? $arr[1] : '';
			if($sz != '' && $count_people != 0){	
				$sz = substr($sz,0,strlen($sz)-1)*1;  
				$cheng= ceil( $sz * 10000 );
				$lucky_id = $cheng % ceil( $count_people );
				$info = array();
				$info = $this->getUserInfoByLuckyId($game_id,$lucky_id);				
				$res['name'] = isset($info['name']) ? $info['name'] : '';
				$res['mobile'] = isset($info['mobile']) ? $info['mobile'] : '';	
				$res['people'] = $count_people;					
				$res['sz'] = $sz;
				$res['lucky_id'] = $lucky_id;
				$cache = Cache::instance('redis');
				$redis = json_encode($res);
				$cache->set('sz_luck_user_'.$game_id, $redis);
			}	
		}
		return $res;
	}
	
	/**
 	* 根据中奖id获取用户信息
 	* @author 郁政
 	*/
	public function getUserInfoByLuckyId($game_id,$lucky_id){
		$res = array();
		$service = new Service_User();
		$person_service = new Service_User_Person_User();
		$user_huodong = ORM::factory('UserHuoDong')->where('game_id','=',$game_id)->where('temp_id','=',$lucky_id)->find();
		if(isset($user_huodong->user_id) && $user_huodong->user_id != ""){
			$user_id = $user_huodong->user_id;
			$userinfo = $service->getUserInfoById($user_id);
	        $userperson = $person_service->getPerson($user_id);
	        $res['name'] =  (isset($userperson->per_realname) && $userperson->per_realname != '') ? $userperson->per_realname : '';
	        $res['mobile'] = (isset($userinfo->mobile) && $userinfo->mobile != '') ? common::decodeUserMobile($userinfo->mobile) : '';	        				
		}		
		return $res;
	}
	
}
?>
