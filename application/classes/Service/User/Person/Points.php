<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户活跃度
 * @author 钟涛
 *
 */
class Service_User_Person_Points extends Service_User_Points{
	/**
	 * 活跃度添加log日志
	 * @param $user_id 用户id
	 * @param $type_name 活跃度类型名称
	 * @param $chuangyebi 创业币
	 * @author 钟涛
	 */
	public function addPoints($user_id,$type_name,$chuangyebi=0){	
		$points = points_person_type::getByName($type_name);
		//积分类型配置
		if(count($points) && $user_id){
			$model = ORM::factory("UserPersonScoreLog");
			$model->where("user_id","=",$user_id)//用户id
			            ->where("score_type","=",$points['id'])//积分类型
			            ->where("status","=",1);//启用的状态
			if($points['is_repeat']==1){//非一次性的
				$today=strtotime(date('Y-m-d 00:00:00',time()));
				$model->where("add_time",">=",$today);//当天的数据
			}
			$counts=$model->count_all();			
			if($counts<$points['cishu']){//当天累计次数小于当天累计上限
				//创建新纪录	
							
				$model2 = ORM::factory("UserPersonScoreLog");
				$model2->user_id = $user_id;
				if($chuangyebi){			
					$model2->score = intval($chuangyebi);//分值
				}else{
					$model2->score = $points['points'];//分值
				}
				$model2->score_type = $points['id'];//分值类型id
				$model2->is_repeat = $points['is_repeat'];//是否为一次性的
				$model2->status = 1;//状态默认1启用
				$model2->add_time = time();
				try{					
					$model2->create();
					$service_user = new Service_User_Person_Huodong();
					$service_user->addChuangYeBi($user_id, 1,$points['chuanyebi_id'],$points['points']);
				}catch(Kohana_Exception $e){
					throw $e;
				}
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 根据活跃度获取等级
	 * @param $user_id 用户id
	 * @author 钟涛
	 */
	public function getScoreLevel($user_id){
		$total_itys=0;//默认0分;
		if($user_id){
			$ser=new Service_Platform_SearchInvestor();
			//总活跃度
			$total_itys = $ser->getAllScore($user_id);
		}
		$level=1;//默认1级
		if($total_itys<=99){
			$level=1;
		}else{//公式计算
			//100*（x-2）^2+100 = total
			$level = (int)floor(sqrt(($total_itys-100)/100)+2);
		}
		return array("level"=>$level,"total_score"=>$total_itys);
	}
	
	/**
	 * 个人用户活跃度积分获取最近6个月的月份
	 * @author 赵路生
	 */
	public function getRencentMonth(){
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
	 * 获取我的活跃度明细列表
	 * @author 赵路生
	 */
	public function getScoreList($mondate,$userid){
		$return_arr=array();
        if($mondate && $userid){
        	//下个月份
        	$nextmondate=strtotime("+1 month",$mondate);
        	$model = ORM::factory("UserPersonScoreLog");
        	$modeldata=$model->where("user_id","=",$userid)//用户id
        		->where("status","=",1)//启用的状态
        		->where("add_time",">=",$mondate)//选择的月份
        		->where("add_time","<",$nextmondate)//下个月份
        		->where("score_type",'<>',16);

        	$count=$modeldata->reset(false)->count_all();
        	$page = Pagination::factory(array(
        			'total_items'    =>$count,
        			'items_per_page' => 10,
        	));
        	$modellist=$modeldata
        	->order_by('add_time','desc')
        	->limit($page->items_per_page)->offset($page->offset)
        	->find_all();
        	$return_arr['list'] = $modellist;
        	$return_arr['page'] = $page;
        	return $return_arr;
        }
        $return_arr['list'] = array();
        $return_arr['page'] = '';
		return $return_arr;
	}
}