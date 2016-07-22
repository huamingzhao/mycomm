<?php

defined('SYSPATH') or die('No direct script access.');

/**
* 用户中心
* @author Smile(jiye)
* @param 
* @create_time  2014-5-23
* @return int/bool/object/array
*/
class Service_QuickPublish_User {
	
	/**
	* 获取升级企业信息
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-23
	* @return int/bool/object/array
	*/
	public function getUserUpgradeComInfo($int_user_id){
		try {
			if($int_user_id){
				//QuickUserCompany
				$arr_comInfo = ORM::factory("QuickUserCompany")->where("com_user_id","=",intval($int_user_id))->find()->as_array();
				return $arr_comInfo;
			}
			return array();
		} catch (Kohana_Exception $e) {
			return array();
		}
	}
	
	/**
	* 实行添加或者修改
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-23
	* @return int/bool/object/array
	*/
	public function DoComInfo($int_user_id,$arr_data = array()){
		try {
			if($int_user_id && $arr_data){
				$arr_com_data = array();
				//企业名称
				$arr_com_data['com_name'] = arr::get($arr_data,"com_name");
				$arr_com_data['com_user_id'] = $int_user_id;
				//企业名称
				$arr_com_data['com_logo'] = common::getImgUrl(arr::get($arr_data,"com_logo"));
				//企业电话
				$arr_com_data['com_phone'] = arr::get($arr_data,"com_phone");
				if(arr::get($arr_data,"branch_phone")){
					$arr_com_data['com_phone'] = $arr_com_data['com_phone']."+".arr::get($arr_data,"branch_phone");
				}
				//联系人
				$arr_com_data['com_contact'] = arr::get($arr_data,"com_contact");
				//公司性质
				$arr_com_data['com_nature'] = arr::get($arr_data,"com_nature");
				
				//公司成立时间
				$arr_com_data['com_founding_time'] = arr::get($arr_data,"com_founding_time_year").arr::get($arr_data,"com_founding_time_month");
				
				//公司注册资本
				$arr_com_data['com_registered_capital'] = arr::get($arr_data,"com_registered_capital");
				//公司注册资本
				$arr_com_data['com_site'] = arr::get($arr_data,"com_site");
				//公司地址
				$arr_com_data['com_area'] = arr::get($arr_data,"com_area");
				$arr_com_data['com_city'] = arr::get($arr_data,"com_city");
				$arr_com_data['com_adress'] = arr::get($arr_data,"com_adress");
				//企业简介
				$arr_com_data['com_desc'] = arr::get($arr_data,"com_desc");
				//企业状态com_auth_status
				$arr_com_data['com_auth_status'] = 1;
				$arr_com_data['com_source_id'] = 1;
				$arr_com_data['com_card_status'] = 1;
				$arr_com_data['platform_service_fee_status'] = 1;
				$arr_com_data['com_service_merchants_time'] = time();
				if(arr::get($arr_data,"com_id")){
					//实行修改
					return $this->DoUpdataData("QuickUserCompany", arr::get($arr_data,"com_id"), $arr_com_data);
				}else{
					// 实行添加
					return  $this->DoInsterData("QuickUserCompany", $arr_com_data);
				}
			}
			return false;
		} catch (Kohana_Exception $e) {
			return  false;
		}
	}
	
	/**
	 * 修改数据
	 * @author Smile(jiye)
	 * @param $str_table $arr_data
	 * @create_time  2014-5-23
	 * @return int/bool/object/array
	 */
	function  DoUpdataData($str_table_name,$int_zhujian_id,$arr_data,$bool_return = true){
		try {
			if($str_table_name && $arr_data && $int_zhujian_id){
				$model = ORM::factory($str_table_name,intval($int_zhujian_id));
				foreach ($arr_data as $key=>$val){
					$model -> $key = $val;
				}
				$obj_data = $model->update();
				if($bool_return == true){
					return $bool_return;
				}else{
					return (array)$obj_data;
				}
			}
		} catch (Kohana_Exception $e) {
			return false;
		}
	}
	
	
	/**
	* 添加数据
	* @author Smile(jiye)
	* @param $str_table $arr_data
	* @create_time  2014-5-23
	* @return int/bool/object/array
	*/
	function  DoInsterData($str_table_name,$arr_data,$bool_return = true){
		try {
			if($str_table_name && $arr_data){
				$model = ORM::factory($str_table_name);
				foreach ($arr_data as $key=>$val){
					$model -> $key = $val;
				}
				$obj_data = $model->save();
				if($bool_return == true){
					return $bool_return;
				}else{
					return (array)$obj_data;
				}
			}
		} catch (Kohana_Exception $e) {
			return false;
		}
	}
	
