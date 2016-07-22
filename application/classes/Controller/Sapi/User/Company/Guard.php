<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 企业用户
 * @author 施磊
 *
 */
class Controller_Sapi_User_Company_Guard extends Controller_Sapi_Basic{

    /**
     * 获取用户的保障状态
     *@author许晟玮
     */
    public function action_getUserGuard(){
        $uid = intval($this->request->post('user_id'));
        $service= new Service_User_Company_ComStatus();
        try {
            //$uid=84105;
            $return = $service->getCompanyStatusInfo($uid,"all");
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
    //end function



}