<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 标签
 * @author 周进
 *
 */
class Service_News_Tag{
	/**
	 * 右侧热门标签
	 * @author 周进
	 */
 	public function tagList(){
        $result = ORM::factory('Zixun_Zxtag')->where('tag_status','=',1)->order_by(DB::expr('RAND()'))->limit(12)->find_all();
        return $result;
    }

}