<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户基本信息
 * @author 龚湧
 */
class Controller_User_Company_Basic extends Controller_User_Company_Template{

    /**
    * @sso
    * 用户中心默认页面
    * @author 龚湧
    */
    public function action_index(){
        $user = $this->userInfo(true);
        $service = Service::factory(
            array(
                'project'=>"User_Company_Project",
                'user'   =>"User",
                'company' =>"User_Company_Card",
                'card' => "Card",
                'integrity' => "User_Company_Integrity",
                'user_company' => "User_Company_User",
                'status'=>"User_Company_ComStatus"
            )
        );

        $LogsModel = new Service_User_UserLoginLog();
        $loginLog = $LogsModel->selectLastLogin($user->user_id);

        $bz_status_arr= $service->status->getCompanyStatusInfo( $user->user_id,'all' );
        $com_id = $user->user_company->com_id;

        $last_logintime = Arr::get($loginLog, 'log_time', 0);
        //最后登录时间年月日
        $login_time_ymd = date("Y.m.d",$last_logintime);
        $login_time_his = date("H：i：s",$last_logintime);
        //企业发布项目总数量
        $project_count = $service->project->getProjectCount($com_id);
        //名片是否已完善
        $is_card_complete = $service->company->isCompanyCardComplete($user->user_id);
        //收到的名片总数量
        $receive_card_count = $service->company->getReceiveCardFromTime($user->user_id);
        //递出名片总数量
        $out_card_count = $service->company->getOutCardCount($user->user_id);
        //距离上次登录收到的名片
        $receive_card_lastlogin = $service->company->getReceiveCardFromTime($user->user_id,$last_logintime);
        //获取最新发布的3个项目
        $list = array();
        $list['list'] = $service->project->getProjectLimit($com_id, $project_count);
        if($list['list']){

            $project_list = $service->project->getResaultList($list['list']);

        }
        else{
            $project_list = false;
        }
        //用户诚信等级
        $ity_level = $service->integrity->getIntegrityLevel($this->userId());
        //用户总诚信点数
        $ity_count = $service->integrity->getIntegrityByTime($this->userId());
        //获取收到的名片2张
        $card_list = $service->card->getReceivedCardLimit($user->user_id,2);
        //获取用户积分
        $points = Service::factory("User_Company_Points");
        $useable_points = $points->getUsablePointsByTime($this->userId());


        $content = View::factory("user/company/usercenter");
        $this->content->rightcontent = $content;

        //模板赋值
        $content->login_time_ymd = $login_time_ymd;
        $content->login_time_his = $login_time_his;
        $content->user = $user;
        //企业基本信息是否完成
        $content->is_complete_basic = $this->is_complete_basic($user->user_id);
        $content->project_count = $project_count;
        //$service->user_company->checkCompanyCertificationStatus($com_id);
        $content->validCerts = $service->user_company->checkCompanyCertificationStatus($com_id);
        $content->is_card_complete = $is_card_complete;
        $content->receive_card_count = $receive_card_count;
        $content->out_card_count = $out_card_count;
        $content->receive_card_lastlogin = $receive_card_lastlogin;
        $content->project_list = $project_list;
        $content->card_list = $card_list;
        $content->useable_points = $useable_points;
        $content->ity_level = $ity_level;
        $content->ity_count = $ity_count;
        //登陆记录
        $content->loginLog = $loginLog;
        //投资金额
        $content->money_list = common::moneyArr();
        //用户登录名
        if( $user->email!='' ){
            $content->userName = empty($user->user_company->com_name)?$user->email:$user->user_company->com_name;
        }else{
            $content->userName= $user->mobile;
        }
        //投资行业
        $allindustry = common::primaryIndustry(0);
        foreach ($allindustry as $key=>$lv){
            $industry[$lv->industry_id] = $lv->industry_name;
        }
        $content->industry_list = $industry;
        //账户记录
        $accountservice = Service::factory("Account");
        $accountdate = $accountservice->checkAccountIndex($this->userId());
        $content->accountday = $accountdate;
        $result = $accountservice->getAccountLog($this->userId(),'30');
        $content->accountlist = $result['list'];
        $account = $accountservice->getAccount($this->userId());
        $content->account = $account['account'];
        $msg_service = new Service_Account();
        $msg_service->checkLoginMsg($this->userId());
        $content->bz_status_arr= $bz_status_arr;


        $cominfo= $service->user_company->getCompanyInfo( $user->user_id );
        $content->com_logo= $cominfo->com_logo;
        //为你推荐
        //$content->getRecommendPerson = $service->user->getRecommendPerson($com_id);
        //猜你喜欢
        //$content->getGuessPerson = $service->user->getGuessPerson( $user->user_id);
        //最新加入会员
        //$content->getNewPerson = $service->user->getNewPerson();
        //最活跃会员
        //$content->getHuoyueduPerson = $service->user->getHuoyueduPerson();
    }

