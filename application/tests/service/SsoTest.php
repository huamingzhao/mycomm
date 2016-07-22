<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 许晟玮
 * TestSso test case.
 */
class Service_SsoTest extends Unittest_TestCase {

    protected  $service;
    private $_email= "phpunit17@123.com";
    private $_mobile= "190012098770";
    /**
    * 为createUser提供数据
    * @return array
    */
    public function provider_userInfo() {
        $info = array (
                    'email' => $this->_email,
                    'user_name' => 'phpunituser',
                    'password' => '123456',
                    'user_type' => 1
        );
        return array(
                array (
                        $info
                )
        );
    }


    /**
    * 测试用户注册接口 成功状态
    * @dataProvider provider_userinfo
    */
    public function test_createUser($userinfo){
            $result= Service_Sso_Client::instance()->appRegister($userinfo,"EMAIL");

            $status= Arr::get($result,"error");

            $this->assertFalse($status);

    }
    //end function

    /**
     * 测试用户注册接口 邮件格式不对
     * @dataProvider provider_userinfo
     */
    public function test_createUserFalseA(){
        $post= array(
                    'email' => 'asdfasdfa',
                    'user_name' => 'phpunituser',
                    'password' => '123456',
                    'user_type' => 1
        );
        $result= Service_Sso_Client::instance()->appRegister($post,"EMAIL");
        $status= Arr::get($result,"code");
        if( $status=='001' ){
            $re= true;
        }else{
            $re= false;
        }
        $this->assertTrue($re);
    }
    //end function



    /**
     * 测试用户注册接口 写入数据缺失不够
     * @dataProvider provider_userinfo
     */
    public function test_createUserFalseB(){
        $post= array(
                'email' => '',
                'user_name' => '',
                'password' => '',
                'user_type' => 1
        );
        $result= Service_Sso_Client::instance()->appRegister($post,"EMAIL");
        $status= Arr::get($result,"error");
        $this->assertFalse($status);
    }
    //end function



    //end function

    /**
     * 通过邮箱获取用户信息( 获取不到就报error )
     * @author许晟玮
     */
    public function test_getUserinfoByEmail(){
        $result= Service_Sso_Client::instance()->getUserInfoByEmail( $this->_email );
        $user_id= $result->id;
        if( ceil( $user_id )>0 ){
            $re= true;
        }else{
            $re= false;
        }
        $this->assertTrue($re);
    }
    //end function

    /**
     *获取用户信息( 获取不到就报error )
     *@author许晟玮
     */
    public function test_getuserinfo(){
        //用户ID存在的时候
        $user_id= 36;
        $return= Service_Sso_Client::instance()->getUserInfoById($user_id);
        if( ceil( $return->id )>0 ){
            $re= true;
        }else{
            $re= false;
        }
        $this->assertTrue($re);


    }
    //end function



    /**
     *更新用基本信息表 修改成功的
     * @author许晟玮
     */
    public function test_updateBasicInfoById(){
        $result= Service_Sso_Client::instance()->updateBasicInfoById( '36',array('user_gender'=>'2') );
        $this->assertTrue($result);
    }
    //end function

    /**
     *更新用基本信息表 修改失败的 用户不存在的情况下
     * @author许晟玮
     */
    public function test_updateBasicInfoByIdFalse(){
        $result= Service_Sso_Client::instance()->updateBasicInfoById( '0',array('user_gender'=>'2') );
           $this->assertFalse($result);

    }
    //end function

    /**
     *更新用基本信息表 修改失败的 字段不存在的情况下
     * @author许晟玮
     */
    public function test_updateBasicInfoByIdFalseB(){
        $result= Service_Sso_Client::instance()->updateBasicInfoById( '36',array('adsaf'=>'2') );
        $this->assertFalse($result);

    }
    //end function


    /**
     * 修改手机号  修改成功的
     * @author许晟玮
     */
    public function test_updateMobileInfoById(){
        $result= Service_Sso_Client::instance()->updateMobileInfoById( '36',array( 'valid_status'=>'0' ) );
        $this->assertTrue($result);
    }

    /**
     * 修改手机号 修改失败的 用户不存在的情况下
     * @author许晟玮
     */
    public function test_updateMobileInfoByIdFalse(){
        $result= Service_Sso_Client::instance()->updateMobileInfoById( '0',array( 'valid_status'=>'0' ) );
            $this->assertFalse($result);
    }
    //end function

