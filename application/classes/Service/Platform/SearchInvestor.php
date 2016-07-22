<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 搜索投资者
 * @author 钟涛
 *
 */
class Service_Platform_SearchInvestor{
	/**
	 * [个人用户+未登录情况]搜索到的投资者名片信息列表
	 * @param  [array] $search [post获取当前页面搜索条件]
	 * @author 钟涛
	 */
	public function searchInvestorInfo($search){
		$permodel= ORM::factory('Personinfo');
		$permodel->join('user_personal_area','LEFT')->on('user_personal_area.per_id','=','per_user_id');
		$permodel->join('user_personal_industry','LEFT')->on('user_personal_industry.user_id','=','per_user_id');
		$search_row = $permodel->getSearchRow();
		foreach($search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件
				$permodel->where($key, '=', $search[$key]);
			}
		}
		//对投资者个人 投资行业 进行筛选
		$perindustrymodel= ORM::factory('UserPerIndustry');
		$perindustry_search_row = $perindustrymodel->getSearchRow();
		foreach($perindustry_search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件（投资行业）
				$permodel->where($key, '=', $search[$key]);
			}
		}

		$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
		$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
		$page_list=$permodel->select('*')->group_by('per_user_id')->reset(false)->find_all( )->as_array();
		$total_count=count($page_list);
		if($total_count>8){
			$rand_keys = array_rand($page_list, 8);//随机获取8条数据
			for($k=0;$k<8;$k++){
				$per_result[]=$page_list[$rand_keys[$k]];
			}
			return array(
					'list'=>$this->addResultData($per_result),
					'totalcount'=>$total_count,
					'page'=>'',
			);
		}
		return array(
				'list'=>$this->addResultData($page_list),
				'totalcount'=>$total_count,
				'page'=>'',
		);
	}
	
	/**
	 * SSO
	 * [个人用户登录+未登录情况]对搜索的投资的者加上相关信息
	 * @author 钟涛
	 */
	function addResultData($personlist){
		$userlist=array();
		$resultlist=array();
		$per_service = new Service_User_Person_User();
		$service_searchinvestor=new Service_Platform_SearchInvestor();
		foreach ($personlist as $list){
			//上次登录时间
			$userlist['last_logintime']=$service_searchinvestor->getLastLoginTime($list->per_user_id);
			//个人行业
			$userlist['this_per_industry']=$per_service->getPersonalIndustryString($list->per_user_id);
			//个人所在地
			$userlist['this_per_area']=$per_service->getPerasonalAreaStringOnlyPro($list->per_user_id);
			//手机是否已经验证
			//@SSO 赵路生 2013-11-12
			$userlist['isMobile']=Service_Sso_Client::instance()->getUserInfoById($list->per_user_id)->valid_mobile;
			$userlist['valid_mobile']=Service_Sso_Client::instance()->getUserInfoById($list->per_user_id)->valid_mobile;
			$userlist['card_type']=4;//无记录
			$userlist['card_id']=0;//无记录
			$resultlist[] = array_merge($list->as_array(),$userlist);
		}
		return $resultlist;
	}
	
	/**
	 * sso
	 * [个人用户登录+未登录情况]对搜索的投资的者加上相关信息
	 * @author 钟涛
	 */
	function addResultData2($list){
		$userlist=array();
		$resultlist=array();
		$per_service = new Service_User_Person_User();
		$service_searchinvestor=new Service_Platform_SearchInvestor();
		//上次登录时间
		$userlist['last_logintime']=$service_searchinvestor->getLastLoginTime($list->per_user_id);
		$userlist['last_logintime_int']=$service_searchinvestor->getLastLoginTimeToInt($list->per_user_id);
		//个人行业
		$userlist['this_per_industry']=$per_service->getPerasonalParentIndustry($list->per_user_id);
		//个人所在地
		$userlist['this_per_area']=$per_service->getPersonalArea($list->per_user_id);
		//总活跃度
		$userlist['this_huoyuedu'] = $this->getAllScore($list->per_user_id);//活跃度
		//手机是否已经验证
		//@sso 赵路生  2013-11-2
		//$userlist['isMobile']='';		
		$userlist['card_type']=4;//无记录
		$userlist['card_id']=0;//无记录
		$resultlist = array_merge($list->as_array(),$userlist);
		return $resultlist;
	}
	
	/**
	 * sso
	 * 获取个人数量
	 * @author 钟涛
	 */
	function getPersonAllCount(){
		return ORM::factory('Personinfo')->count_all();
	}
	
	/**
	 * [企业用户]搜索到的投资者名片信息列表
	 * @param  [array] $search [post获取当前页面搜索条件]
	 * @param  [int] $user_id [企业用户id]
	 * @author 钟涛
	 */
	public function searchInvestorInfoByCom($search,$user_id){
		$permodel= ORM::factory('Personinfo');
		$usermodel= ORM::factory('User');
		$permodel->join('user','LEFT')->on('user_id','=','per_user_id');
		$permodel->join('user_personal_area','LEFT')->on('user_personal_area.per_id','=','per_user_id');
		$permodel->join('user_personal_industry','LEFT')->on('user_personal_industry.user_id','=','per_user_id');
		$search_row = $permodel->getSearchRow();
		foreach($search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件
				$permodel->where($key, '=', $search[$key]);
			}
		}
		//对投资者个人 投资行业 进行筛选
		$perindustrymodel= ORM::factory('UserPerIndustry');
		$perindustry_search_row = $perindustrymodel->getSearchRow();
		foreach($perindustry_search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件（投资行业）
				$permodel->where($key, '=', $search[$key]);
			}
		}
		$user_search_row = $usermodel->getSearchRow();
		foreach($user_search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != '' AND $search[$key] != 0){
				//筛选条件（是否验证手机号）
				$permodel->where($key, '=', $search[$key]);
			}
		}
		$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
		$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
		$page_list=$permodel->select('*')->group_by('per_user_id')->reset(false)->find_all( )->as_array();
		$total_count=count($page_list);
		$page = Pagination::factory(array(
				'total_items'    => $total_count,
				'items_per_page' => 8,
		));
		$page_list2=$permodel->select('*')->group_by('per_user_id')->limit($page->items_per_page)->offset($page->offset)->order_by('per_createtime', 'DESC')->find_all( );
		return array(
				'list'=>$this->addResultDataByCom($page_list2,$user_id),
				'totalcount'=>$total_count,
				'page'=>$page,
		);
	}
	
	/**
	 * [企业用户]随机获取一条数据
	 * @param  [int] $user_id [企业用户id]
	 * @author 钟涛
	 */
	public function getOneInvestorInfo($user_id,$search){
		$idarr=array();
		$idarr= explode(",", arr::get($search,'ids',''));
		$permodel= ORM::factory('Personinfo');
		$permodel->join('user_personal_area','LEFT')->on('user_personal_area.per_id','=','per_user_id');
		$permodel->join('user_personal_industry','LEFT')->on('user_personal_industry.user_id','=','per_user_id');
		$search_row = $permodel->getSearchRow();
		foreach($search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件
				$permodel->where($key, '=', $search[$key]);
			}
		}
		//对投资者个人 投资行业 进行筛选
		$perindustrymodel= ORM::factory('UserPerIndustry');
		$perindustry_search_row = $perindustrymodel->getSearchRow();
		foreach($perindustry_search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件（投资行业）
				$permodel->where($key, '=', $search[$key]);
			}
		}
		$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
		$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
		$page_list=$permodel->select('*')->where('per_user_id','not in',$idarr)->group_by('per_user_id')->find_all( )->as_array();
		$total_count=count($page_list);
		if($total_count>0){
			$rand_keys = array_rand($page_list, 1);//随机获取1条数据
			$per_result[]=$page_list[$rand_keys];
			return $this->addResultDataByCom($per_result,$user_id);
		}else{
			return '';
		}
	}
	
	/**
	 * [企业用户]对搜索的投资的者加上相关信息[传入的是多条数据]
	 * @author 钟涛
	 */
	function addResultDataByCom($personlist,$userid){
		$userlist=array();
		$resultlist=array();
		$per_service = new Service_User_Person_User();
		$card_service=new Service_Card();
		$service_searchinvestor=new Service_Platform_SearchInvestor();
		$thisk=0;
		foreach ($personlist as $list){
			//上次登录时间
			$userlist['last_logintime']=$service_searchinvestor->getLastLoginTime($list->per_user_id);
			//添加行业信息
			$userlist['this_per_industry']=$per_service->getPerasonalParentIndustry($list->per_user_id);
			//个人所在地
			$userlist['this_per_area']=$per_service->getPersonalArea($list->per_user_id);
			//添加判断是否已经查看改投资者名片
			$is_pay=$card_service->getCardinfoCountByid($userid,$list->per_user_id);
			$userlist['is_pay']=$is_pay;
			//sso 赵路生 2013-11-12
			//$userlist['valid_mobile'] = $userlist['isMobile']=Service_Sso_Client::instance()->getUserInfoById($list->per_user_id)->valid_mobile;
			//$userlist['valid_mobile']=Service_Sso_Client::instance()->getUserInfoById($list->per_user_id)->valid_mobile;
			//判断是否已交换名片
			if($card_service->getExchaneCardCountByTwoIdAll($list->per_user_id, $userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($list->per_user_id, $userid)){
				$userlist['card_type']=3;//已经交换
				unset($userlist['sendtime']);
				unset($userlist['card_id']);
			}
			elseif($card_service->getOutCardCountByTwoIdAll($list->per_user_id, $userid)){//已递出
				$userlist['card_type']=2;//已递出
				$query1 = DB::select()->from('card_info')->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list->per_user_id)->and_where('exchange_status', '=', 0);
				$result = $query1->execute()->as_array();
				if(isset($result[0]['send_time'])){//记录递出名片时间[7天后又可重复递出]
					$userlist['sendtime'] = $result[0]['send_time'];
				}else{
					$userlist['sendtime'] = 0;
				}
				$userlist['card_id'] = $result[0]['card_id'];
			}elseif($card_service->getReceiveCardCountByTwoIdAll($list->per_user_id, $userid)){//已收到
				$userlist['card_type']=1;//已收到
				$query1 = DB::select()->from('card_info')->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list->per_user_id)->and_where('exchange_status', '=', 0);
				$result2 = $query1->execute()->as_array();
				$userlist['card_id'] = $result2[0]['card_id'];
				unset($userlist['sendtime']);
			}else{
				$userlist['card_type']=4;//无记录
				unset($userlist['sendtime']);
				unset($userlist['card_id']);
			}
			//判断收藏表中对应的数据
			if ($card_service->getFavoriteStatus($userid,$list->per_user_id)==TRUE){
				$userlist['favorite_status']=1;//已存在对应收藏关系
			}
			else{
				$userlist['favorite_status']=0;//无收藏
			}
			$userlist['this_huoyuedu']=$this->getAllScore($list->per_user_id);//活跃度
			$resultlist[] = array_merge($list->as_array(),$userlist);
			unset($resultlist[$thisk]['password']);
			unset($resultlist[$thisk]['reg_time']);
			unset($resultlist[$thisk]['last_logintime']);
			unset($resultlist[$thisk]['last_login_ip']);
			unset($resultlist[$thisk]['per_createtime']);
			unset($resultlist[$thisk]['per_updatetime']);
			unset($resultlist[$thisk]['per_card_image']);
			unset($resultlist[$thisk]['per_open_stutas']);
			unset($resultlist[$thisk]['user_id']);
			$thisk++;
		}
		return $resultlist;
	}
	
	/**
	 * sso
	 * [企业用户]对搜索的投资的者加上相关信息[传入的是一条数据]
	 * @author 钟涛
	 */
	function addResultDataByCom2($list,$userid){
		$userlist=array();
		$resultlist=array();
		$per_service = new Service_User_Person_User();
		$card_service=new Service_Card();
		$service_searchinvestor=new Service_Platform_SearchInvestor();
		$thisk=0;		
		//投资金额
		if($list->per_amount){
			$money_arr = common::moneyArr();
			$userlist['this_per_amount'] = $money_arr[$list->per_amount];
		}else{
			$userlist['this_per_amount'] = '';
		}
		//上次登录时间
		$userlist['last_logintime']=$service_searchinvestor->getLastLoginTime($list->per_user_id);
		$userlist['last_logintime_int']=$service_searchinvestor->getLastLoginTimeToInt($list->per_user_id);
		//添加行业信息
		$userlist['this_per_industry']=$per_service->getPerasonalParentIndustry($list->per_user_id);
		//获得个人意向投资地区
		$userlist['this_per_area'] = $per_service->getPersonalArea($list->per_user_id);
		//活跃度
		$userlist['this_huoyuedu'] = $this->getAllScore($list->per_user_id);//活跃度
		//添加判断是否已经查看改投资者名片
		$is_pay=$card_service->getCardinfoCountByid($userid,$list->per_user_id);
		$userlist['is_pay']=$is_pay;
		//判断是否已交换名片
		if($card_service->getExchaneCardCountByTwoIdAll($list->per_user_id, $userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($list->per_user_id, $userid)){
			$userlist['card_type']=3;//已经交换
			unset($userlist['sendtime']);
			unset($userlist['card_id']);
		}
		elseif($card_service->getOutCardCountByTwoIdAll($list->per_user_id, $userid)){//已递出
			$userlist['card_type']=2;//已递出
			$query1 = DB::select()->from('card_info')->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list->per_user_id)->and_where('exchange_status', '=', 0);
			$result = $query1->execute()->as_array();
			if(isset($result[0]['send_time'])){//记录递出名片时间[7天后又可重复递出]
				$userlist['sendtime'] = $result[0]['send_time'];
			}else{
				$userlist['sendtime'] = 0;
			}
			$userlist['card_id'] = $result[0]['card_id'];
		}elseif($card_service->getReceiveCardCountByTwoIdAll($list->per_user_id, $userid)){//已收到
			$userlist['card_type']=1;//已收到
			$query1 = DB::select()->from('card_info')->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list->per_user_id)->and_where('exchange_status', '=', 0);
			$result2 = $query1->execute()->as_array();
			$userlist['card_id'] = $result2[0]['card_id'];
			unset($userlist['sendtime']);
		}else{
			$userlist['card_type']=4;//无记录
			unset($userlist['sendtime']);
			unset($userlist['card_id']);
		}
		//判断收藏表中对应的数据
		if ($card_service->getFavoriteStatus($userid,$list->per_user_id)==TRUE){
			$userlist['favorite_status']=1;//已存在对应收藏关系
		}
		else{
			$userlist['favorite_status']=0;//无收藏
		}
		//手机是否已经验证
		//sso 赵路生 2013-11-12
		//$userlist['isMobile']=ORM::factory("User", $list->per_user_id)->valid_mobile;
		$userlist['isMobile']=Service_Sso_Client::instance()->getUserInfoById($list->per_user_id)->valid_mobile;
		$userlist['this_huoyuedu']=$this->getAllScore($list->per_user_id);//活跃度
		$resultlist = array_merge($list->as_array(),$userlist);
		unset($resultlist[$thisk]['password']);
		unset($resultlist[$thisk]['reg_time']);
		unset($resultlist[$thisk]['last_logintime']);
		unset($resultlist[$thisk]['last_login_ip']);
		unset($resultlist[$thisk]['per_createtime']);
		unset($resultlist[$thisk]['per_updatetime']);
		unset($resultlist[$thisk]['per_card_image']);
		unset($resultlist[$thisk]['per_open_stutas']);
		unset($resultlist[$thisk]['user_id']);
		$thisk++;
		return $resultlist;
	}
	
	/**
	 * [个人用户+未登录情况]搜索到的投资者名片信息列表
	 * @param  [array] 
	 * @author 钟涛
	 */
	public function getDataInfo($idarr=array(),$com_user_id=0,$type=1){
		if(count($idarr)){
			$userlist=array();
			$resultlist=array();
			//sso
			$ismobilearr=Service_Sso_Client::instance()->getUserInfoByMoreUserId($idarr);
			foreach ($idarr as $k=>$v){
				$permodel= ORM::factory('Personinfo')->where('per_user_id','=',$v)->find();
				//$page_list=$permodel->select('*')->where('per_user_id','in',$idarr)->find_all( );
				if($type==2 && $com_user_id){
					$userlist[$k]= $this->addResultDataByCom2($permodel,$com_user_id);
				}else{
					$userlist[$k]=$this->addResultData2($permodel);
				}
				$userlist[$k]['isMobile']='';
				if($ismobilearr){
					foreach ($ismobilearr as $k2=>$v2){
						if($permodel->per_user_id==$v2['id']){
							$userlist[$k]['isMobile']=$v2['valid_mobile'];
						}
					}
				}
			}
			//最近登录时间排序
			$userlist= common::multi_array_sort($userlist,'last_logintime_int',SORT_DESC);
			return $userlist;
		}else{
			return array();
		}
	}
	
	/**
	 * 未登录情况获取推荐的3个个人用户(未登录、个人用户登录、企业用户没有项目)
	 * [最新注册的3位用户]
	 * @author 钟涛
	 */
	function getRecommendPerson_old($com_user_id=0,$type=1){
		$permodel= ORM::factory('Personinfo');
		$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
		$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
		$personlist=$permodel->limit(3)->order_by('per_createtime', 'DESC')->find_all( );
		$userlist=array();
		$resultlist=array();
		$per_service = new Service_User_Person_User();
		if($type==2 && $com_user_id){
			$resultlist=$this->addResultDataByCom($personlist,$com_user_id);
		}else{
			foreach ($personlist as $list){
				//个人行业
				$userlist['this_per_industry']=$per_service->getPerasonalParentIndustry($list->per_user_id);
				//个人所在地
				$userlist['this_per_area']=$per_service->getPerasonalAreaStringOnlyPro($list->per_user_id);
				$userlist['this_huoyuedu']=$this->getAllScore($list->per_user_id);//活跃度
				$resultlist[] = array_merge($list->as_array(),$userlist);
			}
		}
		//按照活跃度从高到低排序
		return common::multi_array_sort($resultlist,'this_huoyuedu',SORT_DESC);
	}
	
	/**
	 * 最新 未登录情况获取推荐的3个个人用户(未登录、个人用户登录、企业用户没有项目)
	 * [根据浏览的项目去推]
	 * @author 钟涛
	 */
	function getRecommendPerson($com_user_id=0,$type=1){
		$ser=new Service_Platform_Search();
		$thisip=ip2long(Request::$client_ip);
		//获取我最新浏览的项目的id[首先根据cook，然后根据ip浏览记录]
		$projectarr=$ser->_getVistedLog($com_user_id,$thisip,1);
		if (isset ( $projectarr [0] ['operate_id'] ) && $projectarr [0] ['operate_id']) {
			$proid=$projectarr [0] ['operate_id'];
		}else{//获取最新的一个项目的id
			$proid=ORM::factory('Project')->where('project_status','=',2)->order_by('project_passtime','desc')->find()->project_id;
		}
		$projectservice=new Service_User_Company_Project();
		//项目金额
		$mon= ORM::factory('Project', $proid)->project_amount_type;
		$monarr= common::moneyArr();
		$monname='';
		if($mon>0 && $mon<6){
			$monname= $monarr[$mon];
		}
		//项目行业
		$pro_industry=$projectservice->getProjectindustry($proid);
		//项目地区
		$pro_area=$projectservice->getProjectArea($proid);
		if(count($pro_area)&& is_array($pro_area)){
			$area='';
			foreach ($pro_area as $v){
				$area=$area.$v.',';
			}
			$pro_area= substr($area,0,-1);
		}
		//项目的金额 行业 地区组合的数据
		$tvaule= '"'.$monname.'"  '.$pro_industry.' '.$pro_area;
		$tvaule = str_replace(':',' ',$tvaule);//去除冒号
		$tvaule = str_replace(',',' ',$tvaule);//去除逗号
		
		$Search1 = new Service_Api_Search ();
		$searchresult2 = $Search1->getSearchByInvestor ( $tvaule, '', 0, '');
		$userarrkey=array();
		if(isset($searchresult2['matches']) && count($searchresult2['matches'])>=3){
			$userarrkey = array_rand($searchresult2['matches'], 3);//随机获取3条数据
		}
		$userarrlast2=array();
		if(count($userarrkey)){
			foreach ($userarrkey as $k){
				$userarrlast2[]=$searchresult2['matches'][$k];
			}
		}
		$permodel2= ORM::factory('Personinfo');
		if(count($userarrlast2)==3){
			$personlist=$permodel2->where('per_user_id','in',$userarrlast2)->find_all( );
		}
		else{
			$permodel2->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
			$permodel2->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
			$personlist=$permodel2->limit(3)->order_by('per_createtime', 'DESC')->find_all( );
		}
		$userlist=array();
		$resultlist=array();
		$per_service = new Service_User_Person_User();
		if($type==2 && $com_user_id){
			$resultlist=$this->addResultDataByCom($personlist,$com_user_id);
		}else{
			foreach ($personlist as $list){
				//个人行业
				$userlist['this_per_industry']=$per_service->getPerasonalParentIndustry($list->per_user_id);
				//个人所在地
				$userlist['this_per_area']=$per_service->getPerasonalAreaStringOnlyPro($list->per_user_id);
				$userlist['this_huoyuedu']=$this->getAllScore($list->per_user_id);//活跃度
				$resultlist[] = array_merge($list->as_array(),$userlist);
			}
		}
		//按照活跃度从高到低排序
		return common::multi_array_sort($resultlist,'this_huoyuedu',SORT_DESC);
	}
	/**
	 * 企业登录情况获取推荐的20个个人用户(企业有审核通过的项目)
	 * [浏览我项目的人，不足20个再加意向投资行业为我的项目行业的个人用户]
	 * @author 钟涛
	 */
	function getRecommendPersonIsLogin($com_id,$com_user_id){
		if(intval($com_id)){
			//根据用户id获取我的项目
			$model = ORM::factory('Project');
			$myprojectlist = $model->where('com_id', '=', $com_id)->where('project_status', '=', 2)->find_all();
			$proidarr=array();//企业项目
			$userarr=array();//用户
			foreach ($myprojectlist as $v){
				$proidarr[]=$v->project_id;
			}
			if(count($proidarr)){//我的项目
				//获取最新浏览过我项目的20位投资者
				$perdata=ORM::factory('UserViewProjectLog');
				$perdata->join('user_person','LEFT')->on('user_id','=','per_user_id');
				$perdata->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
				$perdata->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
				$perdata_list=$perdata->where('operate_id','in',$proidarr)->where('operate_type','=',1)->group_by('user_id')->limit(20)->order_by('add_time','DESC')->find_all();
				if(count($perdata_list)){
					foreach ($perdata_list as $vp){
						$userarr[]=$vp->user_id;
					}
				}
				$liulanpeople=count($userarr);
				if($liulanpeople<20){//浏览我的项目的人不足20人，从意向投资行业为我的项目行业中推荐出投资者补足20人
					$othercount=20-$liulanpeople;//还差的人数
					$proser=new Service_User_Company_Project();
					$form_inv = $proser->getInventesByProArr($proidarr);
					if(count($form_inv)){//企业项目的行业
						$industry_one=array();//1级行业
						if(isset($form_inv['one']) && count($form_inv['one'])){
							$industry_one=$form_inv['one'];
						}
						$industry_two=array();//2级行业
						if(isset($form_inv['two']) && count($form_inv['two'])){
							$industry_two=$form_inv['two'];
						}
						if(count($industry_two)){//先从个人意向2级行业查找
							$permodel= ORM::factory('Personinfo');
							$permodel->join('user_personal_industry','LEFT')->on('user_personal_industry.user_id','=','per_user_id');
							$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
							$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
							if(count($userarr)){//不包括浏览过投资者
								$permodel->where('per_user_id','not in',$userarr); 
							}
							$permodel->where('industry_id','in',$industry_two); //2级意向投资行业
							$person_list=$permodel->select('*')->group_by('per_user_id')->limit($othercount)->order_by('per_createtime','DESC')->find_all( );
							foreach ($person_list as $vplist){
								$userarr[]=$vplist->per_user_id;
							}
						}
						if(count($userarr)<5 && count($industry_one)){//如果还不足5人，再从意向1级行业查找
							$liulanpeople=count($userarr);
							$othercount=20-$liulanpeople;//还差的人数
							$permodel2= ORM::factory('Personinfo');
							$permodel2->join('user_personal_industry','LEFT')->on('user_personal_industry.user_id','=','per_user_id');
							$permodel2->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
							$permodel2->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
							if(count($userarr)){//不包括浏览过投资者
								$permodel2->where('per_user_id','not in',$userarr);
							}
							$permodel2->where('parent_id','in',$industry_one); //1级意向投资行业
							$person_list2=$permodel2->select('*')->group_by('per_user_id')->limit($othercount)->order_by('per_createtime','DESC')->find_all( );
							foreach ($person_list2 as $vplist){
								$userarr[]=$vplist->per_user_id;
							}
						}
					}
				}
				if(count($userarr) && count($userarr)>2){//最终返回3位推荐用户
					$userarrkey = array_rand($userarr, 3);//随机获取3条数据
					$userarrlast=array();
					foreach ($userarrkey as $k){
						$userarrlast[]=$userarr[$k];
					}
					if(count($userarrlast)==0){//如果没有数据默认为0
						$userarrlast=array(0);
					}
					$permodel= ORM::factory('Personinfo');
					$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
					$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
					$personlist=$permodel->where('per_user_id','in',$userarrlast)->limit(3)->order_by('per_createtime','DESC')->find_all( );
					$userlist=array();
					$resultlist=array();
					$per_service = new Service_User_Person_User();
					$userlist=$this->addResultDataByCom($personlist,$com_user_id);
					//按照活跃度从高到低排序
					return common::multi_array_sort($userlist,'this_huoyuedu',SORT_DESC);
				}
			}
		}
		return array();
	}
	
	/**
	 * [企业用户]随机获取一条数据[组合html]
	 * 
	 * @param [int] $user_id
	 *        	[企业用户id]
	 * @author 钟涛
	 */
	public function getOneInvestorInfoHtml($per_v,$perk) {
		if (! empty ( $per_v ["per_photo"] )) { // 头像
			$imageurl = URL::imgurl ( $per_v ['per_photo'] );
		} else {
			$imageurl = URL::webstatic ( "images/getcards/photo.png" );
		}
		if ($per_v ['per_gender'] == 2) { // 真实姓名
			$relname = mb_substr ( $per_v ['per_realname'], 0, 3 ) . '  女士';
		} else {
			$relname = mb_substr ( $per_v ['per_realname'], 0, 3 ) . '  先生';
		}
		
		// 学历
		$edu_arr = common::getPersonEducation ();
		$edu='';
		if(isset($per_v['per_education']) && $per_v['per_education']){
			$edu= $edu_arr[$per_v['per_education']];
		}
		$monarr = common::moneyArr ();
		$returnhtml = '<div class="geren_left" id="getonecard_'.$per_v['per_user_id'].'_0_div"><img src="' . $imageurl . ' "/>';
		if ($per_v ['is_pay']) {
			$returnhtml .= '<span></span>';
		}
		$returnhtml .= '</div><a class="atestuserid" style="display: none">' . $per_v ['per_user_id'] . '</a>
                        <div class="geren_right" style="width:190px;">
                        	<p class="a">
                        	<span class="name">' . $relname . '</span>
                        	<span>' . $per_v ['this_per_area'] . '</span>
                        	<span>' .$edu. '</span>
                        	</p>
                            <p class="b">
                            <span class="fs">' . $per_v ['this_per_industry'] . '</span>
                            <span class="je">' . $monarr [$per_v ['per_amount']] . '</span>
                            </p>
                            <p class="c">' . mb_substr ( $per_v ['per_remark'], 0, 30 ) . '</p>
                        </div>
                        <div class="qiye_right" id="' . $per_v ['per_user_id'] . '_span">';
		
		if (isset ( $per_v ['card_type'] ) && $per_v ['card_type'] == 3) { // 已交换
			$returnhtml .= '<span>已交换</span>';
		} elseif (isset ( $per_v ['card_type'] ) && $per_v ['card_type'] == 2) { // 递出的名片
			if (time () - (604800 + $per_v ['sendtime']) > 0) { // 7天后又可以重复递出
				$returnhtml .= '<a href="javascript:void(0)" id="resendcard_' . $per_v ['card_id'] . '_' . $per_v ['per_user_id'] . '" class="re_send">递出名片</a>
                                                  <a id="resendcard_' . $per_v ['card_id'] . '_' . $per_v ['per_user_id'] . '_name" style="display:none">' . mb_substr ( $per_v ['per_realname'], 0, 1, 'utf-8' ) . '先生/女士</a>';
			} else {
				$returnhtml .= '<span>已递出</span>';
			}
		} elseif (isset ( $per_v ['card_type'] ) && $per_v ['card_type'] == 1) { // 收到的名片
			$returnhtml .= '<a href="javascript:void(0)" class="changecard" id="exchangecard_' . $per_v ['card_id'] . '_' . $per_v ['per_user_id'] . '">交换名片</a>
                             <a id="exchangecard_' . $per_v ['card_id'] . '_' . $per_v ['per_user_id'] . '_name" style="display:none">' . mb_substr ( $per_v ['per_realname'], 0, 1, 'utf-8' ) . '</a>';
		} else {
			$returnhtml .= '
                        	<a href="javascript:void(0)" id="exchangecard_' . $per_v ['per_user_id'] . '" class="outcard">递出名片</a>
                        	<a id="exchangecard_' . $per_v ['per_user_id'] . '_name" style="display:none">' . mb_substr ( $per_v ['per_realname'], 0, 1, 'utf-8' ) . '先生/女士</a>';
		}
		$returnhtml .= '<a href="javascript:void(0)"  class="viewcard" id="getonecard_' . $per_v ['per_user_id'] . '_0' . '">查看名片</a>';
		
		if (isset ( $per_v ['favorite_status'] ) && $per_v ['favorite_status'] == 1) { // 已收藏
			$returnhtml .= '<span>已收藏</span>';
		} else {
			$returnhtml .= '<div id="shoucang_0_' . $per_v ['per_user_id'] . '_div">
                            <a href="javascript:void(0)" id="shoucang_0_' . $per_v ['per_user_id'] . '" class="sc">收藏名片</a>
                            </div> ';
		}
		
	    // 换一个投资者
		$returnhtml .= '<a href="javascript:void(0)" id="person_' . $perk . '" class="replaceone">不感兴趣</a>';
	
		$returnhtml .= '</div>';
		return $returnhtml;
	}
	
	/**
	 *返回热门搜索，主要是返回标签里面的东西
	 * @author 赵路生
	 */
	public function findTag(){
		$msg = $msg_temp = array();
		$memcache = Cache::instance ( 'memcache' );
		$msg_cache = $memcache->get('searchinvestor_tagnew');
		if($msg_cache){
			$msg = $msg_cache;
		}else{
			$tag = ORM::factory('Usertype');
			$result = $tag->where('tag_status', '=', '1')->find_all();
			foreach ($result as $k=>$v){
					$msg_temp[$v->tag_id] = $v->tag_name;
			}
			$msg = array_unique($msg_temp);
			$memcache->set('searchinvestor_tagnew',$msg,86400);			
		}
		shuffle($msg);
		$msg = count($msg)>6 ? array_slice($msg,0,6) : $msg;
		return $msg;
	}
	
	// 	/**
	// 	 * 搜索投资者，获取当前时间到本月开始的时间
	// 	 * @author 赵路生
	// 	 * $per_user_id 个人用户id
	// 	 */
	// 	public function getCurrentTimeRelated(){
	// 		$date = time();
	// 		$sdate=date('Y-m-1 00:00:00',$date); //当前月开始时间
	// 		//获取开始时间的时间戳和结束时间的时间戳
	// 		$ret = array();
	// 		$ret['stime'] = strtotime($sdate); //当前月开始时间时间戳
	// 		$ret['ctime'] = $date;
	// 		return $ret;
	// 	}
	// 	/**
	// 	 * 搜索投资者，上月的今天
	// 	 * 计算上一个月的今天，如果上个月没有今天，则返回上一个月的最后一天
	// 	 * @author 赵路生
	// 	 *
	// 	 */
	// 	public function getPrevCurrentTime(){
	// 		$date = time();
	// 		//这里是转换到上一个月的最后一天
	//     	$last_month_time = mktime(date("G", $date), date("i", $date),date("s", $date), date("n", $date), 0, date("Y", $date));
	//     	$last_month_t =  date("t", $last_month_time);//返回上个月份的所有天数
	
	//     	if ($last_month_t < date("j", $date)) { //如果上个月的天数小于本月的该天
	//         		 return  $last_month_time; //则返回上个月的最后时间
	//     	}
	//    		return strtotime(date(date("Y-m", $last_month_time) . "-d", $date));	//返回上个月的今天
	// 	}
	/**
	 * 搜索投资者，上月的今天
	 * 计算上一个月的今天，精简版
	 * @author 赵路生
	 *
	 */
	public function getPrevCurrentTime(){
		return mktime(0,0,0,date("m")-1,date("d"),date("Y"));
	}
	/**
	 * 搜索投资者，获取上月今天投资者递出名片数
	 * @author 赵路生
	 * $per_user_id 个人用户id
	 */
	public function getCardSendAmount($per_user_id){
		if($per_user_id){
			$card_amount_ser = ORM::factory('Cardinfobehaviour');
			$ret = self::getPrevCurrentTime();
			if($ret){
				$card_send_amount = $card_amount_ser->where('user_id','=',$per_user_id)->where('status','=','2')->where('add_time','>=',$ret)->count_all();
				if($card_send_amount){
					return $card_send_amount;
				}else{
					return 0;
				}
			}
		}
	}
	/**
	 * 搜索投资者，获取上月今天投资者查看名片数
	 * @author 赵路生
	 * $per_user_id
	 */
	public function getCardCheckAmount($per_user_id){
		if($per_user_id){
			$card_amount_ser = ORM::factory('Cardinfobehaviour');
			$ret = self::getPrevCurrentTime();
			if($ret){
				$card_check_amount = $card_amount_ser->where('user_id','=',$per_user_id)->where('status','=','9')->where('add_time','>=',$ret)->count_all();
				if($card_check_amount){
					return $card_check_amount;
				}else{
					return 0;
				}
			}
		}
	}
	/**
	 * 搜索投资者，获取上月今天投资者查看招商会数
	 * @author 赵路生
	 * $per_user_id
	 */
	public function getInvCheckAmount($per_user_id){
		if($per_user_id){
			$investment_amount_ser = ORM::factory('UserViewProjectLog');
			$ret = self::getPrevCurrentTime();
			if($ret){
				$investment_check_amount = $investment_amount_ser->where('user_id','=',$per_user_id)->where('operate_type','=',2)->where('add_time','>=',$ret)->count_all();
				return $investment_check_amount;
			}
		}
		return 0;
	}
	
	/**
	 * 搜索投资者，获取上月今天投资者最近查看项目
	 * @author 赵路生
	 * $per_user_id 个人用户id
	 */
	public function getViewProject($per_user_id){
		$check_project_ser = ORM::factory('UserViewProjectLog');
		$check_project = $check_project_ser->where('user_id','=',$per_user_id)->where('operate_type','=','1')->limit(1)->order_by('add_time','DESC')->find_all();
		if(count($check_project)){
			foreach($check_project as $value){
				$check_project_id = $value->operate_id;
			}
			//根据项目id返回项目名称
			$project_service= new Service_Platform_Project();
			$projectinfo=$project_service->getProjectInfoByID($check_project_id);
			if($projectinfo){
				return  array(
						'project_brand_name'=>$projectinfo->project_brand_name,
						'project_id'=>$check_project_id
				);
			}
		}
		return array(
				'project_brand_name'=>'0个',
				'project_id'=>''
		);
	}
	
	/**
	 * sso
	 * 搜索投资者，获取用户最后登录时间
	 * @author 赵路生
	 * $userid 用户id
	 */
	public function getLastLoginTime($userid){
		if(intval($userid)){
			//@sso 赵路生 2013-11-12
			$user_basic = ORM::factory('User', $userid);
			$last_logintime = $user_basic->last_logintime;
			if(!$last_logintime){
				$last_login_ser =  Service_Sso_Client::instance()->getUserInfoById($userid);
				$last_logintime = $last_login_ser->reg_time;
			}
			//$last_logintime = date('Y-m-d',$last_logintime);
			$now_time = time();
			$time_section = $now_time - $last_logintime;
			$time_output = self::getTimeSection($time_section);
			return $time_output;
		}
		return '';
	}
	/**
	 * sso
	 * 搜索投资者，获取用户最后登录时间[整形]
	 * @author 赵路生
	 * $userid 用户id
	 */
	public function getLastLoginTimeToInt($userid){
		if(intval($userid)){
			$user_basic = ORM::factory('User', $userid);
			$last_logintime = $user_basic->last_logintime;
			if(!$last_logintime){
				//@sso 赵路生 2013-11-12
				$last_login_ser =  Service_Sso_Client::instance()->getUserInfoById($userid);
				$last_logintime = $last_login_ser->reg_time;
			}
			return $last_logintime;
		}
		return 0;
	}
	/**
	 * 搜索投资者，获取用户最后登录时间 返回时间区间
	 * @author 赵路生
	 * $time_section 时间差值
	 */
	public function getTimeSection($time_section){
		if($time_section < 60){
			return '1分钟内';
		}elseif($time_section >= 60 && $time_section < 3600){
			return floor($time_section/60).' 分钟前';//60
		}elseif($time_section >= 3600 && $time_section < 86400){
			return floor($time_section/3600).' 小时前';//24
		}elseif($time_section >= 86400 && $time_section < 604800){
			return floor($time_section/86400).' 天前';//7
		}elseif($time_section >= 604800 && $time_section <2592000){
			return floor($time_section/604800).' 周前';//4
		}elseif($time_section >= 2592000 && $time_section <31536000){		
			return floor($time_section/2592000).' 个月前';//12
		}elseif($time_section >= 31536000 && $time_section <3*31536000){
			return floor($time_section/31536000).' 年前';//3	
		}else{
			return '很久以前';
		}
	}
	/**
	 * 搜索投资者，根据用户id获取用户的基本信息[企业登录]
	 * @author 赵路生
	 * $userid_list 个人用户ID_list;$user_id当前登录用户id
	 */
	public function getPerBasicInfoByID($userid_list,$user_id){
		$result=array();
		if(count($userid_list)){
			$persondata = ORM::factory("Personinfo")->where('per_user_id','in',$userid_list)->find_all();
			$result=$this->addResultDataByCom($persondata,$user_id);
		}
		return $result;
	}
	
	/**
	 * 搜索投资者，根据用户id获取用户的基本信息[未登录和个人登录]
	 * @author 赵路生
	 * $userid_list 个人用户ID_list;$user_id当前登录用户id
	 */
	public function getPerBasicInfoByIDNotlogin($userid_list,$login_userid=0){
		$result=array();
		if(count($userid_list)){
			$persondata = ORM::factory("Personinfo")->where('per_user_id','in',$userid_list)->find_all();
			$result=$this->addResultData($persondata,$login_userid);
		}
		return $result;
	}
	
	/**
	 * 搜索投资者，根据用户id获取用户的所有信息
	 * @author 赵路生
	 * $userid 个人用户id;$login_userid当前登录用户id
	 */
	public function getPerInfoByID($userid,$login_userid){
		$service_per_user = new Service_User_Person_User();
		$card_service=new Service_Card();
		//获取投资者基本信息
		$result = $service_per_user->getPersonInfo($userid);
		$result['per_user_id'] = $userid;
		$result['industry'] = $service_per_user->getPersonalIndustryString($userid);//意向投资行业
		$result['this_area'] = $service_per_user->getPerasonalAreaString($userid); //个人所在地
		$result['area'] = $service_per_user->getPersonalArea($userid);//获得个人意向投资地区
		$result['experience'] = $service_per_user->listExperience($userid);  //获得个人从业经验
		$service_searchinvestor=new Service_Platform_SearchInvestor();
		//搜索投资者，获取本月投资者递出名片数
		$result['cardsendamount']=$service_searchinvestor->getCardSendAmount($userid);
		//搜索投资者，获取本月投资者查看名片数
		$result['cardcheckamount']=$service_searchinvestor->getCardCheckAmount($userid);
		//搜索投资者，获取本月投资者查看招商会数
		$result['investmentcheckamount']=$service_searchinvestor->getInvCheckAmount($userid);
		//搜索投资者，获取个人投资者最近查看的项目
		$recent_view= $service_searchinvestor->getViewProject($userid);
		$result['recentlyviewproject']=	$recent_view['project_brand_name'];
		$result['recentlyviewproject_id']= $recent_view['project_id'];
		//用户活跃度
		$result['this_huoyuedu']=$service_searchinvestor->getAllScore($userid);//todo活跃度
		//上次登录时间
		$result['last_logintime']=$service_searchinvestor->getLastLoginTime($userid);
		//添加判断是否已经查看改投资者名片
		$is_pay=$card_service->getCardinfoCountByid($login_userid,$userid);
		$result['is_pay']=$is_pay;
	
		//通过这个是否查看来处理对手机号码和邮箱地址的部分隐藏
		//手机号码部分隐藏，格式如139****9476
		if($result['mobile']){
			$result['mobile']=mb_substr($result['mobile'],0,3,'UTF-8').'****'.mb_substr($result['mobile'],7,11,'UTF-8');
		}else{
			$result['mobile']='';
		}
		//邮箱部分显示，格式如********@126.com
		$emailarr = explode("@",$result['email']);
		if(isset($emailarr[1])){
			$result['email']='********@'.$emailarr[1];
		}else{
			$result['email']=$result['email'];
		}
		//判断是否已交换名片
		if($card_service->getExchaneCardCountByTwoIdAll($userid, $login_userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($userid,$login_userid)){
			$result['card_type']=3;//已经交换
			unset($result['sendtime']);
			unset($result['card_id']);
		}elseif($card_service->getOutCardCountByTwoIdAll($userid, $login_userid)){//已递出
			$result['card_type']=2;//已递出
			$query1 = DB::select()->from('card_info')->where('from_user_id', '=', $login_userid)->and_where('to_user_id', '=', $userid)->and_where('exchange_status', '=', 0);
			$result1 = $query1->execute()->as_array();
			if(isset($result1[0]['send_time'])){//记录递出名片时间[7天后又可重复递出]
				$result['sendtime'] = $result1[0]['send_time'];
			}else{
				$result['sendtime'] = 0;
			}
			$result['card_id'] = $result1[0]['card_id'];
		}elseif($card_service->getReceiveCardCountByTwoIdAll($userid, $login_userid)){//已收到
			$result['card_type']=1;//已收到
			$query1 = DB::select()->from('card_info')->where('to_user_id', '=', $login_userid)->and_where('from_user_id', '=', $userid)->and_where('exchange_status', '=', 0);
			$result2 = $query1->execute()->as_array();
			$result['card_id'] = $result2[0]['card_id'];
			unset($result['sendtime']);
		}else{
			$result['card_type']=4;//无记录
			unset($result['sendtime']);
			unset($result['card_id']);
		}
		//判断收藏表中对应的数据
		if ($card_service->getFavoriteStatus($login_userid,$userid)==TRUE){
			$result['favorite_status']=1;//已存在对应收藏关系
		}
		else{
			$result['favorite_status']=0;//无收藏
		}
		return $result;
	}
	
	/**
	 * 搜索投资者发信，获取模板信件内容
	 * @author 赵路生
	 */
	public function getTemLetter($login_userid){
		$login_userid = intval($login_userid);
		if($login_userid){
			$service = ORM::factory('UserLetterTemplate');
			$res = $service->where('user_id','=',$login_userid)->find();
			if($res->loaded()){
				$tem_letter = $res->content;
				return  htmlspecialchars_decode($tem_letter);
			}else{
 				//做缓存
				$memcache = Cache::instance ( 'memcache' );
 				$_cache_get_time = 86400;//一天
 				if(!$memcache->get('searchinvestor_temletter'.$login_userid)){
					$pro_service = new Service_User_Company_User();
					$com_name =$pro_service->getCompanyInfo($login_userid)->com_name;
					$com_id = $pro_service->getCompanyInfo($login_userid)->com_id;
					$project_list = self::getProjectInfo($com_id);//重新写一个方法
					$tem_letter= "您好呀！我们是".$com_name."，主要做".$project_list->project_brand_name."。我觉得挺适合您的，小投资，大回报。希望我们能有一个合作的机会。";
					$memcache->set('searchinvestor_temletter'.$login_userid,$tem_letter,$_cache_get_time);
					return $tem_letter;
 				}else{					
					$tem_letter = $memcache->get('searchinvestor_temletter'.$login_userid);
					return $tem_letter;
				}
			}
		}else{
			return '';
		}
	}
	/**
	 * 搜索投资者发信，获取企业项目信息
	 * @author 赵路生
	 * 通过com_id返回企业的项目，返回一条
	 */
	public function getProjectInfo($com_id){
		  return $res=ORM::factory('Project')->where('com_id', '=', $com_id)->where('project_status','=','2')->find();
	}
	
	/**
	 * 搜索投资者发信，修改模板信件内容
	 * @author 赵路生
	 */
	public function updateTemLetter($login_userid,$postdata,$user_type){
		$login_userid = intval($login_userid);
		if($login_userid && $postdata){
			$service = ORM::factory('UserLetterTemplate');
			$res = $service->where('user_id','=',$login_userid)->find();
			if($res->loaded()){
				$res->content = $postdata;
				$res->add_time = time();
				$res2 = $res->update();
				if($res2){
					return true;
				}
			}else{
				$service->content = $postdata;
				$service->add_time = time();
				$service->user_id = $login_userid;
				$service->user_type = $user_type;
				$res = $service->create();//貌似可以用try_catch来执行
				if($res){
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * 获取近一个月的活跃度值
	 * @author 钟涛
	 */
	public function getNearMonthScore($userid){
		if($userid){
			$score=DB::select(array(DB::expr('sum(score)'),'vitality'))
			 ->from('user_person_score_log')
			 ->where('user_id','=',$userid)
			 ->and_where('status','=',1)
			 ->and_where('add_time','>=',time()-30*86400)
			 ->execute()->as_array();
			if(isset($score['0']['vitality']) && $score['0']['vitality']){
				return $score['0']['vitality'];//返回最近一个月的活跃度的总和
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	/**
	 * 获取总活跃度值
	 * @author 钟涛
	 */
	public function getAllScore($userid){
		if($userid){
			$score=DB::select(array(DB::expr('sum(score)'),'vitality'))
			->from('user_person_score_log')
			->where('user_id','=',$userid)
			->and_where('status','=',1)
			->execute()->as_array();
			if(isset($score['0']['vitality']) && $score['0']['vitality']){
				return $score['0']['vitality'];
			}else{
				return 150;//默认150
			}
		}else{
			return 150;//默认150
		}
	}
	
	/**
	 * 获取总活跃度值【不包括创业币】
	 * @author 钟涛
	 */
	public function getAllScoreNotChuangyibi($userid){
		if($userid){
			$score=DB::select(array(DB::expr('sum(score)'),'vitality'))
			->from('user_person_score_log')
			->where('user_id','=',$userid)
			->and_where('status','=',1)
			->and_where('score_type','!=',16)
			->execute()->as_array();
			if(isset($score['0']['vitality']) && $score['0']['vitality']){
				return $score['0']['vitality'];
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
}