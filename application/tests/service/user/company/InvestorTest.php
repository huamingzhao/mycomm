<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 钟涛
 * company CardUser test case.
 */
class Service_Company_InvestorTest extends Unittest_TestCase {

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
        $this->service=new Service_User_Company_Investor();
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
     * @test
     * 测试获取筛选条件记录(获取无记录情况)
     * @author 钟涛
     */
    public function test_getSearchConditionsConut(){
        $postdata= Array('per_industry' => 1,
                'per_amount' =>"1",
                'per_identity' =>"2",
                'per_join_project' => "1",
                'per_connections' => "2",
                'per_investment_style' => "1",
        );
        //用户id
        $userid = $this->user_id;
        $model=ORM::factory('SearchConditions');
        $result=$this->service->getSearchConditionsConut($model,$postdata,$userid);
        //预期结果空
        $this->assertEquals(0,$result['user_id']);
    }

    /**
     * @test
     * 测试获取筛选条件记录(获取有记录情况)
     * @author 钟涛
     */
    public function test_getSearchConditionsConut2(){
        $postdata= Array('per_industry' => 1,
                'per_amount' =>"",
                'per_identity' =>"",
                'per_join_project' => "",
                'per_connections' => "",
                'per_investment_style' => "",
        );
        //用户id
        $userid = $this->user_id;
        $model=ORM::factory('SearchConditions');
        $result=$this->service->getSearchConditionsConut($model,$postdata,$userid);
        //预期结果为当前用户id
        $this->assertEquals(36,$result['user_id']);
    }

    /**
     * @test
     * 测试获取筛选条件记录(获取无记录情况)
     * @author 钟涛
     */
    public function test_updateSearchConditions(){
        $postdata= Array('per_industry' => 1,
                'per_amount' =>"1",
                'per_identity' =>"1",
                'per_join_project' => "2",
                'per_connections' => "1",
                'per_investment_style' => "1",
        );
        //用户id
        $userid = $this->user_id;
        $model=ORM::factory('SearchConditions');
        $this->service->updateSearchConditions(12,$postdata,$userid);
        $result=$this->service->getSearchConditionsConut($model,$postdata,$userid);
        //预期结果为当前用户id
        $this->assertEquals(36,$result['user_id']);
    }

    /**
     * @test
     * 测试获取筛选条件记录(获取有记录情况)
     * @author 钟涛
     */
    public function test_updateSearchConditions2(){
        $postdata= Array('per_industry' => 1,
                'per_amount' =>"",
                'per_identity' =>"",
                'per_join_project' => "",
                'per_connections' => "",
                'per_investment_style' => "",
        );
        //用户id
        $userid = $this->user_id;
        $model=ORM::factory('SearchConditions');
        $this->service->updateSearchConditions(13,$postdata,$userid);
        $result=$this->service->getSearchConditionsConut($model,$postdata,$userid);
        //预期结果为当前用户id
        $this->assertEquals(36,$result['user_id']);
    }

    /**
     * 测试删除多个搜索记录
     * @author 钟涛
     */
    public function test_deleteConditionsByArr1(){
        $idarr=array(1248,1249,1250);
        //对 筛选记录id=1248,1249,1250 进行删除
        $this->service->deleteConditionsByArr($idarr);
        //对筛选记录id=1248,1249,1250  进行查询
        $result= ORM::factory('SearchConditions')->select('*')->where('id','in',$idarr)->find_all();
        // 断言返回结果为空数组
        $this->assertEquals(0,count($result->as_array()));
    }

    /**
     * 测试删除1个搜索记录
     * @author 钟涛
     */
    public function test_deleteConditionsByArr2(){
        $idarr=array(1252);
        //对 筛选记录id=1252 进行删除
        $this->service->deleteConditionsByArr($idarr);

        //对筛选记录id=1252  进行查询
        $result= ORM::factory('SearchConditions')->select('*')->where('id','in',$idarr)->find_all();
        // 断言返回结果为空数组
        $this->assertEquals(0,count($result->as_array()));
    }

    /**
     * 测试删除多个个搜索记录（其中id有存在的和不存在的情况）
     * @author 钟涛
     */
    public function test_deleteConditionsByArr3(){
        $idarr=array(1252,1253,1444,1555);
        //对 筛选记录id=1252,1253,1444,1555 进行删除
        $this->service->deleteConditionsByArr($idarr);

        //对筛选记录id=1252,1253,1444,1555  进行查询
        $result= ORM::factory('SearchConditions')->select('*')->where('id','in',$idarr)->find_all();
        // 断言返回结果为空数组
        $this->assertEquals(0,count($result->as_array()));
    }

    /**
     * 测试获取最新的一条筛选记录
     * @author 钟涛
     */
    public function test_getOneConditions(){
        $userid=36;//(此用用已经有搜索投资者记录)
        $result = $this->service->getOneConditions($userid);
        // 断言返回结果用户id=36
        $this->assertEquals(36,$result->user_id);
        //时间排序获取所有当前用户数据
        $alldata=ORM::factory('SearchConditions')->where('user_id','=',$userid)->order_by('update_time', 'DESC')->find_all()->as_array();
        //断言返回结果的最新数据的时间相同
        $this->assertEquals($result->update_time,$alldata[0]->update_time);
    }

    /**
     * 测试获取最新的一条筛选记录
     * @author 钟涛
     */
    public function test_getOneConditions2(){
        $userid=37;//(此用用没有有搜索投资者记录)
        $result = $this->service->getOneConditions($userid);
        // 断言返回结果为数量为空数组
        $this->assertEquals('',$result->user_id);
    }
    
    /**
     * Provides test data test_getUserSubscriptionStatus()
     * @return array
     */
    public function provider_proTestUserSubscription()
    {
        return array(
                array(TRUE, 36),//
                array(FALSE, 9999),//这货的id是不存在的
                array(FALSE, 'asdadjdkjho') //肯定不存在
        );
    }
    
    /**
     * @dataProvider provider_proTestUserSubscription
     * 测试此用户是否已经订阅
     * @author 施磊
     * @param bool $return TRUE 已订阅 FALSE 未订阅
     * @param int $param 用户id
     */
    public function test_getUserSubscriptionStatus($return, $param) {
        
        $investorService = new Service_User_Company_Investor();        
        //判断用户是否已经开通
        $funReturn = $investorService->getUserSubscriptionStatus($param);
        
        $this->assertEquals($return, $funReturn);
    }
}

