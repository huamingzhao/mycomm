<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 活动
 * @author 郁政
 */
class Controller_Platform_HuoDong2 extends Controller_Platform_Template{
	/**
	 * 轮盘抽奖 
	 * @author 郁政
	 * status表示状态码，200为正常，201为未登录，202为未绑定手机,203为当天该用户已经抽过奖,204为活动还没开始,205为活动已经结束,206为非个人用户登录
	 * jiangpin表示奖品类型，1为ipod，2为移动充电器，3为U盘，4为T恤，5为100元，6为80元，7为50元，8为20元，9为10元
	 */
	public function action_rouletteDrawFlash(){
		//返回结果
		$result = array('status'=>'200','jiangpin'=>'0');
		$service = new Service_User();
		$person_service = new Service_User_Person_User();
        $huodong = new Service_Platform_HuoDong2();
		//判断是否在活动期间
		$code = $huodong->isHuoDongTime();
		
		//$code= 0;
		
		if($code == 1){
			$result['status'] = '204';
		}elseif($code == 2){
			$result['status'] = '205';
		}else{
			//判断是否已登录
			if($this->loginUser()){
				//当前登录用户id
	            $user_id=$this->userInfo()->user_id;            
	            $userinfo = $service->getUserInfoById($user_id);
	            $userperson = $person_service->getPerson($user_id);
	            if($userinfo->user_type != 2){
	            	$result['status'] = '206';
	            }else{
	            	
	            	if(!isset($userperson->per_realname) || (isset($userperson->per_realname) && $userperson->per_realname == '')){
	            		//207为真实姓名未填写
	            		$result['status'] = '207';
	            	}else{
			            if(isset($userinfo->valid_mobile) && $userinfo->valid_mobile == 1){		            	
			            	$isDraw = $huodong->isAlreadyDraw($user_id);
			            	//$isDraw = false;
			            	if(!$isDraw){		            		
		            			$prize_type = $huodong->getPrizeNumberNum(1,2,1.5);	 
		            			$suc = $huodong->addDrawRecord($user_id,$prize_type,$userinfo->mobile,1);		            		
	//		            		if($huodong->isAreadyBigJiang($user_id)){
	//		            			$num = 1;
	//		            			$prize_type = $huodong->getPrizeByNum($num);            		
	//		            			$suc = $huodong->addDrawRecord($user_id,$prize_type,$userinfo->mobile,1);
	//		            		}else{
	//		            			$num = $huodong->getNumFromJiangChi();
	//		            			$prize_type = $huodong->getPrizeByNum($num);            		
	//		            			$suc = $huodong->addDrawRecord($user_id,$prize_type,$userinfo->mobile);
	//		            		}
			            		if($suc){	    			
			            			$result['jiangpin'] = $prize_type;
			            		}
			            	}else{
			            		//203为当天该用户已经抽过奖
			            		$result['status'] = '203';
			            	}
			            }else{
			            	//202为未绑定手机
			            	$result['status'] = '202';
			            }
	            	}
	            }	            
			}else{
				//201为未登录
				$result['status'] = '201';
			}
		}				
		echo json_encode($result);
		exit;
	} 
	
	/**
 	* 生成奖池
 	* @author 郁政
 	*/
	public function action_buildJiangChi(){		
		$service = new Service_Platform_HuoDong2();
		$service->buildJiangChi();
		exit;
	}
	
	/**
 	* 显示抽奖页面
 	* @author 郁政
 	*/
	public function action_showChouJiang(){
		$content = View::factory('platform/huodong/choujiang2');
		$this->content->maincontent = $content;
		$service = new Service_Platform_HuoDong2();
		$date = common::chouJiangTime2();
		$game_id = $date['game_id'];
		//判断用户是否登录
		$isLogin = false;
		$isLogin = $this->loginUser();		
		#获取用户user_id
	    $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
	    //获取用户类型	
	    $service_user = new Service_User();    
	    $userinfo = $service_user->getUserInfoById($user_id);
	    $user_type = 1;
	    if($userinfo){
	    	$user_type = $userinfo->user_type;
	    }  	     
	    $content->user_type = $user_type;
		//获取参加抽奖的大奖id
		$t_id = array();
		$t_id = $service->getTempUserId($user_id);
		//获取当前抽奖活动正在参与抽奖人数
		$count_people = $service->getRoulettePeopleNum($game_id);
		//获取当前抽奖活动抽奖总数
		$count_roulette = $service->getRouletteCount($game_id);
		//获取上一期中实物奖的名单
		$big_prize_list = $service->getBigPrizeBefore($game_id-1,2);
		//抽奖结束，通过上证指数获得中奖用户信息
		$sz_list = array();
		$cache = Cache::instance('redis');		
		$redis = $cache->get('sz_luck_user_'.$game_id);	
		//var_dump($redis);	
		if($redis === NULL){				
			$sz_list = $service->getUserBySz($game_id);
		}else{						
			$sz_redis = json_decode($redis);
			$sz_list['name'] = $sz_redis->name;
			$sz_list['mobile'] = $sz_redis->mobile;
			$sz_list['people'] = $sz_redis->people;
			$sz_list['sz'] = $sz_redis->sz;
			$sz_list['lucky_id'] = $sz_redis->lucky_id;
		}//print_r($sz_list);
		$content->szlist = $sz_list;
		$content->isLogin = $isLogin;
		$content->temp_id = $t_id;
		$content->countPeople = $count_people;
		$content->countRoulette = $count_roulette;
		$content->big_prize_list = $big_prize_list;
		$this->template->title = "一句话网会员注册盛典_第二期疯狂会员月，大奖天天抽_100%中奖【iPhone5S、ipod、移动电源、U盘、T恤、500万创业币】";
        $this->template->keywords = "免费抽奖活动,2013免费抽奖活动,2013抽奖活动,2013免费抽奖活动,免费抽奖,会员注册";
        $this->template->description = "2013年10月11日——2013年11月10日是一句话网会员注册盛典活动，一句话网提供第二期会员注册活动，百万抽奖，让利500万，更有发放500万创业币给有缘创业的网友。并且提供iPhone5S、iPod、移动电源、U盘、创业T恤、大量的创业币等抽奖活动，100%中奖！";
	}
	
