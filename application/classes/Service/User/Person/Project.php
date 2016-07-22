<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人项目信息
 * @author 钟涛
 *
 */
class Service_User_Person_Project{
    /**
     * 获取个人收藏企业项目总数量
     * @author 钟涛
     */
    public function getProjectWatchAllCount($user_id){
        return ORM::factory('Projectwatch')
        ->where('watch_user_id', '=', $user_id)//用户id
        ->where('watch_status','=',1)//默认1启用
        ->count_all();
    }//funtion end

    /**
     * 筛选我收藏的项目
     * @author 钟涛
     * @return array
     */
    public function searchWatchProjecList($form,$userid){
        $model= ORM::factory('Projectwatch');//主表
        //关联项目表
        $model->join('project_search_card','LEFT')->on('project_search_card.project_id','=','projectwatch.watch_project_id');
        //关联项目地区表
        $model->join('project_area','LEFT')->on('project_area.project_id','=','projectwatch.watch_project_id');
        //对主表筛选
        $model->where('watch_user_id', '=', $userid);//当前用户
        $model->where('watch_status', '=', 1);//启用状态
        $Projectinvestmodel= ORM::factory('Projectwatch');
        $search_row = $Projectinvestmodel->getSearchRow();
        foreach($search_row as $key => $value){
            if(isset($form[$key]) AND $form[$key] != ''){
                if($key=='watch_update_time'){//收藏时间
                    if($form[$key]=='10000') {//半年以上
                        $model->where($key, '<=', time()-(180*24*60*60));
                    }else{//其他的收藏项目时间：1天、2天。。等
                        $model->where($key, '>=', time()-($form[$key]*24*60*60));
                    }
                }
            }
        }
        //对副表筛选[项目行业和项目金额]
        $ProjectSearchCardmodel= ORM::factory('ProjectSearchCard');
        $search_row2 = $ProjectSearchCardmodel->getSearchRow();
        foreach($search_row2 as $key => $value){
            if(isset($form[$key]) AND $form[$key] != ''){
                $model->where($key, '=', $form[$key]);
            }
        }
        //对副表筛选[项目地区]
        $ProjectAreamodel= ORM::factory('Projectarea');
        $search_row3 = $ProjectAreamodel->getSearchRow();
        foreach($search_row3 as $key => $value){
            if(isset($form[$key]) AND $form[$key] != ''){
                $model->where($key, '=', $form[$key]);
            }
        }
        $total_count=$model->reset(false)->select("*")->group_by('watch_project_id')->find_all( )->as_array();
        $page = Pagination::factory(array(
                'total_items'    => count($total_count),
                'items_per_page' => 6,
        ));
        $listArr=$model->select("*")->group_by('watch_project_id')->limit($page->items_per_page)->offset($page->offset)->order_by('watch_update_time', 'DESC')->find_all( );
        $arr=array();
        $arr['list'] = $this->addResultData($listArr,$userid);
        $arr['page']= $page;
        return $arr;
    }

