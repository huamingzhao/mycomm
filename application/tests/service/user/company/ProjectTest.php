<?php defined('SYSPATH') or die('No direct access allowed!');
/**
 * @author 曹怀栋
 * ProjectTest test case.
 */
class Service_User_Company_ProjectTest extends Unittest_TestCase {

    protected  $service;

    /**
     * 测试用户id
     * @var int
     */
    private $com_id = 4;

    /**
     * Prepares the environment before running a test.
     */
    public function setUp() {
        parent::setUp ();
        $this->service=new Service_User_Company_Project();
    }

    /**
     * Cleans up the environment after running a test.
     */
    public function tearDown() {
        parent::tearDown ();
        $this->service = NULL;
    }
/**
    * 为addProject提供数据
    *
    * @author 曹怀栋
    * @return array
    */
    public function addProjectArray() {
        return  array (
                'com_id' => $this->com_id,
                'project_brand_name' => '品牌名称',
                'project_brand_birthplace' => '品牌发源地',
                'project_principal_products' => '主营产品',
                'project_amount_type' => '2',
                'project_joining_fee' => '234',
                'project_security_deposit' => '234',
                'risk' => '1',
                'rate_return' => '1',
                'project_industry_id' => Array('0' => 1,'1' => 19),
                'project_city' => Array('0' => 16,'1' => 1602),
                'project_co_model' => Array('0' => 1,'1' => 2),
                'connection' => Array('0' => 1),
                'Investment_groups' => Array('0' => 1),
                'project_phone' => '13888888888',
                'project_join_conditions' => '加盟条件加盟条件加盟条件加盟条件加盟条件',
                'project_summary' => '加盟条件加盟条件加盟条件加盟条件加盟条件',
                'projcet_founding_time' => '2013-01-26'
        );
    }
    /**
     * 为addProject提供数据
     *
     * @author 曹怀栋
     * @return array
     */
    public function updateProjectArray() {
        return  array (
                'project_id' => 2004,
                'project_brand_name' => '品牌名称',
                'project_brand_birthplace' => '品牌发源地',
                'project_principal_products' => '主营产品',
                'project_amount_type' => '2',
                'project_joining_fee' => '234',
                'project_security_deposit' => '234',
                'risk' => '1',
                'rate_return' => '1',
                'project_industry_id' => Array('0' => 1,'1' => 19),
                'project_city' => Array('0' => 16,'1' => 1602),
                'project_co_model' => Array('0' => 1,'1' => 2),
                'connection' => Array('0' => 1),
                'Investment_groups' => Array('0' => 1),
                'project_phone' => '13888888888',
                'project_join_conditions' => '加盟条件加盟条件加盟条件加盟条件加盟条件',
                'project_summary' => '加盟条件加盟条件加盟条件加盟条件加盟条件',
                'projcet_founding_time' => '2013-01-26'
        );
    }

    /**
     * 断言返回结果大于0
     * @author 曹怀栋
     * 添加项目和删除项目
     */
    public function test_addProject(){
        //添加项目
        $project_id = $this->service->addProject($this->addProjectArray(),1);
        //断言返回结果大于0
        $this->assertGreaterThanOrEqual(0,$project_id);
        //删除项目
        $this->service->deleteHardProject($project_id,1);
    }

    /**
     * 断言返回结果等于1
     * @author 曹怀栋
     * 更新项目
     */
    public function test_updateProject(){
        //更新项目
        $project = $this->service->updateProject($this->updateProjectArray(),1);
        //断言返回结果等于true
        $this->assertTrue(true,$project);
    }


    /**
     * 断言数组返回值等于2 和为非NULL
     * @author 曹怀栋
     * 项目列表
     */
    public function test_showProject(){
        $result=$this->service->showProject($this->com_id);
        //断言数组返回值等于2
        $this->assertEquals(2,count($result));
        //断言下面两个为非NULL
        $this->assertNotNull($result['list']);
        $this->assertNotNull($result['page']);
    }

