<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Controller extends Kohana_Controller {

    public function after(){
        parent::after();
    }

    private $PermissSessionKey = "adminPermission";

    public function isLogin(){
    }



    /**
     * 判断是否登录 不做跳转
     * @author 龚湧
     * @return boolean
     */

    public function isLogins(){
        $token = Cookie::get("authautologin");
        if($token){
            return Service_Sso_Client::instance()->isLogin($token);
        }
        return false;
    }


    /**
     * 取得用户基本信息
     * @author 曹怀栋
     */
    /*
    public function userInfo(){
        $this->isLogin();
        $user_id = Cookie::get("user_id");
        $user =ORM::factory('User',$user_id);
        unset($user->password);
        return $user;
    }
    */

    /**
     * @sso
     * 增加了兼容性
     * @author 龚湧
     * @return Ambigous <boolean, mixed>
     * 2013-11-4 下午2:45:08
     */
    public function userInfo($clean=false){
        $token = Cookie::get("authautologin");
        if( $token==null ){
            return false;
        }
        $user = Service_Sso_Client::instance()->getUserinfo($token);
        $user->user_id = $user->id;
        //对应关系兼容性调整
        if($clean){
            $basic = ORM::factory("User",$user->id);
            $user->basic = $basic;
            //企业用户
            if($user->user_type == 1){
                $user->user_company = $basic->user_company;
            }
            //个人用
            elseif($user->user_type == 2){
                $user->user_person = $basic->user_person;
            }
        }
        return $user;
    }

    /**
     * 只取得用户id
     * @author 曹怀栋
     */
    public function userId(){
        $this->isLogin();
        return Cookie::get("user_id");
    }

    /**
     * 只取得用户id(尽供用户登录时专用)
     * @author 曹怀栋
     */
    public function loginUserId(){
        $session = Session::instance();
        return $session->get("user_id");
    }

    /**
     * 判断是否已经登陆
     * @author 施磊
     * @param $type 结果处理 type = get 返回TRUE OR FALSE type = redirect 直接跳转
     */
    public function isAdminUserLogin($type = 'get') {
       $session = Session::instance();
       $status = $session->get("admin_user_id");
       if($type == 'redirect' && !$status) {
           self::redirect("cms/admin/login");
       }else{
           return $status ? TRUE : FALSE;
       }
    }
    /**
     * 获得用户权限组
     * @author 施磊
     */
    public function getAdminPermission() {
        $session = Session::instance();
        $permissionStatusArr = $session->get($this->PermissSessionKey);
        $adminUserInfo = $this->getAdminUserInfo();

        //判断是否有用户信息
        if(!$adminUserInfo || !$adminUserInfo['user_group_permission']) return array();
        //初始化
        $permission = array();
        if(!$permissionStatusArr) {
            $model = new Service_Cms_Permission();
            $permission = $model->getPermissionListByStatus();
            $userPermission = explode(',', $adminUserInfo['user_group_permission']);
            if($permission) {
                //这个数组存储的结果是 一个权限对应一个值  1为有 0为无
                $permissionStatusArr = array();
                foreach($permission as $val) {
//                    //超级管理员 拥有全部的权限
                    if($adminUserInfo['user_group_status'] == 2) {
                        $permissionStatusArr[$val['permission_name']] = 1;
                    }else{
                        $permissionStatusArr[$val['permission_name']] = in_array($val['permission_id'], $userPermission) ? 1 : 0;
                    }
                }
                //session做个缓存 这样不会对数据库造成负担
                $session->set($this->PermissSessionKey, $permissionStatusArr);
            }

        }
        $return = $permissionStatusArr ? $permissionStatusArr : array();
        return $return;
    }
    /**
     * 检查此用户是否有权限
     * @author 施磊
     * @return TRUE 有权限 FALSE 没权限
     */
    public function checkAdminPermission() {
        $action = $this->request->action();

        $permission = $this->getAdminPermission();
        //如果没有权限 说明是公共的 。如果用权限 但是 使用者的权限的false  则没有权限
        if(array_key_exists($action, $permission) && !$permission[$action])
                die('Without the permission!');
    }
    /**
     * 获得登陆的用户id
     * @author 施磊
     */
    public function getAdminUserId() {
        $session = Session::instance();
        return $session->get("admin_user_id");
    }
    /**
     * 写入后台操作日志
     * @author 施磊
     */
    public function addCmsLogs($message) {
        if(!empty($message)){
            $insertData = array(
                'cms_logs_time' => time(),
                'cms_logs_creat_user_id' => $this->getAdminUserId(),
                'cms_logs_creat_ip' => Request::$client_ip,
                'cms_log_message' => $message
            );
            $LogsModel = new Service_Cms_Logs();
            $LogsModel->insertLogs($insertData);
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 写入登陆日志
     * @author 施磊
     * @param int $user_id 用户id
     * @param int $user_type 用户类型
     * @param int $log_type 记录的日志类型 1为手动登陆，2为自动登陆
     */
    public function addUserLoginLog($user_id, $user_type = 1, $log_type = 1) {
            $user_id = intval($user_id);
            if(!$user_id) return FALSE;
            $insertData = array(
                'user_id' => $user_id,
                'user_type' => intval($user_type),
                'log_ip' => Request::$client_ip,
                'log_time' => time(),
                'log_type' => intval($log_type)
            );
            $LogsModel = new Service_User_UserLoginLog();
            $LogsModel->insertLogs($insertData);
            return TRUE;
    }
    /**
     * 获得用户登陆数据
     * @author 施磊
     * @param $type session 从session 取 sql 从 sql 取
     */
    public function getAdminUserInfo($type = "session") {
       if($type == 'session'){
           $session = Session::instance();
           $userInfo = $session->get("admin_user_info");
           return $userInfo;
       }
       return array();
    }
    /**
     * 此方法只是在用户到达登录页面时判断是否登录
     * @author 曹怀栋
     */
    /*
    public function loginUser(){
        $lifetime = Kohana::$config->load('auth.lifetime');
        if(Cookie::get("authautologin")){
            if(Cookie::get("authautologin") == sha1(Cookie::get("user_id").$lifetime.Cookie::get("email"))){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    */

    /**
     * @sso
     * 判断用户是否登录
     * @author 龚湧
     * @return Ambigous <Ambigous, boolean, mixed, array>|boolean
     * 2013-11-4 上午10:25:23
     */
    public function loginUser(){
        if(Cookie::get("authautologin")){
            $token = Cookie::get("authautologin");
            $flag = Service_Sso_Client::instance()->isLogin($token);
            return $flag;
        }else{
            return false;
        }
    }

    /**
     * 用户登录类型
     * @author 曹怀栋
     */
    public function userType($type,$to_url=''){
        if($to_url){
            self::redirect($to_url);
            exit();
        }
        switch ($type){
            /**case 1:
                self::redirect("member/comlead");
                break;
            case 2:
                self::redirect("member/userlead");
                break;
            case 3:
                self::redirect("member/comlead");
                break;
            default:
                self::redirect("/");
                break;
               **/

            case 1:
                self::redirect("company/member");
                break;
            case 2:
                self::redirect("person/member");
                break;
            case 3:
                self::redirect("company/member");
                break;
            default:
                self::redirect("/");
                break;
        }
    }

    /**
     * 搜索条件，判断是否可进行搜索
     * @author 龚湧
     * @param array $query 提供字符串
     * @param array $expected 必备条件之一
     * @return array 搜索标志和字符串
     */
    public function toSearch($query,$expected){
        //查询条件和必备条件交集，判断是否要进行查询操作
        $intersect = array_intersect(array_keys($query),array_keys($expected));
        $condition = array();
        foreach ($intersect as $in){
            //搜索条件为空，则丢弃该条件
            if(trim($query[$in]) ===''){
                unset($query[$in]);
            }
            $condition[$in] = array('op'=>$expected[$in],'value'=>$query[$in]);
        }
        $is_search = (bool)count($query);
        return array(
            'is_search'=>$is_search,
            'condition' =>$condition
        );
    }

    public function ajaxRst($rst,$errno=0,$err='', $return = false){
        $r = array('rst'=>$rst,'errno'=>$errno*1,'err'=>$err);
        if ($return) {
            return @json_encode($r);
        }else {
            header('Content-type: application/json');
            echo @json_encode($r);exit;
        }
    }
}
