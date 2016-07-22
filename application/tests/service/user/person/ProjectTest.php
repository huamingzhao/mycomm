<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 钟涛
 * company CardUser test case.
 */
class Service_Person_ProjectTest extends Unittest_TestCase {

    protected  $service;

    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->service=new Service_User_Person_Project();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    /**
     * 测试获取我收藏的项目列表
     * @author 钟涛
     */
    public function test_getWatchProjecList(){
        //测试用id=644
        $userid=644;
        $result = $this->service->getWatchProjecList($userid);
        //断言返回的是不为空数组
        $this->assertGreaterThanOrEqual(0,count($result));
        //断言返回结果等于644
        $this->assertEquals($userid,$result[0]['watch_user_id']);
    }

    /**
     * 测试获取我收藏的项目列表
     * @author 钟涛
     */
    public function test_getWatchProjecList2(){
        //测试用id=1
        $userid=1;
        $result = $this->service->getWatchProjecList($userid);
        //断言返回的是空数组
        $this->assertEquals(0,count($result));
    }

}

