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
class Task_InsertProjectImage extends Minion_Task
{
    /**
     * Generates a help list for all tasks
     *  @author 嵇烨
     * @return null
     */
    protected function _execute(array $params){
        #php shell php minion --task=InsertProjectImage
            $this->_projectPro();

    }
    #跑图片脚本
    public function _projectPro(){
        $count = DB::select()->from('Project')->where('project_source', '=', 5)->where("project_temp_status", "=", "2")->execute()->count();
        #每次跑100条数据
        if ($count > 0) {
            for ($i = 0; $i <= $count/100; $i++) {
                $arr_res = DB::select("project_id","outside_id")->from('Project')->where('project_source', '=', 5)->where('project_temp_status', '=', 2)->limit(100)->offset($i)->execute()->as_array();
                $this->_updateImageList($arr_res);
            }
        }
    }
    public function _updateImageList($arr_res){
        foreach ($arr_res as $key=>$val){
            #外采的id
            $int_outside_id = $val['outside_id'];
            #项目的id
            $int_project_id = $val['project_id'];
            $model_project =  ORM::factory("Project",intval($int_project_id));
            #把临时的字段的状态改成1供下次跑project_tag 脚本
            $model_project->project_temp_status = 1;
            $model_project->update();
            #查询czzs_test_project表并获取项目图片(外采的)
            $arr_outProjectImageList = DB::query(Database::SELECT, "SELECT `pro_image` FROM czzs_test_project where czzs_test_project.outside_id = {$int_outside_id}")->execute()->as_array();
            if(!empty($arr_outProjectImageList[0]['pro_image'])){
                $arr_imageList = @array_unique(explode(",", $arr_outProjectImageList[0]['pro_image']));
                $certModel = ORM::factory("Projectcerts");
                #删除相同的项目id的图片
                $models = $certModel->where('project_id','=',$int_project_id)->find_all();
                if(count($models) >0){
                    foreach ($models as $m){
                        $m->delete();
                    }
                }
                #写入数据库
                foreach ($arr_imageList as $k=>$v){
                $certModel->project_id = $int_project_id;
                $certModel->project_type = 1;
                $certModel->project_img = "poster/html/ps_".$int_outside_id."/".$v;
                $certModel->project_addtime = time();
                        $certModel->create();
                        $certModel->clear();
                }
                }
            }
            unset($arr_res);
        }
}
