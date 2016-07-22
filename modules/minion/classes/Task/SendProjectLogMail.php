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
class Task_SendProjectLogMail extends Minion_Task{

	protected function _execute(array $params){
		#php shell php minion --task=SendProjectLogMail
		try {
			 $str_send_content = self::getMailContentOfProjectLog();
			 $to_mail = array('shenpengfei@yijuhua.net',"zhangguiyunD850@tonglukuaijian.com","jiyec458@tonglukuaijian.com");
			//$to_mail = array('jiye@yijuhua.net','chenxin@yijuhua.net');
			 foreach($to_mail as $to){
			 	common::sendemail('875项目数据同步日志-'.date("Y年m月d日"), 'service@yijuhua.com', $to, $str_send_content);
			 }
		}catch (Exception $e){
			
		}
	}

	protected function getMailContentOfProjectLog(){
		try{
			$str = "";
			$today = strtotime("today");
			$invest =  ORM::factory("Projectoutside")->where('addtime', ">=", $today)->where('addtime', "<=", $today+24*60*60)->where('project_type', "=", 2)->find_all();
			$project_num  = ORM::factory("Projectoutside")->where('addtime', ">=", $today)->where('addtime', "<=", $today+24*60*60)->where('project_type', "=", 2)->where("status","=",1)->count_all();
			//echo $project_num;exit;
			$invest_table = "<table>
	                             <thead>
	                                <tr>
	                                    <th>项目ID</th>
	                                    <th>项目名称</th>
										<th>入库说明</th>
	                                    <th>入库时间</th>
	                                </tr>
	                            </thead>
	                            <tbody>";
			$num = count($invest);
			foreach($invest as $v){
				$str_name ="";
				$addtime = date('Y.m.d H:i:s',$v->addtime);
				$url = urlbuilder::project($v->outside_project_id);
				if($v->status == 1){$str_name="已成功入库！";}else{$str_name="入库 失败！";}
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
}
?>