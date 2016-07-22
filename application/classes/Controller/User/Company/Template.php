<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 模板中rightcontent 属性一定要有
 * 企业用户中心模板
 * @author 龚湧
 */
class Controller_User_Company_Template extends Controller_Template{

    /**
    *企业基本信息是否完善 ，即判断是否有企业信息
    * @author 龚湧
    * @param int $user_id
    * @return bool
    */
    public function is_complete_basic($user_id){
        $company = ORM::factory('Companyinfo')->where('com_user_id','=',$user_id)->find();
        if( $company->com_id ){
            return true;
        }
        return false;
    }

    /**
    * @var view
    */
    public $content;
    public $title;
    public $description;
    public $keywords;

    /**
     * 企业用户中心
     * @var 二级模板,指定即可
     */
    private  $content_template = "user/company/template";

    public function before(){
        parent::before();
        $this->isLogin();

        //每天用户首次登录送积分
        $points = Service::factory("User_Company_Points");
        $points->getPointsRepeatByDay($this->userId(),"login");
        //end 每天用户首次登录送积分
        $this->template->description = '';
        $this->template->keywords = '';
        $this->template->content = $this->content = View::factory($this->content_template);
        //记录contrller_方法名
        $con_method= $this->request->controller().'_'.$this->request->action();
        //设置默认值
        $seo_title=common::getCompanySEOTitle($con_method);
        if($seo_title==''){
            $this->template->title = '企业中心－一句话投资招商平台|投资赚钱好项目，一句话的事。';
        }else{
            $this->template->title = $seo_title.'－企业中心－一句话投资招商平台|投资赚钱好项目，一句话的事。';
        }
        //设置默认值
        $this->content->rightcontent = '';
        $service= new Service_User_Company_User();
        $card_service=new Service_User_Company_Card();
        $investor_service = new Service_User_Company_Investor();
        $com_photo=$service->getCompanyInfo($this->userId())->com_logo;
        if($com_photo){
            $this->content->logo = URL::imgurl($com_photo);
        }else{
            $this->content->logo ='';
        }
        //避免非法链接直接进入
        if ($this->userInfo()->user_type!='1')
            $this->userType($this->userInfo()->user_type);
        //邮件未验证的相关操作
        $url = parse_url($_SERVER['REQUEST_URI']) ;
        //手机注册功能开启，关闭次功能暂时
        if($service->getEmailValidCount($this->userId())!=1&&($url["path"]!="/company/member/basic/vemail")&&($url["path"]!="/company/member/basic/vemail/")&&$this->userInfo()->valid_mobile!='1' ){
            self::redirect("/company/member/basic/vemail");
        }
        $this->content->user_name = $this->userInfo()->user_name;
        $this->content->user_email = $this->userInfo()->email;
        $service_project = new Service_User_Company_Project();
        //获取用户积分
        $points = Service::factory("User_Company_Points");
        $useable_points = $points->getUsablePointsByTime($this->userId());
        $ucmsg = Service::factory("User_Ucmsg");
        //用户诚信等级
        $integrityservice=new Service_User_Company_Integrity();
        $ity_level = $integrityservice->getIntegrityLevel($this->userId());
        $this->content->ity_level = $ity_level;
        //生成新消息
        //$ucmsg->generateMsg($this->loginUserId(),$this->userInfo()->last_logintime,$this->userInfo()->user_type);//更新和创建消息
        //记录contrller_方法名
        $this->content->actionmethod = $con_method;
        $this->content->useable_points = $useable_points;
        $this->content->project_count = $service_project->getTemplateProjectCount($this->userId());
        //新收到的名片数量
        $this->content->receivecard_count=$card_service->getReceiveCardNewCount($this->userInfo()->user_id);
        //我递出的名片中新交换名片数量
        $this->content->exchangecard_count=$card_service->getExchangeCardNewCount($this->userInfo()->user_id);
        $search=array();
        if($this->content->actionmethod=='Investor_search'){
            $search = $this->request->query();
        }
        if(count($search)<6){//初次打开页面 自动打开最新搜索条件
            //初次打开搜索投资者 自动打开最新搜索条件
            $onedata = $investor_service->getOneConditions($this->userInfo()->user_id);
            if($onedata->user_id && $onedata->total_count>0){
                $newserarch = $investor_service->getNewSerarch($onedata->as_array());
                $this->content->search_url = '?parent_id='.$newserarch['parent_id'].'&industry_id='.$newserarch['industry_id'].'&pro_id='.$newserarch['pro_id'].'&area_id='.$newserarch['area_id'].'&per_amount='.$newserarch['per_amount'].'&per_identity='.$newserarch['per_identity'].'&per_join_project='.$newserarch['per_join_project'].'&per_connections='.$newserarch['per_connections'].'&per_investment_style='.$newserarch['per_investment_style'].'&hiddenvalue='.$newserarch['hiddenvalue'];
            }else{
                $this->content->search_url ='';
            }
        }else{
            if($this->content->actionmethod=='Investor_search'){
                $newserarch = $investor_service->getNewSerarch($search);
                $this->content->search_url = '?parent_id='.$newserarch['parent_id'].'&industry_id='.$newserarch['industry_id'].'&pro_id='.$newserarch['pro_id'].'&area_id='.$newserarch['area_id'].'&per_amount='.$newserarch['per_amount'].'&per_identity='.$newserarch['per_identity'].'&per_join_project='.$newserarch['per_join_project'].'&per_connections='.$newserarch['per_connections'].'&per_investment_style='.$newserarch['per_investment_style'].'&hiddenvalue='.$newserarch['hiddenvalue'];
            }else{
                $this->content->search_url ='';
            }
        }
    }


    public function after(){
        parent::after();
    }
}