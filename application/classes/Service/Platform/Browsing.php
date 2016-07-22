<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 随便逛逛
 * @author 曹怀栋
 *
 */
class Service_Platform_Browsing{
    /**
     *取得行业一级行业
     * @author 曹怀栋
     */
    public function getPrimaryIndustry(){
        $industry=ORM::factory('Industry')->where('industry_status','=',1)->find_all()->as_array();
        $industry_list = array();
        //取得7个一级行业
        foreach ($industry as $k=>$v){
            $project_list=ORM::factory('Projectlist')->where('project_industry_id','=',$v->industry_id)->find_all()->as_array();
            if(count($project_list) > 0){
                foreach ($project_list as $ke=>$ve){
                    if($ve->project_id > 0){
                        $industry=ORM::factory('Project')->where('project_id','=',$ve->project_id)->find()->as_array();
                        $industry_list[$k+1]['detail'][$ke+1]['project_id'] = $industry['project_id'];
                        $industry_list[$k+1]['detail'][$ke+1]['project_brand_name'] = $industry['project_brand_name'];
                        $industry_list[$k+1]['detail'][$ke+1]['project_logo'] = URL::imgurl($industry['project_logo']);
                        $industry_list[$k+1]['detail'][$ke+1]['project_source'] = $industry['project_source'];
                        $industry_list[$k+1]['detail'][$ke+1]['outside_id'] = $industry['outside_id'];
                        $industry_list[$k+1]['detail'][$ke+1]['list_reasons'] = $ve->list_reasons;
                        $industry_list[$k+1]['detail'][$ke+1]['join_conditions'] = $ve->join_conditions;
                        $industry_list[$k+1]['detail'][$ke+1]['profit_analysis'] = $ve->profit_analysis;
                    }
                }
            }
            if(isset($industry_list[$k+1]['detail'])){
                $industry_list[$k+1]['industry_id'] = $v->industry_id;
                $industry_list[$k+1]['industry_name'] = $v->industry_name;
            }
        }
        return $industry_list;
    }
    /**
     *取得指定日期项目总数
     * @author 曹怀栋
     */
    public function getEverydayCount($project_id,$data){
        $model = ORM::factory('Projectstatistics');
        $result = $model->where('insert_time', '<=', $data['end_time'])->where('insert_time', '>=', $data['start_time'])->where('project_id', '=', $project_id)->count_all();
        return $result;
    }
    /**
     *取得指定项目倍数
     * @author 曹怀栋
     */
    public function getProjectMultiple($project_id){
        $project_list=ORM::factory('Projectlist')->where('project_id','=',$project_id)->find()->as_array();
        $num = 1;
        if($project_list['project_id'] !=""){
            $num = $project_list['multiple'];
        }
        return $num;
    }
    /**
     * 获得项目访问总数
     * @author 施磊
     * 
     */
    public function getProjectAllSta($project_id) {
        $project_id = intval($project_id);
        if(!$project_id) return 0;
        return ORM::factory('Projectstatistics')->where('project_id','=',$project_id)->count_all();
    }
    /**
     *取得项目总数
     * @author 曹怀栋
     */
    public function getProjectCount(){
        $model = ORM::factory('Project');
        $result = $model->where('project_status', '=', 2)->count_all();
        return $result;
    }
    /**
     *取得最多十四个项目数
     * @author 曹怀栋
     */
    public function getProjectList(){
        $model = ORM::factory('Project');
        $result = $model->where('project_status', '=', 2)->limit(14)->find_all()->as_array();
        return $result;
    }
    
    /**
     * 取得30天的pv总值
     * @author 施磊
     */
    public function getProjectPvCount($project_id, $start, $end) {
        $project_id = intval($project_id);
        $query = DB::select(array(DB::expr('COUNT("ip")'), 'count'), 'ip')->from('project_statistics')->where('project_id', '>=', $project_id)->where('insert_time', '>=', $start)->where('insert_time', '<=', $end)->group_by('ip')->order_by('count', 'desc')->execute()->as_array();
        return $query;
    }
}
