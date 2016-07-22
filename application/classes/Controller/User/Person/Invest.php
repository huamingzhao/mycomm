<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户名片信息
 */
class Controller_User_Person_Invest extends Controller_User_Person_Template{
    /**
     * 搜索招商会
     * @author 潘宗磊
     */
    public function action_searchInvest(){
        $service=new Service_User_Person_User();
        //获取登录user_id
        $userid = $this->userid();
        //获得个人基本信息
        $personinfo = $service->getPersonInfo($userid);

            //view页面加载
            $content = View::factory("user/person/showinvest");
            $this->content->rightcontent = $content;
            $invest = new Service_User_Person_Invest();
            //获取表单值
            $search = Arr::map("HTML::chars", $this->request->query());
            if(!empty($search['parent_id'])){
                $content->indust = common::primaryIndustry($search['parent_id']);
            }

            $result = $invest->searchInvestment($search);
            $content->listIndustry = common::primaryIndustry(0);
            $content->area = $invest->getArea();
            if(!empty($search['investment_province'])){
                $content->citys = $invest->getArea($search['investment_province']);
            }
            $content->money = common::moneyArr();
            $content->list = $invest->getResaultList($result['list'],$userid);
            $content->page = $result['page'];
            $content->search = $search;
            $content->cit_name = ORM::factory("City",arr::get($search, 'investment_city'))->cit_name;
            $content->industry_name = ORM::factory("Industry",arr::get($search, 'project_industry_id'))->industry_name;
            $content->mobile=$personinfo['mobile'];
            $content->personinfo=$personinfo['user_person'];
            $content->userid=$userid;

    }

  /**
    * 报名招商会
    * @author 潘宗磊
    */
    public function action_applyInvest(){
        if($this->request->method()== HTTP_Request::POST){
            $post = Arr::map("HTML::chars", $this->request->post());
            $invest = new Service_User_Person_Invest();
            $result=$invest->applyInvest($post);
            if($result>0){
                self::redirect('/person/member/invest/searchInvest');
            }
        }
    }

    /**
     * 我报名的招商会
     * @author 潘宗磊
     */
    public function action_myInvest(){
        $service=new Service_User_Person_User();
        //获取登录user_id
        $userid = $this->userid();
        //获得个人基本信息
        $personinfo = $service->getPersonInfo($userid);
        $invest = new Service_User_Person_Invest();

        if($invest->investCount($userid)==0){//判断是否有报名招商会
            $content = View::factory("user/person/noinvest");
            $this->content->rightcontent = $content;
        }else{
            //view页面加载
            $content = View::factory("user/person/myinvest");
            $this->content->rightcontent = $content;
            $result = $invest->myInvestment($userid);
            $content->list = $result['list'];
            $content->page = $result['page'];
        }
    }

    /**
     * 删除我报名的招商v会
     * @author 潘宗磊
     */
    public function action_deleteInvest(){
        $query = Arr::map("HTML::chars", $this->request->query());
        $apply_id = intval(Arr::get($query, 'apply_id'));
        $invest = new Service_User_Person_Invest();
        $result=$invest->deleteApply($apply_id);
        if($result){
            self::redirect('/person/member/invest/myInvest');
        }
    }
}