	/**
	* 获取用户留言
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-3
	* @return int/bool/object/array
	*/
	public function getUserMessage(){
		// to_user_id  和 项目的id  要联合使用
		$arr_data = array();
		$int_user_id = Cookie::get("user_id");
		if($int_user_id){
			$arr_project_id = array();
			$int_mobile_id = $this->getMobileIdByUserId($int_user_id);
			$obj_new_data = ORM::factory('Quickproject')->where("mobile_id","=",$int_mobile_id)->where("project_status","=",2)->find_all();
			if(count($obj_new_data) > 0){
				foreach ($obj_new_data as $key=>$val){
					$arr_project_id[$key] = $val->project_id;
				}
			}
			if(count($arr_project_id) > 0){
				$int_count = ORM::factory("Message")->where_open()->where("to_user_id","=",intval($int_user_id))->or_where("project_id",'in',$arr_project_id)->where_close()->count_all();
			//	echo $int_count;exit;
				$page = Pagination::factory(array(
						'total_items'    => $int_count,
						'items_per_page' => 10,
				));
				$obj_str = ORM::factory("Message")->where_open()->where("to_user_id","=",intval($int_user_id))->or_where("project_id","in",$arr_project_id)->where_close()->limit($page->items_per_page)->offset($page->offset)->order_by("add_time","DESC")->find_all();
				//echo "<pre>"; print_r($obj_str);exit;
				$arr_data['count'] = $int_count;
				$arr_data['page'] = $page;
				$arr_data['list'] = $this->DoSqlStr($obj_str);
			}
			
		}
		return $arr_data;
	}
	/**
	* 通过项目的ID 获取 留言
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-4
	* @return int/bool/object/array
	*/
	public function getUserMessageByProjectId($int_project_id){
		$arr_data = array();
		if($int_project_id){
		
			$int_count = ORM::factory("Message")->where("project_id",'=',$int_project_id)->count_all();
			//	echo $int_count;exit;
			$page = Pagination::factory(array(
					'total_items'    => $int_count,
					'items_per_page' => 10,
			));
			$obj_str = ORM::factory("Message")->where("project_id","=",$int_project_id)->limit($page->items_per_page)->offset($page->offset)->order_by("add_time","DESC")->find_all();
			$arr_data['count'] = $int_count;
			$arr_data['page']  = $page;
			$arr_data['list']  = $this->DoSqlStr($obj_str);
		}
		return $arr_data;
	}
	/**
	* 通过userid 获取mobileid
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-3
	* @return int/bool/object/array
	*/
	public function getMobileIdByUserId($int_user_id){
		return ORM::factory('MobileAccount')->where("user_id","=",$int_user_id)->find()->mobile_id;
	}
	/**
	* 处理要循环的数据
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-3
	* @return int/bool/object/array
	*/
	public function DoSqlStr($obj_str = ""){
 		//$user_info = Service_Sso_Client::instance()->getUserInfoById(Cookie::get("user_id"));
 	//	echo "<pre>"; print_r($user_info);exit;
		$Service_QuickPublish_Search =  new  Service_QuickPublish_Search();
		if($obj_str){
			$arr_return_data = array();
			foreach ($obj_str as $key=>$val){
				$arr_return_data[$key] = $val->as_array();
				$user_info = Service_Sso_Client::instance()->getUserInfoById(Cookie::get("user_id"));
				//echo "<pre>"; print_r($user_info);exit;
				$arr_return_data[$key]['new_user_name'] = $user_info->user_name;
				$arr_return_data[$key]['new_mobile'] = $user_info->mobile;
				$arr_return_data[$key]['new_email'] = $user_info->email;
				$arr_return_data[$key]['showtime'] = $Service_QuickPublish_Search->jishuanTime($val->add_time);
			}
			return $arr_return_data;
		}
		return array();
	}
	
	/**
	* 项目的留言统计
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-4
	* @return int/bool/object/array
	*/
	public function getProjectMessageCout($int_project_id){
		if($int_project_id){
			return ORM::factory("Message")->where("project_id","=",intval($int_project_id))->count_all();
		}
		return 0;
	}
	/**
	* 判断留言一天只能留言一下
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-5
	* @return int/bool/object/array
	*/
	public function getMessageCountByFromUserId($int_user_id,$int_project_id){
		$todaytime = date("Y-m-d 00:00:00",time());
		$Tomorrowtime = date("Y-m-d 00:00:00",strtotime("+1 day"));
		return ORM::factory('Message')->where("from_user_id","=",$int_user_id)->where("add_time",">",strtotime($todaytime))->where("add_time","<",strtotime($Tomorrowtime))->where("project_id","=",$int_project_id)->count_all();
	}
}