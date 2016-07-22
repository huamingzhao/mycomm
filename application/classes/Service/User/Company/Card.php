<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业名片相关逻辑处理
 * @author 钟涛
 *
 */
class Service_User_Company_Card extends Service_Card{
    /**
     * 我收到的投资名片信息列表
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [当前登录用户ID]
     * @author 钟涛
     */
    public function searchReceiveCardInfo($search,$userid){
        $model= ORM::factory('Cardinfo');
        //个人用户信息表、发送记录表 2张表左连接获取投资者所有信息
        $model->join('user_person','LEFT')->on('per_user_id','=','from_user_id')
        ->where('to_user_id','=',$userid)//发送记录表的接收用户为当前登录用户id
        ->where('to_del_status','=',0);//(我收到的名片 status=0)未删除的名片
        $model->join('user_personal_industry','LEFT')->on('user_id','=','from_user_id');
        return $this->addSearchCondition($model,$search,$userid,1);
    }

    /**
     * 我递出投资名片信息列表
     * @param  [array] $search [post获取当前页面搜索条件]
     * @param  [int] $userid [当前登录用户ID]
     * @author 钟涛
     */
    public function searchOutCardInfo($search,$userid){
        $model= ORM::factory('Cardinfo');
        //个人用户信息表、发送记录表 2张表左连接获取投资者所有信息
        $model->join('user_person','LEFT')->on('per_user_id','=','to_user_id')
        ->where('from_user_id','=',$userid)//发送记录表的接收用户为当前登录用户id
        ->where('from_del_status','=',0);//(我收到的名片 status=0)未删除的名片
        $model->join('user_personal_industry','LEFT')->on('user_id','=','to_user_id');
        return $this->addSearchCondition($model,$search,$userid,2);
    }

    /**
     * 添加筛选条件（我收到的、我递出的投资名片信息列表）
     * @author 钟涛
     */
    protected function addSearchCondition($model,$search,$userid,$tpye){
        //对发送名片记录表添加筛选条件(收到名片时间、名片显示)
        $logmodel= ORM::factory('Cardinfo');
        //获取搜索列的相关信息(具体哪些列为搜索的列在对应的Model中$_search_row定义)
        $cardlog_search_row = $logmodel->getSearchRow();
        foreach($cardlog_search_row as $key => $value){
            if(isset($search[$key]) AND $search[$key] != ''){
                if($key=='send_time'){//对收到名片时间做筛选
                    if($search[$key]=='10000') {//10000定义为收到名片时间：半年以上
                        $model->where($key, '<=', time()-(180*24*60*60));
                    }else{//其他的收到名片时间：1天、2天。。等
                        $model->where($key, '>=', time()-($search[$key]*24*60*60));
                    }
                }
                else {//对名片显示做筛选(不限、已查看名片、未查看名片)
                    $model->where($key, '=', $search[$key]);
                }
            }
        }
        //对投资者个人信息表添加筛选条件(投资金额)
        $permodel= ORM::factory('Personinfo');
        $search_row = $permodel->getSearchRow();
        foreach($search_row as $key => $value){
            if(isset($search[$key]) AND $search[$key] != ''){
                //筛选条件（投资金额）
                $model->where($key, '=', $search[$key]);
            }
        }
        //对投资者个人 投资行业 进行筛选
        $perindustrymodel= ORM::factory('UserPerIndustry');
        $perindustry_search_row = $perindustrymodel->getSearchRow();
        foreach($perindustry_search_row as $key => $value){
            if(isset($search[$key]) AND $search[$key] != ''){
                //筛选条件（投资行业）
                $model->where($key, '=', $search[$key]);
            }
        }
        if($tpye==1){
            $model->group_by('from_user_id');
        }else{
            $model->group_by('to_user_id');
        }
        $page_arr=$model->select('*')->reset(false)->find_all( );
        $page = Pagination::factory(array(
                'total_items'    => count($page_arr),
                'items_per_page' => 10,
        ));
        $listArr=$model->select('*')->limit($page->items_per_page)->offset($page->offset)->order_by('send_time', 'DESC')->find_all( );
        $userlist=array();
        $resultlist=array();
        $userservice=new Service_User_Company_User();
        $per_service = new Service_User_Person_User();
        foreach ($listArr as $list){
            $userlist['this_per_industry']=$per_service->getPersonalIndustryString($list->per_user_id);
            if($userservice->getExperienceCount($list->per_user_id)) {//已添加从业经验
                $userlist['per_experience_stutas']=1;
            }else{//未添加从业经验
                $userlist['per_experience_stutas']=0;
            }
            if($this->getFavoriteStatus($userid,$list->per_user_id)==TRUE){
                $userlist['favorite_status']=1;//已收藏此名片
            }else{
                $userlist['favorite_status']=0;//未收藏此名片
            }
            //判断是否有留言
            $userlist['isHasLetter']=$this->getUserLetterCount($list->per_user_id,$userid);
            $resultlist[] = array_merge($list->as_array(),$userlist);
        }
        return array(
                'page' => $page,
                'list' =>$resultlist,
        );
    }

