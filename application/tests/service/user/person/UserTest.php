<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 周进
 * TestUser test case.
 */
class Service_User_Person_UserTest extends Unittest_TestCase {

    protected  $service;

    /**
    * Prepares the environment before running a test.
    */
    public function setUp() {
        parent::setUp ();
        $this->service=new Service_User_Person_User();
    }

    /**
    * Cleans up the environment after running a test.
    */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }

    /**
     * 断言返回值等于2 和为数字类型
     * @author 曹怀栋
     * 个人名片公开度
     */
    public function test_cardOpenStutas(){
        $result = $this->service->cardOpenStutas(32,2);
        //断言只向您的意向投资行业公开(回值等于2)
        $this->assertEquals(2,$result);
        //断言返回值为数字类型
        $this->assertGreaterThanOrEqual(0,$result);
    }
    /**
     * 为updatePerson提供数据
     *
     * @author 曹怀栋
     * @return array
     */
    public function updatePersonArray() {
        return  array (
                'per_id' => 32,
                'user_id' => 583,
                'per_phone' => 13764575276,
                'per_gender' => 1,
                'per_realname' => '周润发',
                'per_adress' => '周润发家',
                'per_remark' => '投资个性说明'
        );
    }
    /**
     * 断言返回结果等于1
     * @author 曹怀栋
     * 更新个人用户基本信息
     */
    public function test_updatePerson(){
        //更新项目
        $person = $this->service->updatePerson($this->updatePersonArray());
        //断言返回结果等于1
        $this->assertEquals(1,$person);
        $this->assertTrue(true,$person);
    }

}