    /**
     * 查看验证状态
     * @author 钟涛
     */
    public function action_authStatus(){
        //view页面加载
        $content = View::factory("user/company/authentication");
        //获取登录user_id
        $this->content->rightcontent = $content;
        $userid = $this->userInfo()->user_id;
        $service = new Service_User_Company_User();
        //邮箱是否通过验证
        $content->emailstatus=$service->getEmailValidCount($userid);
        //企业资质是否通过验证
        $content->com_authstaus=$service->getCompanyAuthCount($userid);
    }

    /**
     * 上传企业资质认证
     * @author 曹怀栋
     */
    public function action_uploadCertification(){
        $user = ORM::factory("user",$this->userId());
        //has_one 对应关系 公司基本信息
        $company = $user->user_company;
        //补充判断信息不完整
        $content = View::factory("user/company/uploadcertification");
        $postdata = $this->request->query();
        $type=isset($postdata['type'])?$postdata['type']:1;
        $this->content->rightcontent = $content;
        $commonimg_list = ORM::factory("CommonImg")->where('table_name','=',1)->where('user_id','=',$this->userId())->find_all();
        $commonimg = array();
        foreach ($commonimg_list as $k=>$v){
            $commonimg[$v->field_name][$k]['common_img_id'] = $v->common_img_id;
            $commonimg[$v->field_name][$k]['url'] = URL::imgurl(str_replace('/b_','/s_', $v->url));
        }
        $content->commonimg_list=$commonimg;
        //工商营业执照上传图片总数
        $content->com_business_licence=isset($commonimg['com_business_licence']) ? count($commonimg['com_business_licence']) : 0;
        //税务登记证上传图片总数
        $content->tax_certificate=isset($commonimg['tax_certificate']) ? count($commonimg['tax_certificate']) : 0;
        //组织机构代码证上传图片总数
        $content->organization_credit=isset($commonimg['organization_credit']) ? count($commonimg['organization_credit']) : 0;
        $content->com_business_licence_number= $company->com_business_licence_number;
        if($this->request->method()==HTTP_Request::POST){
            $service = Service::factory('User_Company_User');
            $post = Arr::map("HTML::chars", $this->request->post());
            $field_name = "";
            $com_id= $company->com_id;
            $com_business_licence_number= $post['com_business_licence_number'];
            $service->editComBusinessLicenceNumber( $com_id,$com_business_licence_number );
            foreach ($post as $k=>$v){
                if($k=='certs1'){
                    $field_name = "com_business_licence";
                }elseif($k=='certs2'){
                    $field_name = "organization_credit";
                }elseif($k=='certs3'){
                    $field_name = "tax_certificate";
                }
                $oldnum=isset($commonimg[$k]) ? count($commonimg[$k]) : 0;
                $service->uploadCertification($v,$field_name,$this->userId(),$company,$oldnum);
            }

            self::redirect('/company/member/basic/comCertification');
        }
    }

