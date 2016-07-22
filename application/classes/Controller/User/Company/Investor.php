<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 找投资者
 * @author 龚湧
 */
class Controller_User_Company_Investor extends Controller_User_Company_Template{
    /**
     * 搜索投资者
     * @author 钟涛
     */
    public function action_search(){
        $service=new Service_User_Company_User();
        //获取登录user_id
        $userid = $this->userInfo()->user_id;


        //获取页面post表单值
        $search = $this->request->query();
        //投资行业列表
        $allindustry = common::primaryIndustry(0);
        //2级取得行业
        $list_industry2=array();
        if(isset($search['parent_id']) && is_numeric($search['parent_id'])){
            $list_industry2s =common::primaryIndustry($search['parent_id']);
            foreach ($list_industry2s as $k=>$v){
                $list_industry2[$k]['industry_id']=$v->industry_id;
                $list_industry2[$k]['industry_name']=$v->industry_name;
            }
        }
        foreach ($allindustry as $key=>$lv){
            $industry[$lv->industry_id] = $lv->industry_name;
        }
        array_unshift($industry,'不限');
        //投资金额等级
        $level = common::moneyArr();
        array_unshift($level, "不限");
        //view页面加载
        $content = View::factory("user/company/searchinvestor");
        $this->content->rightcontent = $content;
        $content->primaryIndustry = $industry;
        $content->level = $level;

        $investor_service = new Service_User_Company_Investor();
        $invest = new Service_User_Person_Invest();
        //读取省级地区列表
        $content->area = $invest->getArea();
        //获取城市地区
        $pro_id=arr::get($search, 'pro_id','');
        if($pro_id !=''){
            $area = array('pro_id'=>$pro_id);
            $content->cityarea=common::arrArea($area);
        }else{
            $content->cityarea=array();
        }
        //获取当前页数
        $urlpage = $this->request->query('page')?$this->request->query('page'):1;
        //获取搜索到的投资者的名片信息列表
        $type=1;//获取列表信息
        $return_arr=$investor_service->searchInvestorInfoNew($type,$search,$userid,$urlpage);
        $content->list=$return_arr['list'];
        $content->page= $return_arr['page'];
        $content->totalcount= $return_arr['total_count'];
        $content->postlist=$search;
        $content->list_industry2=$list_industry2;
        $this->content->rightcontent = $content;
    }

    /**
     * 筛选投资者历史记录列表页面信息
     * @author 钟涛
     */
    public function action_searchConditionsList(){
        $service=new Service_User_Company_User();
        //获取登录user_id
        $userid = $this->userInfo()->user_id;

        //view页面加载
        $content = View::factory("user/company/searchinvestorlist");
        $this->content->rightcontent = $content;
        $investor_service = new Service_User_Company_Investor();
        //筛选条件历史记录
        $return_arr=$investor_service->searchConditionsList($userid);
        $content->list=$return_arr['list'];
        $content->page= $return_arr['page'];
        $content->totalcount= $return_arr['totalcount'];
        $this->content->rightcontent = $content;
    }

    /**
     * 删除筛选投资者历史记录
     * @author 钟涛
     */
    public function action_deleteConditionsByArr(){
        $getdata = Arr::map("HTML::chars", $this->request->query());
        $investor_service = new Service_User_Company_Investor();
        $idarr=array();
        //id数组
        $idarr= explode(",", $getdata['idarr']);
        if(count($idarr)){//删除
            $investor_service->deleteConditionsByArr($idarr);
        }
        self::redirect("/company/member/investor/searchConditionsList");
    }

    /**
     * 用户订阅投资者功能
     * @author 施磊
     */
    public function action_searchSubscription() {
        $service=new Service_User_Company_User();

        //获取登录userId
        $userId = $this->userInfo()->user_id;

        //判断是否邮箱验证

        $investor_service = new Service_User_Company_Investor();

        //判断用户是否已经开通
        $subscriptionStatus = $investor_service->getUserSubscriptionStatus($userId);
        $subscriptionList = $investor_service->getUserSubscriptionByUserId($userId);
        //view页面加载
        $content = View::factory("user/company/searchSubscription");
        $this->content->rightcontent = $content;
        $this->content->rightcontent->subscriptionStatus = $subscriptionStatus;
        $this->content->rightcontent->email = $this->userInfo()->email;
        $this->content->rightcontent->list = $subscriptionList->subscription_id ? TRUE : FALSE;
    }

}