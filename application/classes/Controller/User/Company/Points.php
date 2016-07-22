<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户积分
 * @author 龚湧
 *
 */
class Controller_User_Company_Points extends Controller_User_Company_Template{

    /**
    * 企业用户积分明细
    * @author 龚湧
    */
    public function action_index(){
        $service = Service::factory("User_Company_Points");

        //表单积分类型列表
        $points_type_list = points_company_type::sort();
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
                'point_type'=>Arr::get($search, "point_type"),
                'from_year'=>Arr::get($search, "from_year"),
                'from_month'=>Arr::get($search, "from_month"),
                'to_year'=>Arr::get($search, "to_year"),
                'to_month'=>Arr::get($search, "to_month")
        );

        //当前用户可用积分
        $useable_points = $service->getUsablePointsByTime($this->userId());
        //更具积分，获取用户等级
        $user_level = $service->getUserLevel($this->userId());

        $content = View::factory("user/company/pointslist");
        $this->content->rightcontent = $content;
        $content->result = $result;
        $content->useable_points = $useable_points;
        $content->user_level = $user_level;
        $content->points_type_list = $points_type_list;
        $content->search_status = $search_status;
        $content->year_list = $year_list;
        $content->month_list = $month_list;
    }


    /**
    * 积分规则
    * @author 龚湧
    */
    public function action_rule(){
        $content = View::factory("user/company/pointsrule");
        $this->content->rightcontent = $content;
    }
}