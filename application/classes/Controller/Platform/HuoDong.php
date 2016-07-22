<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 活动
 * @author 郁政
 */
class Controller_Platform_HuoDong extends Controller_Platform_Template{
	/**
	 * 轮盘抽奖 
	 * @author 郁政
	 * status表示状态码，200为正常，201为未登录，202为未绑定手机,203为当天该用户已经抽过奖,204为活动还没开始,205为活动已经结束,206为非个人用户登录
	 * jiangpin表示奖品类型，1为ipod，2为U盘，3为幸运奖，4为100元，5为80元，6为50元，7为20元，8为10元，9为1元
	 */
	public function action_rouletteDrawFlash(){
		//返回结果
		$result = array('status'=>'200','jiangpin'=>'0');
		$service = new Service_User();
        $huodong = new Service_Platform_HuoDong();
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
	            if($userinfo->user_type != 2){
	            	$result['status'] = '206';
	            }else{
		            if(isset($userinfo->valid_mobile) && $userinfo->valid_mobile == 1){
		            	$isDraw = $huodong->isAlreadyDraw($user_id);
		            	//$isDraw = false;
		            	if(!$isDraw){
		            		if($huodong->isAreadyBigJiang($user_id)){
		            			$num = 1;
		            			$prize_type = $huodong->getPrizeByNum($num);            		
		            			$suc = $huodong->addDrawRecord($user_id,$prize_type,$userinfo->mobile,1);
		            		}else{
		            			$num = $huodong->getNumFromJiangChi();
		            			$prize_type = $huodong->getPrizeByNum($num);            		
		            			$suc = $huodong->addDrawRecord($user_id,$prize_type,$userinfo->mobile);
		            		}
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
		$service = new Service_Platform_HuoDong();
		$service->buildJiangChi();
		exit;
	}
	
	/**
 	* 显示抽奖页面
 	* @author 郁政
 	*/
	public function action_showChouJiang(){
		$content = View::factory('platform/huodong/choujiang');
		$this->content->maincontent = $content;
		$service = new Service_Platform_HuoDong2();
		//获取中实物奖的名单
		$big_prize_list = $service->getBigPrizeBefore(1);
		$content->big_prize_list = $big_prize_list;
		$this->template->title = "一句话网会员注册盛典_百万抽奖一整月，让利300万_100%中奖【Ipad、Ipod、U盘、T恤】";
        $this->template->keywords = "会员注册,百万抽奖,会员注册活动,100%中奖,一句话";
        $this->template->description = "2013年9月1日——2013年9月30日是一句话网会员注册盛典活动，一句话网提供整月抽奖活动，百万抽奖，让利300万，更有发放300万创业币给有缘创业的网友。并且提供Ipad、Ipod、U盘、创业T恤、大量的创业币等抽奖活动，100%中奖！";
	}
	
	/**
 	* 获取获奖名单列表
 	* @author 郁政
 	*/
	public function action_getWinners(){
		$result = array();
		$service = new Service_Platform_HuoDong();
		$result = $service->getWinners();
		echo json_encode($result);
		exit;		
	}	

	/**
 	* 修改redis
 	* @author 郁政
 	*/
	public function action_updateRedis(){
		$result = array();
		$service = new Service_Platform_HuoDong();
		$result = $service->updateRedis();
		print_r($result);
		exit;
	}
	
}
?>