    /**
     * 修改手机号 修改失败的 字段不存在的情况下
     * @author许晟玮
     */
    public function test_updateMobileInfoByIdFalseB(){
        $result= Service_Sso_Client::instance()->updateMobileInfoById( '36',array( 'adsa'=>'0' ) );
        $this->assertFalse($result);
    }
    //end function

    /**
     * 更新邮件基本信息表 修改成功
     * @author许晟玮
     */
    public function test_updateEmailInfoById(){
        $result= Service_Sso_Client::instance()->updateEmailInfoById( '36',array( 'valid_status'=>'0' ) );
        $this->assertTrue($result);
    }
    //end function

    /**
     * 更新邮件基本信息表 修改失败 用户不存在的情况下
     * @author许晟玮
     */
    public function test_updateEmailInfoByIdFalse(){
        $result= Service_Sso_Client::instance()->updateEmailInfoById( '0',array( 'valid_status'=>'0' ) );
        $this->assertFalse($result);
    }
    //end function

    /**
     * 更新邮件基本信息表 修改失败 字段不存在的情况下
     * @author许晟玮
     */
    public function test_updateEmailInfoByIdFalseB(){
        $result= Service_Sso_Client::instance()->updateEmailInfoById( '36',array( 'adasdfasdf'=>'0' ) );
        $this->assertFalse($result);
    }
    //end function


    /**
     *初始化使用，不包含更新已绑定手机号码的操作 数据存在的情况下，错误提示
     *@author许晟玮
     */
    public function test_setUserMobileById(){
        $result= Service_Sso_Client::instance()->getUserInfoByEmail( $this->_email );
        $user_id= $result->id;
        $result= Service_Sso_Client::instance()->setUserMobileById( $user_id,$this->_mobile,1 );
        $this->assertTrue($result);
    }
    //end function


    /**
     *用户密码修改 修改成功
     *@author许晟玮
     */
    public function test_setNewPassword(){
        $result= Service_Sso_Client::instance()->setNewPassword( '36','123456','123456' );
        $this->assertFalse($result['error']);
    }
    //end function


    /**
     *用户密码修改 修改失败 用户不存在
     *@author许晟玮
     */
    public function test_setNewPasswordFalse(){
        $result= Service_Sso_Client::instance()->setNewPassword( '0','123456','123456' );
        if( $result['error']!='' ){
            $re= false;
        }else{
            $re= true;
        }
        $this->assertFalse($re);

    }
    //end function

    /**
     * 重置密码 成功
     * @author许晟玮
     */
    public function test_resetPassword(){
        $result= Service_Sso_Client::instance()->resetPassword( '36','123456' );
        $this->assertFalse($result['error']);
    }
    //end function

    /**
     * 重置密码 失败
     * @author许晟玮
     */
    public function test_resetPasswordFalse(){
        $result= Service_Sso_Client::instance()->resetPassword( '0','123456' );

        if( $result['error']!='' ){
            $re= false;
        }else{
            $re= true;
        }
        $this->assertFalse($re);
    }
    //end function


    /**
     *检查当前输入密码是否正确
     * @author许晟玮
     */
    public function test_isPassowrdOk(){
        $result= Service_Sso_Client::instance()->isPassowrdOk( '36','123456' );
        $this->assertTrue($result);
    }
    //end function

    /**
     *检查当前输入密码是否正确 密码输入不正确
     * @author许晟玮
     */
    public function test_isPassowrdOkFalse(){
        $result= Service_Sso_Client::instance()->isPassowrdOk( '36','123456777' );
        $this->assertFalse($result);
    }
    //end function

    /**
     * 传入手机号获取用户信息
     * @author许晟玮
     */
    public function test_getUserInfoByMobile(){

        $result= Service_Sso_Client::instance()->getUserInfoByMobile( $this->_mobile );
        if( $result===false ){
            $re= false;
        }else{
            $re= true;
        }
        $this->assertTrue($re);
    }
    //end function


    /**
     * 获取会员手机验证的会员列表 bi用(per/com)
     *@author许晟玮
     */
    public function test_getUserMobileBindList(  ){
        $result= Service_Sso_Client::instance()->getUserMobileBindList( 1,1,1,time()-86400,time() );
        if( $result===false ){
            $re= false;
        }else{
            $re= true;
        }
        $this->assertTrue($re);
    }
    //end function

    /**
     *获取会员手机验证的会员count bi用(per/com)
     * @author许晟玮
     */
    public function test_getUserMobileBindCount(  ){
        $result= Service_Sso_Client::instance()->getUserMobileBindCount( 1,1,time()-86400,time() );
        if( $result===false ){
            $re= false;
        }else{
            $re= true;
        }
        $this->assertTrue($re);

    }

    //end function

}

