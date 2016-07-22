<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_CheckOutsidePro extends Minion_Task {
    
    protected function _execute(array $params) {
        $count = DB::select()->from('test_project')->where('project_status', '=', 3)->execute()->count();
        $industry = common::getIndustryList();
        $modProject = new Service_Platform_Project ();
            if ($count) {
                for ($i = 0; $i < $count/100; $i++) {
                    $project = DB::select()->from('test_project')->where('project_status', '=', 3)->limit(100)->offset($i)->execute()->as_array();
                    if ($project) {
                        foreach ($project as $key => $val) {
                            $proName = $val['pro_name'];
                            $comName = $val['com_name'];
                            $proOutside = $val['outside_id'];
                            $status = 0;
                            $industryId = 0;
                            foreach($industry as $keyIn => $valIn) {
                                if(is_int(strpos($proName, $valIn["first_name"])) || is_int(strpos($comName, $valIn["first_name"]))) {
                                      $status = 1; 
                                      $industryId = $keyIn;
                                      break;
                                }else{
                                    if($valIn['secord']){
                                            foreach($valIn['secord'] as $keyTIn => $valTIn) {
                                                if(is_int(strpos($proName, $valTIn)) || is_int(strpos($comName, $valTIn))) {
                                                    $status = 1;
                                                    $industryId = $keyTIn;
                                                    break;
                                                }
                                            }
                                        }
                                }
                            }
                             var_dump('status', $status);
                            if($status) {
                                $industryPId = $modProject->getIndustryPid($industryId);
                                $projectList = DB::select()->from('project')->where('outside_id', '=', $proOutside)->where('project_status' , '=', 2)->limit(1)->offset(0)->execute()->as_array();
                                var_dump('plist:', $projectList);
                                $indArr = array();
                                if($projectList) {
                                    $project_id = $projectList[0]['project_id'];
                                    $indArr[] = $industryId;
                                    if($industryPId) {
                                        $indArr[] = $industryPId;
                                    }
                                    $this->updateProjectIndustry($project_id, $indArr);
                                    var_dump($project_id, $indArr);
                                    $indArr = array();
                                }
                            }else{
                                $industryId = $this->addIndustry($val['pro_industry']);
                                $industryPId = $modProject->getIndustryPid($industryId);
                                $projectList = DB::select()->from('project')->where('outside_id', '=', $proOutside)->where('project_status' , '=', 2)->limit(1)->offset(0)->execute()->as_array();
                                var_dump('plist:', $projectList);
                                $indArr = array();
                                if($projectList) {
                                    $project_id = $projectList[0]['project_id'];
                                    $indArr[] = $industryId;
                                    if($industryPId) {
                                        $indArr[] = $industryPId;
                                    }
                                    $this->updateProjectIndustry($project_id, $indArr);
                                    var_dump($project_id, $indArr);
                                }
                            }
                            $params = array('project_status' => '2');
                            DB::update('test_project')->set($params)->where('pro_id', '=', $val['pro_id'])->execute();
                        }
                        
                        
                    }

            }
        }
    }
    public function addIndustry($pro_industry) {
        $pro_industry = explode(',', preg_replace("/\r/", '', $pro_industry));
        #处理行业
        if ($pro_industry) {
            foreach ($pro_industry as $val) {
                if (trim($val)) {
                    $area = DB::select('question_id')->from('directory')->where('keyword', '=', $val)->where('status', '!=', 2)->where('question_type', '=', 6)->limit(1)->execute()->as_array();
                    if ($area && $area[0]['question_id']) {
                        return $area[0]['question_id'];
                        continue;
                    } else {
                        $pro_industry_status = false;
                        break;
                    }
                    unset($area);
                }
            }
           
        }else {
            return 0;
        }
        return 0;
          
    }
    public function updateProjectIndustry($project_id,$data){
        $project_model = ORM::factory('Projectindustry')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $this->deleteProjectIndustry($v->pi_id);
            }
        }
        //添加招商形式信息
        $this->addProjectIndustry($project_id,$data);
        return true;
    }
    
    public function deleteProjectIndustry($pi_id){
        $project_model = ORM::factory('Projectindustry');
        $result = $project_model->where("pi_id", "=",$pi_id)->find();
        if(!empty($result->pi_id)){
            $project_model->delete($result->pi_id);
            return true;
        }
        return false;
    }
    
    public function addProjectIndustry($project_id,$data){
        if(count($data) > 0){
            foreach ($data as $v){
                $project_model = ORM::factory('Projectindustry');
                $project_model->project_id = $project_id;
                $project_model->industry_id = intval($v);
                $project_model->industry_id = intval($v);
                $project_model->status = 2 ;
                $project_model->save();
            }
            return true;
        }else{
            return false;
        }

    }
}