    /**
     * @sso
     * 获取单个投资者名片信息
     * @author 钟涛
     * 2012.12.07
     */
    public function getReceivecardByID($user_id,$cardid,$tpye,$hid=1){
    	//@sso 赵路生 2013-11-11       
        //$user = ORM::factory('User',$user_id);
    	$user  = Service_Sso_Client::instance()->getUserInfoById($user_id);
        $userserice = new Service_User_Company_User();
        $per_userserice = new Service_User_Person_User();
        $perinfo_result = $per_userserice->getPerson($user_id);
        $person=$userserice->projectInvestmentProups($perinfo_result);
        $user_array = array();
        foreach($user as $key=>$value){
        	$user_array[$key] = $value;
        }
        $result = array_merge($user_array,$person->as_array());
        //投资金额
        $monarr= common::moneyArr();
        $per_amount=$result["per_amount"]== 0 ? '无': $monarr[$result["per_amount"]];
        $result['per_amount']=$per_amount;
        //投资行业
        $result['per_industry']=$per_userserice->getPersonalIndustryString($user_id);
        //个人所在地
        $result['per_adress']=$per_userserice->getPerasonalAreaString($user_id);
        //意向投资地区
        $result['yixiang_area'] = $per_userserice->getPersonalArea($user_id);
        if(!$result['yixiang_area']){
            $result['yixiang_area']='无';
        }
        if(!$result['per_per_label']){
            $result['per_per_label']='无';
        }
        //是否有从业经验
        $result['ishasexperience'] = $per_userserice->getExperienceCount($user_id);
        $result['cardid'] =$cardid;
        //年龄
        $result['perage']=0;//默认0岁
        if($result['per_birthday']){
            $thisyears=date("Y");
            $peryears=date("Y",$result['per_birthday']);
            $age=$thisyears-$peryears;
            if($age>0){
                $result['perage'] = $age ;
            }
        }
        //学历
        if($result['per_education'] && $result['per_education']<=10){
            $edu_arr = common::getPersonEducation();
            $result['per_education'] = $edu_arr[$result['per_education']];
        }else{
            $result['per_education']='暂无信息';//默认空
        }
        //我的人脉关系
        if($result['per_connections'] && $result['per_connections']<=5){
            $con_arr = common::connectionsArr();
            $result['per_connections'] = $con_arr[$result['per_connections']];
        }else{
            $result['per_connections']='无';//默认空
        }
        
        //我的投资风格
        if($result['per_investment_style'] && $result['per_investment_style']<=2){
            $invest_arr = common::investmentStyle();
            $result['per_investment_style'] = $invest_arr[$result['per_investment_style']];
        }else{
            $result['per_investment_style']='不限';//不限
        }
        if($result['per_photo']==null || $result['per_photo']==''){
            $result['per_photo'] = URL::webstatic('images/getcards/photo.png');
        }else{
            $result['per_photo'] = URL::imgurl($result['per_photo']);
        }
        if($tpye){//根据cardid对名片进行设置为已读
            if(mb_strlen($result['per_realname'])>4){
                //姓名过长截取
                $result['per_realname']=mb_substr($result['per_realname'],0,4,'UTF-8').'...';
            }
            if($tpye==1) {//我收到的名片
                $this->updateReceCardReadStatus($cardid);
            }elseif($tpye==2) {//我递出的名片
                $this->updateOutCardReadStatus($cardid);
            }else{	}
        }
        //当前用户id
        $this_user_id=Cookie::get("user_id");
        //新改手机号码与邮箱隐藏----开始
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
        //新改手机号码与邮箱隐藏----结束
        if($hid==1){//隐藏手机号码和邮箱
            //查看名片log
            $this->addCardBehaviourInfo($this_user_id,$user_id,9);
            //被查看名片log
            //$this->addCardBehaviourInfo($user_id,$this_user_id,10);
        }elseif ($hid==2){//服务已经扣除，直接弹出查看名片框
            //查看联系方式log
            $this->addCardBehaviourInfo($this_user_id,$user_id,11);
            //被查看联系方式log
            //$this->addCardBehaviourInfo($user_id,$this_user_id,12);
        }else{//等于3或者其他的是已经付过钱了 直接算查看名片行为
            //查看名片log
            $this->addCardBehaviourInfo($this_user_id,$user_id,9);
            //被查看名片log
            //$this->addCardBehaviourInfo($user_id,$this_user_id,10);
        }
        unset($result['password']);
        unset($result['reg_time']);
        unset($result['last_logintime']);
        unset($result['last_login_ip']);
        unset($result['per_createtime']);
        unset($result['per_updatetime']);
        unset($result['per_card_image']);
        unset($result['per_open_stutas']);
        return $result;
    }

