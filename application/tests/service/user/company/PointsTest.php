<?php

/**
 * 企业积分测试用例
 * @author 龚湧
 *
 */
class Service_User_Company_PointsTest extends Unittest_TestCase {

    public function test_hello(){
        $this->assertGreaterThan(1, 2);
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
        $this->service = Service::factory("User_Company_Points");
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        $this->service = null;
        parent::tearDown ();
    }
}

