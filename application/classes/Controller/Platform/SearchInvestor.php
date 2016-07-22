<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 找投资者
 * @author 钟涛
 */
class Controller_Platform_SearchInvestor extends Controller_Platform_Template{
/**
	 * 搜索投资者
	 * @author 钟涛
	 */
	public function action_index(){
		$content = View::factory('platform/searchinvestor/searchInvestornew');
		$this->content->maincontent = $content;
		
		$search = Arr::map(array("HTML::chars"), $this->request->query());
		$service_Investor=new Service_Platform_SearchInvestor();
		if(!isset($_GET['w'])){
			$_GET['w'] = '';
		}
		$search=new Service_Platform_Search();
		$inputvalue=secure::secureInput(secure::secureUTF($_GET['w']));
		if($inputvalue=='请输入您要搜索的条件。如：上海	餐饮娱乐	10万'){
			$_GET['w'] = $wordShow = '';
		}else{
			$_GET['w'] = $wordShow = $inputvalue;
		}
		$cond = array();
		$cond['sort'] = secure::secureInput(intval(arr::get($_GET, 'sort', 1)));
		#行业id
		$cond['inid'] = secure::secureInput(intval(arr::get($_GET, 'parent_id', 0)));
		#地区id
		$cond['areaid'] = secure::secureInput(intval(arr::get($_GET, 'per_area_id', 0)));
		#投资金额
		$cond['atype'] = secure::secureInput(intval(arr::get($_GET, 'per_amount', 0)));
		if($wordShow !='80后' && $wordShow !='80后创业'){
			$seachAmount = searchservice::getInstance()->getWordAmount($wordShow, 0);
		}else{
			$seachAmount=Array ();
		}
		#未匹配到的标签
		$cond['unallow'] = secure::secureInput(arr::get($_GET, 'unallow', ''));
		//返回下来框选择的值
		$wordNew = $search->newWord($cond);
		if(count(arr::get($seachAmount, 'eddAmountSector', array())) == 1 && !$wordNew) {
			$cond['atype'] = arr::get(arr::get($seachAmount, 'eddAmountSector', array()), 0, 0);
		}

		if($wordNew) {//走选择下拉框进行搜索
			$searchresult=$search->getWordSearchInvestor($wordNew, $cond);
			$content->selectvalue = $wordNew;
		}else{//走输入框输入的值搜索
			if($wordShow){//有输入条件
				$searchresult=$search->getWordSearchInvestor($wordShow, $cond);
			}else{//没有输入任何条件[走sql筛选]
				$searchresult=$search->getInvestorByUserTable($wordShow, $cond);
			}
			$content->selectvalue = $wordShow;
		}
		
		if(!$wordShow && !$wordNew){
			$content->input_value = false;//没有输入任何条件[默认展示]
		}else{
			$content->input_value = true;//有输入条件查询
		}
		//查找的总人数
		$search_count=isset($searchresult['total']) ? $searchresult['total'] : 0;

		$amountSector = isset($searchresult['eddAmountSector']) ? $searchresult['eddAmountSector'] : array();
		$keywords = (isset($searchresult['words']))  ? $searchresult['words'] : array();
		
		$keyGroup = $search->searchWordGroup($keywords);
		$seachCond = $search->searchCondGroup($keyGroup, $cond);
		$seachCond['cond']['w'] = $wordShow;
		$content->totalcount = $search_count;//查找到总人数
		
		if($search_count==0){//没有找到用户 推荐10个
			$Search1 = new Service_Api_Search ();
            //默认活跃度排序[近一个月活跃度最高的]
			$limit = 10;
			$page = isset ( $_GET ['p'] ) ? intval ( $_GET ['p'] ) : 1;
			$offset = ($page - 1) * $limit;
			$searchresult = $Search1->getSearchByInvestor ( '*:*', '', $offset, 'vitality desc' );
			if($wordShow || $wordNew){//输入条件找不到 只默认推荐10个
				$search_count=10;
			}else{
				$search_count=isset($searchresult['total']) ? $searchresult['total'] : 0;
			}
		}
		
		// 临时调试使用
		if(isset($_GET['debug']) && $_GET['debug']==1){
			Kohana::debug($searchresult);
		}
		
		//推荐的3位用户
		$tuijian_personlist=array();
		//判断是否登录
		$islogin=$this->loginUser();
		$ishasproject = 0;//我有审核通过的项目默认为0
		$user_type = 0; //用户类型，默认为未登录
		if($islogin){//如果已登录
			//$userinfo=$this->userInfo();
			$user_type = Cookie::get("user_type");
			$user_id= Cookie::get("user_id");
			if($user_type == 1){//企业用户
				$cominfo=ORM::factory('Companyinfo')->where('com_user_id','=',$user_id)->find();
				$comid=$cominfo->com_id;
				if($comid){
					$ser=new Service_User_Company_Project();
					$ishasproject=$ser->isHasProject($comid);
					if($ishasproject){//我有审核通过的项目
						$tuijian_personlist=$service_Investor->getRecommendPersonIsLogin($comid,$user_id);
					}else{//默认推荐企业用户的
						$tuijian_personlist=$service_Investor->getRecommendPerson($user_id,2);
					}
				}
				//投资者列表[企业登录用户]
				$content->personlist=$service_Investor->getDataInfo($searchresult['matches'],$user_id,2);
			}
		}
		if(count($tuijian_personlist)!=3){//默认推荐
			$tuijian_personlist=$service_Investor->getRecommendPerson(0,1);
			if($islogin){//如果已登录
				//$userinfo=$this->userInfo();
				$user_type = Cookie::get("user_type");
				$user_id= Cookie::get("user_id");
				if($user_type == 1 && $user_id){//企业用户
					//投资者列表[企业用户]
					$content->personlist=$service_Investor->getDataInfo($searchresult['matches'],$user_id,2);
				}else{
					//投资者列表[个人用户]
					$content->personlist=$service_Investor->getDataInfo($searchresult['matches'],0,1);
				}
			}
			else{
				//投资者列表[未登录用户]
				$content->personlist=$service_Investor->getDataInfo($searchresult['matches'],0,1);
			}
		}
		// 【开始】这里是用来好项目--设置 用户medal的内容 @赵路生
		$setting = common::getSpecificProjectSetting();
		$content->setting = $setting;
		$specific_users = array();
		if(time() > $setting['end_time']){
			$card_sr = new  Service_Card();
			$result = $card_sr->getUserTypeForSpePro();
			$specific_users = $result['users']?$result['users']:array();
		}
		$content->specific_users = $specific_users;
		// 【结束】这里是用来好项目
		
		
		
		//推荐列表
		$content->tuijian_personlist = $tuijian_personlist;

		$loginStatus = $this->isLogins();
		$user_id = $loginStatus ? $this->userInfo()->user_id : 0;
		$key = isset ( $_GET ['p'] ) ? 'p' : 'page';
		$page = Pagination::factory(array(
				'total_items' => $search_count,
				'view' => 'pagination/Simple',
				'current_page' => array('source' => 'zhaotouzi', 'key' => $key),
				'items_per_page' => 10,
		));
        $area = array('pro_id' => 0);
        $content->areas = common::arrArea($area);
        $content->wordShow = $wordShow;
        $content->user_id = $user_id;
        $content->alltotalcount = $service_Investor->getPersonAllCount();
        $content->allTag = $seachCond['cond'];
        $content->keyList = $seachCond['keyList'];
        $content->page = $page;
        $content->ishasproject = $ishasproject?$ishasproject:0;//判断企业是否有审核通过的项目
        $content->user_type = $user_type?$user_type:0;//判断用户类型，1企业，2个人，0未登录
        $content->loginStatus = $loginStatus;
		$search = Arr::map(array("HTML::chars"), $this->request->query());
		//第一次返回热门标签[以后的换一批热门标签通过ajax获取]
		$tag = $service_Investor->findTag();
		$content->tag= $tag;
		//学历
		$content->edu_arr=common::getPersonEducation();
		$invest = new Service_User_Person_Invest();
		//读取省级地区列表
		$content->area = $invest->getArea();
		$content->postlist=$search;
		
		//seo优化
		if($content->selectvalue){//有搜索词
			$this->template->title = '第'.$page->current_page.'页'.$content->selectvalue.'投资者_找投资者';
			$this->template->keywords = $content->selectvalue.'，找投资者，投资者，一句话 ';
			$this->template->description = '这里是第'.$page->current_page.'页'.$content->selectvalue.'投资者结果页，我们专业、快速、精准匹配帮助您找投资者，找投资者，上一句话！';
		}else{
			if($page->current_page==1){//首页
				$this->template->title = '一句话找投资者|找分销商_创业加盟找投资者上一句话商机速配网';
				$this->template->keywords = '一句话找投资者,一句话找分销商,找投资者,一句话商机速配网';
				$this->template->description = '一句话商机速配网找投资者平台专业、快速、精准匹配帮助您找投资者，好的项目需更好的投资。一句话商机网投资互动协助你更快的找到创业投资者，找投资者，上一句话商机网！';
			}else{//分页
				$this->template->title = '第'.$page->current_page.'页一句话找投资者|找分销商_创业加盟找投资者上一句话商机速配网';
				$this->template->keywords = '一句话找投资者,一句话找分销商,找投资者,一句话商机速配网';
				$this->template->description = '这里是第'.$page->current_page.'页一句话商机速配网找投资者平台专业、快速、精准匹配帮助您找投资者，好的项目需更好的投资。一句话商机网投资互动协助你更快的找到创业投资者，找投资者，上一句话商机网！';
			}
		}
		//友情链接
		$memcache = Cache::instance ('memcache');
		$friend_link = $memcache->get('friend_cache_searchinvestor');
        if(empty($friend_link)){
            $f_service = new Service_Platform_FriendLink();
            $friend_link = $f_service->getFriendLinkList('searchinvestor');
            $memcache->set('friend_cache_searchinvestor', $friend_link,604800);
        }
        $this->template->friend_link = $friend_link;
	}
	