    /**
     * 对收藏的项目判断 是否已对项目发送名片+是否交换名片+是否已收到名片
     * @author 钟涛
     */
    function addResultData($listArr,$userid){
    	$userid=intval($userid);
        $userlist=array();
        $resultlist=array();
        $card_service=new Service_User_Company_Card();
        $projectservice=new Service_User_Company_Project();
        $platservice = new Service_Platform_Project();
        foreach ($listArr as $list){
        	$project=ORM::factory('Project')->where('project_id','=',$list->watch_project_id)->find();
        	$com_user_id=ORM::factory('Companyinfo',$project->com_id)->com_user_id;
        	$list->user_id=$com_user_id;
            //判断是否已交换名片
//             if($card_service->getExchaneCardCountByTwoIdAll($list->user_id, $userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($list->user_id, $userid)){
//                 $userlist['card_type']=3;//已经交换
//             }
//             elseif($card_service->getOutCardCountByTwoIdAll($list->user_id, $userid)){//已发送
//                 $userlist['card_type']=2;//已递出
//                 $query1 = DB::select()->from('card_info')->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list->user_id)->and_where('exchange_status', '=', 0);
//                 //$sql = " FROM czzs_card_info where from_user_id = ".$userid." and to_user_id = ".$list->user_id." and exchange_status=0 ";
//                 $result = $query1->execute()->as_array();
//                 $userlist['cardinfo'] = $result[0];
//             }elseif($card_service->getReceiveCardCountByTwoIdAll($list->user_id, $userid)){//已收到
//                 $userlist['card_type']=1;//已收到
//                 $query1 = DB::select()->from('card_info')->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list->user_id)->and_where('exchange_status', '=', 0);
//                 //$sql = " FROM czzs_card_info where to_user_id = ".$userid." and from_user_id = ".$list->user_id." and exchange_status=0 ";
//                 $result = $query1->execute()->as_array();
//                 $userlist['cardinfo'] = $result[0];
//             }else{
//                 unset($userlist['cardinfo']);
//                 $userlist['card_type']=4;//无记录
//             }
			if( $project->project_advert){
				$userlist['project_name']=$project->project_advert;//推广语
			}else{
            	$userlist['project_name']=$project->project_brand_name;//项目名
			}
            if($project->project_source != 1) {
            	$userlist['project_logo']=$project->project_logo;//项目logo
            }else{
            	$userlist['project_logo']=URL::imgurl($project->project_logo);//项目logo
            }
            $userlist['project_summary']=$project->project_summary;//项目简介
            $userlist['project_source']=$project->project_source;//项目来源
            $userlist['project_approving_count']=$project->project_approving_count;//赞的数量
            $userlist['project_pv_count']=$project->project_pv_count;//意向加盟的数量
           
            $invest = $platservice->getProjectInvest($list->watch_project_id);
            $userlist['invest_id']=arr::get($invest,'investment_id');//投资考察id
            $userlist['investment_start']=arr::get($invest,'investment_start');//投资考察
            $userlist['project_zhaoshang']=$projectservice->getProjectCoModel($project->project_id);//招商形式
            $userlist["outside_id"]=$project->outside_id;//项目来源
            $userlist["com_id"]=$project->com_id;//公司id
            $userlist["user_id"]=$com_user_id;
            //项目行业
            $userlist['pro_industry']=$projectservice->getProjectindustry($list->watch_project_id);
            $resultlist[] = array_merge($list->as_array(),$userlist);
        }
        return $resultlist;
    }

    /**
     * 获取我收藏的项目列表
     * @author 钟涛
     * @return array
     */
    public function getWatchProjecList($userid){
        $projectlist=array();
        $resultlist=array();
        $model= ORM::factory('Projectwatch');//主表
        //对主表筛选
        $model->where('watch_user_id', '=', $userid);//当前用户
        $model->where('watch_status', '=', 1);//启用状态
        $listArr=$model->select("*")->order_by('watch_update_time', 'DESC')->find_all( );
        $projectservice=new Service_User_Company_Project();
        foreach ($listArr as $list){
            $project=ORM::factory('Project')->where('project_id','=',$list->watch_project_id)->find();
            $projectlist['project_name']=$project->project_brand_name;//项目名
            if($project->project_source != 1) {
                $projectlist['project_logo']=$project->project_logo;//项目logo
            }else{
            	$projectlist['project_logo'] = URL::imgurl($project->project_logo);
            }
            $projectlist['project_summary']=$project->project_summary;//项目简介
            $projectlist['project_amount_type']=$project->project_amount_type;//投资金额
            $projectlist['project_source']=$project->project_source;//项目来源
            $projectlist["outside_id"]=$project->outside_id;//项目来源
            //项目行业
            $projectlist['pro_industry']=$projectservice->getProjectindustry($list->watch_project_id);
            $resultlist[] = array_merge($list->as_array(),$projectlist);
        }
        return $resultlist;
    }

