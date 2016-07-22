<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户 参加的活动
 * @author 郁政
 */
class Controller_User_Person_Huodong extends Controller_User_Person_Template{
	/**
	 * 我 参加的活动
	 * @author 郁政
	 */
	public function action_myGame(){
		$content = View::factory("user/person/mygame");
        $this->content->rightcontent = $content;
        #获取用户user_id
        $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;        
        $service = new Service_Platform_HuoDong4();
        $t_id = array();
        $t_id = $service->getTempUserId($user_id);
        $result = array();
        $huodong = new Service_User_Person_Huodong();
        $get = Arr::map("HTML::chars", $this->request->query());
        $game_id = Arr::get($get, 'game_id' , count(common::getGameName()));         
        $result = $huodong->getRouletteList($user_id, $game_id);
        $content->temp_id = $t_id;
        $content->game_id = $game_id;   
        $content->list = $result['list'];
		$content->page = $result['page'];
	}
	
	/**
	 * 创业币兑换页面
	 * @author 郁政
	 */
	public function action_exchangeChuangYeBi(){
		$content = View::factory("user/person/exchangechuangyebi");
        $this->content->rightcontent = $content;
        #获取用户user_id
        $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;    
        $huodong = new Service_User_Person_Huodong();
        $point = new Service_Platform_SearchInvestor();
        $chuangyebiCount = $huodong->getChuangYeBiCount($user_id);
        $hasUsedCount = $huodong->getHasUsedCount($user_id);        
        $service_upp = new Service_User_Person_Project();
        $service_ps = new Service_Platform_Search();
        $service_pp = new Service_Platform_ProjectGuide();
        //为你推荐
        $pid_tuijian = array();
        $arr_tuijian = array();
        $pid_tuijian = $service_upp->getTuiJianForYou($this->userId(), 5);
        if(!$pid_tuijian){
        	$pid_tuijian = $service_pp->getNewProjectListNByRandom(5,2);
        }
        $pid_tuijian = array_slice($pid_tuijian, 0,5);
        $arr_tuijian = $service_ps->_getProjectInfoByarr($pid_tuijian);	
        //大家都喜欢的创业项目        
        $arr_xihuan = array();
        $arr_xihuan = $service_pp->getMaxProjectListPv(6);  
        $arr_xihuan = array_slice($arr_xihuan, 0,5);      
        $content->pointCount = $point->getAllScore($user_id);
        $content->chuangyebiCount = $chuangyebiCount; 
        $content->hasUsedCount = $hasUsedCount;   
        $content->arr_tuijian = $arr_tuijian; 
        $content->arr_xihuan = $arr_xihuan;      
	}
	
	/**
	 * 兑换创业币 （ajax）
	 * @author 郁政
	 */
//	public function action_goExchange(){
//		if($this->request->is_ajax()){
//			$msg = array();
//			$get = Arr::map("HTML::chars", $this->request->query());
//			$amount = Arr::get($get, 'amount');
//			$huodong = new Service_User_Person_Huodong();
//			#获取用户user_id
//        	$user_id = Cookie::get("user_id")?Cookie::get("user_id"):0; 
//			$msg = $huodong->goExchange($user_id, $amount);
//			echo json_encode($msg);
//			exit;
//		}
//	}
	
	
	/**
	 * 获取创业币列表
	 * @author 郁政
	 */
	public function action_getChuangYeBiList(){
		$content = View::factory("user/person/showchuangyebilist");
        $this->content->rightcontent = $content;
        #获取用户user_id
        $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;    
        $huodong = new Service_User_Person_Huodong();
        $count = $huodong->getChuangYeBiCount($user_id);
        $date_option = array();
        $date_option = $huodong->getRencentMonth();
        $get = Arr::map("HTML::chars", $this->request->query());
        $date = Arr::get($get, 'date' , strtotime(date('Y-m'),time()));
        $list = array();
        $list = $huodong->getChuangYeBiList($user_id, $date);
        //echo "<pre>";print_r($list);exit;
        $content->list = $list['list'];
        $content->page = $list['page'];
        $content->count = $count;
        $content->date_option = $date_option;
        $content->date_sel = $date;
	}

	
	/**
	 * 将以前活跃度表中表示创业币的记录标为禁止状态(一次性)
	 * @author 郁政
	 */
//	public function action_gaiZhuangTai(){
//		$res = DB::update('user_person_score_log')->set(array('status' => 1))->where('score_type','=',16)->execute();
//		var_dump($res);
//		exit;
//	}
	
	/**
	 * 删除导入的创业币数据(一次性)
	 * @author 郁政
	 */
//	public function action_shanChuChuangYeBi(){		
//		$res = DB::delete('user_currency')->where('date','<=',strtotime('2013-11-7 16:15:00'))->execute();
//		var_dump($res);
//		exit;
//	}
	
	/**
	 * 导出以前创业币数据进创业币表(一次性)
	 * @author 郁政
	 */
//	public function action_changePointsStatus(){
//		    $query=$this->request->query();
//		    $limit=arr::get($query,'limit');
//		    if(!$limit || !intval($limit)){
//		    	$limit=500;
//		    }		   
//		    $om = ORM::factory('UserPersonScoreLog')->where('score_type','=',16)->where('status','=',1)->limit($limit)->find_all();		
//			foreach($om as $v){
//				$currency = ORM::factory('Usercurrency');
//				$currency->user_id = $v->user_id;
//				$currency->score_operating = 1;
//				$currency->score_type = 1;
//				$currency->score = $v->score;
//				$currency->date = $v->add_time;
//				$currency->create();				
//				$v->status = 2;
//				$v->update();	
//							
//			}
//			echo '执行了'.count($om);
//		exit;
//	}

	
	/**
	 * 好友邀请页面显示
	 * @author 郁政
	 */
	public function action_showInviteFriends(){
            $userinfo = $this->userInfo();
            $service = new Service_User();
            $get = Arr::map("HTML::chars", $this->request->query());
            $type = arr::get($get, 'type', 0);
            $inviteNum = $service->getUserHuodongChanceInvite($userinfo->user_id);
            $addNum = $service->getUserHuodongChanceAdd($userinfo->user_id);
            $minNum = $service->getUserHuodongChanceMin($userinfo->user_id);
            $nowNum = $service->getUserHuodongChance($userinfo->user_id);
            $user_count = $service->getUserInviterCount($userinfo->user_id);
            $allData = $service->getUserInviter($userinfo->user_id, $type);
            $content = View::factory("user/person/showInviteFriends");
            $this->content->rightcontent = $content;
            $content->inviteNum = $inviteNum;
            $content->addNum = $addNum;
            $content->minNum = $minNum;
            $content->nowNum = $nowNum;
            $content->user_count = $user_count;
            $content->user_id = $userinfo->user_id;
            $content->allData = $allData;
            $content->type = $type;
	}
}
?>