    /**
     * @sso
     * 获取个人用户登的简洁信息
     * @author 钟涛
     * 2013.07.24
     */
    public function getReceivecardLoginid($user_id){
        $result=array();
        if(intval($user_id) && $user_id){
        	//@sso 赵路生 2013-11-11
            //$user  = Service_Sso_Client::instance()->getUserInfoById( $this->userid() );
        	$user  = Service_Sso_Client::instance()->getUserInfoById( $user_id );
            //$user = ORM::factory('User',$user_id);
            $per_userserice = new Service_User_Person_User();
            //个人基本信息
            $personinfo=$per_userserice->getPerson($user_id);
            //真实姓名
            $result['name']=$personinfo->per_realname;
            if($personinfo->per_gender==2){
                $result['sex']='女士';
            }else{
                $result['sex']='先生';
            }
            if(project::checkProLogo(URL::imgurl($personinfo->per_photo))){
                $result['image'] = URL::imgurl($personinfo->per_photo);
            }else{
                if($personinfo->per_gender==2){
                    $result['image'] = URL::webstatic('images/find_invester/photo_woman.jpg');
                }else{
                    $result['image'] = URL::webstatic('images/find_invester/photo_man.jpg');
                }
            }
            //投资金额
            $monarr= common::moneyArr();
            $result['per_amount']=arr::get($monarr,$personinfo->per_amount,'无');
            //投资行业
            $perindustry=$per_userserice->getPersonalIndustryString($user_id);
            $result['per_industry']=$perindustry=='' ? '无': $perindustry;
            //个人所在地
            $result['per_adress']=$per_userserice->getPerasonalAreaString($user_id);
            //意向投资地区
            $result['yixiang_area'] = $per_userserice->getPersonalArea($user_id);
            if(!$result['yixiang_area']){
                $result['yixiang_area']='无';
            }
            //年龄
            $result['perage']=0;//默认0岁
            if($personinfo->per_birthday){
                $thisyears=date("Y");
                $peryears=date("Y",$personinfo->per_birthday);
                $age=$thisyears-$peryears;
                if($age>0){
                    $result['perage'] = $age ;
                }
            }
            if($user->mobile){
                $result['mobile']=mb_substr($user->mobile,0,3,'UTF-8').'****'.mb_substr($user->mobile,7,11,'UTF-8');
            }else{
                $result['mobile']='';
            }
            //邮箱部分显示，格式如********@126.com
            $emailarr = explode("@",$user->email);
            if(isset($emailarr[1])){
                $result['email']='********@'.$emailarr[1];
            }else{
                $result['email']=$user->email;
            }
        }
        return $result;
    }

    /**
     * sso
     * 获取单个投资者联系方式
     * @author 钟涛
     * $type =1 收费 ；$type =2 免费
     * 2013.06.04
     */
    public function getPersonCont($user_id,$type=1){
        //sso 赵路生 2013-11-12
    	$user = Service_Sso_Client::instance()->getUserInfoById($user_id);
        //当前用户id
        $this_user_id=Cookie::get("user_id");
        if($type==1){
            //查看联系方式log
            $this->addCardBehaviourInfo($this_user_id,$user_id,11);
            //被查看联系方式log
            //$this->addCardBehaviourInfo($user_id,$this_user_id,12);
        }elseif($type==2){
            //免费查看联系方式log
            $this->addCardBehaviourInfo($this_user_id,$user_id,13);
            //免费被查看联系方式log
            //$this->addCardBehaviourInfo($user_id,$this_user_id,14);
        }else{	}
        $result['mobile']=$user->mobile;
        $result['email']=$user->email;
        return $result;
    }