    /**
     * 获取我筛选项目历史记录
     * @author 钟涛
     * @return array
     */
    public function getSearchProjecList($userid){
        $projectlist=array();
        $resultlist=array();
        $model= ORM::factory('SearchProjectConditions');//主表
        $listArr=$model->where('user_id', '=', $userid)->order_by('update_time', 'DESC')->find_all( );
        foreach ($listArr as $list){
             $contenttext="";
             $listarr=$list->as_array();
             for($a=1;$a<=10;$a++) {//循环对10个问题组合
                 if($listarr['question'.$a.'_id']!=0){
                    $str=$this->getSpecifiedAttribute($a);
                    if($a!=2){
                        $question_st='';
                        if(isset($str[$listarr['question'.$a.'_id']])){
                            $question_st=$str[$listarr['question'.$a.'_id']];
                        }
                        $contenttext=$contenttext.'“'.$question_st.'”+';
                    }else{
                        $contenttext=$contenttext.'“'.ORM::factory("City",$listarr['question'.$a.'_id'])->cit_name.'”+';
                    }
                 }
            }
            if(!empty($contenttext)){
                $projectlist['content']=substr($contenttext, 0, strlen($contenttext)-1);
            }else{
                $projectlist['content']='';
            }
            $resultlist[] = array_merge($list->as_array(),$projectlist);
        }
        return $resultlist;
    }

    public function getSpecifiedAttribute($q_id){
        if(!is_numeric($q_id) || ($q_id >10)){
            return false;
        }
        switch ($q_id){
            case 1:
                $str=guide::attr1();
                break;
            case 2:
                $str=array();
                break;
            case 3:
                $str=guide::attr3();
                break;
            case 4:
                $str=guide::attr4();
                break;
            case 5:
                $str=guide::attr5();
                break;
            case 6:
                $str=guide::attr6();
                break;
            case 7:
                $str=guide::attr7();
                break;
            case 8:
                $str=guide::attr8();
                break;
            case 9:
                $str=guide::attr9();
                break;
            default:
                $str=guide::attr10();
                break;
        }
        return $str;
    }
    /**
     * 保存搜索项目筛选条件记录
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [当前用户id]
     * @author 钟涛
     */
    function updateSearchConditions($total_count,$search,$userid){
        $data=ORM::factory('SearchProjectConditions');
        $result=$this->getSearchConditionsConut($data,$search,$userid);
        if($result['user_id']!=''){//更新筛选记录
            $data->total_count = $total_count;//搜索到的总项目数量
            $data->update_time = time();//更新时间
            $data->update();

        }else{//添加新的一条筛选记录信息
            if($this->getSearchAllCount($userid)>9){
                //删除最早一条筛选记录[最多存储10条记录]
                $deletedata=ORM::factory("SearchProjectConditions")->where('user_id',"=",$userid)->order_by('update_time', 'ASC')->limit(1)->find_all();
                foreach($deletedata as $v){
                    $model= ORM::factory('SearchProjectConditions', $v->id)->delete();
               }
            }
            $data->user_id = $userid;//用户id
            $data->question1_id= isset($search['question1_id']) && $search['question1_id']!='' ? $search['question1_id'] : 0;
            $data->question2_id= isset($search['question2_id']) && $search['question2_id']!='' ? $search['question2_id'] : 0;
            $data->question3_id= isset($search['question3_id']) && $search['question3_id']!='' ? $search['question3_id'] : 0;
            $data->question4_id= isset($search['question4_id']) && $search['question4_id']!='' ? $search['question4_id'] : 0;
            $data->question5_id= isset($search['question5_id']) && $search['question5_id']!='' ? $search['question5_id'] : 0;
            $data->question6_id= isset($search['question6_id']) && $search['question6_id']!='' ? $search['question6_id'] : 0;
            $data->question7_id= isset($search['question7_id']) && $search['question7_id']!='' ? $search['question7_id'] : 0;
            $data->question8_id= isset($search['question8_id']) && $search['question8_id']!='' ? $search['question8_id'] : 0;
            $data->question9_id= isset($search['question9_id']) && $search['question9_id']!='' ? $search['question9_id'] : 0;
            $data->question10_id= isset($search['question10_id']) && $search['question10_id']!='' ? $search['question10_id'] : 0;
            $data->total_count = $total_count;//搜索到的总项目数
            $data->add_time = time();//添加时间
            $data->update_time = time();//更新时间
            $data->create();
        }
    }

