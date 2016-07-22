<?php
defined('SYSPATH') or die('No direct access allowed!');
/**
 * test case.
 */
class Service_Platform_Search_Test extends Unittest_TestCase {
    private $search;

    /**
     * Prepares the environment before running a test.
     */
    public function setUp() {
        parent::setUp ();
        $this->search=new Service_Platform_Search();

        // TODO Auto-generated Service_Platform_Search_Test::setUp()
    }

    /**
     * Cleans up the environment after running a test.
     */
    public function tearDown() {
        // TODO Auto-generated Service_Platform_Search_Test::tearDown()
        parent::tearDown ();
    }

    /**
     * Constructs the test case.
     */
    public function __construct() {
        // TODO Auto-generated constructor
    }

    /**
     * 一句话搜索
     * @author 沈鹏飞
     */
    public function test_getWordSearch(){
        $word='我在上海想找一个10万的酒水项目';
        $result=$this->search->getWordSearch($word);
        $this->assertEquals(3, count($result));

        $word='我在上海想找一个8千的酒水项目';
        $result=$this->search->getWordSearch($word);
        $this->assertEquals(3, count($result));

        $word='北京   100万    服装';
        $result=$this->search->getWordSearch($word);
        $this->assertEquals(3, count($result));

        $word='我在上海想找一个10万元的酒水项目';
        $result=$this->search->getWordSearch($word);
        $this->assertEquals(3, count($result));

        $word='我在上海想找一个100000的酒水项目';
        $result=$this->search->getWordSearch($word);
        $this->assertEquals(3, count($result));
    }

    /**
     * 精准匹配中(添加或更新指定类型)
     * @author 曹怀栋
     */
    public function test_updateAccurateMatching(){
        $user_id =1;
        $q_id=6;
        $qa_id=2;
        $result=$this->search->updateAccurateMatching($user_id,$q_id,$qa_id);
        $this->assertTrue($result);
        $this->assertEquals(1,$result);
    }

    /**
     * 精准匹配中添加或更新指定类型(当参数不正确时)
     * @author 曹怀栋
     */
    public function test_updateAccurateMatching2(){
        $user_id =1;
        $q_id=6;
        $qa_id="wer";
        $result=$this->search->updateAccurateMatching($user_id,$q_id,$qa_id);
        $this->assertFalse($result);
        $this->assertEquals(0,$result);
    }

    /**
     * 精准匹配中添加或更新指定类型（更新后看看指定值是否要更新的值相同）
     * @author 曹怀栋
     */
    public function test_updateAccurateMatching3(){
        $user_id =1;
        $q_id=6;
        $qa_id=2;
        $this->search->updateAccurateMatching($user_id,$q_id,$qa_id);
        $searchconfig = ORM::factory('Searchconfig')->where('user_id','=',$user_id)->where('question_id','=',$q_id)->find();
        //断言值是否更新成功
        $this->assertEquals($qa_id,$searchconfig->question_answer_id);
    }

    /**
     * 精准匹配中删除指定类型
     * @author 曹怀栋
     */
    public function test_deleteAccurateMatching(){
        $user_id =1;
        $question_id=6;
        $result=$this->search->deleteAccurateMatching($user_id,$question_id);
        $this->assertTrue($result);
        $this->assertEquals(1,$result);
    }
}

