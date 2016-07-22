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
class Task_UpdateProjectTags extends Minion_Task
{
    /**
     * 更新项目表中的tags数据
     * @author 郁政
     */
    protected function _execute(array $params){

        #php minion --task=UpdateProjectTags
        $this->_updateprojectTag();
    }

    #纠正peoject表的peoject_tag字段数据
    protected function _updateprojectTag (){
        $count = DB::select()->from('project')->where("project_status", "=", "2")->where("project_temp_status", "=", "2")->execute()->count();
        #每次跑100条数据
        if ($count > 0) {
            for ($i=0 ;$i<= $count/100; $i++) {
                $arr_res = DB::select("*")->from('project')->where("project_status", "=", "2")->where('project_temp_status', '=', 2)->limit(100)->offset($i)->execute()->as_array();
                $this->_doUpdateProjectTag($arr_res);
            }
        }
    }

    #修改字段
    protected function _doUpdateProjectTag($arr_data){
        $str_data = null;
        foreach ($arr_data as $key=>$val){
            try{
            #拼接项目名称
            $str_data .=$val['project_brand_name'].",";
            #拼接项目投资金额
            $str_data .=$this->_get_project_amount_type($val['project_amount_type']);
            #拼接招商地区
            $str_data .=$this->_get_project_are($val['project_id']);
            #拼接招商行业
            $str_data .=$this->_get_project_industry($val['project_id']);
            #获取招商形式
            $str_data .=$this->_get_project_model($val['project_id']);
            #获取人脉关系
            $str_data .=$this->_get_Project_Connection($val['project_id']);
            #拼接自定义标签
            $str_data .=$this->_get_project_tag($val['project_id']);
            #拼接投资风险
            $str_data .=$this->_get_risk($val['risk']);
           #执行修改
           $model = ORM::factory("Project",$val['project_id']);
           $model->project_tags = $str_data;
           $model->project_temp_status = 1;
           $model->update();
           $model->clear();
           $str_data = null;  }catch (Error $e){
               echo $val['project_id'];exit;
           }
        }
    }
    #获取项目自定义标签
    protected function _get_project_tag($int_project_id){
        $str_name =null;
        if($int_project_id){
            $ormModel = ORM::factory('Projecttag')->where('project_id', '=', $int_project_id)->find_all();
            $tagModel = ORM::factory('tag')->find_all();
            foreach ($ormModel as $key=>$val) {
                $tagModel = ORM::factory('tag',$val->tag_id)->as_array();
                if($tagModel['tag_id']){
                    $str_name .= $tagModel['tag_name'].",";
                }
            }
        }
        return $str_name;
    }
    #获取投资风险
    protected function _get_risk($int_num){
        $str_name = NULL;
        if($int_num){
            $investmentStyle =common::investmentStyle();
            foreach ( $investmentStyle as $k => $v ) {
                if ($int_num == $k) {
                    $str_name .= $v;
                }
            }
        }
        return $str_name;
    }
    #获取人脉关系
    protected function _get_Project_Connection($int_project_id){
        $str_name = null;
        if($int_project_id){
            $project_connection = ORM::factory('Projectconnection')->where("project_id", "=", $int_project_id)->find_all();
            if(!empty($project_connection)){
                $connectionsArr = common::connectionsArr();
                     foreach ($project_connection as $key=>$val){
                        foreach ($connectionsArr as $k=>$v){
                            if($val->connection_id == $k){
                                $str_name .= $v.",";
                            }
                        }
                    }
            }
        }
        return  $str_name;
    }
    #获取招商形式
    protected function _get_project_model($int_project_id){
        $str_name = null;
        if($int_project_id){
            $model = ORM::factory('Projectmodel')->where("project_id", "=", $int_project_id)->find_all();
            if(!empty($model)){
                $type = common::businessForm ();
                foreach ($model as $key=>$val){
                    foreach ($type as $k=>$v){
                        if($val->type_id == $k){
                            $str_name .=$v.",";
                        }
                    }
                }
            }
        }
        return $str_name;
    }
    #获取招商行业
    protected function _get_project_industry($int_project_id){
        $str_name = null;
        $server = new  Service_Public();
        if($int_project_id){
            $model = ORM::factory("Projectindustry")->where("project_id", "=", $int_project_id)->find_all()->as_array();
            if(!empty($model)){
                foreach ($model as $key=>$val){
                    $str_name .= $server->getIndustryNameById($val->industry_id).",";
                }
            }
        }
        return $str_name;
    }
    #获取项目地区
    protected function _get_project_are($int_project_id){
        $str_name = null;
        $server = new  Service_Public();
        if($int_project_id){
            $model = ORM::factory("Projectarea")->where("project_id", "=", $int_project_id)->find_all()->as_array();
            if(!empty($model)){
                foreach ($model as $key=>$val){
                      $str_name .= $server->getAreaName($val->area_id).",";
                }
            }
        }
        return $str_name;
    }
    #获取投资金额
    protected function _get_project_amount_type($int_num){
        $str_name = null;
        if($int_num){
            $amount_type = common::moneyArr ();
            foreach ( $amount_type as $k => $v ) {
                if ($int_num == $k) {
                    $str_name = $v."," ;
                }
            }
        }
        return $str_name;
    }
}
?>
