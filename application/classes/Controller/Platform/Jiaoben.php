<?php

defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

/**
 * 项目向导
 *
 * @author 施磊
 *
 */
class Controller_Platform_Jiaoben extends Controller_Platform_Template {
	
	/**
	* 数据导出
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-9
	* @return int/bool/object/array
	*/
	
	public function action_Daochu(){	
		$count = DB::select("user_company.com_id","user_company.com_user_id","user_company.com_name","user_company.com_contact")
							->from("project")
							->join("user_company")
							->on('project.com_id','=','user_company.com_id')
							->where("project.project_real_source","=",1)
							->group_by("project.com_id")
							->execute();
		#分页跑
		//echo count($count);exit;
		$page = Pagination::factory ( array (
		'total_items' => count($count),
		'items_per_page' =>200
		) );
		#获取项目信息
		$projectList = DB::select("user_company.com_id","user_company.com_user_id","user_company.com_name","user_company.com_contact")
							->from("project")
							->join("user_company")
							->on('project.com_id','=','user_company.com_id')
							->where("project.project_real_source","=",1)
							->group_by("project.com_id")
							->limit ( $page->items_per_page )
							->offset ( $page->offset )
							->execute();
		$arr_data = array();
		if(count($projectList) > 0){
			foreach ($projectList as $key=>$val){
				try {
					$arr_data[$key]['com_id'] = arr::get($val, "com_id");
					$arr_data[$key]['com_name'] = (isset($val['com_name']) and $val['com_name']) ? $val['com_name'] :"没有公司名称";
					$arr_data[$key]['com_contact'] = (isset($val['com_contact']) and $val['com_contact']) ? $val['com_contact'] : "没有企业联系人";
					$arr_user_info = Service_Sso_Client::instance()->getUserInfoById($val['com_user_id']);
					$arr_data[$key]['mobile'] = (isset($arr_user_info->mobile) and $arr_user_info->mobile) ? $arr_user_info->mobile : "没有手机号码";
					$arr_data[$key]['email'] = (isset($arr_user_info->email) and $arr_user_info->email) ? $arr_user_info->email : "没有邮箱";
				} catch (Kohana_Exception $e) {
					print_r($val->com_id);exit;
				}
			}
		}
		#导出开始
		header('Content-Type: text/xls');
		header ( "Content-type:application/vnd.ms-excel;charset=utf-8" );
		$str = mb_convert_encoding("企业信息", 'gbk', 'utf-8');
		header('Content-Disposition: attachment;filename="' .$str . '.xls"');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		$table_data = '<table border="1">';
		$table_data .='<tr>
							<td>'.mb_convert_encoding("企业ID", 'gbk', 'utf-8').'</td>
							<td>'.mb_convert_encoding("企业名称", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("企业联系人", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("手机号码", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("邮箱", 'gbk', 'utf-8').'</td>';
		foreach ($arr_data as $line)
		{
			$table_data .= '<tr>';
			foreach ($line as $key => &$item)
			{
				$item = mb_convert_encoding($item, 'gbk', 'utf-8');
				$table_data .= '<td>' . $item . '</td>';
			}
			$table_data .= '</tr>';
		}
		$table_data .='</table>';
		echo $table_data;
		die();
	}
	
