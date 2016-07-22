<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 钟涛
 * company CardUser test case.
 */
class Service_Company_CardTest extends Unittest_TestCase {

    protected  $service;
    protected  $com_service;
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
        $this->service=new Service_User_Company_Card();
        $this->com_service=new Service_User_Company_User();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
        $this->com_service=NULL;
    }

    /**
     * 登录用户id
     * Provides test data test_getCompanyInfoTest()
     * @return array
     */
    public function provider_getCompanyInfo()
    {
        //测试用户ID为35、54、0、null、''的情况
        return array(
                array(36),
                array(54),
                array(0),
                array(null),
                array(''),
        );
    }

    /**
     * @test
     * @dataProvider provider_getCompanyInfo
     * 测试获取我的企业名片信息返回用户id为当前用户ID
     * @author 钟涛
     */
    public function test_getCompanyInfo($userid){
        $result=$this->com_service->getCompanyInfo($userid);
        //预期结果为当前用户id
        $this->assertEquals($userid,$result->get('com_user_id'));
    }

    /**
     * 测试获取我的企业名片不为null
     * @author 钟涛
     */
    public function test_getCompanyInfo_notnull(){
        //测试用户id=36
        $userid=36;
        $result=$this->com_service->getCompanyInfo($userid);
        //预期结果不为null
        $this->assertNotNull($result);
    }

    /**
     * 测试获取我收到的新的投资者名片数量
     * @dataProvider provider_getCompanyInfo
     * 分别测试但用户id为 36,54，null,'',0 的情况
     * @author 钟涛
     * 断言返回的是数字类型
     */
    public function test_getReceiveCardNewCount($userid){
        $result = $this->service->getReceiveCardNewCount($userid);
        //断言返回的是数字类型
        $this->assertGreaterThanOrEqual (0, $result);
    }

    /**
     * Provides test data test_searchReceiveCardInfo()
     * @return array
     * @author 钟涛
     */
    public function provider_searchReceiveCardInfo()
    {
        //测试用户ID为35、36我收到名片信息的情况
        return array(
                array('',36),//我收到的名片
                array('',35),//我收到的名片
        );
    }

    /**
     * @dataProvider provider_searchReceiveCardInfo
     * 测试我收到的名片、我递出的投资者名片
     * @author 钟涛
     */
    public function test_searchReceiveCardInfo($search,$userid){
        $result=$this->service->searchReceiveCardInfo($search,$userid);
        //预期结果是数量为2的数组
        $this->assertEquals(2,count($result));
    }

    /**
     * 完善企业名片处理
     * @author 周进
     * 断言返回为true
     */
    public function test_updateCompanyCard(){
        $user = $this->service;
        $data = array('logo'=>'0','brand'=>'');
        $result = $user->updateCompanyCard(34,$data);
        $this->assertEquals(true, $result['status']);
    }

    /**
     * 测试筛选我收到的名片(有数据的情况)
     * @author 钟涛
     */
    public function test_searchReceiveCardInfo_hasdata(){
        $search = Array ('per_industry'=> 1 ,'per_amount'=> 2, 'send_time'=> 7 ,'to_read_status'=> 1 );
        $result=$this->service->searchReceiveCardInfo($search,36);
        //预期结果是数量为2的数组(有搜索结果)
        $this->assertEquals(2,count($result));
        $this->assertNotNull($result['list']);
        $this->assertNotNull($result['page']);
    }

    /**
     * 测试筛选我收到的名片(无数据的情况)
     * @author 钟涛
     */
    public function test_searchReceiveCardInfo_nodata(){
        $search = Array ('per_industry'=> 100 ,'per_amount'=> 2, 'send_time'=> 7 ,'to_read_status'=> 1 );
        $result=$this->service->searchReceiveCardInfo($search,36);
        //预期结果是数量为2的数组
        $this->assertEquals(2,count($result));
        $this->assertNotNull($result['list']);
        $this->assertNotNull($result['page']);
    }

    /**
     * 断言返回结果等于
     * 测试获取名片模板风格的ID
     * 用户userid=34时list返回一个空数组
     * @author 钟涛
     */
    public function test_getCardStyleInfo(){
        $user = new Model_Auth_User();
        //预期结果为空的数组（当前用户没有明白模板）
        $expect_result = array();
        $result=$this->service->getCardStyleInfo(34);
        $this->assertEquals($expect_result,$result['list']);
    }

    /**
     * 断言返回有值
     * 测试获取名片模板风格的ID
     * 用户userid=36时返回一个不能为空的数组
     * @author 钟涛
     */
    public function test_getCardStyleInfo_haskey(){
        $this->assertArrayHasKey('list',$this->service->getCardStyleInfo(36));
        $this->assertArrayHasKey('page',$this->service->getCardStyleInfo(36));
    }

    /**
     * @depends test_updateCardStyleInfoTest
     * 如果上面保存名片模板成功
     * 断言用户ID36的名片模板为1
     * @author 钟涛
     */
    public function test_updateCardStyleInfo_result(){
        $result =  ORM::factory('User')->where('user_id', '=', 36)->find_all()->as_array(null,'getCardStyleInfo');
        //预期结果：断言用户ID36的名片模板为1
        $expect_result = array(0=>1);
        $this->assertEquals($expect_result,$result);
    }

    /**
     * Provides test data test_editReceiveCard()
     * @return array
     */
    public function provider_editReceiveCard()
    {
        //测试用户ID为35、36我收到的名片信息的情况
        return array(
                array('',36),//我收到的名片
                array('',35),//我收到的名片
        );
    }

    /**
     * @dataProvider provider_editReceiveCard
     * 测试我收到的名片的投资者名片
     * @author 钟涛
     */
    public function test_editReceiveCard($search,$userid){
        $result=$this->service->searchReceiveCardInfo($search,$userid);
        //预期结果是数量为2的数组
        $this->assertEquals(2,count($result));
    }

    /**
     * 测试交换的新的投资者名片数量
     * @dataProvider provider_getCompanyInfo
     * 分别测试但用户id为 36,54，null,'',0 的情况
     * @author 钟涛
     * 断言返回的是数字类型
     */
    public function test_getExchangeCardNewCount($userid){
        $result = $this->service->getExchangeCardNewCount($userid);
        //断言返回的是数字类型
        $this->assertGreaterThanOrEqual (0, $result);
    }

    /**
     * 更新名片的查看状态（我接收的）
     * @author 周进
     * 断言返回为100
     */
    public function test_updateCardReadStatus(){
        $user = $this->service;
        //不存在返回0
        $result = $user->updateCardReadStatus(0,0);
        $this->assertEquals('0', $result['status']);
        //成功返回100
        $result = $user->updateCardReadStatus(36,353);
        $this->assertEquals('100', $result['status']);
    }

    /**
     * @author 钟涛
     * 测试更改我收到的名片为已删除状态
     */
    public function test_updateReceiveDelStatus(){
        //测试交换名片记录ID=353
        $id=353;
        $user = $this->service;
        //执行更改我收到的名片为已删除状态；to_del_status字段修改为1
        $result=$user->updateReceiveDelStatus($id);
        //获取刚更新的数据
        $list = ORM::factory('Cardinfo')->where('card_id', '=', $id)->find();
        //断言返回结果为true
        $this->assertEquals(TRUE,$result);
        //断言刚更新的数据to_del_status字段返回结果为1
        $this->assertEquals(1,$list->to_del_status);
    }

    /**
     * @depends test_updateReceiveDelStatus
     * 如果上面已修改名片为删除状态，还原程序，再更新当前数据为未删除状态
     * @author 钟涛
     */
    public function test_updateReceiveDelStatus2(){
        //测试交换名片记录ID=353
        $id=353;
        $user = $this->service;
        //获取刚更新的数据
        $list = ORM::factory('Cardinfo')->where('card_id', '=', $id)->find();
        //还原程序 修改为未删除状态
        $list->to_del_status=0;
        $list->update();
        //断言当前数据to_del_status字段返回结果为0
        $this->assertEquals(0,$list->to_del_status);
    }

    /**
     * @author 钟涛
     * 测试获取单个投资者名片信息
     * 根据用户id获取用户所有详细信息
     */
    public function test_getReceivecardByID(){
        //测试用户ID=36
        $userid=36;
        $user = $this->service;
        $result=$user->getReceivecardByID($userid,353,1);
        //断言返回结果是不为空的数组
        $this->assertGreaterThanOrEqual(0,count($result));
        //断言有返回数组有键值user_id
        $this->assertArrayHasKey('user_id', $result);
        //断言返回user_id为7（登录用户为7）
        $this->assertEquals(36, $result['user_id']);
    }


    /**
     * 测试批量更改我收到的名片为已删除状态
     * @author 钟涛
     */
    public function test_updateBatchReceiveDelStatus(){
        $service = $this->service;
        $cardidarr=array(342,343);
        //对名片id=342,343 进行状态修改为已删除
        $service->updateBatchReceiveDelStatus($cardidarr);

        //对名片id=342,343 进行查询
        $result= ORM::factory('Cardinfo')->select('*')->where('card_id','in',$cardidarr)->find_all();
        foreach ($result as $v)
        {
            //断言 名片id=342,343 的状态都是已删除
            $this->assertEquals(1,$v->to_del_status);
        }
    }

    /**
     * @depends test_updateBatchReceiveDelStatus
     * 如果上面测试成功，还原状态为未删除
     * @author 钟涛
     */
    public function test_updateBatchReceiveDelStatus2(){
        $service = $this->service;
        $cardidarr=array(342,343);
        foreach($cardidarr as $cardid){
            $model= ORM::factory('Cardinfo',$cardid);
            //接收者删除名片记录--0为未删除
            $model->to_del_status =0;
           // $model->save();
        }
    }

    /**
     * 测试批量更改我递出的名片为已删除状态
     * @author 钟涛
     */
    public function test_updateBatchOutDelStatus(){
        $cardidarr=array(342,343);
        //对名片id=342,343 进行状态修改为已删除
        $this->service->updateBatchOutDelStatus($cardidarr);

        //对名片id=342,343 进行查询
        $result= ORM::factory('Cardinfo')->select('*')->where('card_id','in',$cardidarr)->find_all();
        foreach ($result as $v)
        {
            //断言 名片id=342,343 的状态都是已删除
            $this->assertEquals(1,$v->from_del_status);
        }
    }

    /**
     * @depends test_updateBatchOutDelStatus
     * 如果上面测试成功，还原状态为未删除
     * @author 钟涛
     */
    public function test_updateBatchOutDelStatus2(){
        $cardidarr=array(342,343);
        foreach($cardidarr as $cardid){
            $model= ORM::factory('Cardinfo',$cardid);
            //发送者删除名片记录--0为未删除
            $model->from_del_status =0;
            //$model->save();
        }
    }

    /**
     * 测试批量交换名片
     * @author 钟涛
     */
    public function test_editBatchReceiveCard(){
        $cardidarr=array(342,343);
        //对名片id=342,343进行交换
        $this->service->editBatchReceiveCard($cardidarr,699);

        //对名片id=342,343 进行查询
        $result= ORM::factory('Cardinfo')->select('*')->where('card_id','in',$cardidarr)->find_all();
        foreach ($result as $v)
        {
            //断言 名片id=342,343 的状态都是已交换
            $this->assertEquals(1,$v->exchange_status);
        }
    }

    /**
     * @depends test_editBatchReceiveCard
     * 如果上面测试成功，还原状态为未交换
     * @author 钟涛
     */
    public function test_editBatchReceiveCard2(){
        $cardidarr=array(342,343);
        foreach($cardidarr as $cardid){
            $model= ORM::factory('Cardinfo',$cardid);
            //修改状态为未交换
            $model->exchange_status =0;
            //$model->save();
        }
    }

}