    /**
     * 企业资质认证
     * @author 潘宗磊
     */
    public function action_comCertification(){
        $user = ORM::factory("user",$this->userId());
        //has_one 对应关系 公司基本信息
        $company = $user->user_company;
//        if(!$company->com_id){
//            //企业信息不完善
//            $content = View::factory("user/company/nocard");
//            $this->content->rightcontent = $content;
//        }else{
            $content = View::factory("user/company/comcertification");
            $this->content->rightcontent = $content;
            $commonimg_list = ORM::factory("CommonImg")->where('table_name','=',1)->where('user_id','=',$this->userId())->find_all();
            $commonimg = array();
            foreach ($commonimg_list as $k=>$v){
                $commonimg[$v->field_name][$k]['common_img_id'] = $v->common_img_id;
                $commonimg[$v->field_name][$k]['url'] = URL::imgurl(str_replace('/b_','/s_', $v->url));
            }
            //工商营业执照上传图片总数
            $com_business_licence=isset($commonimg['com_business_licence']) ? count($commonimg['com_business_licence']) : 0;
            //税务登记证上传图片总数
            $tax_certificate=isset($commonimg['tax_certificate']) ? count($commonimg['tax_certificate']) : 0;
            //组织机构代码证上传图片总数
            $organization_credit=isset($commonimg['organization_credit']) ? count($commonimg['organization_credit']) : 0;
            if($com_business_licence==0||$organization_credit==0){
                self::redirect('/company/member/basic/uploadCertification');
            }
            //税务登记证审核状态
            $content->tax_certificate_status=$company->tax_certificate_status;
            //工商营业执照审核状态
            $content->com_business_licence_status=$company->com_business_licence_status;
            //组织机构代码证审核状态
            $content->organization_credit_status=$company->organization_credit_status;
            //审核未通过原因
            $content->com_auth_unpass_reason=$company->com_auth_unpass_reason;
            //显示列表
            $content->commonimg_list=$commonimg;
//        }
    }

    /**
     * 项目资质认证
     * @author 潘宗磊
     *
     */
    public function action_projectCertification(){
        $content = View::factory("user/company/project/projectcertification");
        $this->content->rightcontent = $content;
        $user_id = $this->userId();
        $user = ORM::factory("user",$user_id);
        //has_one 对应关系 公司基本信息
        $company = $user->user_company;
        //has_many 对应关系 其它认证信息
        if(!$company->com_id){
            //企业信息不完善
            self::redirect("/company/member/basic/company");
        }
        $projectcertifications = $company->com_projectcertifications->where('project_type','=',1)->find_all();
        //其他企业证书缩、原图
        $upnum=$company->com_projectcertifications->where('com_id','=',$company->com_id)->where('project_type','=',1)->count_all();
        $content->count=$upnum;//上传多少张
        $content->num=12-$upnum;//还可以上传多少张
        $project_cert = array();
        foreach ($projectcertifications as $key=>$o){
            $project_cert[$key]['org'] = $o->project_certs_img;//大图
            $project_cert[$key]['spic'] = str_replace('/b_','/s_', $o->project_certs_img);//缩略图
            $project_cert[$key]['file_name'] = $o->project_certs_name; //图片名称
            $project_cert[$key]['file_id'] = $o->project_certs_id;//认证id
        }
        $content->project_cert = $project_cert;
    }

    /**
     * 删除项目资质认证
     * @author 潘宗磊
     *
     */
    public function action_deleteProjectCertification(){
        $get = Arr::map("HTML::chars", $this->request->query());
        $service = Service::factory('User_Company_User');
        $result = $service->deleteProjectCertification(arr::get($get,'id'));
        if($result ==1){
            self::redirect("/company/member/basic/projectCertification");
        }
    }

