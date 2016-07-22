<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 项目官网 Service
 * @author 钟涛
 */

class Service_Platform_Project{
    /**
     * 根据项目ID返回项目信息[审核通过的项目]
     * @author 钟涛
     */
    public function getProjectInfoByID($project_id){
        $project=ORM::factory('Project')->where('project_id','=',$project_id)->where('project_status','=',2)->find();
        if($project->project_id != null){
            return $project;
        }
        return false;
    }

    /**
     * 根据项目ID返回项目信息[所有的项目]
     * @author 钟涛
     */
    public function getProjectInfoByIDAll($project_id){
        $project=ORM::factory('Project')->where('project_id','=',$project_id)->where('project_status','<',4)->find();
        if($project->project_id != null){
            return $project;
        }
        return false;
    }
    /**
     * 根据项目ID返回项目信息[所有的项目]
     * @author 赵路生
     */
    public function getProjectInfoByIDAllNew($project_id){
        $project=ORM::factory('Project')->where('project_id','=',$project_id)->find();
        if($project->project_id != null){
            return $project;
        }
        return false;
    }
    /**
     * 根据项目ID返回项目图片信息
     * @author 钟涛
     */
    public function getProjectImageByID($project_id){
        $project=ORM::factory('Projectcerts')->where("project_id","=",$project_id)->where("project_type","=",1)->find_all()->as_array();
        $result = array();
        if(count($project) > 0){
            foreach ($project as $k=>$v){
             $result[$k]['b_image'] = URL::imgurl(str_replace('/s_','/b_', $v->project_img));
             $result[$k]['s_image'] = URL::imgurl($v->project_img);
            }
        }
        return $result;
    }

    /**
     * 根据项目ID返回项目图片
     * @author 潘宗磊
     */
    public function getProjectImageByIDXml($project_id){
        $project=ORM::factory('Projectcerts')->where("project_id","=",$project_id)->where("project_type","=",1)->find_all()->as_array();
        $str = "";
        if(count($project) > 0){
            foreach ($project as $k=>$v){
                $str .= "<info>";
                $str.= '<image>'.URL::imgurl(str_replace('/s_','/b_', $v->project_img))."</image>";
                $str .= "<name>项目图片</name>";
                $str.= "</info>";
            }
        }
        return $str;
    }

    /**
     * 根据招商会ID返回播报现场图片
     * @author 潘宗磊
     */
    public function getBobaoImageByIDXml($invest_id){
        $com_porject = new Service_User_Company_Project();
        $result=$com_porject->isBobao($invest_id);
        if($result){
            $bobao = $com_porject->getBobao($invest_id);
            $str = "";
            if(count($bobao['img']) > 0){
                foreach ($bobao['img'] as $k=>$v){
                    $str .= "<info>";
                    $str.= '<image>'.URL::imgurl(str_replace('/s_','/b_', $v))."</image>";
                    $str.= "</info>";
                }
            }
            return $str;
        }else{
            return false;
        }

    }

    /**
     * 根据项目ID返回对应企业id
     * @author 钟涛
     */
    public function getComidByProjectID($project_id){
        $project=ORM::factory('Project',$project_id);
        if($project->com_id ==""){
            return false;
        }
        return $project->com_id;
    }

    /**
     * 根据项目id返回2级行业id和1级行业的name
     * @author 钟涛
     */
    public function getIndustryByPorjectid($project_id){
        $returnarr['id']=1;//默认返回的id
        $returnarr['name']='餐饮';//默认返回的name
        if(intval($project_id) && $project_id){
            $p_industry= ORM::factory('Projectindustry')->where("project_id", "=",$project_id)->find_all();
            if(count($p_industry)){
                $one_id=1;//1级行业id 默认1
                foreach ($p_industry as $ve){
                    $industry= ORM::factory("industry",$ve->industry_id);
                    if($industry->parent_id>0){
                        $returnarr['twoid']=$ve->industry_id;//返回2级行业id
                        $returnarr['twoname']=ORM::factory("industry",$ve->industry_id)->industry_name;//2级行业的name
                    }else{//1级行业
                        $returnarr['id']=$ve->industry_id;//返回1级行业id
                        $returnarr['name']=ORM::factory("industry",$ve->industry_id)->industry_name;//1级行业的name
                    }
                }
            }
        }
        return $returnarr;
    }

    /**
     * 根据项目ID返回对应企业用户登录userid
     * @author 钟涛
     */
    public function getUseridByProjectID($project_id){
        $comid=$this->getComidByProjectID($project_id);
        if($comid){
            $cominfo=ORM::factory('Companyinfo',$comid);
            return $cominfo->com_user_id;
        }else{
            return false;
        }
    }

    /**
     * 添加项目举报数据
     * @author 钟涛
     */
    public function addJubao($data){
        $model=ORM::factory('ProjectReport');
        if(arr::get($data,'report_project_id')>0 && intval(arr::get($data,'report_project_id'))){//存在举报的项目id
            if(arr::get($data,'report_type') && intval(arr::get($data,'report_type'))){
                $type=arr::get($data,'report_type');
            }else{
                $type=1;
            }
            if(arr::get($data,'report_content')=='请详细填写，以确保您的举报能被处理。'){
                $content='';
            }else{
                $content=arr::get($data,'report_content');
            }
            $model->report_project_id=arr::get($data,'report_project_id');//被举报项目id
            $model->report_project_name=arr::get($data,'report_project_name');//被举报项目名
            $model->report_contact_name=arr::get($data,'report_contact_name');//姓名
            $model->report_mobile=arr::get($data,'report_mobile');//手机
            $model->report_email=arr::get($data,'report_email');//邮箱
            $model->report_content=$content;//内容
            $model->report_identity_type=arr::get($data,'report_identity_type');//身份
            $model->report_type=$type;//举报类型
            $model->report_addtime=time();//举报时间
            try{
                $model->create();
                return true;
            }catch(Kohana_Exception $e){
                   return false;
               }
        }
        return false;
    }

