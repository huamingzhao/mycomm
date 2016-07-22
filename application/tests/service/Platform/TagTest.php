<?php
defined('SYSPATH') or die('No direct access allowed!');
/**
 * test case.
 */
class Service_Platform_Tag_Test extends Unittest_TestCase {
    private $tag;

    /**
     * Prepares the environment before running a test.
     */
    public function setUp() {
        parent::setUp ();
        $this->tag=new Service_Platform_Tag();
    }

    /**
     * Cleans up the environment after running a test.
     */
    public function tearDown() {
        parent::tearDown ();
    }

    /**
     * Constructs the test case.
     */
    public function __construct() {
        // TODO Auto-generated constructor
    }
    
    public function test_addTag(){
    	
    	$tags='口香糖8';
    	$tagtype=1;
    	$tags_arr=array('tag'=>$tags,'tagtype'=>$tagtype);
    	$result=$this->tag->addTag($tags_arr);
    	
    	$tags='口香糖42,口香糖322,口香糖411,口香糖544,口香糖655';
    	$tagtype=1;
    	$tags_arr=array('tag'=>$tags,'tagtype'=>$tagtype);
    	$result=$this->tag->addTag($tags_arr);
		
    	$tags_arr='口香糖11111';
    	$result=$this->tag->addTag($tags_arr);
    	$this->assertFalse($result);
    	
    	$tags='口香糖8,益达';
    	$tagtype=1;
    	$tags_arr=array('tag'=>$tags,'tagtype'=>$tagtype);
    	$result=$this->tag->addTag($tags_arr);
    	
    }
    
    public function test_denyTag(){
    	
    }
    
    public function test_getTag(){
    	
    }
    
    
    
    
}

