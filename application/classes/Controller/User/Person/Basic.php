<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户基本信息
 * @author 周进
 */
class Controller_User_Person_Basic extends Controller_User_Person_Template{

    /**
    * 个人用户中心首页
    * @author 潘宗磊
    */
    public function action_index(){
        $content = View::factory("user/person/usercenter");
        $this->content->rightcontent = $content;
        $user = $this->userInfo(true);
        $service = Service::factory(
                array(
                        'project'=>"User_Company_Project",
                        'user'   =>"User",
                        'person' =>"User_Person_Card",
                        'card' => "Card",
                        'per_project' => "User_Person_Project"
                )
        );
        $LogsModel = new Service_User_UserLoginLog();
        $loginLog = $LogsModel->selectLastLogin($user->user_id);
        //$com_id = $user->user_person->per_id;
        $last_logintime = $user->basic->last_logintime;
        //最后登录时间年月日
        $login_time_ymd = date("Y.m.d",$last_logintime);
        $login_time_his = date("H:i:s",$last_logintime);
        //收到的名片总数量
        $content->receive_card_count = $service->person->getReceiveCardFromTime($user->user_id);
        //递出名片总数量
        $content->out_card_count = $service->person->getOutCardCount($user->user_id);
        //距离上次登录收到的名片
        $content->receive_card_lastlogin = $service->person->getReceiveCardFromTime($user->user_id,$last_logintime);
        //获取收到的名片2张
        $return_arr=$service->person->twoReceiveCard($user->user_id,2);
        //我收藏的名片总数
        $content->favorite_count=$service->person->getFavoriteCardCount($this->userInfo()->user_id);
        //名片列表
        $content->card_list = $service->person->getAllSerializeArrayList($return_arr['list']);

        // 获取我咨询过的项目
        // 获取金额的
        $monarr = common::moneyArr();
        $service_card = new Service_User_Person_Card();
        $return_arr = $service_card->getHistoryConsultArray($user->user_id);
        $return_arr_slice = count($return_arr) > 2 ? array_slice($return_arr, 0, 2,true):$return_arr;
        $consult_list = array();
         if(count($return_arr_slice)>0){
             $project_com_service = new Service_User_Company_Project();
             $project_service = new Service_Platform_Project();
            foreach($return_arr_slice as $value){
                //获取地区
                $pro_area = $project_com_service->getProjectArea($value->to_project_id);
                $area = '';
                if($pro_area == '全国'){
                    $area = $pro_area;
                }else{
                    foreach($pro_area as $v){
                        $area .= $v.' ';
                    }
                }
                //获取项目的信息
                $projectinfo = $project_service->getProjectInfoByIDAllNew($value->to_project_id);
                $url = URL::webstatic('images/common/company_default.jpg');
                if($projectinfo){
                    //项目logo图片处理
                    if($projectinfo->project_source != 1) {
                        $tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());
                        if(!project::checkProLogo($tpurl)){
                            $tpurl = $url;
                        }
                    }else{
                        $tpurl=URL::imgurl($projectinfo->project_logo);
                        if(!project::checkProLogo($tpurl)){
                            $tpurl = $url;
                        }
                    }
                    $consult_list[] = array($value->to_project_id,$area,$tpurl,$projectinfo->project_brand_name,Arr::get($monarr,$projectinfo->project_amount_type,'暂无'),$value->content,date('Y-m-d H:i:s', $value->add_time),$projectinfo->project_status);
                }else{
                    $consult_list[] = array($value->to_project_id,$area,$url,'','暂无',$value->content,date('Y-m-d H:i:s', $value->add_time),0);
                }
            }
        }
        $content->consult_list = $consult_list;

        //我收藏的项目列表
        $project_list_tmp = $service->per_project->getWatchProjecList($user->user_id);
        $content->project_list_count = count($project_list_tmp);
           $content->project_list = count($project_list_tmp) > 5 ? array_slice($project_list_tmp, 0, 5,true):$project_list_tmp;
           $content->login_time_ymd = $login_time_ymd;
        $content->login_time_his = $login_time_his;
        $level_per=new Service_User_Person_Points();
        $monthmoder=new  Service_Platform_SearchInvestor();
        $content->level=$level_per->getScoreLevel($user->user_id);
        $content->monthlevel=$monthmoder->getNearMonthScore($user->user_id);
        $content->user = $user;
        //登陆记录
        $content->loginLog = $loginLog;
        //用户登录名
        if( $user->from_type=='1' ){
           if( $user->email!='' ){
            $content->userName = empty($user->user_person->per_realname)?$user->email:$user->user_person->per_realname;
           }else{
               $content->userName= $user->mobile;
           }
        }else{
            $content->userName= $user->user_name;
        }
        //判断会员是否绑定过帐号


        //用户性别
        $content->sex = $user->user_person->per_gender;
        //个人是否有从业经验
        $user_service= new Service_User_Person_User();
        $content->experiences = $user_service->isExperienceExist($user->id);
        //个人信息是否完善
        $content->is_complete_basic = $this->is_complete_basic($user->user_id);
        //身份证验证是否通过审核
        $content->identificationCard_status=$user->user_person->per_auth_status;
        //名片公开度
        $content->per_open_stutas=$user->user_person->per_open_stutas;

        $rs_person= $user_service->getPerson($user->user_id);
        $user_photo = $rs_person->per_photo;
        $content->user_photo= $user_photo;

