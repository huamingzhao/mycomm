<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 企业用户
 * @author 施磊
 *
 */
class Controller_Sapi_User_Personal_User extends Controller_Sapi_Basic{
    //公用的model实例化
    private $model = '';
    public function before() {
        parent::before();
        $this->model = new Service_User_Person_User();
    }

    /**
     * 获得个人用户数据
     * @author 施磊
     */
    public function action_getPersonalUserList() {
        $status = intval($this->request->post('status'));
        try {
           $return = $this->model->getPersonalUserList($status);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 获得企业用户数据
     * @author 施磊
     */
    public function action_getPersonalUserTag() {
        $return = '';
        try {
           $serviceProject = new Service_User_Company_Project();
           $return = $serviceProject->findTag();
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 添加个人用户
     * @author 施磊
     */
    public function action_addPersonalUser() {
        $param = json_decode($this->request->post('param'), TRUE);
        try {
           $return = $this->model->addPersonalUser($param);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

     /**
     * 添加个人用户标签
     * @author 施磊
     */
    public function action_addUserTag() {
        $tag = json_decode($this->request->post('tag'), TRUE);
        $user_id = intval($this->request->post('user_id'));
        try {
           $return = $this->model->addPersonCrowd($user_id, $tag);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
    /**
     * 获得单个用户信息
     * @author 施磊
     */
    public function action_getPersonalInfo() {
        $user_id = intval($this->request->post('user_id'));
        try {
           $return = $this->model->getPersonalInfo($user_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 修改个人用户信息
     * @author 施磊
     */
    public function action_editPersonalUser() {
        $user_id = intval($this->request->post('user_id'));
        $param = json_decode($this->request->post('editUserParam'), TRUE);
        if(!$user_id || !$param) $this->setApiReturn('405');
        try {
           $return = $this->model->editPersonalUser($user_id, $param);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }

    /**
     * 修改个人用户标签
     * @author 施磊
     */
    public function action_editUserTag() {
        $user_id = intval($this->request->post('user_id'));
        $param = json_decode($this->request->post('tag'), TRUE);
        if(!$user_id || !$param) $this->setApiReturn('405');
        try {
           $return = $this->model->updatePersonCrowd($user_id, $param);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }


    /**
     * 身份证验证处理后添加活跃度[供cms大后台掉用]
     * @author 钟涛
     */
    public function action_addPointsByPerson(){
        $user_id = intval($this->request->post('userid'));
        $ser1=new Service_User_Person_Points();
        $ser1->addPoints($user_id, 'per_auth_status');//身份证认证
    }
    /**
     * 获取会员手机验证的总数 bi用 (per/com)
     * @author 许晟玮
     */
    public function action_getPerUserMobileBindCount(){
        $post= $this->request->post();
        $user_type= Arr::get($post, 'type',2);
        $valid= Arr::get($post, 'valid',3);
        $begin= Arr::get($post, 'begin');
        $end= Arr::get($post, 'end');
        $uid= Arr::get($post, 'uid',array());

        //获取总的页数
        $re= Service_Sso_Client::instance()->getUserMobileBindCount( $user_type,$valid,$begin,$end,$uid );
        $this->setApiReturn('200', 'ok', $re['return']);
    }
    //end function

    /**
     * 获取会员手机验证的会员列表 bi用(per/com)
     * @author 许晟玮
     */
    public function action_getPerUserMobileBindList(){
        $post= $this->request->post();
        $now_page= Arr::get($post, 'page',1);
        $user_type= Arr::get($post, 'type',2);
        $valid= Arr::get($post, 'valid',3);
        $begin= Arr::get($post, 'begin');
        $end= Arr::get($post, 'end');
        $uid= Arr::get($post, 'uid',array());

        //获取总的页数
        $re= Service_Sso_Client::instance()->getUserMobileBindList( $now_page,$user_type,$valid,$begin,$end,$uid );

        $this->setApiReturn('200', 'ok', $re);

    }
    //end function

    /**
     * 创建authaotulogin这个cookie
     * @author许晟玮
     */
    public function action_setcookie(){
        header('P3P: CP=CAO PSA OUR');
        $post= $this->request->query();
        $stoken= Arr::get($post, 'stoken');
        $remember= Arr::get($post, 'remember');
        if( $remember ){
            $lifetime = '1209600';//14天
        }else{
            $lifetime= 0;
        }
        if( $stoken=='' ){
            $this->setApiReturn('500', 'error', '$stoken is null');
        }else{
            //delete cookie
            Cookie::delete('authautologin');

            $url = parse_url(Kohana::$config->load("site.website"));
            Cookie::$domain = ltrim($url['host'],"www.");
            $re=Service_Sso_Client::instance()->getUserInfo($stoken);

            Cookie::set('authautologin', $stoken, $lifetime,Cookie::$path);
            Cookie::set('email', $re->email, $lifetime,Cookie::$path);
            Cookie::set('user_type', $re->user_type, $lifetime,Cookie::$path);
            Cookie::set('user_id', $re->id, $lifetime,Cookie::$path);
            Cookie::set('user_name',$re->user_name,$lifetime,Cookie::$path);

            $return= $this->setApiReturn('200', 'ok', '');
        }
    }
    /**
     * 退出登陆
     * @author yamasa
     */
    public function action_clearlogout(){
        $token= Cookie::get('authautologin');
        $service = Service_Sso_Client::instance ()->logout ( $token );
    }

    /**
     * 获取18个最近登录的投资者 875用
     * @author 花文刚
     */
    public function action_getRecentlyLoginUser(){
        $user = $this->model->getRecentlyLoginUser();
        echo json_encode($user);
    }
    //end function

    /**
     * sso
    * 获取某个时间段的注册总数
    *@author 许晟玮
    */
    public function action_getUserRegNumBySso(){
        $post= $this->request->post();
        $begin= Arr::get($post,'begin');
        $end= Arr::get($post, 'end');
        $sso= Service_Sso_Client::instance();
        $result= $sso->getUserRegNumInBasic( $begin,$end );
        if( Arr::get($result, 'error')===false ){
            $num= Arr::get($result, 'return');
            $this->setApiReturn('200', 'ok', $num);
        }else{
            $this->setApiReturn('500', 'error');
        }
    }
    //end function

    /**
     * 获取时间段 内，注册的会员ID
     *@author 许晟玮
     */
    public function action_getUserIdsByDate(){
        $post= $this->request->post();
        $begin= Arr::get($post,'begin');
        $end= Arr::get($post, 'end');
        $sso= Service_Sso_Client::instance();
        $uids= $sso->getUserIdsByDate( $begin,$end );
        if( $uids!==false ){
            $this->setApiReturn('200', 'ok', $uids);
        }else{
            $this->setApiReturn('500', 'error');
        }
    }
    //end function

    /**
    * 获取用户基本信息
    *@author 许晟玮
    */
    public function action_getUserInfoById(){
        $post= $this->request->post();
        $user_id= Arr::get($post, 'user_id');
        $sso= Service_Sso_Client::instance();
        $res= $sso->getUserInfoById($user_id);
        if( $res===false ){
            $this->setApiReturn('500', 'error');
        }else{
            $this->setApiReturn('200', 'ok',$res);
        }
    }

    //end function


}