	/*
	public function action_DoUpdateProjectOutsideID(){
		$obj_data = ORM::factory("Project")->where("outside_id","=",0)->where_open()->where("project_real_order","=",1)->or_where("project_real_order","=",2)->or_where("project_real_order","=",3)->or_where("project_real_order","=",5)->where_close()->find_all();
		$arr_data = array();
		$Service_Api_Basic =  new Service_Api_Basic();
		if(count($obj_data) > 0){
			foreach ($obj_data as $key=>$val){
				$arr_data[$key]['project_brand_name'] = $val->project_brand_name;
				$arr_data[$key]['project_id'] = $val->project_id;
			}
		}
		//echo  "<pre>"; print_r($arr_data);exit;
		try {
			if(count($arr_data) > 0){
				foreach ($arr_data as $key=>$val){
					$json_data = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/postProjectExactList",array("pro_name"=>arr::get($val,"project_brand_name")));
					//echo "<pre>"; print_r($json_data);exit;
					if(arr::get($json_data,'msg')){
						$bool = $this->UpdateProject(arr::get($val,"project_id"),arr::get($json_data['msg'][0],'pro_id'));
						if($bool == true){
							//入库日志	#入库到log开始
	        				$arr_Projectoutside_data = array('outside_project_id'=>arr::get($val,"project_id"),
	        									'outside_project_name'=>arr::get($val,"project_brand_name"),
	        									'status'=>1,
	        									'addtime'=>time(),
	        									'project_type'=>20
	        									);
	        				$bool_ruku = self::insert_project_outside($arr_Projectoutside_data);
							if($bool_ruku == true){
								echo  "项目名称 / ".arr::get($val,"project_brand_name")."/ 修改成功\n";
							}
						}
					}
				}
			}
		}catch (Exception $e){
			
		}
	}
	
	//修改主表项目
	public function UpdateProject($project_id,$outside_id){
	//	echo "<pre>"; print_r($outside_id);exit;
		try {
			if($project_id && $outside_id){
				$model = ORM::factory("Project",intval($project_id));
				if($model->loaded()){
					if($model->outside_id =="" || $model->outside_id == 0)
					$model->outside_id = $outside_id;
					$model->project_source = 2;
					$model->update();
					$model->clear();
					return  true;
				}
			}
			return false;
		}catch (Exception $e){
			return false;
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
	
	
	public function action_getOutProject(){
		 #获取总数
        $count = ORM::factory("Project")->where("project_status","=",2)->where_open()->where("project_source","=",4)->or_where("project_source","=",5)->where_close()->count_all();
        //echo "<pre>"; print_R($count);exit;
        #分页跑
         $page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' =>300
        ) );
         #获取项目信息
         $projectList = ORM::factory("Project")->limit ( $page->items_per_page )->offset ( $page->offset )->where("project_status","=",2)->where_open()->where("project_source","=",4)->or_where("project_source","=",5)->where_close()->find_all()->as_array();
         #导出数组
         $arr_out = array();
		 $projectservice = new Service_User_Company_Project();
		 $service = new Service_Platform_Project();
         foreach ($projectList as $key=>$val){
         	$com_id = "";
         	try {
         		#项目id
         		$arr_data[$key]['project_id'] = $val->project_id;
         		#项目名称
         		$arr_data[$key] ['project_brand_name']= $val->project_brand_name;
         		
         		if($val->project_source == 4){
         			$com_id = $val->outside_id;
         		}elseif($val->project_source == 5){
         			$com_id = $val->outside_com_id;
         		}
         		$companyinfo = $service->getCompanyByProjectID($val->project_id);
         		$arr_data[$key] ['com_name'] = isset($companyinfo->com_name) ? $companyinfo->com_name : "暂无公司名称";
				#投资金额
				if($val->project_amount_type == 1){
					$arr_data[$key] ['project_amount'] = "5万以下";
				}elseif($val->project_amount_type == 2){
					$arr_data[$key] ['project_amount'] = "5-10万";
				}elseif($val->project_amount_type == 3){
					$arr_data[$key] ['project_amount'] = "10-20万";
				}elseif($val->project_amount_type == 4){
					$arr_data[$key] ['project_amount'] = "20-50万";
				}elseif($val->project_amount_type == 5){
					$arr_data[$key] ['project_amount'] = "50万以上";
				}else{
					$arr_data[$key] ['project_amount'] = "暂无投资金额";
				}
				#地区
				$pro_area = $projectservice->getProjectArea($val->project_id);
				$area = '';
                if (count($pro_area) && is_array($pro_area)) {
                    foreach ($pro_area as $v) {
                        $area = $area . $v . '/';
                    }
                    $area = substr($area, 0, -1);
                } else {
                    $area = $pro_area;
                }
				$arr_data[$key] ['project_city'] = $area ? $area : "暂无招商地区";
				#行业17238
				$pro_industry = $projectservice->getProjectindustryAndId($val->project_id);
				if($pro_industry){
				$arr_data[$key] ['project_industry'] = arr::get($pro_industry,"one_name","")."/".arr::get($pro_industry,"two_name","");
				}else{
				$arr_data[$key] ['project_industry'] = "暂无行业";
				}
				#形式
				$projectcomodel = $projectservice->getProjectCoModel($val->project_id);
				//echo "<pre>"; print_R($projectcomodel);exit;
				$ProjectCoModels = "";
				$lst = common::businessForm();
				$pro_count=count($projectcomodel);
                    if($pro_count){
                        $comodel_text=0;
                        foreach ($projectcomodel as $v){
                            $comodel_text++;
							$ProjectCoModels .= $lst[$v];
                            if($comodel_text < $pro_count){
                               $ProjectCoModels.='/';
                            }
                        }
                    }else{
                        $ProjectCoModels =  '暂无招商形式';
                    }
				$arr_data[$key] ['ProjectCoModels'] = $ProjectCoModels ;
         		$arr_out=$arr_data;
         	}catch ( ErrorException $e){
         		echo $val->project_id;exit;
         	}
         }
		 #导出开始
        header('Content-Type: text/xls');
        header ( "Content-type:application/vnd.ms-excel;charset=utf-8" );
         $str = mb_convert_encoding("外采信息", 'gbk', 'utf-8');
         header('Content-Disposition: attachment;filename="' .$str . '.xls"');
         header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
         header('Expires:0');
         $table_data = '<table border="1">';
         $table_data .='<tr><td>'.mb_convert_encoding("项目ID", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("项目名称", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("企业名称", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("项目投资金额", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("项目招商地区", 'gbk', 'utf-8').'</td>
							 <td>'.mb_convert_encoding("项目行业", 'gbk', 'utf-8').'</td>
                             <td>'.mb_convert_encoding("项目招商形式", 'gbk', 'utf-8').'</td>';
         foreach ($arr_out as $line)
         {
             $table_data .= '<tr>';
             foreach ($line as $key => &$item)
             {
                 $item = mb_convert_encoding($item, 'gbk', 'utf-8');
                 $table_data .= '<td>' . $item . '</td>';
             }
             $table_data .= '</tr>';
         }
         $table_data .='</table>';
         echo $table_data;
         die();
	}
	
	#获取企业信息
	private function _getComInfo($com_id,$type){
		$model ="";
		if($com_id && $type){
			if($type == 4){
				$model = ORM::factory("Usercompany",intval($com_id))->com_name;
			}else{
				$model =  $model=ORM::factory('TestCompany',$com_id)->com_name;
			}
		}
		return $model;
	}
	*/
}