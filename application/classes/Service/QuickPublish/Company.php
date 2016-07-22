<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 企业信息
 * @author：兔毛 2014-05-20
 */
class Service_QuickPublish_Company {
	

	/**
	 * 企业信息
	 * @param unknown_type $company_id：企业id
	 * @param unknown_type $is_null：数据不存在，默认：无
	 * author: 兔毛  2014-05-20
	 */
	public function getCompanyInfo($company_id,$is_null='暂无信息')
	{
		$company_info=ORM::factory("Companyinfo")->where("com_id","=",intval($company_id))->find()->as_array();
		return $company_info;
	}
	
	/**
	 * 手机授权的信息
	 * @param unknown_type $mobile_id：手机账户ID
	 * author: 兔毛  2014-05-21
	 */
	public function getMobileAccountInfo($mobile_id)
	{
		$mobile_info=ORM::factory("MobileAccount")->where("mobile_id","=",intval($mobile_id))->find()->as_array();
		return $mobile_info;
	}
	
	
	/**
	 * 用户信息
	 * @param unknown_type $user_id：用户ID
	 * author: 兔毛  2014-05-20
	 */
	public function getUserPersonInfo($user_id)
	{
		$user_person_info=ORM::factory("Personinfo")->where("per_id","=",intval($user_id))->find()->as_array();
		return $user_person_info;
	}
	
	
	/**
	 * 身份证认证状态默认为0未验证,1为验证中，2为通过验证，3未通过认证
	 * @param unknown_type $user_id：用户ID
	 * author: 兔毛  2014-05-20
	 */
	public function getUserAuthStatus($user_id)
	{
		$user_person_info=$this->getUserPersonInfo($user_id);
		$authStatus=0;
		if(isset($user_person_info['per_auth_status']))
			$authStatus=$user_person_info['per_auth_status'];
		return $authStatus;	
	}
}
?>