	/**
	 * 搜索投资者[301规则：
	 * @author 钟涛
	 */
	public function action_investorto(){	
		$url=urlbuilder::zhaotouzi("zhaotouzi");
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>";
	}

    /**
     * 搜索投资者
     * @author 钟涛
     */
    public function action_search(){
        //获取页面post表单值
        $search = Arr::map(array("HTML::chars"), $this->request->query());
        $page = Arr::get($search, 'page') ? $search['page'] : 1;
        unset($search['x']);
        unset($search['y']);
        $islogin=$this->loginUser();
        $service=new Service_Platform_SearchInvestor();
        $content = View::factory('platform/searchinvestor/person_searchinvestor');
        if($islogin){//如果已登录，获取用户名
            $userinfo=$this->userInfo();
            if($userinfo->user_type == 1){//企业用户
                $content = View::factory('platform/searchinvestor/company_searchinvestor');
                $resultlist=$service->searchInvestorInfoByCom($search,$userinfo->user_id);
                $content->total_pages=$resultlist['page']->total_pages;
            }else{//个人用户
                $resultlist=$service->searchInvestorInfo($search);
                $content->total_pages=0;
            }
            $content->islogin=true;
        }else{//未登录
            $resultlist=$service->searchInvestorInfo($search);
            $content->islogin=false;
            $content->total_pages=0;
        }
        //echo $s_url;exit;
        $this->content->maincontent = $content;
        //学历
        $content->edu_arr=common::getPersonEducation();
        $invest = new Service_User_Person_Invest();
        //读取省级地区列表
        $content->area = $invest->getArea();
        $content->postlist=$search;
        $content->list=$resultlist['list'];
        $content->totalcount=$resultlist['totalcount'];
        $t_parm="";
        $content->page=$page;
        if(count($search)){
            $t_parm='?per_area='.arr::get($search,'per_area','').'&per_amount='.arr::get($search,'per_amount','').'&per_education='.arr::get($search,'per_education','').'&parent_id='.arr::get($search,'parent_id','').'&valid_mobile='.arr::get($search,'valid_mobile','');
        }
        $content->t_url=$t_parm;
    }

