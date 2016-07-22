<?php
//防垃圾注册
class Service_User_PrevioReg{
	
	//ip + 系统
	public function genHashId(){
		$ip = Request::$client_ip;
		$os = Request::user_agent("platform");//系统版本
		return ip2long($ip)."-".md5($ip.$os);
	}
	
	//写入注册日志信息
	public function createPrev(){
		$ip = Request::$client_ip;
		$ua = Request::$user_agent;
		$obj = ORM::factory("PrevioReg");
		$obj->ip = ip2long($ip);
		$obj->ua = $ua;
		$obj->reg_time = time();
		$os = Request::user_agent("platform");//系统版本
		$obj->hash_id = ip2long($ip)."-".md5($ip.$os);
		$obj->create();
	}
	
	//获取最新的hash_id 注册时间,十分钟内大于3次注册
	public function lastHash($hash_id){
		//10分钟内注册数量
		$obj = ORM::factory("PrevioReg")
					->where("hash_id","=",$hash_id)
					->where("reg_time",">=",time()-(60*10))
					->find_all();
		if($obj->count()>=3){
			return false;
		}
		return true;
		
	}
	
}