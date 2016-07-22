<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 周进
 * CardUser test case.
 */
class Service_CardTest extends Unittest_TestCase {

    protected  $service;
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
        $this->service=new Service_Card();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    /**
     * 测试企业用户取消收藏名片
     * @author 周进
     */
    public function test_updateFavorite(){
        //测试用户ID=5,单个更新，数据返回false
        $user = new Service_Card();
        $result = $user->updateFavorite(5,1,1);
        $this->assertFalse($result['status']);
        $favorite = ORM::factory('Favorite');
        $data = $favorite->where('favorite_id', '=', 1)->where('user_id','=',34)->find()->as_array();
        if ($data['favorite_id']&&$data['favorite_status']==0){
            //测试用户ID=1,单个更新，先查数据，然后更新若返回TRUE；测试完还原数据
            $result1 = $user->updateFavorite(34,1,1);
            $this->assertTrue($result1['status']);
            $result2 = $user->addFavorite(34, 1, 1);
            $this->assertTrue($result2['status']);
        }
    }

    /**
     * 测试企业用户添加收藏名片
     * @author 周进
     */
    public function test_addFavorite(){
        $result = array('status'=>FALSE,'success'=>0,'error'=>0);
        //测试用户ID=34,单个更新，数据返回TRUE
        $user = new Service_Card();
        $result = $user->addFavorite(34,1,1);
        $this->assertTrue($result['status']);
        $this->assertEquals(1,$result['success']);
        //第二个参数不传1或则2时返回FALSE
        $result1 = $user->updateFavorite(34,0,0);
        $this->assertFalse($result1['status']);
    }

    /**
     * Provides test data for test_checkComPhone()
     * 提供数据的方法
     * @author 施磊
     * @return array
      */
    public function provider_checkComPhone() {
        return array(
            array('021-12345678+123', '021-12345678-123'),
            array('021-12345678+123', '021-12345678-123'),
            array('021-12345678', '021-12345678'),
        );
    }

    /**
     * 测试公司名片显示方法封装
     * @author  施磊
     * $comPhone 传入的电话号码 $expected 传出的电话号码
     * @dataProvider provider_checkComPhone
     */
    public function test_checkComPhone($comPhone, $expected) {
        $serviceCode = new Service_Card;
        $result = $serviceCode->checkComPhone($comPhone);
        $this->assertSame($expected, $result);
    }

    /**
     * 测试更新当天名片发送次数总数统计数量
     * 测试用户已经发送过记录(更新表数据)
     * @author  钟涛
     */
    public function test_updateSendCardCountLog1() {
        $userid=36;//测试用户id36 （此用户已经发送记录）
        $serviceCode = new Service_Card;
        //获取名片发送前 发送次数
        $result1=$serviceCode->getSendCardCountInfo($userid);
        //发送名片
        $serviceCode->updateSendCardCountLog($userid);
        //获取发送名片后 发送次数
        $result2=$serviceCode->getSendCardCountInfo($userid);
        if(date("Ymd")>$result1['last_sent_time'])//过了当天 即发送次数归1[当天又可以发送30次]
        {
            //断言发送名片后次数 = 1
            $this->assertEquals(1, $result2['day_send_count']);
        }
        else{
            //断言发送名片后次数 = 断言发送名片后次数+1
            $this->assertEquals($result1['day_send_count'] + 1, $result2['day_send_count']);
        }
    }

    /**
     * 测试更新当天名片发送次数总数统计数量
     * 测试用户没有发送过记录(添加表数据)
     * @author  钟涛
     */
    public function test_updateSendCardCountLog2() {
        $userid=469465454;//测试用户id469465454 （此用户未发送过名片）
        $serviceCode = new Service_Card;
        $serviceCode->updateSendCardCountLog($userid);
        //获取发送名片后 发送次数
        $result=$serviceCode->getSendCardCountInfo($userid);
        //断言发送名片次数为1
        $this->assertEquals(1, $result['day_send_count']);
    }

    /**
     * @depends test_updateSendCardCountLog2
     * 如果上面已添加记录成功 则删除测试的记录
     * @author 钟涛
     */
    public function test_updateSendCardCountLog3(){
        $userid=469465454;//测试用户id469465454
        $data=ORM::factory('Cardsendcoutlog');
        $result = $data->where("user_id", "=",$userid)->find();
        if(!empty($result->user_id)){
            $result->delete();
        }
    }
}