    /**
     * 获取我的名片模板信息
     * @param  [int] $com_id [企业用户信息表ID]
     * @author 钟涛
     */
    public function getCardStyleInfo($urlpage){
        //获取名片模板数量(目前名片模板图片存在在数组中)
        $count = count(common::card_img_small());
        $page_size = 9; //分页大小
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => $page_size,
        ));
        //以下对数组信息模拟分页情况
        $return_arr=array();
        $return_arr['list'] = array_slice(common::card_img_small(), ($urlpage-1)*$page_size, $page_size);
        $return_arr['page'] = $page;
        return $return_arr;
    }

    /**
     * 更新我的名片模板信息
     * @param  [int] $user_id [当前用户登录ID]
     * @param  [int] $cardstyle [当前选择的模板ID]
     * @author 钟涛
     */
    public function updateCardStyleInfo($user_id,$cardstyle){
        $model = ORM::factory('User',$user_id);
        $model->card_style =$cardstyle;
        if($model->save()) {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 企业获取已经付钱查看过的名片
     * @param  [int] $user_id [当前用户登录ID]
     * @author 钟涛
     */
    public function getAlreadyViewCard($user_id){
    	$result=array();
    	if($user_id){
    		$data=ORM::factory('Cardinfobehaviour')
    			->where('user_id','=',$user_id)
    			->where('user_type','=',1)
    			->where('status','=',11)//付款查看的名片
    			->group_by('second_user_id');
    		$page_arr=$data->select('*')->reset(false)->find_all( );
    		$page = Pagination::factory(array(
    				'total_items'    => count($page_arr),
    				'items_per_page' => 16,
    		));
    		$listArr=$data->select('*')->limit($page->items_per_page)->offset($page->offset)->order_by('add_time', 'DESC')->find_all( );
    		
    		$now_time = time();
    		$service_searchinvestor=new Service_Platform_SearchInvestor();
    		$per_userserice = new Service_User_Person_User();
    		$monarr= common::moneyArr();
    		foreach($listArr as $k=>$v){
    			$permodel= ORM::factory('Personinfo');
    			$permodeldata=$permodel->where('per_user_id','=',$v->second_user_id)->find();
    			$result[$k]['second_user_id']=$v->second_user_id;
    			$result[$k]['per_realname']=$permodel->per_realname;
    			$perphoto1=URL::imgurl($permodeldata->per_photo);
    			if($perphoto1 != URL::imgurl('')){//有存在的图片
    				$result[$k]['per_photo']=$perphoto1;//照片
    			}else{//没有头像
    				$result[$k]['per_photo']=URL::webstatic("/images/getcards/photo.png");//照片
    			}
    			$time_section = $now_time - $v->add_time;
    			//查看时间
    			$result[$k]['add_time'] = $service_searchinvestor->getTimeSection($time_section);
    			//获得个人意向投资地区
    			$result[$k]['per_area'] = $per_userserice->getPersonalArea($v->second_user_id);
    			//获得个人意向投资金额
    			$result[$k]['per_amount'] = arr::get($monarr, $permodeldata->per_amount,'');
    			//添加行业信息
    			$this_per_industry=$per_userserice->getPerasonalParentIndustry($v->second_user_id);
    			if($this_per_industry){
    				foreach($this_per_industry as $key){
    					$result[$k]['this_per_industry']=$key;
    					break;
    				}
    			}else{
    				$result[$k]['this_per_industry']='';
    			}
    		}
    		return array(
    				'page' => $page,
    				'list' => $result,
    		);
    	}else{
    		return array(
    				'page' => '',
    				'list' => $result,
    		);
    	}
    	
    }
    
    /**
     * 显示完善企业名片
     * @author 周进
     */
    public function getCompanyCard($user_id){
        $company = ORM::factory('Companyinfo');
        $result['companyinfo'] = $company->where('com_user_id', '=', $user_id)->find();
        $result['pro'] = $company->com_project
                                 ->where('com_id', '=', $result['companyinfo']->com_id)
                                 ->where('project_status', '=', 2)//通过审核的项目
                                 ->find_all();
        return $result;
    }

    /**
     * 完善企业名片处理
     * @author 周进
     */
    public function updateCompanyCard($user_id,$post){
        $result['status'] = false;
        if (isset($post['brand'])&&count($post['brand'])>3)
            return $result;
        $company = ORM::factory('Companyinfo');
        $result['companyinfo'] = $company->where('com_user_id', '=', $user_id)->find();
        $arr['logo'] = $post['logo'];
        $arr['brand'] = isset($post['brand'])?serialize($post['brand']):'';
        $arr['time']=time();
        $company->com_card_config = serialize($arr);
        try
        {
            $company->update();
            $result['status'] = true;
            $result['pro'] = $company->com_project
                                      ->where('com_id', '=', $result['companyinfo']->com_id)
                                      ->where('project_status', '=', 2)//通过审核的项目
                                      ->find_all();
        }
        catch (Kohana_Exception $e)
        {
            $result['status'] = false;
        }
        return $result;
    }

    /**
     * 查看名片(我接收到的)
     * @author 周进
     */
    public function updateCardReadStatus($user_id,$data){
        $tab = ORM::factory('Cardinfo');
        $count = $tab->where('card_id','=',$data)->find()->as_array();
        if (count($count)==0){
            $result['status'] = '-200';
        }
        else{
            if ($count['to_read_status']==0){
                $tab->to_read_status = 1;
                $tab->to_read_time = time();
                try {
                    $tab->update();
                    $result['status'] = '100';
                } catch (Kohana_Exception $e) {
                    $result['status'] = '0';
                }
            }
            else
                $result['status'] = '100';
        }
        return $result;
    }

    /**
     * 企业收藏名片搜索
     * @author 周进
     */
    public function searchFavorite($search='',$user_id){
        $search = Arr::map(array("HTML::chars"), $search);
        $user_id=intval($user_id);
        if ($search['exchange_status']==0&&$search['from_read_status']=='-1'){
            $queryresult = DB::select()->from('favorite')->join('user_person', 'LEFT')->on('per_user_id', '=', 'favorite_user_id');
        }else{
            $queryresult = DB::select()->from('card_info','favorite')->join('user_person', 'LEFT')->on('per_user_id', '=', 'favorite_user_id');
        }
        //行业筛选
        if(isset($search['industry_id']) && $search['industry_id']!=""){//2级行业
            $queryresult=$queryresult->join('user_personal_industry', 'LEFT')->on('user_personal_industry.user_id', '=', 'favorite.favorite_user_id')->where('industry_id','=',$search["industry_id"]);
            //$industry_sql = " LEFT JOIN czzs_user_personal_industry on czzs_user_personal_industry.user_id=czzs_favorite.favorite_user_id  where industry_id=".$search['industry_id'].' and ';
        }elseif(isset($search['parent_id']) && $search['parent_id']!=""){//1级行业
            $queryresult=$queryresult->join('user_personal_industry', 'LEFT')->on('user_personal_industry.user_id', '=', 'favorite.favorite_user_id')->where('parent_id','=',$search["parent_id"]);
            //$industry_sql = " LEFT JOIN czzs_user_personal_industry on czzs_user_personal_industry.user_id=czzs_favorite.favorite_user_id  where parent_id=".$search['parent_id'].' and ';
        }else{
            //$industry_sql =' where ';
        }
        //名片状态不限，名片显示不限（名片状态指已交换、已收到、已递出，名片显示指查看、未查看）
        if ($search['exchange_status']==0&&$search['from_read_status']=='-1'){
            $queryresult = $queryresult->where('favorite_status', '=', 1)->and_where('favorite.user_id', '=', $user_id)->group_by('favorite_user_id');
            //$sql = " FROM czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql." favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
        }
        elseif ($search['exchange_status']==0&&$search['from_read_status']!='-1'){//名片状态不限，有名片显示条件限制

            if($search['from_read_status']=='0')//未查看（包括的情况只有已经交换未查看）
                $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('per_user_id'))->and_where('to_user_id', '=', $user_id)->where_close()->or_where_open()->where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id','=',$user_id)->or_where_close()->where_close()->and_where('from_read_status','=',0)->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('exchange_status','=',1)->and_where('favorite.user_id','=',$user_id)->group_by('favorite_user_id');
                //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql."  ((from_user_id = per_user_id and to_user_id = ".$user_id.") or (to_user_id = per_user_id and from_user_id = ".$user_id." )) and from_read_status=0 and from_del_status=0 and to_del_status=0 and favorite_status=1 and exchange_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
            if($search['from_read_status']=='1')//已查看（包括的情况有已经交换的查看、未交换递出、已交换递出）
                $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('per_user_id'))->and_where('to_user_id', '=', $user_id)->where_close()->or_where_open()->where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id','=',$user_id)->and_where_open()->where('exchange_status','=',0)->or_where_open()->where('from_read_status','=',1)->and_where('exchange_status','=',1)->or_where_close()->and_where_close()->or_where_close()->where_close()->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id)->group_by('favorite_user_id');
                //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql."  ((from_user_id = per_user_id and to_user_id = ".$user_id.") or (to_user_id = per_user_id and from_user_id = ".$user_id." and (exchange_status=0 or (from_read_status=1 and exchange_status=1)))) and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
        }
        elseif ($search['exchange_status']!=0){//名片状态限制（含名片显示）
            if ($search['exchange_status']=='1'){//已交换
                //$add.=" and exchange_status=1";
                if ($search['from_read_status']=='0'){//已交换未读
                    $queryresult=$queryresult->where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id', '=', $user_id)->and_where('from_read_status','=',0)->and_where('from_del_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('exchange_status','=',1)->and_where('favorite.user_id','=',$user_id)->group_by('favorite_user_id');
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql." to_user_id = per_user_id and from_user_id = ".$user_id." and from_read_status=0 and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }elseif ($search['from_read_status']=='1'){//已交换已查看
                    $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('per_user_id'))->and_where('to_user_id', '=', $user_id)->where_close()->or_where_open()->where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id','=',$user_id)->and_where('from_read_status','=',1)->where_close()->where_close()->where('from_del_status','=',0)->where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('exchange_status','=',1)->and_where('favorite.user_id','=',$user_id)->group_by('favorite_user_id');
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql." ((from_user_id = per_user_id and to_user_id = ".$user_id.") or (to_user_id = per_user_id and from_user_id = ".$user_id." and from_read_status=1)) and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }else//不限查看状态
                    $queryresult=$queryresult->where_open()->where_open()->where('from_user_id','=',DB::expr('per_user_id'))->and_where('to_user_id', '=', $user_id)->where_close()->or_where_open()->where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id','=',$user_id)->or_where_close()->where_close()->where('from_del_status','=',0)->where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('exchange_status','=',1)->and_where('favorite.user_id','=',$user_id)->group_by('favorite_user_id');
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql." ((from_user_id = per_user_id and to_user_id = ".$user_id.") or (to_user_id = per_user_id and from_user_id = ".$user_id.")) and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
            }
            elseif ($search['exchange_status']=='2'){//已收到（不包含交换的）
                //$add.=" and exchange_status=0";
                if($search['from_read_status']=='0'){//不存在
                    $queryresult=$queryresult->and_where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id','=',$user_id)->and_where('from_del_status','=',100)->and_where('exchange_status','=',0)->group_by('favorite_user_id');
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql." to_user_id = per_user_id and from_user_id = ".$user_id." and from_del_status=100 and to_del_status=100 and favorite_status=100 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }else{//我为from_user_id递出的已查看不存在应该放入交换中，我为to_user_id时都是
                    $queryresult=$queryresult->and_where('from_user_id','=',DB::expr('per_user_id'))->and_where('to_user_id','=',$user_id)->and_where('from_del_status','=',0)->and_where('exchange_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('to_read_status','=',1)->and_where('favorite.user_id','=',$user_id)->group_by('favorite_user_id');
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql."  from_user_id = per_user_id and to_user_id = ".$user_id." and from_del_status=0 and to_del_status=0 and favorite_status=1 and to_read_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }
            }
            elseif ($search['exchange_status']=='3'){//已递出,不存在未读的
                //$add.=" and exchange_status=0";
                if($search['from_read_status']=='0'){//不存在
                    $queryresult=$queryresult->and_where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id','=',$user_id)->and_where('from_del_status','=',100)->and_where('exchange_status','=',0)->group_by('favorite_user_id');
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql."  to_user_id = per_user_id and from_user_id = ".$user_id." and from_del_status=100 and to_del_status=100 and favorite_status=100 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }else{
                    $queryresult=$queryresult->and_where('to_user_id','=',DB::expr('per_user_id'))->and_where('from_user_id','=',$user_id)->and_where('from_del_status','=',0)->and_where('exchange_status','=',0)->and_where('to_del_status','=',0)->and_where('favorite_status','=',1)->and_where('favorite.user_id','=',$user_id)->group_by('favorite_user_id');
                    //$sql = " FROM czzs_card_info,czzs_favorite LEFT JOIN czzs_user_person on per_user_id=favorite_user_id ".$industry_sql." to_user_id = per_user_id and from_user_id = ".$user_id." and from_del_status=0 and to_del_status=0 and favorite_status=1 and czzs_favorite.user_id=".$user_id.$add.' group by favorite_user_id '.$order;
                }
            }
        }
        if(isset($search['per_amount'])&&$search['per_amount']!=""){
            $queryresult=$queryresult->where('per_amount','=',$search['per_amount']);
            //$add.=" and per_amount=".$search['per_amount'];//投资金额
        }
        if (isset($search['send_time'])&&$search['send_time']!=""){//收藏时间
            if($search['send_time']=='10000'){//10000定义为收到名片时间：半年以上
                $queryresult=$queryresult->where('favorite_time','<=',(time()-(180*24*60*60)));
                //$add.=" and favorite_time<=".(time()-(180*24*60*60));
            }else{//其他的收到名片时间：1天、2天。。等
                $queryresult=$queryresult->where('favorite_time','>=',(time()-($search['send_time']*24*60*60)));
                //$add.=" and favorite_time>=".(time()-($search['send_time']*24*60*60));
            }
        }
        $queryresult=$queryresult->group_by('favorite_user_id')->order_by('favorite_time', 'DESC');
        //执行查询
        $num = $queryresult->execute()->as_array();
        $page = Pagination::factory(array(
                'total_items'    => count($num),
                'items_per_page' => 8,
        ));
        $queryresult=$queryresult->limit($page->items_per_page)->offset($page->offset);
        //$limit = " limit ".$page->offset.",".$page->items_per_page;
        $result = $queryresult->execute()->as_array();
        return array(
                'page' => $page,
                'list' => $this->addResultData($result,$user_id),
                'total_count'=>count($num),
        );
    }

    /**
     * 组合筛选（收藏列表相关）
     * @author 周进
     */
    function addResultData($personlist,$userid){
        $userid=intval($userid);
        $userlist=array();
        $resultlist=array();
        $user_service=new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        $per_service=new Service_User_Person_User();
        foreach ($personlist as $list){
            $userlist['this_per_industry']=$per_service->getPersonalIndustryString($list['per_user_id']);
            //判断是否已交换名片
            if($card_service->getExchaneCardCountByTwoIdAll($list['per_user_id'], $userid) || $card_service->getReceivedExchageCardCountByTwoIdAll($list['per_user_id'], $userid)){
                $userlist['exchangecardcount'] = 1;//已经与此用户交换名片
                $query1 = DB::select()->from('card_info')->where_open()->where_open()->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list['per_user_id'])->where_close()->where_close()->or_where_open()->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list['per_user_id'])->or_where_close()->and_where('exchange_status', '=', 1);
                //$sql = " FROM czzs_card_info where ((from_user_id = ".$userid." and to_user_id = ".$list['per_user_id'].") or (to_user_id = ".$userid." and from_user_id = ".$list['per_user_id'].")) and exchange_status=1 ";
                $result = $query1->execute()->as_array();
                $userlist['cardinfo'] = $result[0];
                if ($userlist['cardinfo']['from_user_id']==$userid)
                    $userlist['cardinfo']['card_type']=2;
                else
                    $userlist['cardinfo']['card_type']=1;
            }
            elseif($card_service->getOutCardCountByTwoIdAll($list['per_user_id'], $userid)){
                $userlist['exchangecardcount'] = 0;
                $userlist['outcardcount'] = 1;//已经给此用户发送名片
                $userlist['receivedcardcount'] = 0;//没有收到
                $query1 = DB::select()->from('card_info')->where('from_user_id', '=', $userid)->and_where('to_user_id', '=', $list['per_user_id'])->and_where('exchange_status', '=', 0);
                //$sql = " FROM czzs_card_info where from_user_id = ".$userid." and to_user_id = ".$list['per_user_id']." and exchange_status=0 ";
                $result = $query1->execute()->as_array();
                $userlist['cardinfo'] = $result[0];
                $userlist['cardinfo']['card_type']=2;
            }elseif($card_service->getReceiveCardCountByTwoIdAll($list['per_user_id'], $userid)){//当前用户是否收到此投资者发送名片
                $userlist['exchangecardcount'] = 0;
                $userlist['outcardcount'] = 0;
                $userlist['receivedcardcount'] = 1;//已经收到
                $query1 = DB::select()->from('card_info')->where('to_user_id', '=', $userid)->and_where('from_user_id', '=', $list['per_user_id'])->and_where('exchange_status', '=', 0);
                //$sql = " FROM czzs_card_info where to_user_id = ".$userid." and from_user_id = ".$list['per_user_id']." and exchange_status=0 ";
                $result = $query1->execute()->as_array();
                $userlist['cardinfo'] = $result[0];
                $userlist['cardinfo']['card_type']=1;
            }else{
                $userlist['cardinfo']=array();
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

            //判断是否有留言
            $userlist['isHasLetter']=$this->getUserLetterCount($list['per_user_id'],$userid);
            $resultlist[] = array_merge($list,$userlist);
        }
        return $resultlist;
    }

    /**
     * sso
     * @todo 判断标准待完善
     * 判断企业用户名片中是否有项目 （即判断名片是否完善）
     * @author 龚湧
     * @param int $user_id
     * @return boolean
     */
    public function isCompanyCardComplete($user_id){
        $user = ORM::factory("User",$user_id);
        //@sso 赵路生  2013-11-12
        $com_user_ser = new Service_User_Company_User();
        $company = $com_user_ser->getCompanyInfo($user_id);
        $config = unserialize($company->com_card_config);
        $brand = unserialize($config['brand']);
        return $this->isHasCardComplete($brand);
    }

    /**
     * 查看用户名片信息
     * @author施磊
     */
    public function getCompanyCardInfo($user_id) {
        $model= ORM::factory('User')->where("user_id", '=', $user_id)->find_all();
        return $this->addCardInfoResultData($model);
    }

    /**
     * 名片数量统计
     * @author 钟涛
     */
    function addCardInfoResultData($listArr){
        $resultlist=array();
        $countlist=array();
        foreach ($listArr as $list){
            $cardlist=$list->as_array();
            $userid=$cardlist['user_id'];
            $countlist['receivecardcount']=$this->getReceiveCardCountAll($userid);//收到名片总数
            $countlist['outcardcount']=$this->getOutCardCountAll($userid);//递出名片总数
            $countlist['exchangecardcount']=$this->getExchaneCardCountOutAll($userid)+$this->getExchaneCardReceivedAll($userid);//交换名片总数
            $resultlist[] = array_merge($cardlist,$countlist);
        }
        return $resultlist;
    }

    /**
     * 收到名片总数：根据当前ID获取我收到的 投资者\企业 名片总数量(包含用户删除的名片)
     * @param  [int] $user_id 接收用户(当前用户)
     * @author 钟涛
     */
    public function getReceiveCardCountAll($user_id){
        return ORM::factory('Cardinfo')
        ->where('to_user_id','=',$user_id)//接收用户
        ->count_all();
    }

    /**
     * 递出名片总数：根据当前ID获取给 投资者\企业 发送名片数量(包含删除的名片)
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getOutCardCountAll($user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $user_id)//发送用户
        ->count_all();
    }

    /**
     * 我递出的交换的名片数量(包含删除的名片)
     * @param  [int] $user_id 发送用户(当前登录用户)
     * @author 钟涛
     */
    public function getExchaneCardCountOutAll($user_id){
        return ORM::factory('Cardinfo')
        ->where('from_user_id', '=', $user_id)//发送用户
        ->where('exchange_status','=',1)//已经交换的
        ->count_all();
    }

    /**
     * 我收到的交换的名片数量(包含删除的名片)
     * @param  [int] $user_id 接收用户
     * @author 钟涛
     */
    public function getExchaneCardReceivedAll($user_id){
        return ORM::factory('Cardinfo')
        ->where('to_user_id', '=', $user_id)//发送用户
        ->where('exchange_status','=',1)//已经交换的
        ->count_all();
    }

    /**
     * 企业生成名片图片
     * @author 钟涛
     */
    public function getComCardImage($companyinfo,$pro,$brand){
        $background_image=URL::webstatic("images/card_image/company_background2.jpg");
        $myImage = imagecreatefromjpeg($background_image);
        //白色字体
        $white=ImageColorAllocate($myImage, 255, 255, 255);
        //企业名称字体颜色
        $username=ImageColorAllocate($myImage, 83, 31, 0);
        //企业旗下项目字体
        $prjectname=ImageColorAllocate($myImage, 221, 249, 8);
        //项目名称字体
        $prjectname2=ImageColorAllocate($myImage, 233, 166, 53);
        //黑色字体
        $black=ImageColorAllocate($myImage, 0, 0, 0);
        //灰色字体
        $huise=ImageColorAllocate($myImage, 109,109, 109);
        $comname= mb_substr($companyinfo->com_name,0,17,'UTF-8');
        $comusername=mb_substr($companyinfo->com_contact,0,4,'UTF-8');
        $phone=$companyinfo->com_phone;
        $adress='公司地址：'.mb_substr($companyinfo->com_adress,0,22,'UTF-8');
        $com_site='公司网址：'.mb_substr($companyinfo->com_site,0,40,'UTF-8');
        $font='modules/fonts/simsun.ttc';
        $font2='modules/fonts/msyh.ttf';
        imagettftext($myImage, 19, 0, 206, 82, $black, $font2,  $comname);
        imagettftext($myImage, 13, 0, 245, 142, $white, $font2,  $comusername);
        imagettftext($myImage, 15, 0, 418, 143, $white, $font2,  $phone);
        imagettftext($myImage, 12, 0, 222, 180, $white, $font2,  $adress);
        if($companyinfo->com_site!=''){
               imagettftext($myImage, 12, 0, 222, 208, $white, $font2,  $com_site);
        }
        if ($brand!="" && $brand && count($pro)>0){
            imagettftext($myImage, 16, 0, 55, 275, $black, $font2,  ">>企业旗下项目：");
            //名片添加项目信息
            foreach ($pro as $v){
                if ($brand!="" && $brand){
                    $ids = $brand;
                    $y_siet=280;//Y坐标280 310 340
                    foreach ($ids as $j){
                        $y_siet=$y_siet+30;
                        if ($v->project_id==$j){
                            $sumary_text=htmlspecialchars_decode($v->project_summary);
                            $this_sumary=mb_substr(strip_tags($sumary_text),0,30,'UTF-8');
                            imagettftext($myImage, 11, 0, 58, $y_siet, $black, $font2,  $v->project_brand_name.'--');
                            imagettftext($myImage, 11, 0, 165, $y_siet, $black, $font2, $this_sumary);
                        }
                    }
                }
            }
        }else{
            imagettftext($myImage, 12, 0, 300, 320, $huise, $font2,"暂无项目信息");
        }
        $image_path='application/cache/card_image/mycardimage_'.$companyinfo->com_user_id.'.jpg';
        //添加到缓存
        imagejpeg($myImage,$image_path);
        //上传到服务器
        $files= array();
        $files['com_card_image']['tmp_name']=$image_path;
        $files['com_card_image']['size']=55040;
        $files['com_card_image']['name']='mycardimage_'.$companyinfo->com_user_id.'.jpg';
        $files['com_card_image']['type']='image/jpeg';
        $files['com_card_image']['error']='0';
        $size = getimagesize($files['com_card_image']['tmp_name']);
        //大小图的长宽
        $w=$size[0];$h=$size[1];
        $img = common::uploadPic($files,'com_card_image',array(array($w,$h)));
        if($img['error']==''){//添加成功
            //删除之前的图片
            common::deletePic(URL::imgurl($companyinfo->com_card_image));
            //企业基本信息表保存当前路径
            $s_path = Arr::get($img,'path');
            $companyinfo->com_card_image=common::getImgUrl($s_path);
            $companyinfo->save();
        }
        //释放资源
        ImageDestroy($myImage);
        //删除缓存的图片
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
}