    /**
     * 企业基本信息管理
     * @author周进
     */
    public function action_editCompany(){
        $userid = $this->userInfo()->user_id;
        $content = View::factory("user/company/companyedit");
        $service = new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        /**if (!$service->getEmailValidCount($userid)){//判断是否邮箱验证
            self::redirect("/company/member/basic/vemail");
        }**/
        $postdata = $this->request->query();
        $content->type=isset($postdata['type'])?$postdata['type']:1;
        //获取地域信息
        $invest= new Service_User_Person_Invest();
        $pro= $invest->getArea();
        $content->area              = $pro;


        if($this->request->method()== HTTP_Request::POST){
            $getdata = $this->request->query();
            $post = Arr::map("HTML::chars", $this->request->post());
            foreach ($post as $k=>$v)
                $data[$k] = trim($v);



            //在后端对提交的数据做统一验证处理 add by gongyong && 许晟玮
            $valid_post = Validation::factory($data);
            $valid_post->rule("com_phone",
                              function( Validation $valid_error, $field, $value ){
                                  if( !Valid::not_empty( $value ) || !Valid::min_length( $value,5 ) || !Valid::max_length( $value,20 ) ){
                                       $valid_error->error($field, '请输入正确的联系方式');
                                       return false;
                                  }

                                   if($value=="" or preg_match("/^[0-9-]*$/", $value) ){
                                       return true;
                                   }
                                   else{
                                       $valid_error->error($field, '请输入正确的联系方式');
                                       return false;
                                   }
                               },
                               array(':validation', ':field', ':value')
                            )//座机电话必须是数字
                       ->rule("branch_phone",
                               function(Validation $valid_error, $field, $value){
                                   if( !Valid::max_length($value,5) ){
                                       $valid_error->error($field, '分机号最多只能5位');
                                       return false;
                                   }
                                   if($value=="" or Valid::numeric($value) ){
                                       return true;
                                   }
                                   else{
                                       $valid_error->error($field, '分机号错误');
                                       return false;
                                   }

                               },
                               array(':validation', ':field', ':value')
                            )
                       ->rule("mobile","not_empty")//手机号必填
                       ->rule("mobile",
                               function(Validation $valid_error, $field, $value){
                                   //判断此手机号是否已经被绑定

                                   if( !Valid::exact_length($value,11) || !is_numeric($value) ){
                                       $valid_error->error($field, '请输入正确的手机号码');
                                       return false;
                                   }
                                   else
                                   {
                                        return true;
                                   }

                               },
                               array(':validation', ':field', ':value')
                             )

                       ->rule("com_contact","not_empty")

                       ->rule("com_contact",
                               function(Validation $valid_error, $field, $value){
                                   $rule       = "\x80-\xff";
                                   if( !preg_match("/[$rule]/",$value) && !preg_match('/^[a-zA-Z]+$/', $value) ){
                                       $valid_error->error($field, '请输入2-10个字符，由汉字、拼音组成');
                                       return false;
                                   }elseif ( !Valid::min_length( $value,2 ) || !Valid::max_length( $value,10 ) )
                                   {
                                       $valid_error->error($field, '请输入2-10个字符，由汉字、拼音组成');
                                       return false;
                                   }
                                   else
                                   {
                                        return true;
                                   }

                               },
                               array(':validation', ':field', ':value')
                             )

                       ->rule("com_nature","not_empty")
                       ->rule("com_nature","numeric")

                       ->rule("com_site",
                               function(Validation $valid_error, $field, $value){
                                   //判断是否为带http://的URL地址字符串
                                   if( $value=="" ){
                                        return true;
                                   }else{
                                       if( !Valid::url( $value ) )
                                       {
                                          $valid_error->error($field, '请输入正常的公司网址');
                                          return false;
                                       }
                                       else
                                       {
                                            return true;
                                       }


                                   }

                               },
                               array(':validation', ':field', ':value')
                           )

                     ->rule("com_adress",
                               function(Validation $valid_error, $field, $value){
                                   if( !Valid::max_length( $value,30 ) || !Valid::not_empty( $value ) ){
                                       $valid_error->error($field, '请输入正确的公司地址');
                                       return false;
                                   }
                                   else{
                                       return true;
                                   }
                               },
                               array(':validation', ':field', ':value')
                           )

                       //公司注册时间-年份
                      ->rule("com_founding_time_year",
                            function(Validation $valid_error, $field, $value){


                                   if( !Valid::exact_length( $value,4 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) || ceil($value)>date("Y") ){
                                       $valid_error->error($field, '请输入正确的公司注册时间');
                                       return false;
                                   }
                                   else{
                                       return true;
                                   }
                               },
                               array(':validation', ':field', ':value')

                      )
                      //公司注册时间-月份
                      ->rule("com_founding_time_month",
                            function(Validation $valid_error, $field, $value){

                                   if( !Valid::max_length( $value,2 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) || ceil($value)>12 ){
                                       $valid_error->error($field, '请输入正确的公司注册时间');
                                       return false;
                                   }
                                   else{
                                       return true;
                                   }
                               },
                               array(':validation', ':field', ':value')

                      )
                      //公司注册资本
                      ->rule("com_registered_capital",
                            function(Validation $valid_error, $field, $value){
                                   if( !Valid::max_length( $value,10 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) ){
                                       $valid_error->error($field, '请输入正确的公司注册资本');
                                       return false;
                                   }
                                   else{
                                       return true;
                                   }
                               },
                               array(':validation', ':field', ':value')

                      )
                      ->rule("com_registered_capital",
                            function(Validation $valid_error, $field, $value){
                                   if( !Valid::max_length( $value,10 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) ){
                                       $valid_error->error($field, '请输入正确的公司注册资本');
                                       return false;
                                   }
                                   else{
                                       return true;
                                   }
                               },
                               array(':validation', ':field', ':value')

                      )

                      ->rule("com_desc",
                            function(Validation $valid_error, $field, $value){
                                   if( !Valid::not_empty( $value ) ){
                                       $valid_error->error($field, '请输入公司简介');
                                       return false;
                                   }
                                   else{
                                       return true;
                                   }
                               },
                               array(':validation', ':field', ':value')

                      )




                    ;
            if(!$valid_post->check()){
                $result['error'] = $valid_post->errors();

                $result['status'] = -1;
            }
            else{
                //exit('ok');
                $result = $service->updateCompanyBasic($data,$this->userId(),$this->userInfo()->user_name,$this->userInfo()->mobile);
            }
            //验证判断结束


            //添加用户完成基本信息积分
            if($result['status'] == 1){
                $points = Service::factory("User_Company_Points");
                $points->getPointsOnce($userid,"complete_basic");
            }
            //添加招商项目时，没有完成企业基本信息的，先保存已经填写好的到session里，等完成基本信息后再写入数据库
            //@author曹怀栋
            if(isset($result['com_id'])){
                $session = Session::instance();
                if($session->get("addproject")){
                    $session->set("addProject1",'addProject');
                   $res = $service->getSessionProject($session->get("addproject"),$result['com_id'],$this->userId());
                   if( $res == true) self::redirect("/company/member/project/showproject");
                }
            }
            if ($result['status']=="-1"){//判断是否有误
                $content = View::factory("user/company/companyedit");
                $content->error = $result['error'];
                $companyinfo = $card_service->getCompanyCard($this->userInfo()->user_id);
                $post['com_logo']=URL::imgurl($companyinfo['companyinfo']->com_logo);
                $content->companyinfo = $post;
                $content->type=isset($postdata['type'])?$postdata['type']:1;
                $content->user_name = $this->userInfo()->user_name;
                $content->email = $this->userInfo()->email;
                $content->user_info = $this->userInfo();
                $content->mobile = $post['mobile'];//获取用户手机号
                $content->com_phone = $post['com_phone'];
                $content->branch_phone = $post['branch_phone'];
                $session = Session::instance();
                if($session->get("addProject2")){
                    $content->addproject= "ok";
                    $session->delete("addProject2");
                }else{
                    $content->addproject= "no";
                }
                $content->com_founding_time_year     = $post['com_founding_time_year'];
                $content->com_founding_time_month    = $post['com_founding_time_month'];
                $content->com_registered_capital     = $post['com_registered_capital'];
                $content->com_desc                   = $post['com_desc'];
                $content->areaIds           = $companyinfo['companyinfo']->com_city;

                $this->content->rightcontent = $content;
            }elseif(isset($getdata['type']) && $getdata['type']==2){//返回我的名片
                self::redirect(URL::site('/company/member/card/mycard'));
            }else{//返回企业基本信息管理页面
                self::redirect(URL::site('/company/member/basic/company'));
            }

        }else{
             $companyinfo = $card_service->getCompanyCard($this->userInfo()->user_id);
             $content->companyinfo = $companyinfo['companyinfo']->as_array();
             $content->user_name = $this->userInfo()->user_name;
             $content->email = $this->userInfo()->email;
             $content->mobile = $this->userInfo()->mobile;//获取用户手机号
             $session = Session::instance();
             if($session->get("addProject2")){
                $content->addproject= "ok";
                $session->delete("addProject2");
             }else{
                $content->addproject= "no";
             }

             //ger area and city ,show it
             $pro_id = $companyinfo['companyinfo']->com_area;
             if($pro_id !='' && $pro_id !='88'){
                 $area                   = array('pro_id'=>$pro_id);
                 $cityarea               = common::arrArea($area);
             }else{
                 $cityarea               = array();
             }
             $content->areaIds           = $companyinfo['companyinfo']->com_city;
             $content->cityarea          = $cityarea;
             $content->pro_id			= $pro_id;


             if(!empty($companyinfo['companyinfo']->com_phone)){
                $com_phone=explode('+', $companyinfo['companyinfo']->com_phone);
                if(!empty($com_phone[1])){//判断座机号码是否为空
                    $content->com_phone = $com_phone[0];
                    $content->branch_phone = $com_phone[1];
                }else{
                    $content->com_phone = $companyinfo['companyinfo']->com_phone;
                    $content->branch_phone = '';
                }
             }else{
                $content->com_phone = "";
                $content->branch_phone = '';
             }

             $content->com_founding_time_year     = UTF8::substr( $companyinfo['companyinfo']->com_founding_time,0,4 );
             $content->com_founding_time_month     = UTF8::substr( $companyinfo['companyinfo']->com_founding_time,4 );

             $content->com_registered_capital     = $companyinfo['companyinfo']->com_registered_capital;
             $content->com_desc                   = $companyinfo['companyinfo']->com_desc;
            $this->content->rightcontent = $content;
            $content->user_info = $this->userInfo();

        }

    }

