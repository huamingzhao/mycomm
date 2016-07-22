<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 活动 Service
 * @author 郁政
 */

class Service_Platform_HuoDong{
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
 	* 根据用户id返回该用户是否中过实物奖
 	* @author 郁政
 	*/
	public function isAreadyBigJiang($user_id){
		$user_id = intval($user_id);
		$roulette = ORM::factory('Roulette');
		$count = $roulette->where('user_id','=',$user_id)->where('prize_type','in',array(1,2,3))->count_all();
		if($count > 0){
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
		//$date = '2013-09-30';
		$om = ORM::factory('JiangChi');
		$jiangchi = $om->where('date','=',$date)->find();
		if($jiangchi->id != ''){
			$prize_key = $jiangchi->prize_key;
			$now = $jiangchi->now_site;
			if(strlen($prize_key) > $now){
				$num = $prize_key[$now];
			}else{
				$num = 1;
			}			
		}
//		$redis = Rediska_Manager::get("temp");
//		$redis_name = 'jiangchi_redis';
//		$res = $redis->getList($redis_name);
//		foreach($res as $v){
//			$arr[] = json_decode($v);	
//		}	
//		//echo "<pre>";print_r($arr);exit;
//		foreach($arr as $val){			
//			if($val->date == $date){
//				if(UTF8::strlen($val->prize_key) > $val->now_site){
//					$num = $val->prize_key[$val->now_site];
//				}else{
//					$num = 1;
//				}
//			}
//		}		
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
		if($rand >= 1 && $rand <=5){
			$res = 4;
		}elseif($rand >= 6 && $rand <= 15){
			$res = 5;
		}elseif($rand >= 16 && $rand <= 27){
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
		$jiangchi = new jiangchi();
		$arr = array();
		$arr = $jiangchi->begin();
		$date = common::chouJiangTime();
		$redis = Rediska_Manager::get("temp");
		$redis_name = 'jiangchi_redis';
		$jiangchi = array();	
		$om = ORM::factory('JiangChi');	
		$count = $om->count_all();
		if($count > 0){
			return false;
		}	
		foreach($arr as $k => $v){	
			$om = ORM::factory('JiangChi');		
			$om->date = $v['date'];
			$om->prize_key = $v['jiangchi'];
			$om->game_id = $date['game_id'];
			$om->create();
			$jiangchi['id'] = ''.intval($k+1).'';
			$jiangchi['date'] = $v['date'];
			$jiangchi['prize_key'] = $v['jiangchi'];
			$jiangchi['now_site'] = '0';
			$jiangchi['game_id'] = '1';	
			try{
                //事物开始
                $transcation = $redis->transaction();
                $transcation->prependToList($redis_name, json_encode($jiangchi));
                $transcation->increment($redis_name."_counter");
                $transcation->execute();                
            //事务结束
            }
            catch(Rediska_Exception $e){
                return false;
            }			
		}
	}
	
	/**
 	* 抽奖时添加相应记录入库并发送短信和冲创业币
 	* @author 郁政
 	*/
	public function addDrawRecord($user_id,$prize_type,$mobile,$type = 0){
		$arr1 = array();
		$arr2 = array();
		$user_id = intval($user_id);
		$date = date('Y-m-d',time());
		//$date = '2013-09-30';
		$prize_type = intval($prize_type);
		$service = new Service_User_Person_Points();
		$user_service = new Service_User();
//		$redis = Rediska_Manager::get("temp");
//		$redis_name = 'jiangchi_redis';
		try {
			$roulette = ORM::factory('Roulette');
			$roulette->user_id = $user_id;
			$roulette->prize_type = $prize_type;
			$roulette->lucky_time = time();
			$roulette->save();
			if($type != 1){
			$jiangchi = ORM::factory('JiangChi')->where('date','=',$date)->find();
			if($jiangchi->loaded()){				
//				$arr1['id'] = $jiangchi->id;
//				$arr1['date'] = $jiangchi->date;
//				$arr1['prize_key'] = $jiangchi->prize_key;
//				$arr1['now_site'] = $jiangchi->now_site;
//				$arr1['game_id'] = $jiangchi->game_id;	
//				$referenceValue = json_encode($arr1);
//			    //print_r($referenceValue);exit();
//				$arr2['id'] = $jiangchi->id;
//				$arr2['date'] = $jiangchi->date;
//				$arr2['prize_key'] = $jiangchi->prize_key;
//				$arr2['now_site'] = ''.($jiangchi->now_site+1).'';
//				$arr2['game_id'] = $jiangchi->game_id;	
//				$value = json_encode($arr2);				
				$old_site = $jiangchi->now_site;
				$jiangchi->now_site = $old_site + 1;
				$cg = $jiangchi->update();
//				if($cg->id > 0){
//				//事务开始
//		        try{
//		            $transcation = $redis->transaction();
//		            $transcation->insertToList($redis_name, "BEFORE", $referenceValue, $value);
//		            $transcation->deleteFromList($redis_name, $referenceValue,1);
//		            $result = $transcation->execute();		            
//		        }
//		        catch (Rediska_Exception $e){
//		            return false;
//		        }
//		        //事务结束
//				}else{
//					return false;
//				}
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
				$msg = "恭喜您获得U盘一个，我们将尽快为您发送奖品，请将此消息分享给您的好友，每天都能抽奖一次，祝您明天好运来。";
				$msg2 = common::send_message($mobile, $msg, 'online');
				//消息发送成功
	            if($msg2->retCode === 0){
	                $user_service->messageLog($mobile,$user_id,1,$msg,1);	                
	            }else{//发送失败
	                $user_service->messageLog($mobile,$user_id,1,$msg,0);
	            }
			}elseif($prize_type == 3){
				$msg = "恭喜您获得T恤一件，我们将尽快为您发送奖品，请将此消息分享给您的好友，每天都能抽奖一次，祝您明天好运来。";
				$msg2 = common::send_message($mobile, $msg, 'online');
				//消息发送成功
	            if($msg2->retCode === 0){
	                $user_service->messageLog($mobile,$user_id,1,$msg,1);	                
	            }else{//发送失败
	                $user_service->messageLog($mobile,$user_id,1,$msg,0);
	            }
			}elseif($prize_type == 4){
				$service->addPoints($user_id, 'chuanyebi',100);
			}elseif($prize_type == 5){
				$service->addPoints($user_id, 'chuanyebi',80);
			}elseif($prize_type == 6){
				$service->addPoints($user_id, 'chuanyebi',50);
			}elseif($prize_type == 7){
				$service->addPoints($user_id, 'chuanyebi',20);
			}elseif($prize_type == 8){
				$service->addPoints($user_id, 'chuanyebi',10);
			}elseif($prize_type == 9){
				$service->addPoints($user_id, 'chuanyebi',1);
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
		$date = common::chouJiangTime();
		$end_time = strtotime($date['end_time']);
		if($time < $end_time){
			$user_huodong = ORM::factory('UserHuoDong');
			$user_huodong->user_id = $user_id;
			$user_huodong->game_id = $date['game_id'];
			$user_huodong->datetime = time();
			$user_huodong->create();
		}
		return true;
	}
	
	/**
 	* 获取获奖名单列表
 	* @author 郁政
 	*/
	public function getWinners($limit=500){
		$return = array();
		$res = array();
		$res1 = array();
		$res2 = array();
		$arr = array(
			'0' => '',
			'1' => 'ipod',
			'2' => 'U盘',
			'3' => '幸运奖',
			'4' => '100创业币',
			'5' => '80创业币',
			'6' => '50创业币',
			'7' => '20创业币',
			'8' => '10创业币',
			'9' => '1创业币'
		);	
		$person_service = new Service_User_Person_User();
		$client = Service_Sso_Client::instance();	
		//$om1 = DB::select('user_person.per_realname','user.mobile','roulette.prize_type')->from('roulette')->join('user','left')->on('roulette.user_id','=','user.user_id')->join('user_person','left')->on('roulette.user_id','=','user_person.per_user_id')->where('roulette.prize_type','in',array(1,2,3))->where('roulette.game_id','=',1)->execute()->as_array();
		$om1 = DB::select('user_id','prize_type')->from('roulette')->where('prize_type','in',array(1,2,3))->where('game_id','=',1)->order_by('lucky_time','desc')->limit(20)->execute()->as_array();
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
		$om2 = DB::select('user_id','prize_type')->from('roulette')->where('prize_type','not in',array(1,2,3))->where('game_id','=',1)->order_by('lucky_time','desc')->limit(80)->execute()->as_array();		
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
		$date = common::chouJiangTime();
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
 	* 修改redis
 	* @author 郁政
 	*/
	public function updateRedis(){
		$result = array();
		$arr1 = array();
		$arr2 = array();
		$date = date('Y-m-d',time());
		$redis = Rediska_Manager::get("temp");
		$redis_name = 'jiangchi_redis';
		$jiangchi = ORM::factory('JiangChi')->where('date','=',$date)->find();
		if($jiangchi->loaded()){
			$arr1['id'] = $jiangchi->id;
			$arr1['date'] = $jiangchi->date;
			$arr1['prize_key'] = $jiangchi->prize_key;
			$arr1['now_site'] = '0';
			$arr1['game_id'] = $jiangchi->game_id;	
			$referenceValue = json_encode($arr1);
			$arr2['id'] = $jiangchi->id;
			$arr2['date'] = $jiangchi->date;
			$arr2['prize_key'] = $jiangchi->prize_key;
			$arr2['now_site'] = $jiangchi->now_site;
			$arr2['game_id'] = $jiangchi->game_id;	
			$value = json_encode($arr2);
			//事务开始
	        try{
	            $transcation = $redis->transaction();
	            $transcation->insertToList($redis_name, "BEFORE", $referenceValue, $value);
	            $transcation->deleteFromList($redis_name, $referenceValue,1);
	            $result = $transcation->execute();		            
	        }
	        catch (Rediska_Exception $e){
	            return false;
	        }
	        //事务结束		
		}
		return $result;
	}
}
?>
