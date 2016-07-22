<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 钟涛
 * company CardUser test case.
 */
class Service_Person_CardTest extends Unittest_TestCase {

    protected  $service;
   /**
     * @var 用户信息
     */
    private $user;

    /**
     * 测试用户id
     * @var int
     */
    private $user_id = 36;

    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->user = ORM::factory("User",$this->user_id);
        $this->service=new Service_User_Person_Card();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    /**
     * 保存个人名片模板
     * @author 周进
     */
    public function test_updateCardStyleInfo(){
        $personinfo1 = ORM::factory('User','525');
        $result = $this->service->updateCardStyleInfo(525,2);
        //断言保存名片模板返回true
        $this->assertTrue($result);
        //查询前面更新的结果，验证更新的card_style=2
        $personinfo = ORM::factory('User','525');
        $this->assertEquals(2,$personinfo->card_style);
        //还原数据,断言保存名片模板返回true
        $result1 = $this->service->updateCardStyleInfo(525,$personinfo1->card_style);
        $this->assertTrue($result1);
    }

    /**
     * 个人名片公开度
     * @author 曹怀栋
     */
    public function test_cardOpenStutas(){
        $service=new Service_User_Person_Card();
        $result = $service->cardOpenStutas(32,2);
        //断言返回结果大于0
        $this->assertGreaterThanOrEqual(0,$result);
        //断言数组返回值等于2
        $this->assertEquals(2,$result);
    }

    /**
     * 个人递出/收到名片筛选 [测试筛选条件:条件为空的情况]
     * @author 钟涛
     */
    public function test_searchReceiveCard(){
        $user_id=36;//测试当前用户id36
        $search=array();//测试条件为空的情况
        $result = $this->service->searchReceiveCard($search, $user_id);
        //断言返回数组有键值page
        $this->assertArrayHasKey('page',$result);
        //断言返回数组有键值list
        $this->assertArrayHasKey('list',$result);
        //断言返回名片数量大于0
        $this->assertGreaterThanOrEqual(0,count($result['list']));
    }

    /**
     * 个人递出/收到名片筛选 [测试筛选条件:项目属性]
     * @author 钟涛
     */
    public function test_searchReceiveCard2(){
        $user_id=36;//测试当前用户id36
        $search=array( 'parent_id' => 1,//一级行业 餐饮娱乐
                    'project_industry_id' => 8,//2级行业
                    'project_amount_type' => 1);//投资金额 5万以下
        $result = $this->service->searchReceiveCard($search, $user_id);
        //断言返回数组有键值page
        $this->assertArrayHasKey('page',$result);
        //断言返回数组有键值list
        $this->assertArrayHasKey('list',$result);
        //断言返回名片数量等于0
        $this->assertEquals(0,count($result['list']));
    }

    /**
     * 个人递出/收到名片筛选 [测试筛选条件:名片属性]
     * @author 钟涛
     */
    public function test_searchReceiveCard3(){
        $user_id=36;//测试当前用户id36
        $search=array( 'send_time' => 30,//近一个月
                'send_count' => 1,//发送名片一次
                'exchange_status' => 1,//已经交换的名片
                'to_read_status' => 1,//已读名片
                );//投资金额 5万以下
        $result = $this->service->searchReceiveCard($search, $user_id);
        //断言返回数组有键值page
        $this->assertArrayHasKey('page',$result);
        //断言返回数组有键值list
        $this->assertArrayHasKey('list',$result);
        //断言返回名片数量大于等于0
        $this->assertGreaterThanOrEqual(0,count($result['list']));
    }
}