    /**
     * 企业基本信息管理
     * @author潘宗磊
     */
    public function action_company(){
        $userid = $this->userInfo()->user_id;
        $user = $this->userInfo(true);
        $service = new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        $content = View::factory("user/company/company");
        $companyinfo = $card_service->getCompanyCard($this->userInfo()->user_id);
        /**if (!$service->getEmailValidCount($userid)){//判断是否邮箱验证
            self::redirect("/company/member/basic/vemail");
        }**/
        //判断企业基本信息是否完整，如果不完整就跳转到修改页面
        if(empty($companyinfo['companyinfo']->com_logo)||empty($companyinfo['companyinfo']->com_phone)||empty($companyinfo['companyinfo']->com_contact)||empty($companyinfo['companyinfo']->com_adress)){
            self::redirect(URL::site('/company/member/basic/editcompany').'?type=1');
        }
        $content->companyinfo = $companyinfo['companyinfo'];
        $content->visit_card=$user->basic->visit_card;//获取是否访问过名片页面
        $content->user_name = $user->user_name;
        $content->user_info = $user;
        $content->email = $user->email;
        $content->mobile = $user->mobile;//获取用户手机号
        if(!empty($companyinfo['companyinfo']->com_phone)){//获取座机号码
            $com_phone=explode('+', $companyinfo['companyinfo']->com_phone);
            if(!empty($com_phone[1])){//判断座机号码是否为空
                $content->com_phone = $com_phone[0];
                $content->branch_phone = $com_phone[1];
            }else{
                $content->com_phone = $companyinfo['companyinfo']->com_phone;
                $content->branch_phone = '';
            }
        }else{
            $content->com_phone = "";
            $content->branch_phone = '';
        }

        //获取公司成立年份
        $content->com_founding_time     = $companyinfo['companyinfo']->com_founding_time;

        //获取公司注册资本
        $content->com_registered_capital = $companyinfo['companyinfo']->com_registered_capital;

        //获取公司简介
        $content->com_desc              = $companyinfo['companyinfo']->com_desc;

        //获取地址
        if( ceil($companyinfo['companyinfo']->com_area)>0 ){
            $area_arr= array('id'=>$companyinfo['companyinfo']->com_area);
            $rs_area= common::arrArea($area_arr);
            $area_name= $rs_area->cit_name;
        }else{
            $area_name= '';
        }

        if( ceil( $companyinfo['companyinfo']->com_city!='' )>0  ){
            $city_arr= array('id'=>$companyinfo['companyinfo']->com_city);
            $rs_city= common::arrArea($city_arr);
            $city_name= $rs_city->cit_name;
        }else{
            $city_name= '';
        }
        $content->area_name= $area_name;
        $content->city_name= $city_name;
        $this->content->rightcontent = $content;
    }

