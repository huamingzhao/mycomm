<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 潘宗磊
 * TestUser test case.
 */
class Service_User_Person_InvestTest extends Unittest_TestCase {

    protected  $service;

    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->service=new Service_User_Person_Invest();
    }

    /**
     * Cleans up the environment after running a test.
     */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    /**
     * 招商会搜索测试
     * @author 潘宗磊
     */
    public function test_searchInvestment(){
        $search=array( 'parent_id' => 1,//一级行业 餐饮娱乐
                'project_industry_id' => '',//2级行业
                'amount' => '',//投资金额
                'investment_start'=>"",
                'investment_end'=>"");
        $result = $this->service->searchInvestment($search);
        //断言返回数组有键值page
        $this->assertArrayHasKey('page',$result);
        //断言返回数组有键值list
        $this->assertArrayHasKey('list',$result);
    }
}