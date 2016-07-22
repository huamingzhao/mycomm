<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_UpdateProjectSummary extends Minion_Task
{
    /**
     * Generates a help list for all tasks
 
     * @author 嵇烨
     * @return null
     */
	//合法参数列表
	//protected $_options = array("harddel"=>1,"type");
	
    protected function _execute(array $params){
        #php shell php minion --task=UpdateProjectSummary
//     	Minion_CLI::write(
// 	    	array(
// 		    	'-----------------------------------------------------------',
// 		    	'|    --harddel = 1 必须设置原有的域名图片' ,
// 		    	'|    --type=badtemp 为清除 项目简介中的图片无法显示',
// 		    	'-----------------------------------------------------------'
// 	    		)
//     	);
//     	$harddel = Arr::get($params,"harddel");
//     	if($harddel){
//     		$todo = Minion_CLI::read("请设置原有的域名");
//     		if($todo){
//     			if(stristr($todo,"http://") == true){
//     				echo 111;
//     			}else{
//     				Minion_CLI::read("输入有误,请设置原有的域名");
//     			}
//     		}else{
//     			$todo = Minion_CLI::read("请设置原有的域名");
//     		}
//     	}
//     	exit;
    	
        self::get_project();
    }
    protected function get_project(){
    	$num = 300;
    	$count = ORM::factory("Project")->where("project_source","!=",1)->where('project_status', '=', 2)->where("time_limit", "=", 1)->count_all();
    	if($count == 0){
    		echo "项目已经全部处理完毕\n"; exit;
    	}
    	if($count < $num){
    		$num = $count;
    	}
    	#获取项目
    	for ($i=0; $i<=$count;$i++){
    		if($i > $num){
    			echo "大爷我跑不动了  休息一下\n"; break;
    		}
    		$project_data =  DB::select("project_id","project_brand_name","project_summary")->from('project')->where("project_source","!=",1)->where('project_status', '=', 2)->where("time_limit", "=", 1)->limit($num)->offset($i)->execute();
    		$bool = self::do_update($project_data[$i]);
    		if($bool == true){
    			echo "项目名:  ".$project_data[$i]['project_brand_name'] ."  被成功处理\n";
    		}
    	}
    	echo "未被处理的总数量:  ".$count. " 条\n";
    	echo "已被处理    ".$num."  条\n";
    	if($count >= 100){
    		echo "剩余  ".($count - $num)."  条未处理\n"; exit;
    	}else{
    		echo "剩余  ".$count."  条未处理\n"; exit;
    	}
    }
    
    protected  function do_update($project_data){
    	$bool = false;
    	$arr_data = array();
    	$arr_insert_data = "";
    	try {  
	    	if(arr::get($project_data,"project_summary") !=""){
	    		#替换域名
	    		$project_data['project_summary'] = str_ireplace("http://pic.yijuhua-beta.net/",URL::imgurl(""),arr::get($project_data,"project_summary"));
	    		#匹配全部图片
	    		@preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU",arr::get($project_data,"project_summary"),$matches);
	    		if(arr::get($matches, 2,"") != ""){
	    			$arr_data = array_unique(arr::get($matches, 2));
	    			if($arr_data){
	    				#循环匹配图片
	    				foreach ($arr_data as $key=>$val){
	    					$img = "";
	    					#判断图片是否纯在
	    					$bool_file_exists = @file_exists(URL::imgurl($val));
	    					if($bool_file_exists == FALSE){
	    						#在到字符串中匹配这个img url  并作替换动作
	    						if(empty($arr_insert_data)){
	    							$arr_insert_data = arr::get($project_data,"project_summary");
	    						}
	    						$img = '<img alt="" src="'.$val.'" />';
	    						$arr_insert_data = str_ireplace($img," ",$arr_insert_data);
	    					}
	    				}
	    				$bool = true;
	    			}
	    		}
	    	}
	    	#更新数据库
	    	$project = ORM::factory ( "Project", arr::get($project_data, "project_id"));
	    	if($project->loaded()){
	    		if($arr_insert_data){
	    			$project->project_summary = $arr_insert_data;
	    		}
		    	$project->time_limit = 2;
		    	$project->update();
		    	$project->clear();
	    	}
    	}catch (ErrorException $e){
    		print_r($e);exit;
    	}
    		return $bool;
    }
}