        //判断用户是否是导入的
        $inuser= $user->old_soure;
        $content->inuser= $inuser;
        $eof= $service->user->getEditEmailLog( $this->userId() );
        if( $eof===true ){
            //已修改过邮箱
            $content->eof= '1';
        }else{
            //未修改过
            $content->eof= '0';
        }
        $get = Arr::map("HTML::chars", $this->request->query());
        $error= Arr::get($get, 'error');
        $content->error= $error;
        $open_eof= $service->user->openEditEmailEof( $this->userId() );
        if( $open_eof===false ){
            //下次不弹出
            $content->showemailwindow= '1';
        }else{
            //下次弹出
            $content->showemailwindow= '0';
        }
        //获取创业币总数
        $huodong = new Service_User_Person_Huodong();
        $chuangyebiCount = $huodong->getChuangYeBiCount($user->user_id);
        $content->chuangyebiCount = $chuangyebiCount;
        $spp = new Service_User_Person_Project();
        $sps = new Service_Platform_Search();
        $spg = new Service_Platform_ProjectGuide();
        $memcache= Cache::instance('memcache');
        $cache = Rediska_Manager::get("rc");
        //为你推荐
        $pid_tuijian = array();
        $arr_tuijian = array();
        $pid_tuijian = $spp->getTuiJianForYouNew($this->userId(), 20);
        $arr_tuijian = $sps->_getProjectInfoByarr($pid_tuijian);
        //echo "<pre>";print_r($arr_tuijian);exit;
        //猜你喜欢
        $pid_xihuan = array();
        $arr_xihuan = array();
        $arr_xihuan = $memcache->get('guess_you_like');
        if(!$arr_xihuan){
            $pid_xihuan = $spg->getHardProByConfig(20);
            $arr_xihuan = $sps->_getProjectInfoByarr($pid_xihuan);
            $memcache->set('guess_you_like', $arr_xihuan,7200);
        }
        //echo "<pre>";print_r($arr_xihuan);exit;
        //最热项目
        $pid_zuire = array();
        $arr_zuire = array();
        $arr_zuire = $memcache->get('best_hot_project_list');
        if(!$arr_zuire){
            $arr1 = $spg->getSpecialPro(12, 1);
            $arr2 = $spg->getImpAdProByConfig(8);
            $pid_zuire = array_merge($arr1,$arr2);
            $arr_zuire = $sps->_getProjectInfoByarr($pid_zuire);
            $memcache->set('best_hot_project_list', $arr_zuire,86400);
        }
        //echo "<pre>";print_r($arr_zuire);exit;
        //最新商机
        $new = array();
        $pid_zuixin = array();
        $arr_zuixin = array();
        $arr_zuixin = $memcache->get('best_new_project_list');
        if(!$arr_zuixin){
            $arr1 = $spg->getPersonNewTuiJian(8,1);
            $arr2 = $spg->getImpAdProByConfig(6);
            $arr3 = $spg->getPersonNewTuiJian(6,4);
            $pid_zuixin = array_merge($arr1,$arr2,$arr3);
            $arr_zuixin = $sps->_getProjectInfoByarr($pid_zuixin);
            $memcache->set('best_new_project_list', $arr_zuixin,86400);
        }

        //echo "<pre>";print_r($arr_zuixin);exit;
        $content->tuijian = $arr_tuijian;
        $content->xihuan = $arr_xihuan;
        $content->zuire = $arr_zuire;
        $content->zuixin = $arr_zuixin;

        $session = Session::instance();
        $oauth_userInfo = $session->get('oauth_userInfo');
        $oauth_id= Arr::get( $oauth_userInfo , 'id');
        $ino= Service_Sso_Client::instance()->getOauthInfoById( $oauth_id,$this->userId() );
        if( $ino['oauth_id']=='' ){
            $ino['bind_user_id']= '-1';
        }
        $content->oauinfo= $ino;
        
