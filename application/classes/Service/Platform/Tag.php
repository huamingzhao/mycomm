<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @标签系统服务
 * @author shenpengfei
 *
 */
class Service_Platform_Tag{

    private $pre;

    public function __construct(){
        //初始化配置
        $this->pre=Kohana::$config->load('basic.pre');
    }

    /**
    * 增加标签
    * @author shenpengfei
    * @param arr $tags_arr
    * @return boolean，arr
    */
    public function addTag($tags_arr){
        $sql_con="";
        if(Arr::is_array($tags_arr) && isset($tags_arr['tag'])){

            $tags=explode(',',$tags_arr['tag']);

            //如果没有传入标签类型，则默认为项目标签
            //标签类型，1为项目标签，2为个人标签，3为项目和个人标签。

            $tag_type=isset($tags_arr['tagtype'])?$tags_arr['tagtype']:1;
            $arrcount=count($tags);
            $tagModel = ORM::factory("Tag");
            foreach ($tags as $key=>$val){
            	if(!empty($val)){
            		$tagModel->tag_name = $val;
            		$tagModel->tag_type = $tag_type;
            		$tagModel->create();
            		$tagModel->clear();
            	}
            }
            $result=true;
        }else{
            $result=false;
        }
        return $result;

    }

}