    /**
     * 搜索投资者 查看从业经验
     * @author 赵路生
     */
    public function action_checkExperience(){
        $getdata = Arr::map("HTML::chars", $this->request->query());
        $per_user_id = arr::get($getdata,'per_user_id');
        $service = new Service_User_Person_User();
        $content = view::factory("platform/searchinvestor/checkexperience");
        $this->content->maincontent = $content;

        //从业经验赋值
        $experiences = $service->listExperience($per_user_id);
        $service_user= new Service_User();
        foreach( $experiences as $k=>$v ){
            //行业类别
            $rs_profession= $service_user->getProfessionRow( $v['exp_industry_sort'] );
            $experiences[$k]['exp_industry_sort_name']= $rs_profession['profession_name'];
            //职业类别
            $rs_pos= $service_user->getPositionRow( $v['exp_occupation_type'] );
            $experiences[$k]['pos_name']= $rs_pos['position_name'];
            //职业名称
            if( $v['exp_occupation_name']!='0' ){
                $rs_pos= $service_user->getPositionRow( $v['exp_occupation_name'] );
                $experiences[$k]['occ_name']= $rs_pos['position_name'];
            }else{
                $experiences[$k]['occ_name']= $v['exp_user_occupation_name'];
            }
        }
        $content->experiences = $experiences;

        //根据用户id获取用户名字
         $result = $service->getPerson($per_user_id);
         $content->per_username = $result->per_realname;
    }
    