    /**
     * 根据项目ID返回企业基本信息
     * @author 钟涛
     */
    public function getCompanyByProjectID($project_id){
        $com_id=$this->getComidByProjectID($project_id);
        if($com_id && $com_id != Kohana::$config->load('basic.com_id')){
            $model=ORM::factory('Companyinfo',$com_id);
            if($model->loaded()){
               return $model;
            }else{
                return false;
            }
        }else{
            $obj_project  = ORM::factory('Project',$project_id);
            $model=ORM::factory('TestCompany',$obj_project->outside_com_id);
            if($model->loaded()){
                $model->com_desc = trim($obj_project->outside_com_introduce) ? trim($obj_project->outside_com_introduce) : $model->com_desc;
                return $model;
            }elseif($obj_project->outside_com_id == 0){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 根据项目ID返回企业基本信息
     * @author 钟涛
     */
    public function getCompanyByProjectID2($project_id){
        $com_id=$this->getComidByProjectID($project_id);
        if($com_id && $com_id != Kohana::$config->load('basic.com_id')){
            $model=ORM::factory('Companyinfo',$com_id);
            return $model;
        }else{
            $obj_project  = ORM::factory('Project',$project_id);
            $model=ORM::factory('OutComUser',$obj_project->outside_com_id);
            if($model->loaded()){
                $model->com_desc = trim($obj_project->outside_com_introduce) ? trim($obj_project->outside_com_introduce) : $model->com_desc;
                return $model;
            }
            $model=ORM::factory('TestCompany',$obj_project->outside_com_id);
            $model->com_desc = trim($obj_project->outside_com_introduce) ? trim($obj_project->outside_com_introduce) : $model->com_desc;
            return $model;
        }
    }
    /**
     * @sso
     * 个人收藏企业项目
     * @author 钟涛
     */
    public function addProjectWatchInfo($user_id,$project_id){
        $data=ORM::factory('Projectwatch');
        $result=$data->select('*')->where('watch_user_id','=',$user_id)->where('watch_project_id','=',$project_id)->find();
        if($result->watch_id != null){//已存在更新收藏状态
            $data->watch_status = 1;
            $data->watch_update_time = time();
            $data->update();
        }else{//添加新的一条记录信息
            $data->watch_user_id = $user_id;
            $data->watch_project_id = $project_id;
            $data->watch_status = 1;
            $data->watch_update_time = time();
            $data->watch_add_time = time();
            $data->create();
        }
        if($user_id && $project_id){//个人用户添加活跃度by钟涛
            #找项目
            $msg = new Service_User_Ucmsg();
            $projectList = ORM::factory('Project',$project_id)->as_array();
            if($projectList['project_id'] > 0){
                #找企业信息 $project_id['com_id']
                $comList = ORM::factory('Usercompany',$projectList['com_id'])->as_array();
                if($comList['com_id'] > 0){
                    #找用信息
                    //sso 赵路生 2013-11-12
                    //$userModel = ORM::factory("User")->where('user_id','=',intval($user_id))->where('user_type',"=",intval(2))->find()->as_array();
                    $userinfo = Service_Sso_Client::instance()->getUserInfoById($user_id);
                    if($userinfo->user_type != 2){
                        return false;
                    }else{
                        $userModel = array();
                        foreach($userinfo as $key=>$value){
                            $userModel[$key] = $value;
                        }
                    }
                    #用户详细表
                    $arr_xiangxi_data = DB::select()->from("user_person")->where("per_user_id","=", $userModel['id'])->execute()->as_array();
                    $user_name = "";
                    if($arr_xiangxi_data[0]['per_id'] > 0 && $arr_xiangxi_data[0]['per_realname']){
                        $user_name = $arr_xiangxi_data[0]['per_realname'];
                    }else{
                        $user_name = $userModel['user_name'];
                    }

                    //$msg->pushMsg($comList['com_user_id'],'company_sc',"投资者   <a  target='_blank' href='".URL::website('platform/SearchInvestor/showInvestorProfile?userid=').$user_id."'>".$user_name." </a> 收藏了您的<a target='_blank' href='".urlbuilder::project($projectList['project_id'])."'>'".$projectList['project_brand_name']."'</a>项目",URL::website('platform/SearchInvestor/showInvestorProfile?userid=')."{$user_id}");
                    $smsg = Smsg::instance();
                    //内部消息发送
                    $smsg->register(
                            "tip_company_sc",//个人收藏我的项目
                            Smsg::TIP,//类型
                            array(
                                    "to_user_id"=>$comList['com_user_id'],
                                    "msg_type_name"=>"company_sc",
                                    "to_url"=>URL::website('platform/SearchInvestor/showInvestorProfile?userid='.$user_id)
                            ),
                            array(
                                    "url"=>URL::website('platform/SearchInvestor/showInvestorProfile?userid=').$user_id."'>".$user_name,
                                    "purl"=>urlbuilder::project($projectList['project_id']),
                                    "name"=>$projectList['project_brand_name']

                            )

                    );



                }
            }
            $ser1=new Service_User_Person_Points();
            $ser1->addPoints($user_id, 'favorite_project');//收藏项目
            #发送给企业的信息
        }
    }//funtion end

    /**
     * 个人取消收藏企业项目[可批量]
     * @author 钟涛
     */
    public function updateProjectWatchInfo($user_id,$project_arr){
        if(!empty($project_arr)){
            foreach($project_arr as $projectid){
               $data=ORM::factory('Projectwatch');
               $result=$data->where('watch_user_id','=',$user_id)->where('watch_project_id','=',$projectid)->find_all();
               if(count($result)){
                   foreach($result as $vs){
                       $vs->watch_status = 0;
                       $vs->watch_update_time = time();
                       $vs->update();
                   }
               }
            }
        }
    }//funtion end

    /**
     * 收藏企业项目数量
     * @author 钟涛
     */
    public function getProjectWatchCount($user_id,$project_id){
        return ORM::factory('Projectwatch')
        ->where('watch_user_id', '=', $user_id)//用户id
        ->where('watch_project_id','=',$project_id)//项目id
        ->where('watch_status','=',1)//默认1启用
        ->count_all();
    }//funtion end

    /**
     * 添加从项目官网链接到公司网址记录信息
     * @author 钟涛
     */
    public function addProjectOutLog($user_id,$postdata){
        $data=ORM::factory('Projectoutlog');
        $data->project_id = $postdata['projectid'];
        $data->url = $postdata['hrefurl'];
        $data->ip = ip2long(Request::$client_ip);
        $data->user_id = $user_id;
        $data->add_time = time();
        $data->create();
    }//funtion end



    /**
     * 记录个人用户最近浏览的项目与招商会情况
     * $user_id 用户id ;$operate_id项目id或者招商会id;$operate_type 1项目id;2招商会id
     * @author 钟涛
     */
    public function addPersonBehaviour($user_id,$operate_id,$operate_type){
        if(intval($operate_id) && intval($operate_type) && ($operate_type == intval(1) || $operate_type == intval(8))){
            $project = ORM::factory('Project')->where('project_id','=',$operate_id)->where('project_status','=',2)->find();
            if($project->project_id != "" && $project->project_id > 0){
            $ip=ip2long(Request::$client_ip);
            $data=ORM::factory('UserViewProjectLog')->where('user_id','=',$user_id)->where('operate_id','=',$operate_id)->where('operate_type','=',$operate_type)->where('ip','=',$ip)->find();
            if($data->loaded() && $operate_type == 1){
                    $data->update_time=time();
                    $data->update();
            }else{
                if($operate_id && $operate_type){//确保添加数据正确性
                    $data->user_id = $user_id;
                    $data->operate_id = $operate_id;
                    $data->operate_type = $operate_type;
                    $data->ip = ip2long(Request::$client_ip);
                    $data->add_time = time();
                    $data->update_time = time();
                    $data->create();
                }
            }
            if(intval($operate_type) == 1){
                    $history_project = Cookie::get('history_project') ? unserialize(Cookie::get('history_project')) : array();
                    $arr = array();
                    $arr1 = array();
                    $arr2 = array();
                    $arr3 = array();
                    if(empty($history_project)){
                        $arr[] = array('id' => $data->id, 'operate_id' => $operate_id);
                    }elseif(!empty($history_project)){
                        $arr = $history_project;
                        $arr1['id'] = $data->id;
                        $arr1['operate_id'] = $operate_id;
                        array_unshift($arr, $arr1);
                        //去重
                        foreach ($arr as $k=>$v){
                            if (!in_array($v['operate_id'], $arr2)){
                                $arr2[]=$v['operate_id'];
                                array_push($arr3,$v);
                            }
                        }
                        $arr = array_slice($arr3, 0,10);
                    }
                    Cookie::set('history_project', serialize($arr),2592000);
            }
        }
      }else{
              $arr_data = array();
              $arr_data = explode(" ",$operate_id);
              #判断用户是不是个人还是企业 还是没有登录
              //$arr_user_data = ORM::factory('User',intval($user_id))->as_array();
              //if($arr_user_data['user_id'] > 0 && $arr_user_data['user_type'] == intval(2)){
                  foreach ($arr_data as $key=>$val){
                      #判断是不是审核通过的招商会
                      $arr_data = $investes = ORM::factory('Projectinvest')->where('investment_id', '=', $val)->where('investment_status','=',intval(1))->find()->as_array();
                      #判断是不是企业自己查看
                      if(isset($arr_data) && $arr_data['investment_id'] > 0){
                          #判断是不是企自己查看
                          $model_com = ORM::factory("Usercompany",intval($arr_data['com_id']))->as_array();
                          //对于875同步过来的项目，com_id 大都为0 @花文刚
                          if($arr_data['com_id'] == 0 || (isset($model_com) && $model_com['com_user_id'] != intval($user_id))){
                              #直接进数据
                              $data = ORM::factory('UserViewProjectLog');
                              $data->user_id = $user_id;
                              $data->operate_id = $val;
                              $data->operate_type = $operate_type;
                              $data->ip = ip2long(Request::$client_ip);
                              $data->add_time = time();
                              $data->update_time = time();
                              $data->create();
                          }
                      }
                  }
              //}
      }
    }//funtion end

    /**
     * 获取个人用户最近浏览的项目与招商会情况
     * $user_id 用户id ;$operate_type 1项目id;2招商会id
     * @author 钟涛
     */
    public function getPersonBehaviour($user_id,$operate_type){
        if(intval($user_id) && intval($operate_type)){
            $data=ORM::factory('UserViewProjectLog')
                   ->where('user_id','=',$user_id)
                   ->where('operate_type','=',$operate_type)
                   ->order_by('uppdate_time', 'DESC')
                   ->find();
            if($data->loaded()){//有记录
                if($operate_type==1){//返回项目
                    $project=ORM::factory('Project',$data->operate_id);
                    return $project;
                }elseif($operate_type==2){//返回招商会
                    $invest=ORM::factory('Projectinvest',$data->operate_id);
                    return $invest;
                }else{
                    return false;
                }
            }else{//无记录
                return false;
            }
        }else{//传入值有误
            return false;
        }
    }//funtion end

    /**
     * 获取当天浏览项目数量
     * @author 钟涛
     */
    public function getPersonBehaviourCount($user_id,$projectid){
        if($user_id && $projectid){
            $today=strtotime(date('Y-m-d 00:00:00',time()));
            $data=ORM::factory('UserViewProjectLog')
                   ->where('user_id','=',$user_id)
                   ->where('operate_id','=',$projectid)
                   ->where('operate_type','=',1)
                   ->where('update_time','>=',$today)
                   ->count_all();
            return $data;
        }else{
            return -1;
        }
    }

    /**
     * 根据项目ID判断招商会是否存在
     * @author 潘宗磊
     */
    public function isProjectInvest($project_id,$preview=0){
        if($preview==1){
            //招商会预览
            $invest=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->count_all();
            $invest_will=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_status','=',1)->where('investment_start','>',time())->count_all();
            if($invest_will > 0){
                $projectInvest=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_status','=',1)->where('investment_start','>',time())->order_by('investment_start')->find();
            }
            else{
                $projectInvest=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_status','=',1)->order_by('investment_start','DESC')->find();
            }

        }else{
            $invest=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_status','=',1)->count_all();
        }
        if($invest>0){
            return isset($projectInvest->investment_id)?$projectInvest->investment_id:'a';
        }else{
            return false;
        }
    }

    /**
     * 根据项目ID返回招商会最近开始的信息
     * @author 潘宗磊
     * @param int preview 1预览招商会 0审核才能查看招商会
     */
    public function getProjectInvest($project_id,$preview=0){
        $now=time();
        if($preview==1){
            $invest=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_start','>=',$now)->order_by('investment_status','desc')->order_by('investment_start','asc')->limit(1)->find();
        }else{
            $invest=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_status','=',1)->where('investment_start','>=',$now)->order_by('investment_status','desc')->order_by('investment_start','asc')->limit(1)->find();
        }
        $province = ORM::factory("City",$invest->investment_province)->cit_name;
        $city = ORM::factory("City",$invest->investment_city)->cit_name;
        //距离招商会报名时间还剩多少天
        $spantime=floor(($invest->investment_start+24*60*60-$now)/(24*3600));
        $resault_array['investment_name'] = $invest->investment_name;
        if ($invest->investment_type==2&&$invest->outside_investment_id!=""){
            $resault_array['investment_address'] = $invest->investment_address;
        }
        else
            $resault_array['investment_address'] = $province.$city.$invest->investment_address;
        $resault_array['investment_start'] = date('Y.m.d',$invest->investment_start);
        $resault_array['investment_end'] = date('Y.m.d',$invest->investment_end);
        $resault_array['spantime'] = $spantime;
        $resault_array['investment_logo'] = URL::imgurl($invest->investment_logo);
        $resault_array['investment_id'] = $invest->investment_id;
        $resault_array['com_name'] = $invest->com_name;
        $resault_array['com_phone'] = $invest->com_phone;
        $resault_array['investment_agenda'] = $invest->investment_agenda;
        //招商简介
        $resault_array['investment_details'] = $invest->investment_details;
        $resault_array['investment_preferential'] = $invest->investment_preferential;
        //外网信息
        $resault_array['investment_type'] = $invest->investment_type;
        $resault_array['outside_investment_id'] = $invest->outside_investment_id;

        return $resault_array;
    }
    /**
     * 根据招商会ID返回招商会的信息
     * @author 潘宗磊
     */
    public function getInvestById($invest_id,$com_id){
        $now=time();
        $invest = ORM::factory("Projectinvest",$invest_id)->as_array();

        $invest_bak = ORM::factory("Investbakup",$invest_id);
        if($invest_bak->invest_id && $invest['com_id'] == $com_id){
            $invest = array_merge($invest,unserialize($invest_bak->content));
        }
        $province = ORM::factory("City",$invest['investment_province'])->cit_name;
        $city = ORM::factory("City",$invest['investment_city'])->cit_name;
        //距离招商会报名时间还剩多少天
        $spantime=floor(($invest['investment_start']+24*60*60-$now)/(24*3600));
        $resault_array['investment_name'] = $invest['investment_name'];

        if ($invest['investment_type'] == 2 && $invest['outside_investment_id'] != "")
            $resault_array['investment_address'] = $invest['investment_address'];
        else
            $resault_array['investment_address'] = $province.$city.$invest['investment_address'];

        $resault_array['investment_start'] = $invest['investment_start'];
        $resault_array['investment_end'] = $invest['investment_end'];
        $resault_array['spantime'] = $spantime;
        $resault_array['investment_logo'] = URL::imgurl($invest['investment_logo']);
        $resault_array['investment_id'] = $invest['investment_id'];
        $resault_array['com_name'] = $invest['com_name'];
        $resault_array['com_phone'] = $invest['com_phone'];
        $resault_array['investment_agenda'] = $invest['investment_agenda'];
        $resault_array['investment_city'] = $city;
        //招商简介
        $resault_array['investment_details'] = $invest['investment_details'];
        $resault_array['investment_preferential'] = $invest['investment_preferential'];

        //报名人数
        $in_num = ORM::factory("Applyinvest")->where('invest_id','=',$invest_id)->count_all();
        if ($invest['com_id'] == $com_id)
            $resault_array['in_num'] = $in_num;
        else
            $resault_array['in_num'] = $in_num + $invest['investment_virtualapply'];

        //外网信息
        $resault_array['investment_type'] = $invest['investment_type'];
        $resault_array['outside_investment_id'] = $invest['outside_investment_id'];

        //虚拟意向人数
        $resault_array['virtual_viewer'] = $invest['virtual_viewer'];

        return $resault_array;
    }

    /**
     * 根据项目ID返回招商会正在开始或者为开始的个数
     * @author 潘宗磊
     */
    public function getProjectInvestCount($project_id){
        $project=ORM::factory('Project',$project_id);
        $count=ORM::factory('Projectinvest')->where('project_id','=',$project_id)->where('investment_status','=',1)->count_all();
        return $count;
    }

    /**
     * 根据项目ID返回项目资质信息
     * @author 潘宗磊
     */
    public function getProjectCertsByID($project_id){
        $project=ORM::factory('Projectcerts')->where("project_id","=",$project_id)->where("project_type","=",2)->find_all()->as_array();
        $result = array();
        if(count($project) > 0){
            foreach ($project as $k=>$v){
                $result['b_image'][$k]['image'] = URL::imgurl(str_replace('/s_','/b_', $v->project_img));
                //$result['b_image'][$k]['name'] = '项目资质图片';
            }
        }
        return $result;
    }

    /**
     * 根据项目ID返回项目资质信息
     * @author 潘宗磊
     */
    public function getProjectCertesByID($project_id){
        $project=ORM::factory('Projectcerts')->where("project_id","=",$project_id)->where("project_type","=",2)->find_all()->as_array();
        $str = "";
        if(count($project) > 0){
            foreach ($project as $k=>$v){
                $str .= "<info>";
                $str.= '<image>'.URL::imgurl(str_replace('/s_','/b_', $v->project_img))."</image>";
                $str .= "<name>".$v->project_imgname."</name>";
                $str.= "</info>";
            }
        }
        return $str;
    }

    /**
     * 根据项目ID返回项目资质信息
     * @author 钟涛
     */
    public function getProjectCertesByIDNew($project_id){
        $project=ORM::factory('Projectcerts')->where("project_id","=",$project_id)->where("project_type","=",2)->find_all()->as_array();
        $result = array();
        if(count($project) > 0){
            foreach ($project as $k=>$v){
                $result[$k]['b_image'] = URL::imgurl(str_replace('/s_','/b_', $v->project_img));
                $result[$k]['s_image'] = URL::imgurl($v->project_img);
                $result[$k]['content'] = $v->project_imgname;
            }
        }
        return $result;
    }

    /**
     * 根据项目ID返回企业资质信息
     * @author 潘宗磊
     */
    public function getProjectCompanyByID($project_id){
        $com_id=ORM::factory('Project',$project_id)->com_id;
        $user_id = ORM::factory("Companyinfo",$com_id)->com_user_id;
        $project=ORM::factory('CommonImg')->where("user_id","=",$user_id)->where("table_name","=",1)->find_all()->as_array();
        $result = array();
        if(count($project) > 0){
            foreach ($project as $k=>$v){
                $result['b_image'][$k]['image'] = URL::imgurl($v->url);
            }
        }
        return $result;
    }

    /**
     * 根据项目ID返回企业资质信息
     * @author 潘宗磊
     */
    public function getProjectCompanysByID($project_id){
        $com_id=ORM::factory('Project',$project_id)->com_id;
        $user_id = ORM::factory("Companyinfo",$com_id)->com_user_id;
        $project=ORM::factory('CommonImg')->where("user_id","=",$user_id)->where("table_name","=",1)->find_all()->as_array();
        $str = "";
        if(count($project) > 0){
            $field_name="";
            foreach ($project as $k=>$v){
                if($v->field_name=='com_business_licence'){
                    $field_name='企业营业执照';
                }elseif($v->field_name=='tax_certificate'){
                    $field_name='税务登记证';
                }elseif($v->field_name=='organization_credit'){
                    $field_name='组织机构代码证';
                }
                $str .= "<info>";
                $str.= '<image>'.URL::imgurl($v->url)."</image>";
                $str .= "<name>".$field_name."</name>";
                $str.= "</info>";
            }
        }
        return $str;
    }

    /**
     * 根据项目ID返回项目海报信息
     * @author 潘宗磊
     */
    public function getProjectPosterByID($project_id){
        $model=ORM::factory('Project',$project_id);
        $result = array();
        $result['poster_content'] = ORM::factory("ProjectposterContent",$project_id)->content;
       /* if($result['poster_content']){
            if(substr($result['poster_content'],-1) != "/"){
                $result['poster_content'] = $result['poster_content']."/";
            }
        }*/
        $result['project_logo'] = URL::imgurl($model->project_logo);
        $result['project_name'] = $model->project_brand_name;
        $result['project_source'] = $model->project_source;
        $result['outside_id'] = $model->outside_id;
        $result['project_summary'] = Text::limit_chars($model->project_summary,30);
        $imgs=ORM::factory('Projectcerts')->where('project_id', '=', $project_id)->where('project_type','=',1)->limit(4)->find_all();
        foreach ($imgs as $v){
            $result['b_imgs'][] = URL::imgurl(str_replace('/s_','/b_', $v->project_img));
            $result['s_imgs'][] = URL::imgurl($v->project_img);
        }
        return $result;
    }

    /**
     * 项目访问量统计
     * @author 曹怀栋
     * @param $int_project_id(项目id)  $page_type(页面类型)
     */
    public function insertProjectStatistics($project_id,$page_type = 1){
        #开启缓存
        $redis = Cache::instance ('redis');
        $service_user = new Service_User_Company_Project();
        $service = new Service_Platform_Project();
        if(!isset($project_id) || !is_numeric($project_id)){
            return false;
        }
        $projectindustry=ORM::factory('Projectindustry')->where('project_id', '=', $project_id)->find_all();
        if(count($projectindustry) > 0){
            foreach ($projectindustry as $v){
                if($v->industry_id < 8){
                    $industry_id = $v->industry_id;
                }
            }
        }
        if(!isset($industry_id)) $industry_id = 0;
        $statistics=ORM::factory('Projectstatistics');
        $statistics->project_id = $project_id;
        $statistics->industry_id = $industry_id;
        $statistics->ip = ip2long(Request::$client_ip);
        $statistics->page_type = intval($page_type);
        $statistics->insert_time = time();
        $obj_data = $statistics->create();
        $int_project_redis_count = $redis->get($project_id."_project_count");
        #如果存在  缓存加1  数据库更新
        if($obj_data->statistics_id > 0){
            if($int_project_redis_count){
                #存入缓存
                $redis->set($project_id."_project_count", $int_project_redis_count + 1,60*20);
                #更新入库
                $service_user->updateProjectByParam($project_id,array("project_pv_count"=>$int_project_redis_count + 1));
            }else{
                $project_count_num = $service->get_project_count($project_id);
                #放入缓存
                $redis->set($project_id."_project_count", $project_count_num,60*20);
                #更新入库
                $service_user->updateProjectByParam($project_id,array("project_pv_count"=>$project_count_num));
            }
            return true;
        }
        return  false;
    }

    /**
     * 获取网页
     * @author 潘宗磊
     */
     public function getOutIframe($url,$encode=''){
        $absoluteUrl = $url;
        $absolute = parse_url($absoluteUrl);
        $domain = $absolute['scheme'].'://'.$absolute['host'];
        $absoluteUrl = pathinfo($absoluteUrl);
        if(isset($absoluteUrl['extension'])){
            $absoluteUrl = $absoluteUrl['dirname'].'/';
        }else{
            $absoluteUrl = $absoluteUrl['dirname'].'/'.trim($absoluteUrl['basename']).'/';
        }
        $absolut=$url;
        $com = file_get_contents($absolut);

       //CSS linke href 替换
        $com = preg_replace("/(<link[^>]+href=)[\"\']?(\/[^\"\'\s]+\.css)[\"\']?([^>]+>)/i", "\${1}\"{$domain}\${2}\"\${3}", $com);
        $com = preg_replace("/(<link[^>]+href=)[\"\']?([^\"\'\s:]+\.css)[\"\']?([^>]+>)/i", "\${1}\"{$absoluteUrl}\${2}\"\${3}", $com);

        // scripts 标签替换
        $com = preg_replace("/(<script[^>]+src=)[\"\']?(\/[^\"\'\s]+)[\"\']?([^>]*?>)/i", "\${1}\"{$domain}\${2}\"\${3}", $com);
        $com = preg_replace("/(<script[^>]+src=)[\"\']?([^\"\'\s:]+)[\"\'\s>]{1}([^>]*?>)/i", "\${1}\"{$absoluteUrl}\${2}\"\${3}", $com);
        //css内容链接和图片地址替换
        $com = preg_replace("/(background|background-image):url\([\"\']?(\/[^\'\s)]+)[\"\']?\)/i", "\${1}:url(\"{$domain}\${2}\"\${3})", $com);
        $com = preg_replace("/(background|background-image):url\([\"\']?([^\'\s:)]+)[\"\']?\)/i", "\${1}:url(\"{$absoluteUrl}\${2}\"\${3})", $com);
        //图片路径替换
        $com = preg_replace("/(<img[^>]+src=[\"\']{1})(\/[^\"\'\s:]+[\"\']{1})([^>]+>)/i", "\${1}{$domain}\${2}\${3}", $com);
        $com = preg_replace("/(<img[^>]+src=[\"\']{1})([^\"\'\s:]+[\"\']{1})([^>]+>)/i", "\${1}{$absoluteUrl}\${2}\${3}", $com);
        //flash链接路径替换
        $com = preg_replace("/(<embed[^>]+src=[\"\']{1})(\/([^\"\'\s]+)\.swf)([\"\']{1}[^>]+>)/i", "\${1}{$domain}\${2}\${4}", $com);
        $com = preg_replace("/(<embed[^>]+src=[\"\']{1})(([^\"\'\s:]+)\.swf)([\"\']{1}[^>]+>)/i", "\${1}{$absoluteUrl}\${2}\${4}", $com);
        $com = str_replace('"images/','"'.$absoluteUrl.'images/',$com);
        $com = str_replace('\'images/','\''.$absoluteUrl.'images/',$com);
        //过滤875的js
        $com = str_replace('http://jt.875.cn/js/jquery.js','',$com);
        $com = str_replace('<script src="http://jt.875.cn/js/jquery.js"></script>','',$com);
        $com = preg_replace("/<meta[^>]>/i", "\${1}{$domain}\${2}\${3}", $com);
        $base_url = $absoluteUrl;
        preg_match_all('#<iframe(.*?)src="(.*?)".*?</iframe>#is', $com,$matches);
        $iframe_urls = Arr::get($matches,2);
        if($iframe_urls){
            foreach($iframe_urls as $url){
                if(strpos($url, "http://") === 0){
                }
                else{
                    $com = str_replace($url,$base_url.$url,$com);
                }
            }
        }
        preg_match_all('/<head[^>]*?>([\s\S]+?)<\/head>/i', $com, $head);
        preg_match_all('/<body[^>]*?>([\s\S]+)<\/(body|html)>/i', $com, $body);
        if (!isset($head[1][0])) return ;
        $head = $head[1][0];
        $head = preg_replace('/<meta\s+([\s\S]+?)>/i', '', $head);
        $head = preg_replace('/<title>([\s\S]+?)<\/title>/i', '', $head);
        if(!isset($body[1][0])) return ;
        $body = $body[1][0];
        $body = preg_replace('/<(\/?(?:body[^>]*?))>/i','',$body);
        if($encode==""){
            $str = mb_convert_encoding(trim($head).$body,'utf-8');
        }else{
            $str = mb_convert_encoding(trim($head).$body,'utf-8',$encode);
        }
        return  $str;
    }

    /**
     * 判断海报是否存在
     * @author 潘宗磊
     */
    public  function isPoster($projectId){
        $ispage = false;
        $poster=$this->getProjectPosterByID($projectId);
        $projectinfo = ORM::factory("Project",$projectId);
        if($projectinfo->project_real_order !=6){
            if($projectinfo->project_real_order == 4){
                //本站
                $posterModel = new Service_Platform_Poster();
                $ispage = $posterModel->isCollectPoster($poster['outside_id']);
            }elseif ($projectinfo->project_real_order == 1 || $projectinfo->project_real_order == 2 || $projectinfo->project_real_order == 3 || $projectinfo->project_real_order == 5){
                //875项目
                $url_images = ORM::factory("ProjectposterContent",intval($projectId))->content;
                if($url_images){
                    $ispage = true;
                }
            }
        }else{
            //外采
            $posterModel = new Service_Platform_Poster();
            $ispage = $posterModel->isCollectPoster($poster['outside_id']);
        }
        /*
        if($projectinfo->project_source==4){
            $posterModel = new Service_Platform_Poster();
            $ispage = $posterModel->isCollectPoster($poster['outside_id']);
        }elseif($projectinfo->project_source!=4){
            if($projectinfo->project_source == 1){
                $poster = ORM::factory("Projectposter",intval($projectId));
                if($poster->project_id > 0 && $poster->poster_type == 2 && $poster->poster_status == 2){
                    $url_images = ORM::factory("ProjectposterContent",intval($projectId))->content;
                    if($url_images){
                        $ispage = true;
                    }
                }
            }else{
                $poster875 = ORM::factory("ProjectposterContent",$projectinfo->project_id)->content;
                if($poster875 != ""){
                    $ispage = true;
                }
            }
        //}elseif($projectinfo->project_source==2||$projectinfo->project_source==3||$projectinfo->project_source==1){
        }
        */
        return $ispage;
    }

    /**
     * 返回认领项目信息
     * @author 钟涛
     */
    public  function getRenlingInfoData($projectId){
         $renlingmodel = ORM::factory ( 'ProjectRenling' )->where ( 'project_id', '=', $projectId )->where ( 'project_status', '=', 1 )->count_all ();
        return $renlingmodel;
    }

    /**
     * 添加认领项目信息
     * @author 钟涛
     */
    public  function addProjectRenlin($project_id,$com_id,$com_id_old,$data){
        $model = ORM::factory('ProjectRenling');
        $data = Arr::map(array("HTML::chars"), $data);
        $project_id=intval($project_id);
        $com_id=intval($com_id);
        $result['status'] = false;
        $model2 = ORM::factory('ProjectRenling')->where('com_id','=',$com_id)->where('project_id','=',$project_id)->find();
        try{
            if($model2->id != "" && $model2->id>0){//更新动作
                $model2->company_name=$data['companyename'];//填写的公司名称
                $model2->project_brand_name=$data['projectname'];//填写的项目名称
                $model2->project_principal_products=$data['mainproject'];//填写的主营产品
                $model2->project_phone=$data['com_phone'];//填写的座机电话(热线)
                //$model2->project_phone_fenjihao=$data['branch_phone'];//分机号
                $model2->project_contact_people=$data['com_contact'];//填写的联系人
                $model2->project_summary=$data['com_desc'];//项目简介
                $model2->project_status=0;//项目认领状态 0 提交审核中
                $model2->updatetime=time();
                $model2->update();
            }else{//添加动作
                $projectinfo = array(
                        'project_id' => $project_id,//项目id
                        'com_id' => $com_id,//企业id
                        'com_id_old' => $com_id_old,//原企业id
                        'company_name' =>$data['companyename'],//填写的公司名称
                        'project_brand_name' => $data['projectname'],//填写的项目名称
                        'project_principal_products' =>$data['mainproject'],//填写的主营产品
                        'project_phone' =>$data['com_phone'],//填写的座机电话(热线)
                        //'project_phone_fenjihao' => $data['branch_phone'],//分机号
                        'project_contact_people' =>$data['com_contact'],//填写的联系人
                        'project_summary' => $data['com_desc'],//项目简介
                        'project_status' =>0,//项目认领状态 0 提交审核中
                        'addtime' =>time(),
                        'updatetime' =>time()
                );
                $model->values($projectinfo)->create();
            }
            //更新项目表
            $promodel = ORM::factory('Project',$project_id);
            $promodel->project_addtime=time();
            $promodel->isrenling_project=1;
            $promodel->update();
            $result['status'] = true;
        }
        catch(Kohana_Exception $e){
            $result['status'] = false;
        }
        return $result;
    }

    /**
     * 删除认领项目图片信息
     * @author 钟涛
     */
    public function deleteRenlingProjectImages($id){
        $Projectcerts=ORM::factory("ProjectRenlingImage");
        $result = $Projectcerts->where("id", "=",$id)->find();
        //当这条数据存在的情况下，这删除这个数据并删除相应的图片
        if($result->id !=""){
            if(!empty($result->project_img)){
                $de_imge=URL::imgurl($result->project_img);
                $delete = common::deletePic($de_imge);
                if($delete != 1) return false;
            }
            $Projectcerts->delete($id);
            return true;
        }
        return false;
    }

    /**
     * 添加认领项目图片信息
     *
     * @author 钟涛
     */
    public function addRenlingProjectImages($data) {
        if (isset($data ['img']) && count ( $data ['img'] ) > 0) {
            $str='';
            foreach ( $data ['img'] as $k => $v ) {
                try {
                    $project = ORM::factory ( 'ProjectRenlingImage' );
                    $project->project_id = $data ['project_id'];
                    $project->project_img = common::getImgUrl($v);
                    $project->project_imgname = trim($data['name'][$k]);
                    $project->com_id = $data['com_id'];
                    $project->addtime = time ();
                    $resutcreate=$project->create ();
                    $str .= $data ['project_id'].'||'.$resutcreate->id.'||'.$v.',';
                } catch ( Kohana_Exception $e ) {
                    return false;
                }
            }
            if($str==''){
                return '';
            }
            return substr($str, 0, strlen($str)-1);
        }else{
            return false;
        }
    }

    /**
     * 添加项目的 赞
     * @author 郁政
     * @param  $user_id 用户id,$project_id 项目id
     */
    public function addApproving($user_id,$project_id){
        $user_id = intval($user_id);
        $project_id = intval($project_id);
        $ip = ip2long(Request::$client_ip);
        try {
            $approing = ORM::factory("UserApproingLog");
            $approing->user_id = $user_id;
            $approing->project_id = $project_id;
            $approing->log_time = time();
            $approing->ip = $ip;
            $res = $approing->save();
            if($res->id){
                $cache = Cache::instance ( 'memcache' );
                $zan_cache = $cache->get('zan_num_'.$project_id);
                $log_count = ORM::factory("UserApproingLog")->where('project_id','=',$project_id)->count_all();
                if($zan_cache){
                    $zan_cache = $zan_cache >= $log_count ? $zan_cache+1 : $log_count;
                    $cache->set('zan_num_'.$project_id, $zan_cache,3600*24*30);
                }else{
                    $cache->set('zan_num_'.$project_id, $log_count,3600*24*30);
                }
                $project = ORM::factory('Project')->where('project_id','=',$project_id)->find();
                if($project->loaded()){
                    $project->project_approving_count = $log_count;
                    $project->update();
                }
            }
        } catch (Kohana_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * 判断某用户是否赞过项目
     * @author 郁政
     * @param  $user_id 用户id,array $project_id 项目id数组
     */
    public function isApprovings($user_id,array $project_id){
        $result = array();
        $user_id = intval($user_id);
        if(empty($project_id)){
            return false;
        }
        foreach ($project_id as $v){
            $result[]['project_id'] = intval($v);
            $approing = ORM::factory("UserApproingLog")->where('user_id','=',$user_id)->where('project_id','=',intval($v))->find_all()->as_array();
            if(count($approing)>=1){
                $result[]['approving'] = 1;
            }else{
                $result[]['approving'] = 0;
            }
        }
        return $result;

    }
    /**
     * 获得项目赞的总数
     * @author 施磊
     */
    public function getApprovingCount($project_id) {
        $project_id = intval($project_id);
        $multiple = 1;
        $cache = Cache::instance ( 'memcache' );
        $approving_count = 0;
        $zan_cache = $cache->get('zan_num_'.$project_id);
        $log_count = ORM::factory("UserApproingLog")->where('project_id','=',$project_id)->count_all();
        if($zan_cache){
            $approving_count = $zan_cache >= $log_count ? $zan_cache : $log_count;
        }else{
            $approving_count = $log_count ? $log_count : rand(1, 10);
            $cache->set('zan_num_'.$project_id, $log_count,3600*24*30);
        }
        $ranking = ORM::factory("ProjectRankingList")->where('project_id','=',$project_id)->where('type','=',1)->find()->as_array();
        if($ranking['project_id'] != ""){
            $multiple = intval($ranking['multiple_30']);
        }
        return $approving_count*$multiple;
    }

    /**
     * 判断某用户是否赞过单个项目
     * @author 郁政
     * @param  $user_id 用户id,$project_id 项目id
     */
    public function isApproving($user_id,$project_id){
        $user_id = intval($user_id);
        $project_id = intval($project_id);
        $ip = ip2long(Request::$client_ip);
        $time = time()-86400;
        $count = 0;
        if($project_id == ""){
            return false;
        }
        if($user_id != 0){
            $count = ORM::factory("UserApproingLog")->where('user_id','=',$user_id)->where('project_id','=',$project_id)->where('log_time','>',$time)->count_all();
            return $count > 0 ? true : false;
        }else{
            $count = ORM::factory("UserApproingLog")->where('ip','=',$ip)->where('project_id','=',$project_id)->where('log_time','>',$time)->count_all();
              return $count > 0 ? true : false;
        }
    }


    /**
     * 判断该项目是否有最近正在进行的招商会
     * @author 郁政
     * @param  $project_id 项目id
     */
    public function issetInvestment($project_id){
        $result = array();
        $project_id = intval($project_id);
        $time = time();
        $investment = ORM::factory("Projectinvest")->where('project_id','=',$project_id)->where('investment_status','=',1)->where('investment_start','>',$time)->order_by('investment_start','asc')->limit(1)->find();
        if(isset($investment) && $investment->investment_id != ""){
            $result['investment_id'] = $investment->investment_id;
            $investment_start = $investment->investment_start;
            $date = date('m月d日',$investment_start);
            if(strpos($date, "0") == 0){
                $date = substr($date, 1);
            }
            $investment_city = $investment->investment_city;
            $city = ORM::factory("City")->where('cit_id','=',$investment_city)->find();
            $city = $city->cit_name;
            $info = $date."&nbsp;&nbsp;".$city."&nbsp;&nbsp;"."招商会报名中";
            $result['info'] = $info;
        }else{
            return false;
        }
        return $result;
    }
    /**
     * 获取项目2张图片
     * @author 嵇烨
     * @param 项目ID
     */
    public function getTwoProjectImgs($project_id, $outside_id = 0, $project_source = 0){
        if($project_id){
            $model = ORM::factory('Projectcerts');
            $Imgs = $model->where('project_id', '=', $project_id)->where('project_type', '=', 1)->find_all();
            $returnImgs = array();
            if(count($Imgs)>0){
                    $num = 1;
                    foreach ($Imgs as $k=>$v){
                        if($num > 2){
                            continue;
                        }
                        if($project_source == 5 || $project_source == 4) {
                            $project_img = $v->project_img;
                            $project_outside = $outside_id;
                            $project_img = str_replace("poster/html/ps_{$project_outside}/project_images/", "poster/html/ps_{$project_outside}/project_images/125_100/", $project_img);
                            $returnImgs[$v->project_certs_id] = URL::imgurl($project_img);
                        }else {
                            $returnImgs[$v->project_certs_id] = URL::imgurl($v->project_img);
                        }
                        $num++;
                    }
            }
            return $returnImgs;
        }
    }
      /**
     * 条件收索项目
     * @author 嵇烨
     * @param array() 以及  $type
     * @param  return array();
     */
    public function getSeachProjectList($arr_list = array(),$type = 0,$user_id = 0){
        $array = array('list' => array());
        $project_id = array();
        if(!empty($arr_list)){
            //获取招商行业的项目的id
            if(isset($arr_list['industry_id']) && $arr_list['industry_id']){
                $industryModel = ORM::factory("Projectindustry")->where("industry_id", "=", $arr_list['industry_id'])->where("status",'=','2')->find_all();
                $project_id[1] = array();
                foreach ($industryModel as $key=>$val){
                    $project_id[1][] = $val->project_id;
                }
            }
            //招商地区的项目的id
            if(isset($arr_list['area_id']) && $arr_list['area_id']){
                if($arr_list['area_id'] == 88){
                    $arr_Model = ORM::factory("Projectarea")->where("status","=","2")->find_all();
                }else{
                    $arr_Model = ORM::factory("Projectarea")->where("area_id", "=", $arr_list['area_id'])->where("status","=","2")->find_all();
                }
                $project_id[2] = array();
                foreach ($arr_Model as $key=>$val){
                    $project_id[2][] = $val->project_id;
                }
            }

            //招商投资金额的项目的id
            if(isset($arr_list['project_amount_type']) && $arr_list['project_amount_type']){
                $project_Model = ORM::factory("Project")->where("project_amount_type", "=", $arr_list['project_amount_type'])->where("project_status","=","2")->find_all();
                $project_id[3] = array();
                foreach ($project_Model as $key=>$val){
                    $project_id[3][] = $val->project_id;
                }
            }

            //招商投资风险的项目的id
            if(isset($arr_list['risk']) && $arr_list['risk']){
                $project_Model = ORM::factory("Project")->where("risk", "=", $arr_list['project_amount_type'])->where("project_status","=","2")->find_all();
                $project_id[4] = array();
                foreach ($project_Model as $key=>$val){
                    $project_id[4][] = $val->project_id;
                }
            }
            //招商投资招商形式的项目的id
            if(isset($arr_list['project_model']) && $arr_list['project_model']){
                $project_Model = ORM::factory("Projectmodel")->where("type_id", "=", $arr_list['project_model'])->where("status","=","2")->find_all();
                $project_id[5] = array();
                foreach ($project_Model as $key=>$val){
                    $project_id[5][] = $val->project_id;
                }
            }
            //招商投资招商会的项目的id
            if(isset($arr_list['project_investment_status']) && $arr_list['project_investment_status']){
                if($arr_list['project_investment_status'] == 0){
                    $project_Model = ORM::factory("Project")->where("project_status","=","2")->find_all();
                }else{
                    $project_Model = ORM::factory("Project")->where("project_investment_status", "=", isset($arr_list['project_investment_status'])?$arr_list['project_investment_status']:0)->where("project_status","=","2")->find_all();
                }
                $project_id[6] = array();
                foreach ($project_Model as $key=>$val){
                    $project_id[6][] = $val->project_id;
                }
            }
            $project_id = $this->getArrayIntersectProject($project_id);
            $project_Models = ORM::factory("Project");
            $count = 0;
            if($project_id) {
                $count  = $project_Models->where("project_id", "in", array_unique($project_id))->count_all();
            }
            if($count>0){
                $page = Pagination::factory ( array (
                    'total_items' => $count,
                    'items_per_page' => 10
                ) );
                if($type ==1 || $type==2 || $type == 0){
                    $list = $project_Models->where("project_id", "in", array_unique($project_id))->limit( $page->items_per_page )->offset ( $page->offset )->order_by("project_pv_count",'desc')->find_all();
                    $tempList = array();
                    foreach($list as $val) {
                        $tempList[] = $val->as_array();
                    }
                    $search = new Service_Platform_Search();
                    $array ['list'] = $search->pushProjectInfo($tempList,$user_id);
                }else{
                    $list = $project_Models->where("project_id", "in", array_unique($project_id))->limit( $page->items_per_page )->offset ( $page->offset )->order_by ( 'project_addtime', 'desc' )->find_all();
                    $tempList = array();
                    foreach($list as $val) {
                        $tempList[] = $val->as_array();
                    }
                    $search = new Service_Platform_Search();
                    $array ['list'] = $search->pushProjectInfo($tempList,$user_id);
                }
            }else{
                $page = Pagination::factory ( array (
                    'total_items' => 50,
                    'items_per_page' => 10
                ) );
                if($type ==1 || $type==2 || $type == 0){
                    $list = $project_Models->where("project_status", "=", 2)->limit( $page->items_per_page )->offset ( $page->offset )->order_by("project_pv_count",'desc')->find_all ();
                    $tempList = array();
                    foreach($list as $val) {
                        $tempList[] = $val->as_array();
                    }
                    $search = new Service_Platform_Search();
                    $array ['list_make_up'] = $search->pushProjectInfo($tempList,$user_id);
                }else{
                    $list = $project_Models->where("project_status", "=", 2)->limit( $page->items_per_page )->offset ( $page->offset )->order_by ( 'project_addtime', 'desc' )->find_all ();
                    $tempList = array();
                    foreach($list as $val) {
                        $tempList[] = $val->as_array();
                    }
                    $search = new Service_Platform_Search();
                    $array ['list_make_up'] = $search->pushProjectInfo($tempList,$user_id);
                }
            }
            $array ['page'] = $page;
        }
        $array['total_count'] = $count;
        return $array;
    }

    /**
     * 通过搜索条件来取的相同的项目id
     * @author 曹怀栋
     */
    public function getArrayIntersectProject($arr){
        $tmp_arr =  array();
        if (count($arr)>0){
            $tmp_arr = array_shift($arr);
            foreach($arr as $key=>$val) {
                $tmp_arr = array_intersect($tmp_arr,$val);
            }
        }
        return $tmp_arr;
    }

    /**
     * 根据问题id和对应的值返回相对应的内容
     * @author 郁政
     * @param  $qid 问题id,$val 对应的值
     */
    public function getQuestCont($qid,$val){
        $qid = intval($qid);
        $val = intval($val);
        $res = "";
        //根据问题id返回对应的内容
        if($qid == 1){
            $business = guide::attr1();
            $res = $business[$val];
            return $res;
        }elseif($qid == 2){
            $city = ORM::factory("City")->where('cit_id','=',$val)->find()->as_array();
            if($city['cit_id'] != ""){
                $res = $city['cit_name'];
                return $res;
            }else{
                return false;
            }
        }elseif($qid == 5){
            $connections = guide::attr5();
            $res = $connections[$val];
            return $res;
        }elseif($qid == 6){
            $industry = ORM::factory("Industry")->where('industry_id','=',$val)->find()->as_array();
            if($industry['industry_id'] != ""){
                $res = $industry['industry_name'];
                return $res;
            }else{
                return false;
            }
        }elseif($qid == 7){
            $money = guide::attr7();
            $res = $money[$val];
            return $res;
        }elseif($qid == 10){
            $investment = guide::attr10();
            $res = $investment[$val];
            return $res;
        }
        return $res;
    }

    /**
     * 根据行业id返回父id
     * @author 郁政
     * @param  $industry_id 行业id
     */
    public function getIndustryPid($industry_id){
        $res = 0;
        $industry_id = intval($industry_id);
        $industry = ORM::factory("Industry")->where('industry_id','=',$industry_id)->find()->as_array();
        if($industry['industry_id'] != ""){
            $res = $industry['parent_id'];
            return $res;
        }else{
            return false;
        }
    }

     /**
     * 根据地域id返回父id
     * @author 郁政
     * @param  $cit_id 城市id
     */
    public function getCityPid($cit_id){
           $res = 0;
        $industry_id = intval($cit_id);
        $industry = ORM::factory("City")->where('cit_id','=',$cit_id)->find()->as_array();
        if($industry['cit_id'] != ""){
            $res = $industry['pro_id'];
            return $res;
        }else{
            return false;
        }
    }
    /**
     * 更新项目的赞数和点击数
     * @author 郁政
     */
    public function updataApproingCount(){
        $project = ORM::factory("Project")->where('project_status','=',2)->find_all();
        try {
            foreach($project as $v){
                $approingCount = ORM::factory("UserApproingLog")->where('project_id','=',$v->project_id)->count_all();
                  $pvCount = ORM::factory("Projectstatistics")->where('project_id','=',$v->project_id)->count_all();
                   $project = ORM::factory('Project')->where('project_id','=',$v->project_id)->find();
                  $project->project_approving_count = $approingCount;
                 $project->project_pv_count = $pvCount;
                $project->update();
                      echo "项目id:".$v."更新成功<br/>";
                }
            } catch (Exception $e) {
                return "更新失败";
            }

        return "更新完成";
    }


    /**
     * 获得所有项目的总数
     * @author 施磊
     */
    public function getProjectCount() {
        $projectCount = ORM::factory("project")->where('project_status','=', 2)->count_all();
        return $projectCount;
    }
    /**
     * 根据id组获得项目数据
     * @author 施磊
     */
    public function getProjectByProjectIds($ids = array()) {
        $project = ORM::factory("project");
        if($ids) {
            $projectArr = $project->where('project_status','=', 2)->where("project_id", 'in', $ids)->find_all();
        }else{
            $projectArr = $project->where('project_status','=', 2)->find_all();
        }
        $return = array();
        foreach($projectArr as $val) {
            $return[] = $val->as_array();
        }
        return $return;
    }
    /**
     * 根据id获得项目 分页
     * @author 施磊
     */
    public function getProjectByIdsPage($ids = array(),$user_id = 0,$type = 0,$order = 0) {
        $mod = new Service_Platform_Search();
        $project = ORM::factory("project");
        $projectCount = 0;
        if($ids) {
            $projectCount = $project->where('project_status','=', 2)->where("project_id", 'in', $ids)->count_all();
        }else{
            $projectCount = $project->where('project_status','=', 2)->count_all();
        }
        $page = Pagination::factory ( array (
                    'total_items' => $projectCount,
                    'items_per_page' => 10,
                    'view' => 'pagination/Simple',
                    'current_page' => array('source' => 'fenlei', 'key' => 'page')
    ) );
        $return['page'] = $page;
        if($ids) {

            if($type == 1){
                if($order == 1){
                    $projectList = $project->where('project_status','=', 2)->where("project_id", 'in', $ids)->limit($page->items_per_page)->offset($page->offset)->order_by("project_updatetime","ASC")->find_all();
                }elseif($order == 2){
                    $projectList = $project->where('project_status','=', 2)->where("project_id", 'in', $ids)->limit($page->items_per_page)->offset($page->offset)->order_by("project_updatetime","DESC")->find_all();
                }
            }elseif($type == 2){
                if($order == 1){
                    $projectList = $project->where('project_status','=', 2)->where("project_id", 'in', $ids)->limit($page->items_per_page)->offset($page->offset)->order_by("project_pv_count","ASC")->find_all();
                }elseif($order == 2){
                    $projectList = $project->where('project_status','=', 2)->where("project_id", 'in', $ids)->limit($page->items_per_page)->offset($page->offset)->order_by("project_pv_count","DESC")->find_all();
                }
            }else{

                $projectList = $project->where('project_status','=', 2)->where("project_id", 'in', $ids)->limit($page->items_per_page)->offset($page->offset)->order_by("project_real_order","ASC")->order_by("project_id","DESC")->find_all();
            }
        }else{
            if($type == 1){
                if($order == 1){
                    $projectList = $project->where('project_status','=', 2)->limit($page->items_per_page)->offset($page->offset)->order_by("project_updatetime","ASC")->find_all();
                }elseif($order == 2){
                    $projectList = $project->where('project_status','=', 2)->limit($page->items_per_page)->offset($page->offset)->order_by("project_updatetime","DESC")->find_all();
                }
            }elseif($type == 2){
                if($order == 1){
                    $projectList = $project->where('project_status','=', 2)->limit($page->items_per_page)->offset($page->offset)->order_by("project_pv_count","ASC")->find_all();
                }elseif($order == 2){
                    $projectList = $project->where('project_status','=', 2)->limit($page->items_per_page)->offset($page->offset)->order_by("project_pv_count","DESC")->find_all();
                }
            }else{
                $projectList = $project->where('project_status','=', 2)->limit($page->items_per_page)->offset($page->offset)->order_by("project_real_order","ASC")->order_by("project_id","DESC")->find_all();
            }
        }
        $list = array();
        foreach($projectList as $val) {
            $list[] = $val->as_array();
        }
        $return['list'] = $mod->pushProjectInfo($list, $user_id);
        $return['projectCount'] = $projectCount;
        return $return;
    }

    /**
     * 项目向导分类没搜索到项目时显示
     * @author 郁政
     */
    public function showListByNoResult($user_id = 0){
        $mod = new Service_Platform_Search();
        $res = array();
        $page = Pagination::factory ( array (
                'total_items' => 50,
                'items_per_page' => 10,
                'view' => 'pagination/Simple',
                'current_page' => array('source' => 'fenlei', 'key' => 'page')
        ) );
        $res['page'] = $page;
        $project = ORM::factory('Project')->where('project_status','=',2)->limit($page->items_per_page)->offset($page->offset)->order_by("project_pv_count","DESC")->find_all();
        $list = array();
        foreach($project as $v) {
            $list[] = $v->as_array();
        }
        $res['list'] = $mod->pushProjectInfo($list, $user_id);
        return $res;
    }

    /**
     * 删除project表中手动添加的875项目
     * @author 郁政
     */
    public function delHandPro(){
        $project = ORM::factory('Project');
        $arr = array();
        $suc = 1;
        $res = $project->where('project_source', '=', 2)->where('outside_id','<>',0)->find_all();
        foreach($res as $v){
            $arr[] = $v->project_id;
        }
        try {
            echo "总计".count($arr)."<br/>";
            if($arr) {
            foreach($arr as $v){
                ORM::factory('Project',$v)->delete();
                        echo "删除项目id:$v<br/>";
                }
            }
        } catch (Exception $e) {
            return "删除失败！";
        }
        return "删除完成！";
    }

    /**
     * 更新项目的招商会状态
     * @author 郁政
     */
    public function updateInvestmentStatus(){
        $project_ids = array();
        $project_id = array();
        $time = time();
        $project = ORM::factory('Project')->where('project_status','=',2)->find_all();
        foreach($project as $v){
            $project_ids[] = $v->project_id;
        }
        try {
            $investment = ORM::factory('Projectinvest')->where('project_id','in',$project_ids)->where('investment_status','=',1)->where('investment_start','>=',$time)->find_all();
            foreach($investment as $v){
                $project_id[] = $v->project_id;
            }
            foreach ($project_ids as $v){
                $update0 = ORM::factory('Project')->where('project_id','=',$v)->find();
                $update0->project_investment_status = 0;
                $update0->update();
            }
            foreach($project_id as $v){
                $update1 = ORM::factory('Project')->where('project_id','=',$v)->find();
                $update1->project_investment_status = 1;
                $update1->update();

                echo "项目id:".$v."更新成功<br/>";
            }
        } catch (Exception $e) {
            return "更新失败！";
        }
        return "更新完成！";
    }

    /**
     * 获取即将开始或者历史的招商会
     */
    public function getWillInvest($project_id,$com_id,$history=""){
        $invest = array();
        $model = ORM::factory('Projectinvest')->where('project_id', '=', $project_id)->where('investment_status','=',1);
        if($history){
            $model->where('investment_start','<',time())->order_by('investment_start','desc');
        }else{
            $model->where('investment_start','>=',time())->order_by('investment_start','asc');
        }
        $investes = $model->find_all();
        foreach ($investes as $k=>$v){
             $invest[$k]['investment_id']=$v->investment_id;
             $invest[$k]['project_id']=$v->project_id;
             $invest[$k]['investment_logo']=URL::imgurl($v->investment_logo);
             $invest[$k]['investment_name']=$v->investment_name;
             $invest[$k]['investment_details']=Text::limit_chars($v->investment_details,30);
             $invest[$k]['investment_start']=$v->investment_start;
             $invest[$k]['investment_end']=$v->investment_end;
             $invest[$k]['spantime']=floor(($v->investment_start+24*60*60-time())/(24*3600));
             $investment_province = ORM::factory('City',$v->investment_province)->cit_name;
             $investment_city = ORM::factory('City',$v->investment_city)->cit_name;
             if ($v->outside_investment_id!=""&&$v->investment_type==2)
                 $invest[$k]['investment_address']=$v->investment_address;
             else
                 $invest[$k]['investment_address']=$investment_province.$investment_city.$v->investment_address;
             if($history && $v->investment_apply==0 && $v->com_id != $com_id){
                 $invest[$k]['investment_apply'] = $v->investment_virtualapply;
             }
             else
                $invest[$k]['investment_apply'] = $v->investment_apply;
        }
        return $invest;
    }

    /**
     * 根据项目id获取项目来源
     * @author 郁政
     */
    public function getProSource($project_id){
        $project_id = intval($project_id);
        $project = ORM::factory('Project',$project_id);
        return $project->project_source;
    }

    /**
     * 根据项目id返回是否显示投递名片按钮
     * @author 郁政
     */
    public function isShowSendCard($project_id){
        $project_id = intval($project_id);
        $project = ORM::factory('Project',$project_id);
        if($project->com_id != 0){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 根据项目id获取企业名称
     * @author 郁政
     */
    public function getComName($project_id){
        $project_id = intval($project_id);
        $project = ORM::factory('Project',$project_id);
        $com_id = $project->com_id;
        $com_name = ORM::factory('Companyinfo',$com_id)->com_name;
        return $com_name;
    }

    /**
     * 根据项目名称返回项目id
     * @author 郁政
     */
    public function getPidByPname($project_brand_name){
        $project = ORM::factory('Project')->where('project_brand_name','=',$project_brand_name)->where('project_status','=',2)->find();
        if($project->project_id != null){
            return $project->project_id;
        }
        return false;
    }

    /**
     * 获得单个项目的访问量
     * int $day 时间[单位为天，如-1为昨天，0表示今天，30表示近30天浏览量]
     * @author 郁政
     */
    public function getPvCountByProject($project_id,$day = 0){
        if(intval($project_id) && $project_id){
            $pvcount=ORM::factory('Projectstatistics')->where('project_id','=',$project_id);
            if($day==-1){//昨天
                $nowtime=strtotime(date('Y-m-d 00:00:00', time()-86400));//昨天
                $nowtime2=strtotime(date('Y-m-d 00:00:00', time()));//当天零点时间
                $pvcount->where('insert_time','<',$nowtime2);
            }elseif($day==30){//近30天
                $nowtime=strtotime(date('Y-m-d 00:00:00', time()-86400*30));//近30天
            }else{//默认今天
                $nowtime=strtotime(date('Y-m-d 00:00:00', time()));//当天零点时间
            }
            $pvcount->where('insert_time','>=',$nowtime);
            return $pvcount->count_all();
        }else{
            return 0;
        }
    }
    /**
     * 项目统计数据
     * @author jiye
     * date 2013/11/5
     * return int
     */
    public function get_project_count($int_project_id){
        if($int_project_id){
            return ORM::factory('Projectstatistics')->where("project_id","=",intval($int_project_id))->count_all();;
        }
        return 0;
    }
    /**
     * 项目官网页面获取历史咨询
     * @author 赵路生
     * @param int $project_id
     * return array
     */
    public function getProjectHistoryConsult($project_id){
        $result = array();

        if(intval($project_id)){
            $project_id = intval($project_id);
            $redis = Cache::instance('redis');
            $red_result = $redis->get('getProjectHistoryConsult'.$project_id);
            $red_result = '';
            if($red_result){
                return $red_result;
            }else{
                //获取历史咨询的资料
                $consult = ORM::factory('UserLetter')
                ->where('to_project_id','=',$project_id)
                ->where('user_type','=',2)
                ->where('letter_status','=',1)
                ->where('content','!=','')
                ->limit(50)->order_by('add_time','DESC')->find_all()->as_array();
                $per_service = new Service_User_Person_User();
                //获取用户数组信息
                $user_id_array = array();
                foreach($consult as $value){
                    $reply_id_array[] = $value->id;
                    $user_id_array[] = $value->user_id;
                    //获取企业回复内容
                    $letter_reply = $this->getProHisConByLetterId($value->id);
                    //获取ip
                    $user_info =   	$this->getIp($value->user_id);
                    //个人信息
                    $perinfo_ser = new Service_User_Person_User();
                    $perinfo = $perinfo_ser->getPerson($value->user_id);
                    $result[] = array(
                            'content' => $value->content,
                            'time' => date('Y-m-d',$value->add_time),
                            'reply_content' => $letter_reply ? $letter_reply->content:'',
                            'reply_time' => $letter_reply ? $letter_reply->add_time:'',
                            'last_login_ip' =>($user_info && long2ip($user_info->last_login_ip) !=='0.0.0.0') ? (substr_replace(long2ip($user_info->last_login_ip),"***",strrpos(long2ip($user_info->last_login_ip),'.'))):'',
                            'photo' => $perinfo?(stristr($perinfo->per_photo, 'http://static')?$perinfo->per_photo:(url::imgurl($perinfo->per_photo))):'',
                            'user_name'=>$perinfo?$perinfo->per_realname:''
                    );
                }
                $red_result = $redis->set('getProjectHistoryConsult'.$project_id,$result,86400);
            }
        }
        return $result;
    }//end function
    /**
     * 项目官网页面--历史咨询--企业回复内容获取
     * @author 赵路生
     * @param int  $letter_id
     * return array
     */
    public function getProHisConByLetterId($letter_id){
        $letter_id = intval($letter_id)?intval($letter_id):0;
        $return = false;
        $reply_model = ORM::factory('UserLetterReply')->where('letter_id','=',$letter_id)->find();
        if($reply_model->loaded()){
            $return = $reply_model;
        }
        return $return;
    }//end function
    /**
     * 项目官网页面--历史咨询--获取用户ip
     * @author 赵路生
     * @param int $user_id_array
     * return array
     */
    public function getIp($user_id){
        $user_id = intval($user_id)?intval($user_id):0;
        $return = false;
        $user_model = ORM::factory('User')->where('user_id','=',$user_id)->find();
        if($user_model->loaded()){
            $return = $user_model;
        }
        return $return ;
    }

    /**
     * 获取项目的seo设置
     * @author 郁政
     */
    public function getProSeo($project_id){
        $res = array();
        $om = ORM::factory('ProjectSeo')->where('project_id','=',$project_id)->find();
        if($om->loaded()){
            $res['title'] = $om->title;
            $res['keyword'] = $om->keyword;
            $res['description'] = $om->description;
        }
        return $res;
    }

    /**
     * 记录项目发布的统计
     * @author 许晟玮
     */
    public function setProjectStat( $info=array() ){
        $orm= ORM::factory('ProjectStat');
        $orm->project_id= Arr::get($info, 'project_id');
        $orm->user_id= Arr::get($info, 'user_id',0);
        $orm->action_ip= ip2long(Request::$client_ip);
        $orm->action_time= time();
        $orm->sid= Arr::get($_COOKIE, 'Hm_lvqtz_sid',10000);
        $orm->fromdomain= Arr::get ( $_COOKIE, 'Hm_lvqtz_refer' );
        $orm->convert= common::convertip( Request::$client_ip );
        $orm->aid= Cookie::get('cpa_aid','');
        $orm->campaignid= Arr::get($_COOKIE, 'campaignid');
        $orm->adgroupid= Arr::get($_COOKIE, 'adgroupid');
        $orm->keywordid= Arr::get($_COOKIE, 'keywordid');

        $result= $orm->create();
        if( $result->id>0 ){
            return true;
        }else{
            return false;
        }
    }
     //end function
}