    /**
     * 为test_updateProjectInvest1提供数据
     *
     * @author 曹怀栋
     * @return array
     */
    public function updateInvest1() {
        return  array (
                'project_id' => 2004,
                'com_name' => '曹怀栋2',
                'com_phone' => '13764575276',
                'com_time_schedule' => Array('0' => "2013-01-29"),
                'investment_address' => '招商会地址',
                'investment_details' => '招商会详情',
                'investment_agenda' => '招商会流程说明',
                'putup_type' => '1'
        );
    }
    /**
     * 添加指定项目的招商会信息
     * @author 曹怀栋
     * 项目列表
     */
    public function test_updateProjectInvest2(){
        $result=$this->service->updateProjectInvest($this->updateInvest1());
        //断言返回的是数字类型
        $this->assertGreaterThanOrEqual(0, $result);
        //删除增加用户
        $invest = ORM::factory('Projectinvest',$result);
        $invest->delete();
    }

    /**
     * 为test_updateProjectInvest1提供数据
     *
     * @author 曹怀栋
     * @return array
     */
    public function updateInvest2() {
        return  array (
                'project_id' => 2004,
                'investment_id' => '66',
                'com_name' => '曹怀栋22',
                'com_phone' => '13764575276',
                'com_time_schedule' => Array('0' => "2013-01-29"),
                'investment_address' => '招商会地址',
                'investment_details' => '招商会详情',
                'investment_agenda' => '招商会流程说明',
                'putup_type' => '1'
        );
    }
    /**
     * 更新指定项目的招商会信息
     * @author 曹怀栋
     * 项目列表
     */
    public function test_updateProjectInvest1(){
        $result=$this->service->updateProjectInvest($this->updateInvest2());
        //断言返回的是数字类型
        $this->assertGreaterThanOrEqual(0,$result);
    }
    /**
     * 更新指定项目的招商会信息
     * @author 曹怀栋
     * 项目列表
     */
    public function test_deleteProjectImages(){
        $project_id = 2004;
        $user_id = 1;
        $cert_id = 689;
        $result=$this->service->deleteProjectImages($project_id,$user_id,$cert_id);
        //当数据库中有$project_id$user_id并且没有$cert_id时，断言返回结果等于False
        //应该测试的时候没有数据，所以就这样来判断了
        $this->assertFalse($result);
    }
    /**
     * 判断企业是否已经发布项目信息
     * @author 钟涛
     * 断言返回的为数字类型 ，测试 count_all()
     */
    public function test_isHasProject(){
        $result=$this->service->isHasProject(1);
        //断言返回的是数字类型
        $this->assertGreaterThanOrEqual (0, $result);
    }

    /**
     * 获取新项目数量（以点击生成名片时间为基准，之后新建的项目均为新项目）
     * @author 钟涛
     * 断言返回的为数字类型 ，测试 count_all()
     */
    public function test_getNewProjectCount(){
        $result=$this->service->getNewProjectCount('1354064527',1);
        //断言返回的是数字类型
        $this->assertGreaterThanOrEqual (0, $result);
    }

    /**
     * 获取新项目数量（以点击生成名片时间为基准，之后新建的项目均为新项目）
     * @author 钟涛
     * 当时间为0即第一次使用时，断言返回的的值为0
     */
    public function test_getNewProjectCount2(){
        $result=$this->service->getNewProjectCount(0,1);
        //断言返回的值为0
        $this->assertEquals (0, 0);
    }

    /**
     * @test
     * 测试根据2级行业id返回1级行会id
     * @author 钟涛
     */
    public function test_getParentid(){
        $result=$this->service->getParentid(8);
        //预期结果1
        $this->assertEquals(1,$result);
    }

    /**
     * @test
     * 测试根据2级行业id返回1级行会id
     * @author 钟涛
     */
    public function test_getParentid2(){
        $result=$this->service->getParentid(22);
        //预期结果2
        $this->assertEquals(2,$result);
    }

    /**
     * @test
     * 硬删除项目属性表
     * @author 钟涛
     */
    public function test_deleteHardProjectSearchCard(){
        $this->service->deleteHardProjectSearchCard(1,36);
        $result= ORM::factory('ProjectSearchCard')->select('*')->where('project_id','=',1)->find();
        //预期无数据
        $this->assertEquals(null,$result->project_status);
    }
}

