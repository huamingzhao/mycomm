<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 周进
 * 账户服务订单相关
 */
class Service_AccountTest extends Unittest_TestCase {

    protected  $service;
   /**
     * @var 用户信息
     */
    private $user;

    /**
     * 测试用户id
     * @var int
     */
    private $user_id = 34;

    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->user = ORM::factory("User",$this->user_id);
        $this->service=new Service_Account();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    public function provider_getTruePost(){
        return array('order_account'=>'0.00',
                'order_real_account'=>'0.00',
                'order_bank_name'=>'工商银行',
                'order_realname'=>'测试姓名',
                'order_line_time'=>time(),
        );
    }

    public function provider_getFalsePost(){
        return array('order_account'=>'0.00',
                'order_real_account'=>'0.00',
                'order_line_time'=>time(),
        );
    }

    /**
     * 检测account账户金额表中是否已有该用户的信息
     * @author 周进
     */
    public function test_checkAccountUser(){
        //测试用户ID=34,断言返回为true
        $result = $this->service->checkAccountUser($this->user_id);
        $this->assertTrue($result);
        //查看实际数据验证是否真为真
        $model = Model::factory('Account');
        $num = $model->get_num($this->user_id);
        //断言存在一条对应的数据数据
        $this->assertEquals("1",$num);
    }

    /**
     * 添加线下银行汇款数据
     * @author 周进
     */
    public function test_editOutLineRecharge(){
        //添加测试数据，断言返回为FALSE
        $result = $this->service->editOutLineRecharge($this->user_id,$this->provider_getFalsePost());
        $this->assertFalse($result);
        //添加测试数据，断言返回数据是个大于0的数字，清空产生的测试数据
        $result = $this->service->editOutLineRecharge($this->user_id,$this->provider_getTruePost());
        $this->assertGreaterThan(0,$result);
        if ($result>0){
            $model = ORM::factory('Accountorder',$result);
            $model->delete();
        }
    }
    /**
     * 检测account账户操作金额日志表
     * @author 周进
     */
    public function test_getAccountLog(){
        //测试用户ID=34,断言返回为true
        $result = $this->service->getAccountLog($this->user_id);
        $this->assertNotEquals("0",$result);
        //测试用户ID=0,断言返回为0
        $result = $this->service->getAccountLog(0);
        $this->assertEquals("",$result['addaccount']);
    }

    /**
     * 购买服务类
     * @author 周进
     */
    public function test_buyService(){
        //数据不正确时断言为假
        $post = array('service_id'=>'000');
        $result = $this->service->buyService($post,$this->user_id);
        $this->assertFalse($result);
        /*数据正常时返回buy_id
                    避免用户余额不足，先给用户充值700 用于测试用例
                    测试完删除相应的几张表生成的数据
        */
        $this->service->checkAccountUser($this->user_id,'700',1);
        $post = array('service_id'=>'1_2_');
        $result = $this->service->buyService($post,$this->user_id);
        //断言购买成功返回的是ID
        $this->assertGreaterThan(0,$result);
        //测试完成删除相应数据
        $model = ORM::factory('Buyservice');
        $buysource = $model->where('buy_id', '=', $result)->find();
        $order = ORM::factory('Accountorder',$buysource->order_id);
        $order->delete();
        $model->buy_id = $buysource->buy_id;
        $model->delete();
        $accountlog = ORM::factory('Accountlog');
        $result = $accountlog->where('account_type', '=', '2')->where('account_type_id','=',$buysource->buy_id)->find();
        ORM::factory('Accountlog',$result->account_log_id)->delete();
    }
}