    /**
     * 获取搜索项目筛选条件记录
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [当前用户id]
     * @author 钟涛
     */
    function getSearchConditionsConut($model,$search,$userid){
        return $model->where('user_id', '=', $userid)//当前用户
        ->where('question1_id','=',isset($search['question1_id']) && $search['question1_id']!='' ? $search['question1_id'] : 0)
        ->where('question2_id','=',isset($search['question2_id']) && $search['question2_id']!='' ? $search['question2_id'] : 0 )
        ->where('question3_id','=',isset($search['question3_id']) && $search['question3_id']!='' ? $search['question3_id'] : 0)
        ->where('question4_id','=',isset($search['question4_id']) && $search['question4_id']!='' ? $search['question4_id'] : 0 )
        ->where('question5_id','=',isset($search['question5_id']) && $search['question5_id']!='' ? $search['question5_id'] : 0)
        ->where('question6_id','=',isset($search['question6_id']) && $search['question6_id']!='' ? $search['question6_id'] : 0 )
        ->where('question7_id','=',isset($search['question7_id']) && $search['question7_id']!='' ? $search['question7_id'] : 0)
        ->where('question8_id','=',isset($search['question8_id']) && $search['question8_id']!='' ? $search['question8_id'] : 0 )
        ->where('question9_id','=',isset($search['question9_id']) && $search['question9_id']!='' ? $search['question9_id'] : 0)
        ->where('question10_id','=',isset($search['question10_id']) && $search['question10_id']!='' ? $search['question10_id'] : 0 )
        ->find()->as_array();
    }

    /**
     * 获取当前用户搜索项目筛选条件记录总数【个人中心筛选记录表】
     * @param  [int] $userid [当前用户id]
     * @author 钟涛
     */
    function getSearchAllCount($userid){
        return ORM::factory('SearchProjectConditions')->where('user_id', '=',$userid)->count_all();
    }

    /**
     * 获取当前用户搜索项目筛选条件记录总数【前台筛选记录表】
     * @param  [int] $userid [当前用户id]
     * @author 钟涛
     */
    function getSearchConditionsAllCount($userid){
        return ORM::factory('Searchconfig')->where('user_id', '=',$userid)->count_all();
    }

    /**
     * 获取单条搜索记录
     * [当前页面的搜索记录id]
     * @author 钟涛
     */
    public function getOneConditionsByid($id) {
        return ORM::factory ( 'SearchProjectConditions')->where('id','=',$id)->find()->as_array();
    }

    /**
     * 删除搜索记录
     * [当前页面的搜索记录id]
     * @author 钟涛
     */
    public function deleteConditionsByid($id) {
        $model = ORM::factory ( 'SearchProjectConditions', $id );
        if ($model->loaded ()) {
            $model->delete ();
        }
    }
    
    /**
     * 个人中心-为你推荐
     * @author 郁政
     */
	public function getTuiJianForYou($user_id,$limit){
		$res = array();
		$result = array();
		$industry_id = 0;
		$count = 0;
		$memcache = Cache::instance ( 'memcache' );
		$countKey = 'cache_tuijianforyou_count_'.$user_id;
		$listKey = 'cache_tuijianforyou_list_'.$user_id;
		$count = $memcache->get($countKey);
		$res = $memcache->get($listKey);
		if(!$count){
			$userPerIndustry = ORM::factory('UserPerIndustry')->where('user_id','=',$user_id)->find()->as_array();		
			if(isset($userPerIndustry['parent_id']) && $userPerIndustry['parent_id'] != 0){
				$industry_id = $userPerIndustry['parent_id'];
				$count = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project_industry.industry_id','=',$industry_id)->where('project_industry.status','=',2)->count_all();
				$memcache->set($countKey, $count, 86400);
			}			
		}
		if(!$res){
			$userPerIndustry = ORM::factory('UserPerIndustry')->where('user_id','=',$user_id)->find()->as_array();		
			if(isset($userPerIndustry['parent_id']) && $userPerIndustry['parent_id'] != 0){
				$industry_id = $userPerIndustry['parent_id'];
				$offset = intval($count/$limit);
				$num = $offset ? rand(0, $offset-1) : 0;
				$result = DB::select('project.project_id')->from('project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project_industry.industry_id','=',$industry_id)->where('project_industry.status','=',2)->limit($limit)->offset($num)->execute()->as_array();
				if($result){
					foreach($result as $v){
						$res[] = $v['project_id'];
					}
				}				
				$memcache->set($listKey, $res, 7200);
			}
		}		
    	return $res;
    }
    