        // 【开始】这里是用来好项目--设置 用户medal的内容 @赵路生
        $setting = common::getSpecificProjectSetting();
        $content->setting = $setting;
        $specific_users = array();
        if(time() > $setting['end_time']){
        	$card_sr = new  Service_Card();
        	$result = $card_sr->getUserTypeForSpePro();
        	$specific_users = isset($result['users']) ?$result['users']: array();
        }
        $content->specific_users = $specific_users;
        // 【结束】这里是用来好项目
        
    }
    /**
    * 修改个人登录密码
    * @author 周进
    */
    public function action_modifyPassword(){
        if($this->request->method()== HTTP_Request::POST){
            $content = View::factory('user/person/modifypassword');
            $post = Arr::map("HTML::chars", $this->request->post());
            $service = new Service_User_Person_User();

            $result = $service->modifyPassword($post,$this->userId());
            //修改密码是否成功
            if($result == 1){
                $userinfo= $this->userInfo();
                if( $userinfo->valid_mobile=='1' ){
                   $mobile= $userinfo->mobile;

                   //send mobile
                   $smsg = Smsg::instance();
                   //内部消息发送
                   $smsg->register(
                           "mobile_person_edit_password",
                           Smsg::MOBILE,//类型
                           array(
                                   "receiver"=>$mobile
                           )
                   );
                }

                $email= $userinfo->email;
                //send email
                $smsg = Smsg::instance();
                $smsg->register(
                        "email_person_edit_password",
                        Smsg::EMAIL,//类型
                        array(
                                "subject"=>'一句话修改密码通知',
                                "to_email"=>$email
                        ),
                        array(
                                "name"=>$userinfo->user_name
                        )
                );


                $content->password = 'ok';
            }
        }else{
            $content = View::factory('user/person/modifypassword');
        }
        $this->content->rightcontent = $content;
    }

    /**
     * 个人用户活跃度积分明细
     * @author 钟涛
     */
    public function action_itylist(){
        $content = View::factory("user/person/itylist");
        $this->content->rightcontent = $content;
        $points_ser = new Service_Platform_SearchInvestor();
        $userid = $this->userInfo()->user_id;
        $score_total = $points_ser->getAllScore($userid);//获取总活跃度值
        $score_near_month = $points_ser->getNearMonthScore($userid); //这里获取的是近30天的活跃度值
        $points_ser = new Service_User_Person_Points();
        $score_level = $points_ser->getScoreLevel($userid);//获取总活跃度等级
        $month_arr = $points_ser->getRencentMonth();//获取最近6个月的数组
        $content->score_total = $score_total;
        $content->score_near_month = $score_near_month;
        $content->score_level = $score_level;
        $content->month_arr = $month_arr;
        //获取创业币总数
        $huodong = new Service_User_Person_Huodong();
        $chuangyebiCount = $huodong->getChuangYeBiCount($userid);
        $content->chuangyebiCount = $chuangyebiCount;
        $post = Arr::map("HTML::chars", $this->request->query());
        $type = arr::get($post, 'type',1);
        if($this->request->method()== HTTP_Request::GET){
            if(arr::get($post, 'yearandmonth',0)){
                if($type == 1){
                    $scorelist=$points_ser->getScoreList(arr::get($post, 'yearandmonth'), $userid);
                }elseif($type == 2){
                    $scorelist=$huodong->getChuangYeBiList($userid, arr::get($post, 'yearandmonth'));
                }
                $content->score_list = $scorelist['list'];
                $content->page = $scorelist['page'];
            }else{
                if($type == 1){
                    $scorelist=$points_ser->getScoreList(strtotime(date('Y-m',time())), $userid);
                }else{
                    $scorelist=$huodong->getChuangYeBiList($userid, strtotime(date('Y-m',time())));
                }
                $content->score_list = $scorelist['list'];
                $content->page = $scorelist['page'];
            }
        }else{
            $today=strtotime(date('Y-m',time()));
            $scorelist=$points_ser->getScoreList($today, $userid);
            $content->score_list = $scorelist['list'];
            $content->page = $scorelist['page'];

        }
        $content->postlist = $post;
        $content->type = $type;
    }

    /**
     * @sso
     * 个人用户基本信息( 改版后 )
     * @author 许晟玮
     */
    public function action_person(){


        //新版本的基本信息
        $service    = new Service_User_Person_User();
        $invest     = new Service_User_Person_Invest();

        $content    = View::factory("/user/person/person_new");
        $result     = $service->getPersonInfo( $this->userId() );


        if( $result['person']->per_realname=='' ) {
            self::redirect("/person/member/basic/personupdate");
        }


        //地区获取
        $pro        = $invest->getArea();
        $all        =  array('cit_id' => 88,'cit_name' => '全国');
        //array_unshift( $pro, $all );
        $areaIds    = $result['person']->per_city;
        //获取城市地区
        $pro_id     = $result['person']->per_area;
        if($pro_id !='' && $pro_id !='88'){
            $area   = array('pro_id'=>$pro_id);
            $cityarea = common::arrArea($area);
        }else{
            $cityarea = array();
        }

        $edu_arr                        = common::getPersonEducation();


        $content->cityarea              = $cityarea;
        $content->area                  = $pro;
        $content->areaIds               = $areaIds;
        $content->pro_id				= $pro_id;

        if( $result['person']->per_education!='' ){

            $content->edu               = $edu_arr[$result['person']->per_education];

        }else{
            $content->edu               = '';
        }

        $content->personinfo            = $result;
        $this->content->rightcontent    = $content;

        $content->showmodel             = '1';

    }

    /**
     * 获取用户的个人意向投资信息
     */
    public function action_personInvestShow(){


        $content                    = View::factory("/user/person/person_invest_show");
        $service   					= new Service_User_Person_User();
        $result     				= $service->getPersonInfo( $this->userId() );
        //如果没有写过，则直接跳转到填写页
        if( $result['person']->per_connections=='' || $result['person']->per_connections=='0' ){
            self::redirect("/person/member/basic/personInvest");

        }

        //获取我的人脉关系
        $attr5  					= guide::attr5();
        $connection					= '';
        foreach( $attr5 as $k=>$vss ){
            if( $k==$result['person']->per_connections ){
                $connection	= $vss;
                break;
            }else{

            }

        }

        //我的投资风险
        if( $result['person']->per_investment_style=='0' ){
            $investment_style			= '不限';
        }else{
            $attr10        				= guide::attr10();
            $investment_style			= '';
            foreach( $attr10 as $k=>$vss ){
                if( $k==$result['person']->per_investment_style ){
                    $investment_style	= $vss;
                    break;
                }else{

                }

            }
        }

        $content->personinfo        = $result;
        $content->personalIndustryString = $service->getPersonalIndustryString($this->userId());
        $content->personIndArea 	= $this->getPersonalArea($this->userId());
        $content->connection        = $connection;
        $content->investment_style        = $investment_style;

        $this->content->rightcontent= $content;
    }

    /**
     * @sso
     * 更新个人用户基本信息
     * @author 曹怀栋
     */
    public function action_personUpdate(){
            $content                    = View::factory("/user/person/personupdate_new");

            $service                    = new Service_User_Person_User();
            $invest                     = new Service_User_Person_Invest();

            $user                       = $this->userInfo(true);
            //个人用户信息

            $personinfo                 = $service->transformationData( $user->user_person );
            $pro						= $invest->getArea();
            $all						= array('cit_id' => 88,'cit_name' => '全国');
            //array_unshift($pro, $all);

            $areaIds                    = $personinfo->per_city;
            //获取城市地区
            $pro_id                     = $personinfo->per_area;

            if($pro_id !='' && $pro_id !='88'){
                $area                   = array('pro_id'=>$pro_id);
                $cityarea               = common::arrArea($area);
            }else{
                $cityarea               = array();
            }


            $gettype 					= $this->request->query();
            $sso_user= Service_Sso_Client::instance()->getUserInfoById( $this->userId() );


            //post
            if($this->request->method()== HTTP_Request::POST){
                //过滤字符串
                $post                   = Arr::map( "HTML::chars", $this->request->post() );
               // $arrPost                = isset($post['project_city']) ? $post['project_city'] : array();
                //print_r ($arrPost);exit;
                //unset($post['project_city']);
                //验证开始

                //验证结束
                $post['user_id']         = $this->userId();
                //$post['per_area']        .= ($post['area_id']) ? ','.$post['area_id'] : '';
                $post['per_city']		=  $post['area_id'];
                unset( $post['area_id'] );

                if( $post['per_birthday']!='' ){
                    $post['per_birthday']   = strtotime( $post['per_birthday'] );
                }
                if( $post['per_education']=='' ){
                    $post['per_school']   = '';
                }


                //个人用户添加活跃度by钟涛
                $ser1=new Service_User_Person_Points();
                if( arr::get($post, 'per_id') !="" ){
                    //更新数据
                    $res                = $service->updatePersonNew( $post );
                    $ser1->addPoints(arr::get($post, 'per_id'), 'per_perfect_one');//完善基本信息
                }else{
                    //添加数据
                    $post['per_user_id'] =$this->userId();
                    $res                = $service->addPerson_new( $post );
                    $ser1->addPoints($this->userId(), 'per_perfect_one');//完善基本信息
                }



                //添加地域信息
               // $service->insertPersonalArea( $this->userId(), $arrPost );
                //修改名片图片
                //$card_service           = new Service_User_Person_Card();

                if( isset($gettype['type']) && $gettype['type']==2 ){//名片修改链接过来时，返回我的名片
                    self::redirect("/person/member/card/mycard");
                }elseif( $res['status']==-1 ){
                    $content->error         = $res;
                    $content->email         = $sso_user->email;
                    $content->mobile        = $sso_user->mobile;
                    $content->personinfo    = $personinfo;
                    $content->user          = $user;

                }else{
                    self::redirect("/person/member/basic/person");
                }
            }



            $content->user              = $user;
            $content->mobile            = $sso_user->mobile;
            $content->email             = $sso_user->email;
            $content->area              = $pro;
            $content->areaIds           = $areaIds;
            $content->cityarea          = $cityarea;
            $content->edu_arr           = common::getPersonEducation();
            $content->type				= isset($gettype['type'])?$gettype['type']:1;
            $content->pro_id			= $pro_id;


            $this->content->rightcontent= $content;
            $content->personinfo        = $personinfo;



    }

    /**
     * 身份证验证处理
     * @author 周进
     */
    public function action_IdentificationCard(){
        $content = View::factory("user/person/identificationcard");
        $this->content->rightcontent = $content;
        $service = new Service_User_Person_User();
        $personinfo = $service->getPersonInfo($this->userId());
//        if ($personinfo['user_person']->per_user_id==NULL || $personinfo['user_person']->per_amount== 0){//先完成个人信息
//              $content = View::factory("user/person/noidenticfication");
//              $this->content->rightcontent = $content;
//        }else{
            $content = View::factory("user/person/identificationcard");
            $this->content->rightcontent = $content;
            $query = Arr::map("HTML::chars", $this->request->query());
            //上传身份证
            if($this->request->method()==HTTP_Request::POST)
            {
                if ($_FILES['per_identification_photo']['name']==""){
                    $content->personinfo = $service->getPersonInfo($this->userId());
                    $content->error = "请先选择图片！";
                }
                else{
                    $result = $service->uploadIdentification($_FILES, 'per_identification_photo',$this->userId());
                    if($result==true){
                        $content->personinfo = $service->getPersonInfo($this->userId());
                    }
                    else{
                        $content->personinfo = $service->getPersonInfo($this->userId());
                        $content->error = "身份证图片上传失败！请检查您的文件格式及大小宽高不能小于300px*200px";
                    }
                }
            }
            elseif (intval(Arr::get($query, 'status'))==1&&$personinfo['user_person']->per_auth_status==0)
            {
                $service->updateIdentification(1,$this->userId());
                $content->personinfo = $service->getPersonInfo($this->userId());
            }
            elseif (Arr::get($query, 'img')!="")
            {
                $service->deleteIdentification($this->request->query('img'),$this->userId());
                $content->personinfo = $service->getPersonInfo($this->userId());
            }
            elseif (Arr::get($query, 'status')=="-1"&&$personinfo['user_person']->per_auth_status==3)//重新认证
            {
                $person = ORM::factory('Personinfo');
                $data = $person->where('per_user_id', '=', $this->userId())->find()->as_array();
                $person->per_id = $data['per_id'];
                $person->per_auth_status = 0;
                try {
                    $person->update();
                } catch (Kohana_Exception $e) {
                }
                $content->personinfo = $service->getPersonInfo($this->userId());
            }
            else{
                $content->personinfo = $service->getPersonInfo($this->userId());
            }
//        }
    }

    /**
     * 添加工作经验 以及列表
     * @author 龚湧
     */
    public function action_experience(){
        $user_id = $this->userId();
        $service = Service::factory("User_Person_User");
        $service_user= new Service_User();
        //表单提交处理
        if($this->request->method() === HTTP_Request::POST){
            $post = $this->request->post();
            //$post_div_num= Arr::get($post, 'now_add_div_num_name',1);
            //按照添加的数量,循环
            $post_div_num= Arr::get($post, 'add_div_name');

            for( $i=0;$i<count($post_div_num);$i++ ){

                if( $post_div_num[$i]!=0 ){
                    $n= $i+1;
                    $post_info= array();
                    $post_info['exp_starttime']= $post['exp_starttime_'.$n];
                    $post_info['exp_endtime']= $post['exp_endtime_'.$n];
                    $post_info['pro_id']= $post['pro_id_'.$n];
                    $post_info['area_id']= $post['area_id_'.$n];
                    $post_info['exp_company_name']= $post['exp_company_name_'.$n];
                    $post_info['exp_nature']= $post['exp_nature_'.$n];
                    $post_info['exp_scale']= $post['exp_scale_'.$n];
                    $post_info['exp_industry_sort']= $post['exp_industry_sort_'.$n];
                    $post_info['exp_department']= $post['exp_department_'.$n];
                    $post_info['exp_occupation_type']= $post['exp_occupation_type_'.$n];
                    $post_info['exp_occupation_name']= $post['exp_occupation_name_'.$n];
                    $post_info['exp_user_occupation_name']= $post['exp_user_occupation_name_'.$n];
                    $post_info['exp_description']= strip_tags($post['exp_description_'.$n]);

                    if( $post_info['exp_endtime']=="" ){
                        $post_info['exp_endtime']= 0;
                    }

                    $service->createExperience($post_info,$user_id);

                }


            }


            self::redirect("person/member/basic/experiencesave");
        }

        //用户从业经验列表
        $experiences = $service->listExperience($user_id);
        if( !empty( $experiences ) ){
            foreach( $experiences as $k=>$v ){
                //职业名称
                if( $v['exp_occupation_name']!='0' ){
                    $rs_pos= $service_user->getPositionRow( $v['exp_occupation_name'] );
                    $experiences[$k]['occ_name']= $rs_pos['position_name'];
                }else{
                    $experiences[$k]['occ_name']= $v['exp_user_occupation_name'];
                }
            }

        }

        $content = View::factory("user/person/experience");
        //$content = View::factory("user/person/experience_new");
        $this->content->rightcontent = $content;

        //从业经验列表赋值
        $content->experiences = $experiences;

        //从事行业一级大分类
        $allindustry = common::primaryIndustry(0);
        foreach ($allindustry as $key=>$lv){
            $industry[$lv->industry_id] = $lv->industry_name;
        }
        array_unshift($industry,'请选择');
        //公司规模
        $scale = common::comscale();
        array_unshift($scale,'请选择');



        //读取省级地区列表
        $area = array();
        $invest = new Service_User_Person_Invest();
        $pros=$invest->getArea();
        if($pros){
            foreach($pros as $pro){
                $area[Arr::get($pro, "cit_id")] = Arr::get($pro, "cit_name");
            }
        }
        array_unshift($area,'请选择');

        //表单赋值
        $content->industry = $industry;
        $content->scale = $scale;


        //身份列表
        $content->area = $area;

        //获取行业类别
        $memcache= Cache::instance('memcache');
        $mem_rs_professioin= $memcache->get( 'memcache_professioin' );
        if( empty( $mem_rs_professioin ) ){
            $rs_professioin= $service_user->getProfessionAll();
            $memcache->set( 'memcache_professioin', $rs_professioin, 86400 );
        }else{
            $rs_professioin= $mem_rs_professioin;

        }
        $content->professoin= $rs_professioin;

        //获取一级的职业类别
        $mem_rs_pos= $memcache->get( 'memcache_position' );
        if( empty( $mem_rs_pos ) ){
            $rs_pos= $service_user->getPosition(0);
            $memcache->set( 'memcache_position', $rs_pos, 86400 );
        }else{
            $rs_pos= $mem_rs_pos;
        }


        $content->position= $rs_pos;


    }

    /**
     * 删除工作经验
     * @author 龚湧
     */
    public function action_delExperience(){
        $user_id = $this->userId();
        $exp_id = Arr::get($this->request->query(), "exp_id");
        $service = Service::factory("User_Person_User");
        if(!$service->delExperience($user_id,$exp_id)){
            //TODO 错误页面
            echo "删除失败";
            exit();
        }
        else{
            self::redirect("person/member/basic/experience");
        }
    }

    /**
     * 编辑工作经验
     * @author 龚湧
     */
    public function action_editExperience(){
        $user_id = $this->userId();
        $service = Service::factory("User_Person_User");
        $service_user= new Service_User();
        $exp_id = Arr::get($this->request->query(), "exp_id");
        $edit = $service->getExperienceById($user_id,$exp_id);

        //没有本条记录编辑权限
        if(!$edit){
            //TODO错误提示
            self::redirect("person/member/basic/experience");
        }

        //表单提交处理
        if($this->request->method() === HTTP_Request::POST){
            $post = $this->request->post();

            if( $post['exp_endtime']=='' ){
                $post['exp_endtime']= '0';
            }
            if($edit = $service->editExperience($post,$edit)){
                //TODO 跳转
                self::redirect("person/member/basic/experiencesave");
            }
            else{
                //TODO 编辑失败
                self::redirect("person/member/basic/experiencesave");
            }
        }

        //用户从业经验列表
        $experiences = $service->listExperience($user_id);
        if( !empty( $experiences ) ){
            foreach( $experiences as $k=>$v ){
                //职业名称
                if( $v['exp_occupation_name']!='0' ){
                    $rs_pos= $service_user->getPositionRow( $v['exp_occupation_name'] );
                    $experiences[$k]['occ_name']= $rs_pos['position_name'];
                }else{
                    $experiences[$k]['occ_name']= $v['exp_user_occupation_name'];
                }
            }

        }
        $content = View::factory("user/person/edit_experience");
        $this->content->rightcontent = $content;

        //从业经验列表赋值
        $content->experiences = $experiences;


        //公司规模
        $scale = common::comscale();
        array_unshift($scale,'请选择');


        //读取省级地区列表
        $area = array();
        $area_sub = array();
        $invest = new Service_User_Person_Invest();
        $pros=$invest->getArea();
        if($pros){
            foreach($pros as $pro){
                $area[Arr::get($pro, "cit_id")] = Arr::get($pro, "cit_name");
            }
            $area_subs = $invest->getArea($edit->pro_id);
            if($area_subs){
                foreach($area_subs as $sub){
                    $area_sub[Arr::get($sub, "cit_id")] = Arr::get($sub, "cit_name");
                }
            }
        }
        array_unshift($area,'请选择');
        //二级地区



        //表单赋值

        $content->scale = $scale;

        $content->edit = $edit;

        //还原日期
        $exp_start = $edit->exp_starttime;
        $exp_end = $edit->exp_endtime;
        $exp_start_year = substr($exp_start, 0,4);
        $exp_start_month = substr($exp_start,4);

        $exp_end_year = substr($exp_end, 0,4);
        $exp_end_month = substr($exp_end,4);
        if( $exp_end=="0" ){
            $exp_end_year= "";
            $exp_end_month= "";
        }
        $content->exp_start_year = $exp_start_year;
        $content->exp_start_month = $exp_start_month;
        $content->exp_end_year = $exp_end_year;
        $content->exp_end_month = $exp_end_month;


        //省份列表
        $content->area = $area;
        $content->area_sub = $area_sub;

        //获取行业类别

        $rs_professioin= $service_user->getProfessionAll();
        $content->professoin= $rs_professioin;

        //获取一级的职业类别
        $rs_pos= $service_user->getPosition(0);
        $content->position= $rs_pos;

        //行业
        $rs_exp_industry_sort= $service_user->getProfessionRow( $edit->exp_industry_sort );
        $content->rs_exp_industry_sort= $rs_exp_industry_sort;

        //职位类别
        $rs_exp_occupation_type= $service_user->getPositionRow( $edit->exp_occupation_type );
        $content->rs_exp_occupation_type= $rs_exp_occupation_type;

        //职位name
        if( $edit->exp_occupation_name!='0' ){
            $rs_pos= $service_user->getPositionRow( $edit->exp_occupation_name );
             $occ_name= $rs_pos['position_name'];
         }else{
             $occ_name= $edit->exp_user_occupation_name;
         }
         $content->occ_name= $occ_name;

             //当前职业类别下的职业名称
            $rs_down_occ= $service_user->getPosition( $edit->exp_occupation_type );
            $content->rs_down_occ= $rs_down_occ;



       }


    /**
     * 个人邮件验证发送页面
     * @author 周进
     */
    public function action_vEmail(){
        $this->isLogin();
        $service = new Service_User_Person_User();
        //已经通过验证的
        if($service->getEmailValidCount($this->userId())==1)
            self::redirect("/person/member");
        if ($this->request->query('email')=="send"){
            $result = $service->updateCheckValidEmail($this->userInfo()->user_id,$this->userInfo()->email);
        }
        $content = View::factory('user/person/vemail');
        $content->email = $this->userInfo()->email;

        $content->showtime = $service->getValidCode($this->userId(),1,Kohana::$config->load('message.expire.email'));
        $toemailurl = explode('@', $this->userInfo()->email);
        if( count($toemailurl)>1 ){
            if ($toemailurl[1]=="gmail.com"){
                $content->toemailurl = "http://mail.google.com/";
            }else{
                $content->toemailurl = "http://mail.".$toemailurl[1];
            }
        }
        $this->content->rightcontent = $content;
    }

    /**
     * 处理个人地域信息
     * @author 施磊
     */
    private function _checkPersonalArea($area) {
        if(!$area) return FALSE;
        $return = array();
        foreach($area as $value) {
           if($value['pro_id'] == $value['area_id']) {
               $return[$value['pro_id']] =  $value;
           }else{
               $return[$value['pro_id']]['data'][$value['area_id']] = $value;
           }
        }
        return $return;
    }
    /**
     * 获得个人投资行业的临时方法
     * @author 施磊
     */
    private function _getPersonalIndustry() {
        $service = new Service_User_Person_User();
        $userIndusty = $service->getPerIndustryByUserId($this->userId());
        $return = array('parent_id' => 0,  'industry_id' => 0, 'industry_name' => 0, 'industry_arr' => array());
        if($userIndusty) {

            foreach($userIndusty as $val) {
                if($val['industry_id'] == $val['parent_id']) {
                    $return['parent_id'] = $val['industry_id'];
                }else {
                    $return['industry_arr'] = $service->getAllIndustryByPid($val['parent_id']);
                    $return['industry_id'] = $val['industry_id'];
                    $return['industry_name'] = isset($return['industry_arr'][$val['industry_id']]['industry_name']) ? $return['industry_arr'][$val['industry_id']]['industry_name'] : '';
                }
            }
        }
        return $return;
    }



    /**
     * @sso
     * 个人意向投资
     * @author 许晟玮
     */
    public function action_personInvest(){

        $service                    = new Service_User_Person_User();
        //$user                       = ORM::factory('User',$this->userId());
        //$user = Service_Sso_Client::instance()->getUserInfoById( $this->userid() );
        $service_user= new Service_User_Person_User();
        $user= $service_user->getPerson($this->userid());

        $invest                     = new Service_User_Person_Invest();
        $serviceProject             = new Service_User_Company_Project();


        $personalIndustry           = $this->_getPersonalIndustry();
        $personalArea               = $service->selectPersonalAreaByUserId($this->userId());
        $readArea                   = $this->_checkPersonalArea($personalArea);
        $personinfo                 = $service->transformationData($user);


        $content                    = View::factory("/user/person/person_invest");
        $content->readArea          = $readArea ? $readArea : array();
        $content->personalIndustry  = $personalIndustry;
        $content->personinfo        = $personinfo;

        $area                       = array('pro_id'=>0);
        $content->areas             = common::arrArea($area);
        $content->tag               = $serviceProject->findTag();

        $this->content->rightcontent= $content;
        //提交
        if($this->request->method()== HTTP_Request::POST){
            $post = Arr::map("HTML::chars", $this->request->post());

            $arrPost                = isset($post['project_city']) ? $post['project_city'] : array();
            unset($post['project_city']);
            //数组改成字符串
            $post                   = $service->arrayToString($post);

            $post['user_id']        = $this->userId();
            //个人用户添加活跃度by钟涛
            $ser1=new Service_User_Person_Points();
            if(arr::get($post, 'per_id') !=""){
                //更新数据
               //print_r ($post);exit;
                $res                = $service->updatePersonInvest($post);
                $ser1->addPoints(arr::get($post, 'per_id'), 'per_perfect_two');//完善意向投资信息
            }else{
                //添加数据
                $post['per_user_id'] =$this->userId();
                $res                = $service->addPersonInvest($post);
                $ser1->addPoints($this->userId(), 'per_perfect_two');//完善意向投资信息
            }

            //添加地域信息
            $service->insertPersonalArea($this->userId(), $arrPost);
            if( $res===true ){
                self::redirect("/person/member/basic/personInvestShow");

            }else{
                self::redirect("/person/member/basic/personInvest");

            }

        }

    }

    /**
     * 个人从业经验显示
     *@author许晟玮
     */
    public function action_experiencesave(){
        $user_id = $this->userId();
        $service = Service::factory("User_Person_User");
        $service_user= new Service_User();
        //用户从业经验列表
        $experiences = $service->listExperience($user_id);
        $content = View::factory("user/person/experience_save");
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
        $this->content->rightcontent = $content;

        //从业经验列表赋值
        $content->experiences = $experiences;
    }
    //end function

    /**
     * 修改用户的邮箱和密码
     * @author许晟玮
     */
    public function action_editUserEmail(){


        if($this->request->method()== HTTP_Request::POST){
            $post = $this->request->post();
            $email= Arr::get($post, 'email');
            $psd= Arr::get($post, 'psd');
            $uid= $this->userId();
            $service= new Service_User();
            //判断格式
            if( strpos( $email,'@' )===false ){
                self::redirect('/person/member/basic/index?error=emr');
            }
            //判断邮箱是否存在
            if( $service->forgetPasswordEmail($email)===true ){
                //邮箱存在
                self::redirect('/person/member/basic/index?error=email');
            }else{
                //读取文档 找对应的密码
                $dir = APPPATH."cache/data/data.txt";
                $txt= @file_get_contents($dir);
                if( $txt!='' ){
                    $data_arr= explode("\r\n", $txt);
                    $psddd= '';
                    foreach ( $data_arr as $vv ){
                        $arr= explode(',',$vv);
                        $email_address= $arr[1];
                        if( $email_address==$this->userInfo()->email ){
                            $psddd= trim($arr[2]);
                            break;
                        }
                    }

                    if( $psddd=='' ){
                        self::redirect('/person/member/basic/index?error=nopp');
                    }else{

                    }

                    $result= $service->editUserEmail( $uid,$email );
                    if( $result===true ){
                        //邮箱状态为未验证
                        $service->unbindEmail($uid);

                        //记录日志
                        $result= $service->setEditEmailLog($uid);
                        if( $result===true ){
                            //send email
                            //发送验证邮件by周进
                            $mailservice = new Service_User_Company_User();
                            $mailservice->updateCheckValidEmail($uid,$email);

                            //判断是否修改密码
                            if( $psd!="" ){
                                //修改密码
                                $service->editUserPsd($uid, $psd,$email);
                            }else{

                                $service->editUserPsd($uid, $psddd,$email);
                            }

                        }else{
                        }
                        self::redirect('/member/showemail?type=ed');
                    }else{
                        //edit email false
                    }
                }else{
                    self::redirect('/member/showemail?type=notxt');
                }
            }
        }

    }
    //end function


    /**
     * 修改会员邮箱
     * @author许晟玮
     */
    public function action_editMail(){
        //必须登录
        $this->isLogin();
        //判断是否设置了密码，没设置密码，设置密码去
        $eof= Service_Sso_Client::instance()->getUserPasswordEof( $this->userId() );
        if( $eof===false ){
            if( $user->valid_email==1 ){
                self::redirect('/person/member/basic/setpassword?type=email');
            }
        }

        $content = View::factory('/user/person/editemail_a');
        $this->content->rightcontent = $content;
        //判断是否修改过2次邮箱
        $service = new Service_User();
        $edit_count= $service->getEditEmailCount( $this->userId() );
        $content->username= $this->userInfo()->email;
        if( $edit_count>=2 ){
            self::redirect('/person/member/safe');
        }else{


        }


    }

    //end function

    /**
     * 修改邮箱第二步
     * @author许晟玮
     */
    public function action_editMailB(){
        if($this->request->method()== HTTP_Request::POST){
            $content = View::factory('/user/person/editemail_b');
            $this->content->rightcontent = $content;
            $password= Arr::get($this->request->post(), 'password');
            if( $password=='' ){
                self::redirect('/person/member/basic/editMail');
            }
            $content->password= $password;
        }else{
            self::redirect('/person/member/basic/editMail');

        }

    }
    //end function

    /**
     * 修改邮箱第二步ok
     * @author许晟玮
     */
    public function action_editMailBOk(){
        if($this->request->method()== HTTP_Request::POST){
            $post= $this->request->post();
            $email= Arr::get($post, 'new_email');
            $password= Arr::get($post, 'password');
            $uid= ceil( $this->userId() );

            if( $email=='' || $password=='' || $uid<=0 ){
                self::redirect('/person/member/basic/editMail');
            }else{
                $service_user= new Service_User();
                //email tof

                if( $service_user->forgetPasswordEmail( $email )!==true ){
                    //eidt email
                    $result= $service_user->editUserEmail($uid, $email);
                    if( $result===true ){
                        //set edit email log
                        $result= $service_user->setEditEmailLog($uid);
                        //unbind email
                        $service_user->unbindEmail($uid);
                        //edit password
                        $result_p= $service_user->editUserPsd($uid, $password, $email);
                        if( $result_p===true ){
                            //send email
                            $service_company_user= new Service_User_Company_User();
                            $service_company_user->updateCheckValidEmail($uid,$email,true);
                            $content = View::factory('/user/person/editMailBOK');
                            $this->content->rightcontent = $content;
                            $content->email= $email;
                            $toemailurl = explode('@', $email);
                            if ($toemailurl[1]=="gmail.com"){
                                $content->toemailurl = "http://mail.google.com/";
                            }else{
                                $content->toemailurl = "http://mail.".$toemailurl[1];
                            }
                        }else{
                            self::redirect('/person/member/basic/editMail?1');
                        }
                    }else{
                        //eidt email false
                        self::redirect('/person/member/basic/editMail?2');
                    }

                }else{
                    self::redirect('/person/member/basic/editMail?3');
                }
            }
        }else{
            self::redirect('/person/member/basic/editMail?4');
        }

    }
    //end function

    /**
     * 修改邮箱第三步
     * @author许晟玮
     */
    public function action_editemailc(){
        $content = View::factory('/user/person/editemailc');
        $this->content->rightcontent = $content;
    }

    //end function

    /**
     * 邮箱未验证进去验证的页面
     * @author 许晟玮
     */
    public function action_setEmail(){
        $content = View::factory('/user/person/setemail');
        $this->content->rightcontent = $content;
        //user info
        $userinfo= $this->userInfo(true);
        $get= $this->request->query();
        //if type=oauth && email is no null && email is alerdy vaild
        if( Arr::get($get, 'type')=='oauth' ){
            if( $userinfo->valid_email==1 ){
                self::redirect('/person/member/basic/setpassword?type=email');
            }
        }

        if( ceil( $userinfo->valid_email )!=0 ){
            self::redirect('/person/member');
        }else{
        }
        $content->email= $userinfo->email;





        $content->oauth_type= Arr::get($get, 'type');

    }
    //end function

    /**
     * 发送邮件给会员
     * @author许晟玮
     */
    public function action_sendEmail(){
        if($this->request->method()== HTTP_Request::POST){
            $userinfo= $this->userInfo(true);
            if( ceil( $userinfo->valid_email )!=0 ){
                self::redirect('/person/member');
            }else{
            }

            $post= $this->request->post();
            $email= Arr::get($post, 'inp_email');
            if( $email=='' ){
                self::redirect('/person/member/basic/setEmail');
            }else{
                $user= $this->userInfo(true);
                if( $user->email=='' ){
                    //set
                    $cl= Service_Sso_Client::instance();
                    $cl->setUserEmailById( $user->id,$email );
                }else{
                    //edit
                    $cl= Service_Sso_Client::instance();
                    $cl->updateEmailInfoById( $user->id,array( 'email'=>$email,'valid_status'=>'0' ) );
                }

                $mailservice = new Service_User_Company_User();
                //如果是第三方登录进来
                if( Arr::get($post, 'oauth_type')=='oauth' ){
                    $editemail= '2';
                }else{
                    $editemail= false;
                }

                $mailservice->updateCheckValidEmail($user->id,$email,$editemail );

                $content = View::factory('/user/person/email_valid_ok');
                $this->content->rightcontent = $content;
                $content->email= $email;

                $toemailurl = explode('@', $email);
                if ($toemailurl[1]=="gmail.com"){
                    $content->toemailurl = "http://mail.google.com/";
                }else{
                    $content->toemailurl = "http://mail.".$toemailurl[1];
                }
            }
        }else{
            self::redirect('/person/member');
        }
    }
    //end function


    /**
     * 第三方绑定平台帐号
     * @author许晟玮
     */
    public function action_oauthUserBinding(){
        //已经绑定过 跳出去
        $service= new Service_Oauth_Log();
        $eof= $service->getOauthEofByUid( $this->userId() );
        if( $eof===true ){
            self::redirect('/person/member');
        }
        $content = View::factory('/user/person/oauth_user_binding');
        $this->content->rightcontent = $content;

    }
    //end function

    /**
     * 第三方新建并且绑定帐号，死坑的需求,尼玛
     * @author 许晟玮
     */
    public function action_userBindComplete(){
        //如果目前第三方帐号已经绑定过帐号了，就跳到会员中心首页
        $service= new Service_Oauth_Log();
        $eof= $service->getOauthEofByUid( $this->userId() );
        if( $eof===true ){
            self::redirect('/person/member');
        }

        $content = View::factory('/user/person/bind_complete_user');
        $this->content->rightcontent = $content;

        if($this->request->method()== HTTP_Request::POST){
            $post = Arr::map("HTML::chars",$this->request->post());
            $client= Service_Sso_Client::instance();
            try{
                //更新会员手机号
                $mobile= Arr::get($post, 'mobile');
                $client->setUserMobileById($this->userId(), $mobile,'1');
                //设置密码
                $password= Arr::get($post, 'password');
                $client->setPasswordOauth($this->userId(), $password);

                //同步
                $basic = ORM::factory("User");
                $basic->user_id = $this->userId();
                $basic->last_logintime = time();
                $basic->last_login_ip = ip2long(Request::$client_ip);
                $sid_md5= arr::get($_COOKIE, 'Hm_lvqtz_sid');
                $basic->sid = $sid_md5;
                $basic->create();

                //如果填写了邮箱，再设置个邮箱吧
                $email= Arr::get($post, 'email');
                if( $email!=''  ){
                    $client->setUserEmailById( $this->userId(),$email );
                }else{
                }

                //完善基本信息
                $service= new Service_User_Person_User();

                $data= array();
                $data['per_user_id']= $this->userId();
                $data['per_realname']= Arr::get($post, 'realname');
                $data['per_amount']= Arr::get($post, 'per_amount');
                $data['per_industry']= Arr::get($post, 'per_industry');
                $data['per_area']= Arr::get($post, 'area_id');
                $data['per_createtime']= time();
                $data['per_photo']= "/user_icon/plant/default_icon_".rand(1,25).".jpg";
                $service->addPersonalUser($data);

                //绑定第三方帐号log
                $session = Session::instance();
                $oauth_userInfo = $session->get('oauth_userInfo');
               /** $log_arr= array();
                $log_arr['oauth_id']= $oauth_userInfo['id'];
                $log_arr['type']= 0;
                $log_arr['act_userid']= $this->userId();
                $service_log= new Service_Oauth_Log();
                $service_log->setOauthLog( $log_arr );
                **/
                //修改basic
                $client->updateBasicInfoById($this->userId(), array("user_name"=>Arr::get($post, 'realname')) );

                //修改oauth
                $client->editOauth( $oauth_userInfo['id'],$this->userId(),'-1' );

            }catch(Kohana_Exception $e){
                 //抛出错误代码

            }
            self::redirect('/person/member');
        }else{
        }
    }
    //end function

    /**
     * 第三方，设置密码
     * @author 许晟玮
     */
    public function action_setpassword(){
        if($this->request->method()== HTTP_Request::POST){
            $post= Arr::map("HTML::chars",$this->request->post());
            $pasw= Arr::get($post, 'passwd');
            //set paswd
            $client= Service_Sso_Client::instance();
            $client->setPasswordOauth($this->userId(), $pasw);
            //set sso user
            $basic = ORM::factory("User");
            $basic->user_id = $this->userId();
            $basic->last_logintime = time();
            $basic->last_login_ip = ip2long(Request::$client_ip);
            $sid_md5	= arr::get($_COOKIE, 'Hm_lvqtz_sid');
            $basic->sid = $sid_md5;
            $basic->create();
            //update oauth bind_user_id
            $session = Session::instance();
            $oauth_userInfo = $session->get('oauth_userInfo');
            $client->editOauth($oauth_userInfo['id'], $this->userId(), $this->userId());

            self::redirect("/person/member");
        }else{
            $content = View::factory('/user/person/setpassword');
            $this->content->rightcontent = $content;
            $get= Arr::map("HTML::chars",$this->request->query());
            $type= Arr::get($get, 'type');
            $info= $this->userInfo();
            if( $type=='email' ){
                $content->showc= '恭喜您!邮箱已验证成功，您可绑定已验证的邮箱直接登录一句话生意网！';
                $content->showt= '验证邮箱';
                $content->show= $info->email;
                $content->url= '/person/member/basic/editMail';
            }else{
                $content->showc= '恭喜您!手机已验证成功，您可绑定已验证的手机号直接登录一句话生意网！';
                $content->showt= '验证手机号';
                $content->show= $info->mobile;
                $content->url= '/person/member/valid/mobile?to=change';
            }
        }
    }
    //end function

    /**
     * 第三方 解除绑定
     * @author 许晟玮
     */
    public function action_oauthjc(){

        $content = View::factory('/user/person/oauth_jc');
        $this->content->rightcontent = $content;

        $service= new Service_Oauth_Log();
        $result= $service->getOauthUserList($this->userId());

        $client= Service_Sso_Client::instance();
        $return= array();
        //获取对应的oauth 信息
        if( !empty( $result ) ){
            foreach ( $result as $key=>$vs ){
                $res= $client->getOauthInfoById($vs->oauth_id, $vs->act_userid);
                $return[$key]['type']= $res['oauth_type'];
                $return[$key]['time']= date("Y.m.d H:i",$vs->act_time);
                $uin= $client->getUserInfoById( $res['bind_user_id'] );
                $return[$key]['name']= $uin->user_name;
                $return[$key]['id']= $vs->id;
                $return[$key]['uid']= $vs->act_userid;
                $return[$key]['oid']= $vs->oauth_id;
            }
        }else{
            self::redirect('/person/member');
        }


        $content->result= $return;
        $get= Arr::map("HTML::chars",$this->request->query());
        $user= $this->userInfo();
        $content->user_name= $user->user_name;

        if( Arr::get($get, 'action')=='jc' ){
            $id= Arr::get($get, 'id');
            $oid= Arr::get($get, 'oid');
            $uid= Arr::get($get, 'uid');
            if( $id== '' || $uid=='' || $oid=='' ){
                self::redirect('/person/member/basic/oauthjc');
            }

            $rf= $client->delOauth( $oid,$uid );
            $rf= true;
            //update
            if( $rf!==false ){
                $service->updateOauthType($id);
            }
            self::redirect('/person/member/basic/oauthjc');
        }


    }
    //end function
   /* 升级公告
         * @author stone shi
         */
    public function action_upgradeNotice() {
        $content = View::factory('/user/company/upgradeNotice');
        $this->content->rightcontent = $content;
    }
}