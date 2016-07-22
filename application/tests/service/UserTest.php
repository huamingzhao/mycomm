<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 龚湧
 * TestUser test case.
 */
class Service_UserTest extends Unittest_TestCase {

    protected  $service;

    /**
    * 为createUser提供数据
    *
    * @author 龚湧
    * @return array
    */
    public function provider_userInfo() {
        $info = array (
                'email' => 'gongsfsfsddyongjosfj@gmail.com',
                'user_name' => 'gongyongsdfs',
                'password' => 'gongyong',
                'confirm' => 'gongyong',
                'user_type' => 1,
                'reg_time' => time ()
        );
        return array(
                array (
                        $info
                )
        );
    }

    /**
    * @test
    * @dataProvider provider_userinfo
    */
    public function test_createUser($userinfo){
        $service = New Service_User();
        $user = $service->createUser($userinfo);
        $this->assertGreaterThan(0,$user->user_id);
        //删除增加用户
        $user->delete();
    }

    /**
     * @var 用户信息
     */
    private $user;

    /**
     * 测试用户id
     * @var int
     */
    private $user_id = 54;

    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->user = ORM::factory("User",$this->user_id);
        $this->service=new Service_User();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    /**
     * 验证已经发送的邮件url确认
     * @author 周进
     * 断言参数不对返回为false
     */
    public function test_editValidEmail(){
        $user = $this->service;
        //参数不正确返回false
        $result = $user->editValidEmail(array('key'=>'34O23434','code'=>'5f75ab0e20e71f72c553f53e1f5ba189d966be90'));
        $this->assertEquals(false, $result['status']);
        //参数正确返回true
        $result = $user->editValidEmail(array('key'=>'376O47735','code'=>'3d7af5c58669b711bad975443c5dc6af'));
        $this->assertEquals(false, $result['status']);
    }

    /**
     * 断言返回结果等于1
     * @author 曹怀栋
     * 测试用户登录邮件和密码的格式正确性
     */
    public function test_loginCheck(){
        $user = new Service_User();
        $array = array('email'=>'272784236@qq.com','password'=>'5201314');
        $result = $user->loginCheck($array);
        //断言返回结果等于1
        $this->assertEquals(1,$result);
    }

    /**
     * 断言返回结果等于1
     * @author 曹怀栋
     * 测试更新用户信息的正确性
     */
    public function test_updateUser(){
        $user = new Service_User();
        $array = array('user_id'=>'1','user_name'=>'5201314');
        $result = $user->updateUser($array);
        //断言返回结果等于1
        $this->assertEquals(1,$result);
    }

    /**
     * 断言返回结果等于1
     * @author 曹怀栋
     * 测试此邮箱是否存在
     */
    public function test_forgetPasswordEmail(){
        $user = new Service_User();
        $email='272784236@qq.com';
        $result = $user->forgetPasswordEmail($email);
        //断言返回结果等于1
        $this->assertEquals(1,$result);
    }

    /**
     * 断言返回结果等于1
     * @author 曹怀栋
     * 邮件找回密码中验证两次密码是否正确，并修改密码
     */
    public function test_passwordSuccess(){
        $user = new Service_User();
        $array = array('newpassword'=>'5201314','confirm'=>'5201314','email'=>'272784236@qq.com');
        $result = $user->passwordSuccess($array);
        //断言返回结果等于1
        $this->assertEquals(1,$result);
    }

}