    /**
     * 一句话直搜，搜索投资者
     * @author 钟涛
     */
    public function action_search2(){
    	if(!isset($_GET['w'])){
    		$_GET['w'] = '';
    	}
    	$search=new Service_Platform_Search();
    	$serviceGuide = new Service_Platform_ProjectGuide ();
    	//$memcache = Cache::instance ( 'memcache' );
    	$_GET['w'] = $wordShow = secure::secureInput(secure::secureUTF($_GET['w']));
    	$cond = array();
    	$cond['sort'] = secure::secureInput(intval(arr::get($_GET, 'sort', 1)));
    	#行业id
    	$cond['inid'] = secure::secureInput(intval(arr::get($_GET, 'inid', 0)));
    	#地区id
    	$cond['areaid'] = secure::secureInput(intval(arr::get($_GET, 'areaid', 0)));
    	#投资金额
    	$cond['atype'] = secure::secureInput(intval(arr::get($_GET, 'atype', 0)));
    	$seachAmount = searchservice::getInstance()->getWordAmount($wordShow, 0);
    	#手机是否已通过验证
    	$cond['istatus'] = secure::secureInput(intval(arr::get($_GET, 'istatus', 0)));
    	#未匹配到的标签
    	$cond['unallow'] = secure::secureInput(arr::get($_GET, 'unallow', ''));
    	$loginStatus = $this->isLogins();
    	$user_id = $loginStatus ? $this->userInfo()->user_id : 0;
    	 
    	//返回新的查询字符串
    	$wordNew = $search->newWord($cond);
    
    	if(count(arr::get($seachAmount, 'eddAmountSector', array())) == 1 && !$wordNew) {
    		$cond['atype'] = arr::get(arr::get($seachAmount, 'eddAmountSector', array()), 0, 0);
    	}
    	 
    	if($wordNew) {//走搜索引擎返回搜索结果
    		$searchresult=$search->getWordSearchInvestor($wordNew, $cond);
    	}else{
    		$searchresult=$search->getWordSearchInvestor($wordShow, $cond);
    	}
    
    	//     	echo '<pre>';
    	//     	print_R($searchresult);
    	$amountSector = isset($searchresult['eddAmountSector']) ? $searchresult['eddAmountSector'] : array();
    	$keywords = (isset($searchresult['words']))  ? $searchresult['words'] : array();
    
    	$keyGroup = $search->searchWordGroup($keywords);
    	$seachCond = $search->searchCondGroup($keyGroup, $cond);
    	$seachCond['cond']['w'] = $wordShow;
    
    	// 临时调试使用
    	if(isset($_GET['debug']) && $_GET['debug']==1){
    		Kohana::debug($searchresult);
    	}
    
    	//     	$project_id_list=isset($searchresult['matches'])?$searchresult['matches']:array();
    	//     	$arr=NULL;
    	//     	foreach ($project_id_list as $val){
    	//     		$arr['result'][]=$val['id'];
    	//     	}
    	$total = 0;
    	if(isset($searchresult['total'])) {
    		$total = $searchresult['total'];
    	}
    
    	//     	echo $total.'<br>';
    	//     	if($total){
    	//     		foreach($searchresult['matches'] as $v){
    	//     			echo 'userid:'.$v['attrs']['per_user_id_attr'].'；peramount:';
    	//     			echo $v['attrs']['per_amount'].'<br>';
    	//     		}
    	//     	}
    	#保留判断
    	//$result = $search->getProjectSqlSearch($arr, $total, $user_id, $cond['sort']);
    
    	#快速搜索
    	if($wordShow || $wordNew) {
    		$content = View::factory('platform/searchinvestor/searchInvestorList');
    	}else{//没输入条件[推荐的投资者]
    		$content = View::factory('platform/searchinvestor/searchInvestorList');
    	}
    	$this->content->maincontent = $content;
    	$content->keywords = $keywords;
    	$page = Pagination::factory(array(
    			'total_items' => $total,
    			'items_per_page' => 10,
    	));
    	$area = array('pro_id' => 0);
    	$content->areas = common::arrArea($area);
    	$content->Industry = common::getIndustryList();
    	$content->person_list = array();
    	$content->wordShow = $wordShow;
    	$content->sort = $cond['sort'];
    	//记录手机是否认证
    	$content->istatus = $cond['istatus'];
    	$content->allTag = $seachCond['cond'];
    	$content->page = $page;
    	$content->keyList = $seachCond['keyList'];
    	$content->totalcount = $total;//查找到总人数
    	$content->loginStatus = $loginStatus;
    	$this->template->title = mb_convert_encoding($_GET['w'],"utf-8")."投资者";
    	$this->template->description = '一句话投资招商平台为企业选符合'.mb_convert_encoding($_GET['w'],"utf-8").'条件的投资者。投资赚钱好项目，一句话的事。';
    	$this->template->keywords = mb_convert_encoding($_GET['w'],"utf-8").'，热门行业投资，投资赚钱好项目';
    }
    
