<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Controller_Template extends Controller{

    /**
    * @var  View  page template
    */
    public $template = 'template';

    /**
    * @var  boolean  auto render template
    **/
    public $auto_render = TRUE;

    /**
    * Loads the template [View] object.
    */
    public function before()
    {

        parent::before();
        //echo ;
        //$path = $this->request->directory().'/'.$this->request->controller().'/'.$this->request->action();
        $path = $_SERVER['REQUEST_URI'];
        $path = strtolower($path);
        $get = $this->request->query();
        if(arr::get(Kohana::$config->load("staticpage.ALL_PATH"), $path) && Kohana::$config->load("staticpage.STATIC_STATUS")) {
            $key = arr::get(Kohana::$config->load("staticpage.ALL_PATH"), $path);
            $filename = Kohana::$config->load("staticpage.{$key}");
            $content = file_get_contents($filename);
            if($content) {
                echo $content;
                exit;
            }
        }

        if ($this->auto_render === TRUE)
        {
            // Load the template
            $this->template = View::factory($this->template);
            $search=new Service_Platform_Search();
            $service_Investor=new Service_Platform_SearchInvestor();
            //记录前一个url start
            /*<?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>*/
            $black_list = array("member/register","member/login");//特殊处理
            if(in_array($this->request->uri(),$black_list)){
                $last_uri = $this->request->query("to_url");
                if($last_uri){
                    $to_url = urldecode($last_uri);//上一个的url
                }
                else{
                    $to_url = "";
                }
            }
            else{
                $re_uri=$_SERVER['REQUEST_URI'];
                $host_url=$_SERVER['HTTP_HOST'];
                if(@stristr($host_url,'wen') ===FALSE){
                    $to_url = URL::website($re_uri);
                }else{
                    $to_url =URL::webwen($re_uri);
                }

                //if($this->request->query()){
                    //$to_url=$to_url."?".http_build_query($this->request->query());
                //}
            }
            //echo $to_url;
            //检验url的合法性
            if($to_url){
                if(strpos(urldecode($to_url),URL::website(""))!==0){//www是否合法
                    if(strpos(urldecode($to_url),URL::webwen(""))!==0){//wen是否合法
                        $to_url = '';
                    }
                }
                $to_url = urlencode($to_url);
            }
            #
            if(strstr($_SERVER['REQUEST_URI'],"/platform/index") == true){
                $to_url =URL::website("");
            }

            if(strstr($_SERVER['REQUEST_URI'],"/platform/index/search") == true){
                $to_url = URL::website("")."xiangdao/";
            }
            View::bind_global("to_url",$to_url);

            //记录前一个url end


            $memcache = Cache::instance('memcache');
            Cookie::set("to_url", urldecode($to_url),"120");
            //print_r ($memcache);
            //总项目数
            try {
                $platform_num  = $memcache->get( 'QuickProjectCount' );
            }
            catch (Cache_Exception $e) {
                $platform_num  = 0;
            }

            if( $platform_num==0 ){
                $service_p= new Service_QuickPublish_ProjectComplaint();
                $platform_num= $service_p->getQucikProjectCount();
                //$memcache->set( 'getProjectTotal', $platform_num, '86400' );
            }else{
            }

            //总用户数
            try {
                $user_num  = $memcache->get( 'getUserTotal' );
            }
            catch (Cache_Exception $e) {
                $user_num  = 0;
            }
            if( $user_num==0 ){
                $service_user = new Service_User();
                $user_num           = $service_user->getRegUserNum();
                $memcache->set( 'getUserTotal', $user_num, '86400' );
            }else{
            }


            $this->template->set_global('reg_fu_user_num',$user_num);
            $this->template->set_global('reg_fu_platform_num',$platform_num);


            //设置默认值
            $this->template->login_user_id=0;
            $this->template->content = '';
            $this->template->title = '';
            $this->template->description = '';
            $this->template->keywords = '';
            $islogin=$this->loginUser();
            $this->template->set_global('reg_fu_is_login',$islogin);
            $this->template->islogin=$islogin;
            $this->template->Tags = $search->getTags();
            #最新浏览的项目
             $this->template->ProjectAndPersonInfo = $search->getNewWatchProjectInfo();
            #项目的申请数量
             $this->template->project_Card_Num = $search->_getProjectComCarNum();
             $this->template->Tags_touzi = $service_Investor->findTag();
             $this->template->alltotalcount = $service_Investor->getPersonAllCount();
             $this->template->projectAllNum = $search->_getProjectAllCount();
            //记录contrller_方法名
            $con_method= $this->request->controller().'_'.$this->request->action();
            $this->template->actionmethod=$con_method;
            $this->template->controllermethod=$this->request->controller().'_'.$this->request->directory();
            $arr_qucik_data = array();
            if($con_method == "Project_projectinfo"){
                $quick_project_id = arr::get($get,"project_id");
                $Service_QuickPublish_Project =  new Service_QuickPublish_Project();
                $arr_qucik_data['area_list'] = $Service_QuickPublish_Project->getAreaNameById($quick_project_id,2);
                 $arr_qucik_data['industry_list'] = $Service_QuickPublish_Project->getIndustryNameById($quick_project_id,2);
                $arr_linshi = $Service_QuickPublish_Project->getProject($quick_project_id);
                //echo  "<pre>"; print_r($arr_qucik_data);exit;
                $arr_qucik_data['project_brand_name'] = arr::get($arr_linshi, "project_brand_name");

            }

            $this->template->arr_qucik_data = $arr_qucik_data;
            if($islogin){//如果已登录，获取用户名
                //sso_user中是否存在会员
                $user_service= new Service_User();
                $user_arr= array(
                    'user_id'=>$this->userId(),
                    'last_logintime'=>time(),
                    'last_login_ip'=> ip2long ( Request::$client_ip )
                );

                $user_service->updateUser( $user_arr );

                $username=$this->userInfo();//print_r ($this->userInfo());exit;
                $this->template->ismobile= $this->userInfo()->valid_mobile;
                $this->template->login_user_id=$this->userId(); //登录的用户id
                //用户跳转url
                $this->template->url = ($username->user_type == 1) ? "/company/member" : "/person/member";
                $this->template->user_type =$username->user_type;
                //未读消息总数
                $ucmsg = Service::factory("User_Ucmsg");
                $msg_total_count = $ucmsg->getMsgCount($this->userId());
                $msg_total_count = $msg_total_count?$msg_total_count:0;
                $this->template->msg_total_count = $msg_total_count;

                Cookie::set("msg_total_count",$msg_total_count,"60");


                if($username->user_type == 1){//企业用户
//                     $com_service= new Service_User_Company_User();
//                     //企业联系人
//                     $thisusername = $com_service->getCompanyInfo($username->user_id)->com_contact;
//                     if($thisusername){
//                         $this->template->username=mb_substr($thisusername,0,7);
//                     }else{
//                         $this->template->username=mb_substr($username->email,0,12);
//                     }
                    $this->template->username="去企业中心";
                }elseif($username->user_type == 2){//个人用户
//                     $perservice=new Service_User_Person_User();
//                     $perinfo=$perservice->getPerson($username->user_id);
//                     //个人真实姓名
//                     if($perinfo->per_realname){
//                         $this->template->username=mb_substr($perinfo->per_realname,0,7);
//                     }else{
//                         $this->template->username=mb_substr($username->email,0,12);
//                     }
                    $this->template->username="去个人中心";
                }else{
                    $this->template->username='';
                }
            }
        }
    }

    /**
    * Assigns the template [View] as the request response.
    */
    public function after()
    {
        if ($this->auto_render === TRUE)
        {
            $this->response->body($this->template->render());
        }

        parent::after();
    }
}
