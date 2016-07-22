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
class Task_InsertProjectList extends Minion_Task
{
    /**
     * Generates a help list for all tasks
 
     * @author 嵇烨
     * @return null
     */
    protected function _execute(array $params){
        #php shell php minion --task=InsertProjectList
    	$Service_Api_Basic =  new Service_Api_Basic();
//     	$arr_875_project_list  = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/postProjectOne",array("pro_id"=>9566));
//     	echo "<pre>"; print_R($arr_875_project_list);exit;
          self::_get_project_list();
    }
    
    protected function _get_project_list(){
    	//echo date("Y-m-d H:i:s",time())."n/";
		$today = strtotime("today");
		$arr_project_id = array();
		$Service_Api_Basic =  new Service_Api_Basic();
		$count = ORM::factory('Projectoutside')->where('status', 'in', array('2','3'))->where("project_type","=",1)->where("addtime",">=",$today)->where("addtime","<=",$today+24*60*60)->count_all();
		$num = 0;
     	for($i=0;$i < $count;$i++){ 
     		unset($arr_project_id);
			$arr_project_id = DB::select("outside_project_id")->from('project_outside')->where('status', 'in', array('2','3'))->where("project_type","=",1)->where("addtime",">=",$today)->where("addtime","<=",$today+24*60*60)->limit(1)->offset($i)->execute()->as_array();
			#调用875接口
			$arr_875_project_list  = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/postProjectOne",array("pro_id"=>arr::get($arr_project_id[0],"outside_project_id")));
			$bool =  self::Do_project_list($arr_875_project_list['msg'][0]);
			if($bool == true){ echo $num ++.",";
				if($num == 5){
					echo "停一下 不能在跑了,跑招商会脚本!";exit;
				}
			}
		}
    }
	#处理项目信息
	protected function  Do_project_list($arr_data){
		$service = new Service_User_Company_Project();
		$Service_Api_Basic =  new Service_Api_Basic();
		$arr_project_data = array();
		$int_project_num = 0;
		if($arr_data){
			#判断是不是875项目 是就不操作
			$int_project_num = ORM::factory("Project")->where_open()->where("project_source","=",intval(2))->or_where("project_source","=",intval(6))->where_close()->where("project_brand_name","=",arr::get($arr_data, "pro_name"))->where("project_status","=",2)->count_all();
			$OK = false;
			if($int_project_num <=0){
				$OK = TRUE;
				#项目来源
				if(arr::get($arr_data,"account_type") == 3){
					$arr_project_data ['project_source'] = 2;
					$arr_project_data ['project_vip_set'] = 2;
				}else{
					$arr_project_data ['project_source'] = 6;
				}
				#来源
				$arr_project_data ['project_real_order'] = arr::get($arr_data,"yjhtype") ? arr::get($arr_data,"yjhtype") : 1;
				$arr_project_data ['project_real_source'] = 2;
				
				#外采id
				$arr_project_data ['outside_id'] =  arr::get($arr_data,"pro_id");
				#项目名称
				$arr_project_data['project_brand_name'] = arr::get($arr_data, "pro_name","");
				#企业id
				$arr_project_data ['com_id'] = 0;
				#项目推广语'
				$arr_project_data['project_advert'] = arr::get($arr_data, "pro_define","");
				#项目logo
				$arr_project_data['project_logo'] = arr::get($arr_data, "pro_logo","");
				#项目品牌发源地
				$arr_project_data['project_brand_birthplace'] = arr::get($arr_data,"pp_address");
				#项目简介
				$arr_project_data ['project_summary'] = arr::get($arr_data,"com_about");
				#项目加盟费
				$arr_project_data ['project_joining_fee'] = arr::get($arr_data,"join_fee",'1');
				#保证金
				$arr_project_data ['project_security_deposit'] =arr::get($arr_data,"join_bail") ? arr::get($arr_data,"join_bail") : 1;
				#产品特点
				$arr_project_data ['product_features'] = arr::get($arr_data,"profit_analysis");
				#加盟条件
				$arr_project_data ['project_join_conditions'] = arr::get($arr_data,"join_area");
				#项目添加时间
				$arr_project_data ['project_addtime'] = strtotime(date("Y-m-d H:i:s"));
				#项目审核通过时间
				$arr_project_data ['project_passtime'] = $arr_project_data ['project_addtime']?$arr_project_data ['project_addtime'] : strtotime(date("Y-m-d H:i:s"));
				#项目修改时间
				$arr_project_data ['project_updatetime'] = $arr_project_data ['project_addtime'] ? $arr_project_data ['project_addtime'] : strtotime(date("Y-m-d H:i:s"));
				if(intval(arr::get($arr_data,"invest_money"))){
					$arr_project_data ['project_amount_type'] =  arr::get($arr_data,"invest_money");
				}else{
					$arr_project_data ['project_amount_type'] = 1;
				}
				
				#项目状态
				$arr_project_data ['project_status'] = 2;
				$arr_project_data ['project_pinyin'] = pinyin::getinitial($arr_project_data['project_brand_name']);
				#项目标签
	    		$tags = arr::get($arr_data,"pro_tags","");
					if($tags){
					    $arr_tags = explode(" ",$tags);
					    $str_tags = "";
					    foreach ($arr_tags as $key=>$val){
					    	if($val !=""){
					    		$str_tags .= $val.",";
					    	}
					    }
					    $arr_project_data ['project_tags'] = $str_tags.arr::get($arr_data,"tl_type");
					}else{
					    $arr_project_data ['project_tags'] = arr::get($arr_data,"tl_type");
	    			}
					#主表入库
					$int_project = self::Do_insert_project_list($arr_project_data);
					//echo date("Y-m-d H:i:s",time())."n/"; exit;
					unset($arr_project_data);
					if($int_project > 0){
						
						#项目海报入库
						$arr_project_url  = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/getMobanInfo",array("pro_id"=>arr::get($arr_data,"pro_id")));
						if(isset($arr_project_url['msg']) && $arr_project_url['msg'] && $arr_project_url['msg'][0]['use_moban_url']){
							$bool_project_haihao = self::do_insert_project_haibao(array("project_id"=>$int_project,"url"=>arr::get($arr_project_url['msg'][0], "use_moban_url")));
							if($bool_project_haihao == true){
								echo "海报添加成功"."\n";
							}else{
								echo "海报添加失败"."\n";
							}
						}
						#处理行业开始
						#根据二级行业匹配(优先)
						if(arr::get($arr_data,'pro_industry_sub')){
							$arr_project_industry = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/postIndustryOne",array("id"=>arr::get($arr_data,'pro_industry_sub',"")));
						}else{
							#走一级行业
							$arr_project_industry = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/postIndustryOne",array("id"=>arr::get($arr_data,'pro_industry',"")));
						}
						$arr_industry_data = array();
						if($arr_project_industry['msg'] && isset($arr_project_industry['msg'][0])){
							try{
								$project_industry = ORM::factory("Directory")->where("keyword","=",arr::get($arr_project_industry['msg'][0], "name"))->find()->as_array();
								if($project_industry['id'] > 0){
									#根据二级行业匹配
									$arr_industry  = ORM::factory("Industry",$project_industry['question_id'])->as_array();
									if($arr_industry['industry_id'] > 0){
										if($arr_industry['parent_id'] !=0){
											$arr_industry_data[] = $arr_industry['parent_id'];
										}
										$arr_industry_data[] = $arr_industry['industry_id'];
										#执行插入
										$bool_industry = $service->updateProjectIndustry($int_project,$arr_industry_data);
										if($bool_industry == true){
											echo "行业添加成功！"."\n";
										}else{
											echo "行业添加失败！"."\n";
										}
									}
								}
							}catch (Error $e){
								//print_R($arr_project_industry);exit;
							}
						
						}else{
							echo "没有返回行业名称！";
						}
						#处理行业结束
						
						#处理招商形式
						$str_project_model = arr::get($arr_data, "tl_type");
						if($str_project_model){
							$arr_project_model = explode(",",$str_project_model);
							foreach ($arr_project_model as $key=>$val){
								if($val == "门店加盟"){
									$arr_project_models[] = 1;
								}elseif ($val == "代理"){
									$arr_project_models [] = 2;
								}elseif ($val == "经销"){
									$arr_project_models [] = 3;
								}
							}
							#执行添加
							$bool_project_model = $service->updateProjectModel($int_project,$arr_project_models);
							if($bool_project_model == true){
								echo "招商形式添加成功！"."\n";
							}else{
								echo "招商形式添加失败！"."\n";
							}
							if(isset($arr_project_models)){unset($arr_project_models);};
							if(isset($arr_project_model)){unset($arr_project_model);};
						}else{
							echo "没有招商形式"."\n";
						}
						#处理招商结束
						#招商地区处理
						$str_project_area = arr::get($arr_data, "zhaoshang_city","");
						if($str_project_area){
							#处理数据
							$arr_project_area = explode(",",$str_project_area);
							if(!empty($arr_project_area)){
								#模糊查找招商地区
								$arr_project_area_data = array();
								foreach ($arr_project_area as $key=>$val){
									$obj_project_area_data = ORM::factory("City")->where("cit_name","like", $val."%")->find();
									if($obj_project_area_data->cit_id > 0){
										$arr_project_area_data [] = $obj_project_area_data->cit_id;
									}
								}
								unset($arr_project_area);
								#执行插入
								if($arr_project_area_data){
								$bool_project_area = self::do_insert_Project_area($int_project, $arr_project_area_data);
									if($bool_project_area == true){
										echo "招商地区插入成功"."\n";
									}else{
										echo "招商地区插入失败"."\n";
									}
									unset($arr_project_area_data);
								}
							}else{
								echo "没有招商地区"."\n";
							}
						}
								#招商地区结束
								#项目小图开始
								$small_image_url = arr::get($arr_data, "pro_pic_show","");
								if($small_image_url){
									$str_xiao_image_url = self::do_uplode_image($small_image_url);
									if($str_xiao_image_url['error'] == "" && $str_xiao_image_url['path']){
										#获取项目推广原图
										$image_name = "org_".basename($str_xiao_image_url['path']);
										$names = explode(basename($str_xiao_image_url['path']),$str_xiao_image_url['path']);
										$image_url = $names[0].$image_name;
										#入库
										$bool_image_xiao = self::Do_insert_project_image($int_project,$image_url,5);
										if($bool_image_xiao == true){
											echo "推广小图成功入库"."\n";
										}else{
											echo "推广小图入库失败"."\n";
										}
										unset($names);
									}else{
										echo "推广小图上传失败！"."\n";
									}
									if(isset($str_xiao_image_url)){unset($str_xiao_image_url);}
								}else{
									echo "没有推广小图"."\n";
								}
								#项目小图结束
								#项广告大图开始
								$big_image_url = arr::get($arr_data, "pro_bannner","");
								if($big_image_url){
								$str_big_image_url = self::do_uplode_image($big_image_url);
									if($str_big_image_url['error'] == "" && $str_big_image_url['path']){
									#获取项目推广原图
									$image_name = "org_".basename($str_big_image_url['path']);
									$names = explode(basename($str_big_image_url['path']),$str_big_image_url['path']);
									$image_url_big = $names[0].$image_name;
									#入库
									$bool_image_big = self::Do_insert_project_image($int_project,$image_url_big,4);
									if($bool_image_big == true){
										echo "推广大图成功入库"."\n";
									}else{
										echo "推广大图入库失败"."\n";
									}
										unset($names);
									}else{
										echo "推广大图上传失败！"."\n";
									}
										unset($str_big_image_url);
									}else{
										echo "没有推广大图"."\n";
									}
								#项广告大图结束
								#入库到log开始
								$arr_Projectoutside_data = array('outside_project_id'=>$int_project,
									'outside_project_name'=>arr::get($arr_data, "pro_name",""),
									'status'=>1,
									'addtime'=>time(),
									'project_type'=>2
									);
									$bool_Projectoutside = self::insert_project_outside($arr_Projectoutside_data);
									if($bool_Projectoutside == true){
										echo "已成功插入日志表"."\n";
									}else{
										echo "未能插入日志表"."\n";
									}
								#入库到log结束
					}else{
						#入库到log开始
						$arr_Projectoutside_data = array('outside_project_id'=>0,
						'outside_project_name'=>arr::get($arr_data, "pro_name",""),
						'status'=>0,
						'addtime'=>time(),
						'project_type'=>2
						);
						$bool_Projectoutside = self::insert_project_outside($arr_Projectoutside_data);
						if($bool_Projectoutside == true){
							echo "已成功插入日志表"."\n";
						}else{
							echo "未能插入日志表"."\n";
						}
						#入库到log结束
					}
			}else{
				echo arr::get($arr_data, "pro_name")."项目已经存在\n";
			}
			return $OK;
		}
	}
	#入库到项目log
	protected function insert_project_outside($arr_data){
		$model_Projectoutside = ORM::factory("Projectoutside");
		if($arr_data){
			foreach ($arr_data as $key=>$val){
				$model_Projectoutside->$key = $val;
			}
			$model_Projectoutside->save();
			$model_Projectoutside->clear();
			return true;
		}
		return false;
	}
	#项目基本信息
	protected function Do_insert_project_list($arr_data){ 
		$bool = false;
		if($arr_data){
			$model_project = ORM::factory('Project');
			#处理一下logo
			$image_url = arr::get($arr_data, "project_logo") ? arr::get($arr_data, "project_logo") : "";
			if($image_url){
				$arr_image_url = self::do_uplode_image($image_url);
				if($arr_image_url && arr::get($arr_image_url,"error") ==""){
					$arr_data['project_logo'] = common::getImgUrl(arr::get($arr_image_url,"path"));
				}
			}
			try {
				foreach ($arr_data as $key=>$val){
					$model_project->$key = $val;
				}
				$obj_project_list = $model_project->create();
				$bool = $obj_project_list->project_id;
				$model_project->clear();
				echo "项目名称为: ".$arr_data['project_brand_name']." 以成功入库"."\n" ;
			}catch (Error $e){
				echo "项目名称为: ".$arr_data['project_brand_name']." 未成功入库"."\n" ;
			}
		}
		unset($arr_data);
		return $bool ;
	}
	#项目海报
	protected function do_insert_project_haibao($arr_data){
		if($arr_data){
			$project_poster_content_model = ORM::factory("ProjectposterContent",intval(arr::get($arr_data,"project_id")));
			if($project_poster_content_model->loaded()){
				$project_poster_content_model->content = arr::get($arr_data,"url");
				$project_poster_content_model->update();
				$project_poster_content_model->clear();
				unset($arr_data);
				return true;
			}else{
				$project_poster_content_model->project_id = intval(arr::get($arr_data,"project_id"));
				$project_poster_content_model->upload_img = '';
				$project_poster_content_model->content = arr::get($arr_data,"url");
				$project_poster_content_model->save();
				$project_poster_content_model->clear();
				unset($arr_data);
				return true;
			}
		}
		return false;
	}
	#图片上传到服务器
	protected function do_uplode_image($str_image_url){
		$str_return_image_url = array();
		if($str_image_url){
			#模拟图片上传
			$files= array();
			$files['project_875_image']['tmp_name'] = $str_image_url;
			$files['project_875_image']['size']='120000';
			$files['project_875_image']['name']= date("Y-m-d").'.jpg';
			#判断图片是什么格式
			$str_type = "image/jpeg";
			if(strstr(".jpg",$str_image_url) == true){
				$str_type = "image/jpeg";
			}elseif(strstr(".png",$str_image_url) == true){
				$str_type = "image/png";
			}elseif(strstr(".gif",$str_image_url) == true){
				$str_type = "image/gif";
			}
			$files['project_875_image']['type']=$str_type;
			$files['project_875_image']['error']='0';
			if($files['project_875_image']['tmp_name'] && project::checkProLogo($files['project_875_image']['tmp_name'])) {
				//echo "处理图片{$files['project_875_image']['tmp_name']}\n";
				$size = getimagesize($files['project_875_image']['tmp_name']);
			}else{
				echo "图片不存在\n";
			}
			$w=$size[0];$h=$size[1];
			try {
				$str_return_image_url = common::uploadPic($files,'project_875_image',array(array($w,$h)));
				 
			}catch (Error $e){
	
			}
		}
		unset($files);
		return $str_return_image_url;
	}
	#项目招商地区
	protected function  do_insert_Project_area($int_project_id,$arr_data){
		$bool = false;
		$service = new Service_User_Company_Project();
		if($int_project_id && $arr_data){
			$bool = $service->updateProjectArea($int_project_id,$arr_data);
		}
		unset($arr_data);
		return $bool;
	}
	#项目图片入库
	protected function Do_insert_project_image($int_project_id,$str_image_ur,$type,$image_des = null){
		$bool = false;
		if($str_image_ur && $type){
			$project = ORM::factory('Projectcerts');
			$project->project_img = common::getImgUrl($str_image_ur);
			$project->project_type = $type;
			$project->project_imgname = trim($image_des);
			$project->project_id = $int_project_id;
			$project->project_addtime = time();
			$project->create();
			$project->clear();
			$bool = true;
		}
		return $bool;
	}
}