<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 活动
 * @author 郁政
 */
class Controller_Platform_HuoDong3 extends Controller_Platform_Template{
	/**
	 * 轮盘抽奖 
	 * @author 郁政
	 * status表示状态码，200为正常，201为未登录，202为未绑定手机,203为当天该用户已经抽过奖,204为活动还没开始,205为活动已经结束,206为非个人用户登录
	 * jiangpin表示奖品类型，1为瑞士十字电脑背包，2为U盘，3为20元话费，4为10元话费，5为100元，6为80元，7为50元，8为20元，9为10元
	 */
	public function action_rouletteDrawFlash(){
		//返回结果
		$result = array('status'=>'200','jiangpin'=>'0');
		$service = new Service_User();
		$person_service = new Service_User_Person_User();
        $huodong = new Service_Platform_HuoDong3();
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
	            			$isDraw = $huodong->isCanDraw($user_id);
			            	//$isDraw = true;
	            			if($isDraw){
	            				$prize_type = $huodong->getPrizeNumberNum(2,3,1.5);	 
		            			$suc = $huodong->addDrawRecord($user_id,$prize_type,$userinfo->mobile);
	            				if($suc){	    			
			            			$result['jiangpin'] = $prize_type;
			            		}
	            			}else{
	            				//203为当天该用户抽奖次数已满
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
 	* 显示抽奖页面
 	* @author 郁政
 	*/
	public function action_showChouJiang(){
		$content = View::factory('platform/huodong/choujiang3');
		$this->content->maincontent = $content;
		$query = Arr::map("HTML::chars", $this->request->query());
//		if(arr::get($query, 'inviter_type') && arr::get($query, 'inviter_user_id')) {
//                    Cookie::set('cookie_user_inviter_id', arr::get($query, 'inviter_user_id'), time()+86400*60);
//                    Cookie::set('cookie_user_inviter_type', arr::get($query, 'inviter_type'), time()+86400*60);
//                }		
		$service = new Service_Platform_HuoDong3();		
		$game_id = 3;
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
		//您可能喜欢的项目
		$service_ps = new Service_Platform_Search();
		$service_pg = new Service_Platform_ProjectGuide();
		$guess_like_list = array();
		$arr_data = array();
		$ip = ip2long(Request::$client_ip);
		$arr_data = $service_ps->_getVistedLog($user_id, $ip, 1);
		if(count($arr_data) == 0){
			$guess_like_list = $service_pg->getNewProjectListNByRandom(5,2,2);
		}else{
			$return_data = array();
			$return_data = isset($arr_data[0]) ? $arr_data[0] : array();
			$guess_like_list = $service_ps->_getOtherProject($return_data,$ip,5);
			$guess_like_list = $service_ps->_getProjectInfoByarr($guess_like_list);			
		}	
		$content->guess_like_list = $guess_like_list;
		$content->szlist = $sz_list;
		$content->isLogin = $isLogin;
		$content->temp_id = $t_id;
		$content->countPeople = $count_people;
		$content->countRoulette = $count_roulette;
		$content->big_prize_list = $big_prize_list;
		$this->template->title = "【2013.11.22-2014.1.22】一句话网会员注册盛典_第三期疯狂会员月，大奖天天抽_100%中奖【iPad、ipod、电脑双肩包、U盘、20元话费、1000万创业币】";
        $this->template->keywords = "注册有奖,免费抽奖活动,2013免费抽奖活动,2014免费抽奖活动,2013抽奖活动,2013免费抽奖活动,免费抽奖,会员注册 ";
        $this->template->description = "2013年11月22日—2013年1月22日是一句话商机网举办【第三期】会员注册有奖活动，会员注册抽奖一整月，千万抽奖，让利1000万，最好的奖品——2万话费、iPad、iPod、电脑双肩包、U盘、20元话费、10元话费、各面额的创业币等奖品等你拿！亲，赶紧行动吧，100%中奖哦。";
	}
	
	/**
 	* 获取获奖名单列表
 	* @author 郁政
 	*/
	public function action_getWinners(){
		$result = array();
		$service = new Service_Platform_HuoDong3();
		$result = $service->getWinners();
		echo json_encode($result);
		exit;		
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
	        $huodong = new Service_Platform_HuoDong3();
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
	
	public function action_test(){
		$service = new Service_Platform_HuoDong3();
		$res = $service->getGameInfo();
		echo "<pre>";print_r($res);exit;
	}
}
?>