    /**
     * 搜索投资者  查看详情
     * @author 赵路生
     */
    public function action_showInvestorProfile(){
    	$arr = Arr::map(array("HTML::chars"), $this->request->query());
    	$sid=arr::get($arr,'sid','');
    	//这里可能还要加企业用户与个人用户的判断
    	$islogin=$this->loginUser();
    	if($islogin){
    		$userinfo=$this->userInfo();
    		if($userinfo->user_type == 1){//企业用户
    			$getdata = Arr::map("HTML::chars", $this->request->query());
    			$userid = arr::get($getdata,'userid');//获取传递过来的userid
    			//在user_person表里面查询，请求的这个用户id是否存在，不存在返回
    			$per_user_service = ORM::factory("Personinfo");
    			$check_per_id= $per_user_service->where('per_user_id','=',$userid)->find_all();
    			if(count($check_per_id)){//判断个人用户是否在这个里面
    				$ishasproject = 0; //默认设置审核通过的项目为0个
    				$userinfo=$this->userInfo(); //返回用户的基本信息
    				$login_userid = $userinfo->user_id; //返回当前登录用户的user_id
    				$content = View::factory('platform/searchinvestor/show_investorprofile_new');
    				$this->content->maincontent = $content;
    				$content->login_userid = $login_userid;
    				//获取登录用户是否有审核通过的项目
    				$cominfo=ORM::factory('Companyinfo')->where('com_user_id','=',$userinfo->user_id)->find();
    				$comid=$cominfo->com_id;
    				if($comid){
    					$ser=new Service_User_Company_Project();
    					$ishasproject=$ser->isHasProject($comid);
    				}
    				$content->ishasproject=$ishasproject;
    				$service=new Service_Platform_SearchInvestor();
    				//获取用户基本信息
    				$result=$service->getPerInfoByID($userid,$login_userid);
    				//获取从业经验
    				$ser_per_user = new Service_User_Person_User();
    				$experiences = $ser_per_user->listExperience($userid);
    				$service_user= new Service_User();
    				if(!empty($experiences)){
    					foreach( $experiences as $k=>$v ){
    						//行业类别
    						$rs_profession= $service_user->getProfessionRow( $v['exp_industry_sort'] );
    						$experiences[$k]['exp_industry_sort_name']= $rs_profession['profession_name'];
    						//职业类别
    						$rs_pos= $service_user->getPositionRow( $v['exp_occupation_type'] );
    						$experiences[$k]['pos_name']= $rs_pos['position_name'];
    						//职业名称
    						if( $v['exp_occupation_name']!='0' ){
    							$rs_pos= $service_user->getPositionRow( $v['exp_occupation_name'] );
    							$experiences[$k]['occ_name']= $rs_pos['position_name'];
    						}else{
    							$experiences[$k]['occ_name']= $v['exp_user_occupation_name'];
    						}
    					}
    					$content->experiences = $experiences;
    				}
    				$content->result = $result;
    			}else{//个人用户不存在
    				if(!$sid){
    					self::redirect("zhaotouzi/");
    				}else{
    					$url=URL::website('zhaotouzi/').'?sid='.$sid;
    					Header("HTTP/1.1 303 See Other");
    					Header("Location: $url");exit;
    				}
    			}
    			 
    		}else{//个人用户
    			if(!$sid){
    				self::redirect("zhaotouzi/");
    			}else{
    				$url=URL::website('zhaotouzi/').'?sid='.$sid;
    				Header("HTTP/1.1 303 See Other");
    				Header("Location: $url");exit;
    			}
    		}
    	}else{ //未登录用户
    		if(!$sid){
    			self::redirect("zhaotouzi/");
    		}else{
    			$url=URL::website('zhaotouzi/').'?sid='.$sid;
    			Header("HTTP/1.1 303 See Other");
    			Header("Location: $url");exit;
    		}
    	}
    }
    
