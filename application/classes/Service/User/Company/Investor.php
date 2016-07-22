<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户中心投资者搜索
 * @author 龚湧
 *
 */
class Service_User_Company_Investor{
	/**
	 * 搜索到的投资者名片信息列表
	 * @param  [int] $type [1:获取列表信息 2：获取总数据行数]
	 * @param  [array] $search [post获取当前页面搜索条件]
	 * @param  [int] $userid [用户id]
	 * @author 钟涛
	 */
	public function searchInvestorInfoNew($type,$search,$userid){
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
		//对投资者个人 意向投资地区 进行筛选
		$perareamodel= ORM::factory('PersonalArea');
		$perarea_search_row = $perareamodel->getSearchRow();
		foreach($perarea_search_row as $key => $value){
			if(isset($search[$key]) AND $search[$key] != ''){
				//筛选条件（投资地区）
				$permodel->where($key, '=', $search[$key]);
			}
		}
		$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
		$permodel->where('per_realname','!=',''); //姓名不为空的
		$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
		$permodel->group_by('per_user_id');
		//个人详细信息表数据
		$total_count=$permodel->select('*')->reset(false)->find_all();
		if($type==2){//返回总行数
			return count($total_count);
		}else{
            $page = Pagination::factory(array(
                    'total_items'    => count($total_count),
                    'items_per_page' => 10,
            ));
			//更新|筛选筛选条件记录
			if(count($search)){
				$this->updateSearchConditions(count($total_count),$search,$userid);
			}
			$pagelist=$permodel->select('*')->limit($page->items_per_page)->offset($page->offset)->order_by('per_createtime','DESC')->find_all( );
			return array(
					'page' => $page,
					'list' =>$this->addResultData($pagelist,$userid,false),
					'total_count'=>count($total_count)
			);
		}
	}
	
     /**
     * 搜索到的投资者名片信息列表
     * @param  [int] $type [1:获取列表信息 2：获取总数据行数]
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [用户id]
     * @param  [int] $urlpage [页数]
     * @author 钟涛
     */
    public function searchInvestorInfo($type,$search,$userid,$urlpage){
    	unset($search['x']);
    	unset($search['y']);
    	//缓存配置
    	$memcache = Cache::instance('memcache');
    	$cacheId = '_Investor_search1_';
    	foreach($search as $key => $value){
    		if(arr::get($search,$key,'')!= ''){
    			$cacheId=$cacheId.$key.arr::get($search,$key,'').'_';
    		}
    	}
    	try {
    		$personlistArr = $memcache->get($cacheId);
    	}
    	catch (Cache_Exception $e) {
    		$personlistArr = array();
    	}
    	if(!$personlistArr) {
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
    		//对投资者个人 意向投资地区 进行筛选
    		$perareamodel= ORM::factory('PersonalArea');
    		$perarea_search_row = $perareamodel->getSearchRow();
    		foreach($perarea_search_row as $key => $value){
    			if(isset($search[$key]) AND $search[$key] != ''){
    				//筛选条件（投资地区）
    				$permodel->where($key, '=', $search[$key]);
    			}
    		}
    		$permodel->where('per_open_stutas','!=','3'); //不包括名片公开度为对所有行业都关闭
    		$permodel->where('per_amount','!=','0'); //不包括没有完善个人基本信息的数据
    		//个人详细信息表数据
    		$personlistObject= $permodel->select('*')->group_by('per_user_id')->order_by('per_createtime','DESC')->find_all( );
    		//获取企业一级行业
    		$arrProjectIndusty = ORM::factory('ProjectSearchCard')->select('parent_id')->where('user_id','=',$userid)->where('project_status','=',2)->find_all();
    		$returnProjectIndustyarr=array();//所有1级行业
    		$returnProjectIndustyarr2=array();//所有2级行业
    		foreach ($arrProjectIndusty as $v){
    			$returnProjectIndustyarr[] = $v->parent_id;
    			$returnProjectIndustyarr2[]=$v->project_industry_id;
    		}
    		$personlistArr=array();
    		foreach ($personlistObject as $key =>$val){
    			if($val->per_open_stutas  == 1){//公开度:所有行业
    				$personlistArr[] = $val->as_array();
    			}elseif($val->per_open_stutas  == 2) {//公开度:意向投资行业用户
    				$this_perindustrymodel= ORM::factory('UserPerIndustry')->select('*')->where('user_id','=',$val->per_user_id)->find_all();
    				$t=1;$t_v='';
    				//获取个人意向投资行业
    				foreach ($this_perindustrymodel as $this_v){
    					$per_i[$t]=$this_v->industry_id;
    					$t++;
    				}
    				if(isset($per_i[1]) && isset($per_i[2]) && $per_i[1]>$per_i[2]){//有2级行业 获取较大的值为2级行业id
    					$t_v=$per_i[1];
    				}elseif(isset($per_i[1]) && isset($per_i[2]) && $per_i[1]<$per_i[2]){//有2级行业 获取较大的值为2级行业id
    					$t_v=$per_i[2];
    				}elseif(isset($per_i[1])){//没有2级行业
    					$t_v=$per_i[1];
    				}else{}
    				//先判断2级
    				if($t_v<8){//没有2级行业
    					if(in_array($t_v, $returnProjectIndustyarr)){
    						$personlistArr[] = $val->as_array();
    					}
    				}else{//有2级行业
    					if(in_array($t_v, $returnProjectIndustyarr2)){
    						$personlistArr[] = $val->as_array();
    					}
    				}
    			}elseif($val->per_open_stutas == 4) {//对诚信认证的企业公开的投资者
    			}else{	}
    		}
    		$memcache->set($cacheId, $personlistArr);
    	}
	    $total_count=count($personlistArr);
        if($type==2){//返回总行数
            return $total_count;
        }else{
            $page_size = 10; //分页大小
            $page = Pagination::factory(array(
                    'total_items'    => $total_count,
                    'items_per_page' => $page_size,
            ));
            //更新|筛选筛选条件记录
            if(count($search)){
                $this->updateSearchConditions($total_count,$search,$userid);
            }
            //分页取10条数据
            $pagelist = array_slice($personlistArr, ($urlpage-1)*$page_size, $page_size);
            return array(
                    'page' => $page,
                    'list' =>$this->addResultData($pagelist,$userid),
                    'total_count'=>$total_count
            );
        }
    }

