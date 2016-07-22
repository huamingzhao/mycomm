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
class Task_UpdateProjectSource extends Minion_Task{
	protected function _execute(array $params){
		#php shell php minion --task=UpdateProjectSource
		$this->GetProjectId(true);
	}
	
	protected function GetProjectId($bool){
		$Service_Api_Basic =  new Service_Api_Basic();
		$arr_data = array();
		$arr_data_id = array();
		$arr_data_list = array();
		$arr_time = array();
		if($bool === true){
			//获取项目的outside_id
			$arr_data = DB::select('outside_id')->from("project")->where('project_source','=',2)->where("outside_id","!=",0)->where("isrenling_project","=",0)->execute()->as_array();
			if($arr_data){
				foreach ($arr_data as $key=>$val){
					$arr_data_list[] = $val['outside_id'];
					unset($arr_data[$key]);
				}
			}
		}else if($bool === false){
			//时间脚本
			$today = strtotime("today");
			$arr_time = array('start_time'=>$today,'end_time'=>$today+24*60*60);
		}
		//调用接口
		//array('time'=>$arr_time)
		$arr_data_id = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/postProjectInfomation",array('arr_project_id'=>$arr_data_list));
		//echo "<pre>"; print_R($arr_data_list);exit;
 		//循环数据
		if(isset($arr_data_id['msg']) && $arr_data_id['msg']){
			$num = 0;
			foreach ($arr_data_id['msg'] as $key=>$val){$num++;
			if($num == 150){
				echo "停一下"; die;
			}
				//修改数据
				$arr_retrun_data = $this->update_source($val);
				if($arr_retrun_data){
					//数据放进操作日志中
					$bool = $this->insert_project_outside(array('outside_project_id'=>$arr_retrun_data['id'],'outside_project_name'=>arr::get($arr_retrun_data, "name"),'status'=>5,'addtime'=>time(),'project_type'=>5));
				}
			}
		}
		//人员名单
		$to_mail = array('shenpengfei@yijuhua.net','jiyec458@tonglukuaijian.com',"chenxin@tonglukuaijian.com");
		//邮件发送
		$str_send_content = $this->getMailContentOfProjectLog();
		foreach($to_mail as $to){
			common::sendemail('修改875项目状态'.date("Y年m月d日"), 'service@yijuhua.com', $to, $str_send_content);
		}
	}
	// 修改状态
	protected  function update_source($outside){
		$return_data = array();
		if($outside){
			$project_model = ORM::factory("Project")->where("outside_id",'=',$outside)->find();
			if($project_model->loaded()){
				if($project_model->project_source == 2 && $project_model->isrenling_project == 0){
					$project_model->project_source = intval(6);
					$project_model->project_updatetime = time();
					$project_model->project_vip_set = 1;
					$project_model->update();
					$return_data['id'] = $project_model->project_id;
					$return_data['name'] = $project_model->project_brand_name;
					$return_data['update_time'] = time();
					$project_model->clear();
				}
			}
		}
		return $return_data;
	}
	
	#发送邮件
	protected function getMailContentOfProjectLog(){
		try{
			$str = "";
			$today = strtotime("today");
			$invest =  ORM::factory("Projectoutside")->where('addtime', ">=", $today)->where('addtime', "<=", $today+24*60*60)->where('project_type', "=", 5)->find_all();
			$project_num  = ORM::factory("Projectoutside")->where('addtime', ">=", $today)->where('addtime', "<=", $today+24*60*60)->where('project_type', "=", 5)->where("status","=",5)->count_all();
			//echo $project_num;exit;
			$invest_table = "<table>
	                             <thead>
	                                <tr>
	                                    <th>项目ID</th>
	                                    <th>项目名称</th>
										<th>修改说明</th>
	                                    <th>修改时间</th>
	                                </tr>
	                            </thead>
	                            <tbody>";
			$num = count($invest);
			foreach($invest as $v){
				$str_name ="";
				$addtime = date('Y.m.d H:i:s',$v->addtime);
				$url = urlbuilder::project($v->outside_project_id);
				if($v->status == 5){$str_name="已成功修改成普通项目！";}else{$str_name="修改失败！";}
				$invest_table .= " <tr>
				<td>$v->outside_project_id</td>
				<td><a href='$url'>$v->outside_project_name</a></td>
				<td>$str_name</td>
				<td>$addtime</td>
				</tr>";
			}
			$invest_table .= "</tbody>
			</table>";
				$shibai_num = $num-$project_num;
				$str = date("Y年m月d日")." 875同步总数为 ($num) 条,成功导入数量为: ($project_num) 条, 导入失败的数量为：($shibai_num) 条<br/>";
				if($num>0){
				$str .= $invest_table;
				}
		}catch (Exception $e){
			
		}
		return $str;
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
}
?>