	/**
 	* 获取获奖名单列表
 	* @author 郁政
 	*/
	public function action_getWinners(){
		$result = array();
		$service = new Service_Platform_HuoDong2();
		$result = $service->getWinners();
		echo json_encode($result);
		exit;		
	}	
	
	/**
 	* 查看奖池（调试用）
 	* @author 郁政
 	*/
	public function action_lookJiangChi(){
		$service = new Service_Platform_HuoDong2();
		//当前登录用户id
	    $user_id=$this->userInfo()->user_id;
	    $res = $service->getJiangChi($user_id);
	    echo "<pre>";print_r($res);exit;
	}	
	
	/**
 	* 老用户获取大奖id(ajax)
 	* @author 郁政
 	*/
	public function action_getTempIdForChouJiang(){	
		$res = array();	
		$arr = array();
		if($this->request->is_ajax()){
			#获取用户user_id
	        $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;	
	        $service = new Service_User();	
	        $huodong = new Service_Platform_HuoDong2();
	        $userinfo = $service->getUserInfoById($user_id);
	        if(isset($userinfo->valid_mobile) && $userinfo->valid_mobile == 1){
	        	$huodong->addUserTeamp($user_id, time());
	        	$arr = $huodong->getTempUserId($user_id);	        	
	        	$res['status'] = isset($arr['temp_id']) ? $arr['temp_id'] : 0;
	        }else{
	        	$res['status'] = 0;
	        }
		}
		echo json_encode($res);	exit;	
	}
	
	/**
 	* 删除redis（调试用）
 	* @author 郁政
 	*/
//	public function action_delRedis(){	
//		$cache = Cache::instance('redis');	
//		$cache->delete('sz_luck_user_2');
//		exit;
//	}
	
	
	/**
 	* 插入奖品数量(一次性)
 	* @author 郁政
 	*/
	/*
	public function action_insertPrizeCount(){	
		$count_1 = 0;	
		$count_2 = 0;
		$count_3 = 0;
		$count_4 = 0;
		$count_1 = ORM::factory('Roulette')->where('game_id','=',2)->where('prize_type','=',1)->count_all();
		$count_2 = ORM::factory('Roulette')->where('game_id','=',2)->where('prize_type','=',2)->count_all();
		$count_3 = ORM::factory('Roulette')->where('game_id','=',2)->where('prize_type','=',3)->count_all();
		$count_4 = ORM::factory('Roulette')->where('game_id','=',2)->where('prize_type','=',4)->count_all();		
		$now_amount_1 = 5 - $count_1;
		$now_amount_2 = 10 - $count_2;
		$now_amount_3 = 32 - $count_3;
		$now_amount_4 = 60 - $count_4;	
		$om = ORM::factory('PrizeAmount')->where('game_id','=',2)->where('p_id','=',1)->find();
		if(!$om->loaded()){
			$om->p_id = 1;
			$om->amount = 5;
			$om->now_amount = $now_amount_1;
			$om->game_id = 2;
			$om->mutiple = 1.0;
			$om->save();
		}	
		$om = ORM::factory('PrizeAmount')->where('game_id','=',2)->where('p_id','=',2)->find();	
		if(!$om->loaded()){
			$om->p_id = 2;
			$om->amount = 10;
			$om->now_amount = $now_amount_2;
			$om->game_id = 2;
			$om->mutiple = 1.0;
			$om->save();
		}	
		$om = ORM::factory('PrizeAmount')->where('game_id','=',2)->where('p_id','=',3)->find();	
		if(!$om->loaded()){
			$om->p_id = 3;
			$om->amount = 32;
			$om->now_amount = $now_amount_3;
			$om->game_id = 2;
			$om->mutiple = 1.0;
			$om->save();
		}	
		$om = ORM::factory('PrizeAmount')->where('game_id','=',2)->where('p_id','=',4)->find();	
		if(!$om->loaded()){
			$om->p_id = 4;
			$om->amount = 60;
			$om->now_amount = $now_amount_4;
			$om->game_id = 2;
			$om->mutiple = 1.0;
			$om->save();
		}	
		exit;
	}
	*/
	
	/**
 	* 查看奖品数量(一次性)
 	* @author 郁政
 	*/
	/*
	public function action_selectPrizeCount(){	
		$res = array();	
		$om = ORM::factory('PrizeAmount')->find_all()->as_array();
		if(count($om) > 0){
			foreach($om as $k => $v){
				$res[$k]['p_id'] = $v->p_id;
				$res[$k]['amount'] = $v->amount;
				$res[$k]['now_amount'] = $v->now_amount;
				$res[$k]['game_id'] = $v->game_id;
				$res[$k]['mutiple'] = $v->mutiple;
			}
		}
		echo "<pre>";print_r($res);
		exit;
	}
	*/
}
?>