    /**
     * 个人中心-为你推荐(新)
     * @author 郁政
     */
    public function getTuiJianForYouNew($user_id,$limit){
    	$result = array();
    	$res = array();
    	$arr = array();
    	$cache= Rediska_Manager::get("rc"); 	
    	$str = $cache->get('slopeOne'.$user_id); 
    	if($str !== NULL){
    		$res1 = explode(' ', $str);
    		$res1 = array_filter($res1);
    		$res = $res1;
    		$count = $limit - count($res1);
    		if(count($res1) < $limit){	    		
	    		foreach($res1 as $v){
	    			$str2 = $cache->get('projectRC'.$v);
	    			$arr = explode(' ', $str2);
	    			$arr = array_filter($arr);
	    			if($str2 !== NULL){
	    				$res = array_merge($res,$arr);
	    				break;
	    			}	    			 
	    		}
	    		$res = array_unique($res);	    		
	    		$res = array_slice($res, 0 , $limit);
    		}    		   			  
    	}    	
    	$res = array_merge($res,array());    	
    	return $res;
    }
    
    /**
     * 个人中心-猜你喜欢
     * @author 郁政
     */
    public function getGuessYouLike($user_id,$limit){
    	$res = array();   
    	$result = array(); 	
    	$amount_type = 0;
    	$count = 0;
    	$num = 0;
    	$offset = 0;
		$memcache = Cache::instance ( 'memcache' );
		$countKey = 'cache_guessyoulike_count_'.$user_id;
		$listKey = 'cache_guessyoulike_list_'.$user_id;
		$count = $memcache->get($countKey);
		$res = $memcache->get($listKey);
		if(!$count){
			$amount_type = $this->_isHasAmountType($user_id);
			if($amount_type > 0){
				$count = ORM::factory('Project')->where('project_status','=',2)->where('project_amount_type','=',$amount_type)->count_all();
				$memcache->set($countKey, $count, 86400);
			}
		}
		if(!$res){
			$amount_type = $this->_isHasAmountType($user_id);
			if($amount_type > 0){
				$offset = intval($count/$limit);
				$num = $offset ? rand(0, $offset-1) : 0;
				$result = DB::select('project_id')->from('project')->where('project_status','=',2)->where('project_amount_type','=',$amount_type)->limit($limit)->offset($num)->execute()->as_array();
				if($result){
					foreach($result as $v){
						$res[] = $v['project_id'];
					}
				}	
				$memcache->set($listKey, $res, 7200);
			}
		}
		return $res;
    }
    
    /**
     * 获取是否有浏览过的项目并该项目是否有投资金额
     * @author 郁政
     */
    public function _isHasAmountType($user_id,$limit = 1){
    	$arr_data = array();
    	$have_browse = array();
    	$amount_type = 0;
    	$ip = ip2long(Request::$client_ip);
		$service = new Service_Platform_Search();
		$arr_data = $service->_getVistedLog($user_id, $ip, $limit);
		if($arr_data){
			$have_browse = isset($arr_data[0]) ? $arr_data[0] : array();
			if($have_browse){
				$om = ORM::factory('Project',$have_browse['operate_id']);				
				$amount_type = $om->project_amount_type;				
			}
		}	
		return $amount_type;	
    }
}