    /**
     * 邮件验证操作
     * @author 周进
     */
    public function action_vEmail(){
        $this->isLogin();
        $service = new Service_User_Company_User();
        //已经通过验证的
        if($service->getEmailValidCount($this->userId())==1)
            self::redirect("/company/member");
        if ($this->request->query('email')=="send"){
            $service = new Service_User_Company_User();
            $result = $service->updateCheckValidEmail($this->userInfo()->user_id,$this->userInfo()->email);
        }
        $content = View::factory('user/company/vemail');
        $content->email = $this->userInfo()->email;
        $content->showtime = $service->getValidCode($this->userId(),1,Kohana::$config->load('message.expire.email'));
        $toemailurl = explode('@', $this->userInfo()->email);
        if ($toemailurl[1]=="gmail.com")
            $content->toemailurl = "http://mail.google.com/";
        else
            $content->toemailurl = "http://mail.".$toemailurl[1];
        $this->content->rightcontent = $content;
    }

    /**
     * 修改登录密码
     *  @author 曹怀栋
     */
    public function action_modifyPassword(){
        if($this->request->method()== HTTP_Request::POST){
            $content = View::factory('user/company/modifypassword');
            $post = Arr::map("HTML::chars", $this->request->post());
            $service = new Service_User_Company_User();
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
                            "mobile_company_edit_password",
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
                        "email_company_edit_password",
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
            $content = View::factory('user/company/modifypassword');
        }
        $this->content->rightcontent = $content;
    }

