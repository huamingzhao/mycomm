<?php

/**
 * 企业项目海报测试用例
 * @author 龚湧
 *
 */
class Service_User_Company_PosterTest extends Unittest_TestCase {

    /**
    * 是否存在海报
    * @author 龚湧
    */
    public function test_isHasPoster(){
        $this->assertGreaterThan(1, 2);
    }

    /**
     * 企业海报service
     */
    private $service;


    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->service = Service::factory("User_Company_Poster");
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        $this->service = null;
        parent::tearDown ();
    }
}