    /**
     * 搜索投资者  列表
     * @author 赵路生
     */
    public function action_showSearchList(){
    	$content = View::factory('platform/searchinvestor/show_person_searchlist');//默认拿数据到这个页面
    	$service=new Service_Platform_SearchInvestor();
    	//获取用户基本信息
    	//获取前面搜索出来的结果，一个装着搜索到的投资者的user_id的数组
    	$userid_list = array(644,84102,84086,84088,84087,84073,84079,84077,84070,84068,84067);
    	$islogin=$this->loginUser(); //是否登录
    	if($islogin){//如果已登录，获取用户名
    		$userinfo=$this->userInfo(); //返回用户的基本信息
    		$login_userid = $userinfo->user_id; //返回当前登录用户的user_id
    		if($userinfo->user_type == 1){//企业用户
    			$content = View::factory('platform/searchinvestor/show_company_searchlist');
    			$result=$service->getPerBasicInfoByID($userid_list,$login_userid);
    			$content->login_userid = $login_userid;
    		}else{//个人用户
    			$result=$service->getPerBasicInfoByIDNotlogin($userid_list);
    		}
    		$content->islogin=true;
    	}else{//未登录
    		$result=$service->getPerBasicInfoByIDNotlogin($userid_list);
    		$content->islogin=false;
    	}
    	//第一次返回热门标签[以后的换一批热门标签通过ajax获取]
    	$tag = $service->findTag();
    	$time = $service->getPrevCurrentTime();
    	$this->content->maincontent = $content;
    	$content->result = $result;
    	$content->tag= $tag;
    	$content->time= $time;
    	//     	echo"<pre>";
    	//     	print_r($result);
    	//     	exit();
    }
}