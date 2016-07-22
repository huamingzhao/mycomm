<?php

/**
 * 企业积分测试用例
 * @author 龚湧
 *
 */
class Service_User_PointsTest extends Unittest_TestCase {

    /**
    * 空的测试用例有警告
    * @author 龚湧
    */
    public function test_NotEmpty(){
        $this->assertEquals(1, 1);
    }


    /**
     * 企业用户中心测试service
     */
    private $service;


    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->service = Service::factory("User_Points");
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        $this->service = null;
        parent::tearDown ();
    }
}