    /**
     * 上传项目资质认证
     * @author 潘宗磊
     *
     */
    public function action_upProjectCert(){
        $user_id = $this->userId();
        $user = ORM::factory("user",$user_id);
        //has_one 对应关系 公司基本信息
        $company = $user->user_company;
        //has_many 对应关系 其它认证信息
        if(!$company->com_id){//如果没有填写企业基本信息，跳转到企业基本信息哪里
            //企业信息不完善
            self::redirect("/company/member/basic/company");
        }
        $upnum=$company->com_projectcertifications->where('com_id','=',$company->com_id)->where('project_type','=',1)->count_all();
        if($this->request->method()==HTTP_Request::POST){
            $service = Service::factory('User_Company_User');
            //TODO 判断企业信息是否完善,上传是否为空,单独写个函数判断
            if(!$company->com_id){
                echo "企业信息不完善";
                return false;
            }
            $files = $_FILES;
            //上传图片判断
            $Projectcerts = ORM::factory("Projectcerts");
            $certs_name = Arr::get(Arr::map("HTML::chars", $this->request->post()), 'test');
            $oldCerts_name = Arr::get(Arr::map("HTML::chars", $this->request->post()), 'certs_name');
            $oldCerts_id = Arr::get(Arr::map("HTML::chars", $this->request->post()), 'certs_id');
            if(!empty($oldCerts_name)&&!empty($oldCerts_id)){//上传图片时候判断是否修改名称
                for ($i=0;$i<count($oldCerts_name);$i++){
                    $result = $service->editProjectCertName($oldCerts_id[$i],$oldCerts_name[$i]);
                    if(!empty($result)){
                    }else{
                        echo '上传失败';
                        return false;
                    }
                    $Projectcerts->clear();
                }
            }
            $result = $service->uploadProjectCertification($files,$certs_name,'project_certification',$Projectcerts,$company,$upnum);
            self::redirect('/company/member/basic/projectCertification');
        }else{
            self::redirect("/company/member/basic/projectCertification");
        }
    }

    /**
     * 诚信列表
     * @author 龚湧
     */
    public function action_integrity(){
        $service = Service::factory("User_Company_Integrity");

        //表单年列表
        $year_list = array(0=>"选择年份");
        for($year=2013;$year<2030;$year++){
            $year_list[$year] = $year."年";
        }
        //表单月列表
        $month_list = array(0=>"选择月份");
        for($month=1;$month<=12;$month++){
            $month_list[$month] = $month."月";
        }

        //搜索明细列表
        $search = $this->request->query();
        $result = $service->getList($this->userId(),$search);
        //初始化表单状态
        $search_status = array(
                'from_year'=>Arr::get($search, "from_year"),
                'from_month'=>Arr::get($search, "from_month"),
                'to_year'=>Arr::get($search, "to_year"),
                'to_month'=>Arr::get($search, "to_month")
        );

        //当前用户可用积分
        $total_itys = $service->getIntegrityByTime($this->userId());
        //更具点数获取用户等级
        $ity_level = $service->getIntegrityLevel($this->userId());

        $content = View::factory("user/company/itylist");
        $this->content->rightcontent = $content;
        $content->result = $result;
        $content->total_itys = $total_itys;
        $content->ity_level = $ity_level;
        $content->search_status = $search_status;
        $content->year_list = $year_list;
        $content->month_list = $month_list;
    }

