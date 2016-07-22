<?php
defined('SYSPATH') or die('No direct access allowed!');
/**
 * 项目官网 测试用例
 * @author 钟涛
 */
class Service_Platform_Project_Test extends Unittest_TestCase {

    private $project;
    /**
     * Prepares the environment before running a test.
     */
    public function setUp() {
        parent::setUp ();
        $this->project=new Service_Platform_Project();
    }

    /**
     * Cleans up the environment after running a test.
     */
    public function tearDown() {
        parent::tearDown ();
        $this->project = NULL;
    }

    /**
     * 测试根据项目ID获取项目名称
     * @author 钟涛
     */
    public function test_getProjectInfoByID(){
        $projectid=2138;
        $result=$this->project->getProjectInfoByID($projectid);
        $this->assertEquals('钟涛测试', $result->project_brand_name);
    }

    /**
     * 测试根据项目ID获取企业基本信息
     * @author 钟涛
     */
    public function test_getCompanyByProjectID(){
        $projectid=2004;
        $result=$this->project->getCompanyByProjectID($projectid);
        //断言返回企业id为4
        $this->assertEquals(4, $result->com_id);
    }

    /**
     * 测试根据项目ID获取企业基本信息
     * @author 钟涛
     */
    public function test_getCompanyByProjectID2(){
        $projectid=20049;
        //不存在的项目id
        $result=$this->project->getCompanyByProjectID($projectid);
        //断言返回为false
        $this->assertEquals(false, $result);
    }


    /**
     * 测试收藏项目
     * @author 钟涛
     */
    public function test_addProjectWatchInfo(){
        $userid=1002;
        $projectid=2201;
        //执行收藏项目
        $this->project->addProjectWatchInfo($userid,$projectid);
        //返回结果
        $data=ORM::factory('Projectwatch');
        $result=$data->select('*')->where('watch_user_id','=',$userid)->where('watch_project_id','=',$projectid)->find();
        //断言返回用户id
        $this->assertEquals(1002, $result->watch_user_id);
        //断言返回项目id
        $this->assertEquals(2201, $result->watch_project_id);
        //断言返回状态
        $this->assertEquals(1, $result->watch_status);
    }

    /**
     * 测试取消收藏项目
     * @author 钟涛
     */
    public function test_updateProjectWatchInfo(){
        $userid=1002;
        $projectid=array(2201);
        //执行收藏项目
        $this->project->updateProjectWatchInfo($userid,$projectid);
        //返回结果
        $data=ORM::factory('Projectwatch');
        $result=$data->select('*')->where('watch_user_id','=',$userid)->where('watch_project_id','=',$projectid)->find();
        //断言返回用户id
        $this->assertEquals(1002, $result->watch_user_id);
        //断言返回项目id
        $this->assertEquals(2201, $result->watch_project_id);
        //断言返回状态
        $this->assertEquals(0, $result->watch_status);
    }

    /**
     * 测试取消收藏项目[批量取消]
     * @author 钟涛
     */
    public function test_updateProjectWatchInfo2(){
        $userid=1002;
        $projectid=array(2201,20012);
        //执行收藏项目
        $this->project->updateProjectWatchInfo($userid,$projectid);
        //返回结果
        $data=ORM::factory('Projectwatch');
        $result=$data->select('*')->where('watch_user_id','=',$userid)->where('watch_project_id','=',2201)->find();
        //断言返回用户id
        $this->assertEquals(1002, $result->watch_user_id);
        //断言返回项目id
        $this->assertEquals(2201, $result->watch_project_id);
        //断言返回状态
        $this->assertEquals(0, $result->watch_status);
    }

    /**
     * @depends test_addProjectWatchInfo
     * 如果上面已添加记录成功 则删除测试的记录
     * @author 钟涛
     */
    public function test_addProjectWatchInfo2(){
        $userid=1002;
        $projectid=2201;
        $data=ORM::factory('Projectwatch');
        $data->select('*')->where('watch_user_id','=',$userid)->where('watch_project_id','=',$projectid)->find();
        $data->delete();
    }

}

