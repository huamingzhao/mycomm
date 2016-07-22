<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人中心--所获奖励
 * @author 赵路生 
 */
class Controller_User_Person_Medal extends Controller_User_Person_Template{
    /**
    * 个人中心--我的奖励
    * @author 赵路生
    */
    public function action_index(){
        $content = View::factory('user/person/medal');       
        $this->content->rightcontent = $content;
        $setting = common::getSpecificProjectSetting();
        $userinfo = $this->userInfo();
        $ishaving = false;
        
        if($userinfo && $userinfo->id){
        	$card_ser = new Service_Card();
        	$ishaving = $card_ser->getIsHavingForSpePro($userinfo->id);
        }
        $content->ishaving = $ishaving;
        $content->setting = $setting;
    }
}