    /**
     * 企业诚信规则
     * @author 龚湧
     */
    public function action_ityrule(){
        $content = View::factory("user/company/ityrule");
        $this->content->rightcontent = $content;
    }

    /**
     * 账户被禁用默认页面
     * @author 周进
     * @modified by 赵路生 2013-11-20
     */
    public function action_accountforbidden(){
        $content = View::factory("user/company/account_forbidden");
        $user_ser = new Service_Account();
        $content->forbid_result = $user_ser->getForbidAccountNote($this->userId());
        $this->content->rightcontent = $content;
    }


    /**
     * 修改会员邮箱
     * @author许晟玮
     */
    public function action_editMail(){
        //必须登录
        $this->isLogin();
        $content = View::factory('/user/company/editemail_a');
        $this->content->rightcontent = $content;
        //判断是否修改过2次邮箱
        $service = new Service_User();
        $edit_count= $service->getEditEmailCount( $this->userId() );
        $content->username= $this->userInfo()->email;
        if( $edit_count>=2 ){
            self::redirect('/company/member/safe');
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
            $content = View::factory('/user/company/editemail_b');
            $this->content->rightcontent = $content;
            $password= Arr::get($this->request->post(), 'password');
            if( $password=='' ){
                self::redirect('/company/member/basic/editMail');
            }
            $content->password= $password;
        }else{
            self::redirect('/company/member/basic/editMail');

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
                self::redirect('/company/member/basic/editMail');
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
                            $content = View::factory('/user/company/editMailBOK');
                            $this->content->rightcontent = $content;
                            $content->email= $email;
                            $toemailurl = explode('@', $email);
                            if ($toemailurl[1]=="gmail.com"){
                                $content->toemailurl = "http://mail.google.com/";
                            }else{
                                $content->toemailurl = "http://mail.".$toemailurl[1];
                            }
                        }else{
                            self::redirect('/company/member/basic/editMail?1');
                        }
                    }else{
                        //eidt email false
                        self::redirect('/company/member/basic/editMail?2');
                    }

                }else{
                    self::redirect('/company/member/basic/editMail?3');
                }
            }
        }else{
            self::redirect('/company/member/basic/editMail?4');
        }

    }
    //end function

    /**
     * 修改邮箱第三步
     * @author许晟玮
     */
    public function action_editemailc(){
        $content = View::factory('/user/company/editemailc');
        $this->content->rightcontent = $content;
    }

    //end function

    /**
     * 邮箱未验证进去验证的页面
     * @author 许晟玮
     */
    public function action_setEmail(){
        $content = View::factory('/user/company/setemail');
        $this->content->rightcontent = $content;
        //user info
        $userinfo= $this->userInfo(true);
        if( ceil( $userinfo->valid_email )!=0 ){
            self::redirect('/company/member');
        }else{
        }
        $content->email= $userinfo->email;

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
                self::redirect('/company/member');
            }else{
            }

            $post= $this->request->post();
            $email= Arr::get($post, 'inp_email');
            if( $email=='' ){
                self::redirect('/company/member/basic/setEmail');
            }else{
                $user= $this->userInfo(true);
                if( $user->email=='' ){
                    //set
                    $cl= Service_Sso_Client::instance();
                    $result_sso= $cl->setUserEmailById( $user->id,$email );
                }else{
                    //edit
                    $cl= Service_Sso_Client::instance();
                    $result_sso= $cl->updateEmailInfoById( $user->id,array( 'email'=>$email,'valid_status'=>'0' ) );
                }


                $mailservice = new Service_User_Company_User();
                $mailservice->updateCheckValidEmail($user->id,$user->email);
                $content = View::factory('/user/company/email_valid_ok');
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
            self::redirect('/company/member');
        }
        
        
        
    }
    //end function
    /**
         * 升级公告
         * @author stone shi
         */
    public function action_upgradeNotice() {
        $content = View::factory('/user/company/upgradeNotice');
        $this->content->rightcontent = $content;
    }
}