     /**
     * 对搜索的投资的者加上相关信息 是否已经给此用户发送名片+是否与此用户交换名片+此用户是否添加从业经验
     * @author 钟涛
     */
    function addResultData($personlist,$userid,$arr=true){
    	$userid=intval($userid);
        $userlist=array();
        $resultlist=array();
        $user_service=new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        $service = new Service_User_Person_User();
        foreach ($personlist as $list){
        	if($arr==false){//传过来的是对象
        		$list=$list->as_array();
        	}
            //个人投行业和
            $userlist['per_industry_string']=$service->getPersonalIndustryString($list['per_user_id']);
            //判断是否已交换名片
            if($card_service->getExchaneCardCountByTwoIdAll($list['per_user_id'], $userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($list['per_user_id'], $userid)){
                $userlist['exchangecardcount'] = 1;//已经与此用户交换名片
            }
            elseif($card_service->getOutCardCountByTwoIdAll($list['per_user_id'], $userid)){//已发送
                $userlist['exchangecardcount'] = 0;
                $userlist['outcardcount'] = 1;//已经给此用户发送名片
                $userlist['receivedcardcount'] = 0;//没有收到
                $query1 = DB::select()->from('card_info')->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list['per_user_id'])->and_where('exchange_status', '=', 0);
                //$sql = " FROM czzs_card_info where from_user_id = ".$userid." and to_user_id = ".$list['per_user_id']." and exchange_status=0 ";
                $result = $query1->execute()->as_array();
                $userlist['cardinfo'] = $result[0];
                $userlist['cardinfo']['card_type']=2;//我递出的名片
            }elseif($card_service->getReceiveCardCountByTwoIdAll($list['per_user_id'], $userid)){//已收到
                $userlist['exchangecardcount'] = 0;
                $userlist['outcardcount'] = 0;
                $userlist['receivedcardcount'] = 1;//已经收到
                $query1 = DB::select()->from('card_info')->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list['per_user_id'])->and_where('exchange_status', '=', 0);
                //$sql = " FROM czzs_card_info where to_user_id = ".$userid." and from_user_id = ".$list['per_user_id']." and exchange_status=0 ";
                $result = $query1->execute()->as_array();
                $userlist['cardinfo'] = $result[0];
                $userlist['cardinfo']['card_type']=1;//我收到的名片
            }else{
                $userlist['receivedcardcount'] = 0;//没有收到
                $userlist['outcardcount'] = 0;//没有递出
                $userlist['exchangecardcount'] = 0;//没有与此用户交换名片
            }
            //判断用户是否添加从业经验
            if($user_service->getExperienceCount($list['per_user_id'])) {
                $userlist['per_experience_stutas']=1;//此用户已经添加从业经验
            }else{
                $userlist['per_experience_stutas']=0;//此用户没有添加从业经验
            }
            //判断收藏表中对应的数据
            if ($card_service->getFavoriteStatus($userid,$list['per_user_id'])==TRUE)
                $userlist['favorite_status']=1;//已存在对应收藏关系
            else
                $userlist['favorite_status']=0;//无收藏
            $resultlist[] = array_merge($list,$userlist);
        }
        return $resultlist;
    }

     /**
     * 保存搜索投资者筛选条件记录(1.此记录不存在做添加动作 2.此记录已存在则做更新动作)
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [当前用户id]
     * @author 钟涛
     */
    function updateSearchConditions($total_count,$search,$userid){
        $data=ORM::factory('SearchConditions');
        $result=$this->getSearchConditionsConut($data,$search,$userid);
        if($result['user_id']!=''){//更新筛选记录
            $data->total_count = $total_count;//搜索到的投资者总人数
            $data->hiddenvalue=isset($search['hiddenvalue']) && $search['hiddenvalue']=='1' ? 1 : 0;//是否显示高级筛选
            $data->update_time = time();//更新时间
            $data->update();
        }else{//添加新的一条筛选记录信息
            $data->user_id = $userid;//用户id
            if(arr::get($search,'industry_id','')!=""){
                $data->per_industry= isset($search['industry_id']) && $search['industry_id']!='' ? $search['industry_id'] : 0;//2级投资行业id
            }else{
                $data->per_industry= isset($search['parent_id']) && $search['parent_id']!='' ? $search['parent_id'] : 0;//1级投资行业id
            }
            $data->per_amount = isset($search['per_amount']) && $search['per_amount']!='' ? $search['per_amount'] : 0 ;//投资金额id
            $data->per_identity = isset($search['per_identity']) && $search['per_identity']!='' ? $search['per_identity'] : 0 ;//投资身份id
            if(arr::get($search,'area_id','')!=""){
                $data->area_id= isset($search['area_id']) && $search['area_id']!='' ? $search['area_id'] : 0;//城市
            }else{
                $data->area_id= isset($search['pro_id']) && $search['pro_id']!='' ? $search['pro_id'] : 0;//省份
            }
            $data->per_join_project = isset($search['per_join_project']) && $search['per_join_project']!='' ? $search['per_join_project'] : 0 ;//投资者加盟项目方式
            $data->per_connections = isset($search['per_connections']) && $search['per_connections']!='' ? $search['per_connections'] : 0;//人脉关系id
            $data->per_investment_style=isset($search['per_investment_style']) && $search['per_investment_style']!='' ? $search['per_investment_style'] : 0;//个人投资风格id
            $data->hiddenvalue=isset($search['hiddenvalue']) && $search['hiddenvalue']=='1' ? 1 : 0;//是否显示高级筛选
            $data->total_count = $total_count;//搜索到的投资者总人数
            $data->add_time = time();//添加时间
            $data->update_time = time();//更新时间
            $data->create();
        }
    }

     /**
     * 获取筛选条件记录
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [当前用户id]
     * @author 钟涛
     */
    function getSearchConditionsConut($model,$search,$userid){
         if(arr::get($search,'industry_id','')!=""){
             $data_industry_id= isset($search['industry_id']) && $search['industry_id']!='' ? $search['industry_id'] : 0;//2级投资行业id
         }else{
             $data_industry_id= isset($search['parent_id']) && $search['parent_id']!='' ? $search['parent_id'] : 0;//1级投资行业id
         }
         if(arr::get($search,'area_id','')!=""){
             $data_area_id= isset($search['area_id']) && $search['area_id']!='' ? $search['area_id'] : 0;//城市
         }else{
             $data_area_id= isset($search['pro_id']) && $search['pro_id']!='' ? $search['pro_id'] : 0;//省份
         }
         return $model->where('user_id', '=', $userid)//当前用户
         ->where('per_industry','=',$data_industry_id)//投资行业id
         ->where('per_amount','=',isset($search['per_amount']) && $search['per_amount']!='' ? $search['per_amount'] : 0 )//投资金额id
         ->where('per_identity','=',isset($search['per_identity']) && $search['per_identity']!='' ? $search['per_identity'] : 0 )//投资身份id
         ->where('area_id','=',$data_area_id)//投资地区id
         ->where('per_join_project','=',isset($search['per_join_project']) && $search['per_join_project']!='' ? $search['per_join_project'] : 0 )//投资者加盟项目方式
         ->where('per_connections','=',isset($search['per_connections']) && $search['per_connections']!='' ? $search['per_connections'] : 0)//人脉关系id
         ->where('per_investment_style','=',isset($search['per_investment_style']) && $search['per_investment_style']!='' ? $search['per_investment_style'] : 0)//个人投资风格id
         ->find()->as_array();
    }

     /**
     * 筛选搜索投资者列表信息
     * @author 钟涛
     */
    public function searchConditionsList($userid){
        $model= ORM::factory('SearchConditions');
        $model->where('user_id','=',$userid); //当前用户
        $total_count=$model->reset(false)->count_all();
        $page = Pagination::factory(array(
                'total_items'    =>$total_count,
                'items_per_page' => 10,
        ));
        //筛选历史记录信息
        $list= $model->select('*')->limit($page->items_per_page)->offset($page->offset)->order_by('update_time','DESC')->find_all( );
        return array(
                'page' => $page,
                'list' => $this->addGroupConditions($list,$userid),
                'totalcount' =>$total_count,
        );
    }

     /**
     * 筛选搜索投资者列表信息
     * @author 钟涛
     */
    public function addGroupConditions($list,$userid){
         $resultlist=array();
         $addlist=array();
         $monarr= common::moneyArr();//投资金额
         $identityarr= common::perIdentityArr();//投资身份
         $join_projectarr= common::joinProjectArr();//投资者加盟项目方式
         $connectionsarr= common::connectionsArr();//人脉关系
         $per_investment_stylearr= common::investmentStyle();//个人投资风格
         foreach ($list as $v){
             if($v->per_industry==0 && $v->per_amount==0 && $v->per_identity==0 && $v->area_id==0 && $v->per_join_project==0 && $v->per_connections==0 && $v->per_investment_style==0){
                 $addlist['group']='搜索所有的投资者';
                 $addlist['url']=URL::site('/company/member/investor/search').'?per_industry=&per_amount=&per_identity=&per_join_project=&per_connections=&per_investment_style=';
                 $newcount= $this->searchInvestorInfoNew(2,array(),$userid) - $v->total_count;
                 if($newcount<0){
                     $newcount=0;
                 }
                 $addlist['nowtotalcount']=$newcount;
             }else{
                 $group='';//筛选的条件组合输出
                 $url=URL::site('/company/member/investor/search').'?';//url地址添加筛选条件
                 if($v->per_industry != 0){//投资行业
                     if($v->per_industry>7){//2级行业
                         $industrylist2=ORM::factory("industry")->where('industry_id','=',$v->per_industry)->find();
                         $group .= '“'.$industrylist2->industry_name.'” + ';
                     }else{//1级行业
                         $industrylist = common::primaryIndustry(0,$v->per_industry);//1级投资行业
                         $group .= '“'.$industrylist[0]->industry_name.'” + ';
                     }
                 }
                 if($v->per_amount!=0){//投资金额
                     $group .= '“'.$monarr[$v->per_amount].'” + ';
                 }
                 if($v->per_identity!=0){//投资身份
                     $group .= '“'.$identityarr[$v->per_identity].'” + ';
                 }
                 if($v->area_id != 0){//投资地区
                     $citys=ORM::factory("City")->where('cit_id','=',$v->area_id)->find();
                     $group .= '“'.$citys->cit_name.'” + ';
                 }
                 if($v->per_join_project!=0){//投资者加盟项目方式
                     $group .= '“'.$join_projectarr[$v->per_join_project].'” + ';
                 }
                 if($v->per_connections!=0){//人脉关系
                     $group .= '“'.$connectionsarr[$v->per_connections].'” + ';
                 }
                 if($v->per_investment_style!=0){//个人投资风格
                     $group .= '“'.$per_investment_stylearr[$v->per_investment_style].'” + ';
                 }
                 $newserarch=$this->getNewSerarch($v->as_array());
                 if($newserarch['per_industry']>7){
                     $one_ser=new Service_User_Company_Project();
                     $one_per_industry=$one_ser->getParentid($newserarch['per_industry']);
                     $per_url='industry_id='.$newserarch['per_industry'].'&parent_id='.$one_per_industry;
                 }else{
                     $per_url='parent_id='.$newserarch['per_industry'];
                 }
                 if($newserarch['area_id']>100){
                     $area_ser=new Service_Public();
                     $one_area_id=$area_ser->getProidByCityid($newserarch['area_id']);
                     $area_url='&area_id='.$newserarch['area_id'].'&pro_id='.$one_area_id;
                 }else{
                     $area_url='&pro_id='.$newserarch['area_id'];
                 }
                 $resulturl = $url.$per_url.$area_url.'&per_amount='.$newserarch['per_amount'].'&per_identity='.$newserarch['per_identity'].'&per_join_project='.$newserarch['per_join_project'].'&per_connections='.$newserarch['per_connections'].'&per_investment_style='.$newserarch['per_investment_style'].'&hiddenvalue='.$newserarch['hiddenvalue'];

                 $group = substr($group,0,strlen($group)-3).'的投资者';
                 $newcount=$this->searchInvestorInfoNew(2,$newserarch,$userid) - $v->total_count;
                 if($newcount<0){
                     $newcount=0;
                 }
                 $addlist['nowtotalcount']= $newcount;
                 $addlist['url']= $resulturl;
                 $addlist['group']=$group;
             }
             $resultlist[] = array_merge($v->as_array(),$addlist);
         }
        return $resultlist;
    }

    /**
     * 根据搜索记录表构建搜索条件
     * @param  [object] $v [搜索记录数据]
     * @author 钟涛
     */
    public function getNewSerarch($search){
        return $newserarch=array(
                'per_industry' =>isset($search['per_industry']) && $search['per_industry']!='0' ? $search['per_industry'] : '',//投资行业id
                'area_id' =>isset($search['area_id']) && $search['area_id']!='0' ? $search['area_id'] : '',//城市
        		'parent_id' =>isset($search['parent_id']) && $search['parent_id']!='0' ? $search['parent_id'] : '',//投资行业id
        		'industry_id' =>isset($search['industry_id']) && $search['industry_id']!='0' ? $search['industry_id'] : '',
        		'pro_id' =>isset($search['pro_id']) && $search['pro_id']!='0' ? $search['pro_id'] : '',//省份
                'per_amount' => isset($search['per_amount']) && $search['per_amount']!='0' ? $search['per_amount'] : '' , //投资金额id,
                'per_identity' => isset($search['per_identity']) && $search['per_identity']!='0' ? $search['per_identity']:'',//投资身份id
                'per_join_project' => isset($search['per_join_project']) && $search['per_join_project']!='0' ? $search['per_join_project'] : '',//投资者加盟项目方式
                'per_connections' => isset($search['per_connections']) && $search['per_connections']!='0' ? $search['per_connections'] : '',//人脉关系id
                'per_investment_style' => isset($search['per_investment_style']) && $search['per_investment_style']!='0' ? $search['per_investment_style'] : '',//个人投资风格id
                'hiddenvalue' => isset($search['hiddenvalue']) && $search['hiddenvalue']=='1' ? 1 : 0,//是否显示高级筛选
        );
    }

     /**
     * 删除搜索记录
     * @param  [array] $arrid [当前页面的搜索记录id]
     * @author 钟涛
     */
    public function deleteConditionsByArr($arrid=array()){
        foreach ($arrid as $id){
            $model= ORM::factory('SearchConditions', $id);
            if($model->loaded()){
                $model->delete();
            }
        }
    }

     /**
     * 获取最新的一条筛选记录
     * @param  [int] $user_id [当前用户id]
     * @author 钟涛
     */
    public function getOneConditions($user_id){
       return ORM::factory('SearchConditions')->where('user_id','=',$user_id)->order_by('update_time', 'DESC')->limit(1)->find();
    }

    /**
     * 获取用户是否已经订阅邮件
     * @author 施磊
     * @param  int $userId 当前用户id
     * @return bool TRUE or FALSE
     */
    public function getUserSubscriptionStatus($userId){
        if(!intval($userId)) return FALSE;
        //先获得数据
       $data = ORM::factory('Subscription')->where('subscription_user_id','=',$userId)->find();
       //判断是否已开通
       return $data->subscription_status ? TRUE : FALSE;
    }

    /**
     * 查看存在用户和是否已经订阅
     * @author 施磊
     * @param  int $userId 当前用户id
     */
    public function getUserSubscriptionByUserId($userId) {
        if(!intval($userId)) return FALSE;
        //先获得数据
       $data = ORM::factory('Subscription')->where('subscription_user_id','=',$userId)->find();
       return $data;
    }

    /**
     * 检查邮件key和id是否合法
     * @author 施磊
     * @param  int $userId 当前用户id
     * @param  int $key 邮件的key
     */
    public function checkUserSubscriptionByIdAndKey($userId, $key) {
        if(!intval($userId) || !$key) return FALSE;
        $data = ORM::factory('Subscription')->where('subscription_user_id','=',$userId)->where('subscription_email_key', '=', $key)->count_all();
        return $data ? TRUE : FALSE;
    }

    /**
     * 用户订阅投资者
     * @author 施磊
     * @param  int $userId 当前用户id
     */
    public function updateUserSubscriptionStatus($userId ,$status = 1) {
        if(!intval($userId)) return FALSE;
        $ormModel = ORM::factory('Subscription');
        $result = $this->getUserSubscriptionByUserId($userId);
        //必须存在此记录 并且用户未订阅
        if($result->subscription_id && $result->subscription_status != $status){
            $result->subscription_status = $status;
            $result->subscription_time = time();
            //订阅后七天才发送符合的投资者
            $result->subscription_next_time = time() + 86400*7;
            $result->update();
        }else if(!$result->subscription_id){
            //不存在记录 就添加
            $ormModel->subscription_user_id = $userId;
            $ormModel->subscription_time = time();
            $ormModel->subscription_next_time = time() + 86400*7;
            $ormModel->subscription_status = $status;
            $ormModel->subscription_email_key = md5(rand(0, 999).time());
            $ormModel->create();
        }
        //还有一种状况就是存在并且已经订阅。。。不操作
        return TRUE;
    }

    /**
     * 根据id修改数据
     * @author 施磊
     * @param  int $id 数据id
     */
    public function updateUserSubscription($id, $param) {
        if(!intval($id)) return FALSE;
        $ormModel = ORM::factory('Subscription', intval($id));
        $ormModel->values($param)->check();
        $ormModel->update();
    }

    /**
     * 根据id修改数据
     * @author 施磊
     * @param  int $user_id 用户id
     */
    public function updateUserSubscriptionByUserId($user_id, $param) {
        if(!intval($user_id)) return FALSE;
        $ormModel = ORM::factory('Subscription')->where('subscription_user_id', '=', $user_id)->find();
        $ormModel->values($param)->check();
        $ormModel->update();
    }

    /**
     * 获得今天要发送订阅的用户
     * @author 施磊
     */
    public function getNowSendUserSubscription() {
        $dateCheck = $this->_getTodayTime();
        $data = ORM::factory('Subscription')->where('subscription_status', '=' , 1)->where('subscription_next_time', '>=', $dateCheck['begin'])->where('subscription_next_time', '<=', $dateCheck['end'])->find_all();
        return $data;
    }

    /**
     * 订阅脚本需要的数据
     * @author 施磊
     */
    public function searchConditionsListForCron($userid){
        $model= ORM::factory('SearchConditions');
        $model->where('user_id','=',$userid);
        //筛选历史记录信息
        $listObj = $model->select('*')->order_by('update_time','DESC')->find_all();
        $list = $this->addGroupConditions($listObj,$userid);
        return $list;
    }

    /**
     * 处理日期的小函数
     * 获得今天的开始时间和结束时间
     * @author 施磊
     * @return $return 一个今天开始的时间戳，一个今天结束的时间戳
     */
    private function _getTodayTime() {
        $time = time();
        $date = date("Y-m-d", $time);
        $begin = strtotime($date) + 1;
        $end = $begin + 86400;
        return array('begin' => $begin, 'end' => $end);
    }

}