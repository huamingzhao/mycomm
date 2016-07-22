<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 企业用户
 * @author 施磊
 *
 */
class Controller_Sapi_User_Company_User extends Controller_Sapi_Basic{
    //公用的model实例化
    private $model = '';
    public function before() {
        parent::before();
        $this->model = new Service_User_Company_User();
    }
    /**
     * 获得企业用户数据
     * @author 施磊
     */
    public function action_getCompanyUserList() {
        $status = intval($this->request->post('status'));
        try {
           $return = $this->model->getCompanyUserList($status);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
    /**
     * 修改企业用户属性
     * @author 施磊
     */
    public function action_editUser() {
        $id = intval($this->request->post('id'));
        $param = json_decode($this->request->post('param'), TRUE);
        try {
           $return = $this->model->editUser($id, $param);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
    /**
     * 重置密码
     * @author 施磊
     */
    public function action_resetPassWord() {
        $id = intval($this->request->post('id'));
        try {
           $return = $this->model->resetPassWord($id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 新增企业用户
     * @author 施磊
     */
    public function action_addCompanyUser() {
        $param = json_decode($this->request->post('param'), TRUE);
        if(!$param || !Arr::get($param, 'email') || !Arr::get($param, 'password')) $this->setApiReturn('405');
        try {
           $return['user_id'] = $this->model->addCompanyUser($param);
        } catch(Kohana_Exception $e){

            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 新增企业信息
     * @author 施磊
     */
    public function action_addCompanyInfo() {
        $param = json_decode($this->request->post('param'), TRUE);
        if(!$param || !Arr::get($param, 'com_user_id') || !Arr::get($param, 'com_name')) $this->setApiReturn('405');
        try {
           $return['com_id'] = $this->model->addCompanyInfo($param);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

     /**
     * 新增企业认证图片信息
     * @author 施磊
     */
    public function action_addCompanyAuthImg() {
        $param = json_decode($this->request->post('arr'), TRUE);
        if(!$param) $this->setApiReturn('405');
        try {
           $return = $this->model->addCompanyAuthImg($param);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 检查用户邮箱是非被注册过
     * @author 施磊
     * @param string $email
     */
    public function action_checkUserEmailOrId() {
        $param = json_decode($this->request->post('param'), TRUE);
        if(!$param || !Arr::get($param, 'email')) $this->setApiReturn('405');
        $email = Arr::get($param, 'email');
        $user_id = Arr::get($param, 'user_id');
        try {
           $return = $this->model->checkUserEmailOrId($email, $user_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 检查企业名是非被注册过
     * @author 施磊
     * @param string $com_name
     */
    public function action_checkComNameOrId() {
        $param = json_decode($this->request->post('param'), TRUE);
        if(!$param || !Arr::get($param, 'com_name')) $this->setApiReturn('405');
        $com_name = Arr::get($param, 'com_name');
        $com_id = Arr::get($param, 'com_id');
        try {
           $return = $this->model->checkComNameOrId($com_name, $com_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
    /**
     * 获得企业用户和企业基本信息
     * @author 施磊
     */
    public function action_getCompanyAndUserBasic() {
        $user_id = intval($this->request->post('user_id'));
        if(!$user_id) $this->setApiReturn('405');
        try {
           $return = $this->model->getCompanyAndUserBasic($user_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 修改企业用户信息
     * @author 施磊
     */
    public function action_editCompanyUser() {
        $user_id = intval($this->request->post('user_id'));
        $userParam = json_decode($this->request->post('userParam'), TRUE);
        if(!$user_id || !$userParam) $this->setApiReturn('405');
        try {
           $return = $this->model->editCompanyUserByApi($user_id, $userParam);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
    /**
     * 修改企业信息
     * @author 施磊
     */
    public function action_editCompanyInfo() {
        $com_id = intval($this->request->post('com_id'));
        $arreditCompany = json_decode($this->request->post('arreditCompany'), TRUE);
        if(!$com_id || !$arreditCompany) $this->setApiReturn('405');
        try {
           $return = $this->model->editCompanyInfoByApi($com_id, $arreditCompany);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /*
     * 获得用户最后一次充值信息
     * @author 施磊
     */
    public function action_getAccountLastLogById() {
        $user_id = intval($this->request->post('user_id'));
        if(!$user_id) $this->setApiReturn('405');
        try {
           $return = $this->model->getAccountLastLogById($user_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
}