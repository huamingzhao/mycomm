<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网站地图
 * @author 嵇烨
 
 */
 
class Service_Map_Map{
    /**
     * 拿取最新的网站数(1000)
     * @author 嵇烨
     */
    public function  getProjectInfo(){
        $array = array ();
        #项目的数量
       /* $count = DB::select('project_id')
                    ->from('project')
                    ->where("project_status", '=', 2)
                    ->order_by("project_addtime","DESC")
                    ->limit(1000)
                    ->execute()
                    ->count();
        
        #开始分页
        $page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' => 1000
        ) );
        */
        #拿取最新的1000条数据
        $array ['list'] = DB::select('project_id','project_brand_name')
                            ->from('project')
                            ->where("project_status",'=','2')
                            ->limit ( 1000 )
                            ->order_by("project_addtime","DESC")
                            ->execute()
                            ->as_array();
        return $array;
    }
    /**
     * 处理数据
     * @author 嵇烨
     *
     */
    public function  dealProject($arr_data,$pageNum = NULL,$str_stringname = NULL){
        $memcache = Cache::instance ( 'memcache' );
        $_cache_get_time = 86400;
        if($str_stringname){
            $_cache_get_project = "project_info_".$str_stringname."_".intval($pageNum);
        }else{
            $_cache_get_project = "project_info";
        }
        $return = array();
        if(!empty($arr_data)){
            $return['list'] = arr::get($arr_data,"list",array());
            $return['page'] = isset($arr_data['page']) ? $arr_data['page'] : "";
        }
        $memcache->set($_cache_get_project, $return,$_cache_get_time);
        return $return;
    }
    /**
     *通过字母查找项目
     *@author 嵇烨
     */
    public function  findProjectByGrapheme($str_data){
        $array = array ();
        if($str_data){
            $array = array ();
            $model =  ORM::factory("Project");
            #项目的数量
            $count = $model->where("project_status", '=', 2)->order_by("project_addtime","DESC")->limit(1500)->where("project_pinyin","=",$str_data)->count_all();
            #开始分页
            $page = Pagination::factory(array(
                        'total_items' => $count,
                        'items_per_page' => 200,
                        'current_page' => array('source' => 'siteMap', 'key' => 'page')
            ));
            #拿取最新的1500条数据
            $array ['list'] = DB::select('project_id','project_brand_name')
            				  ->from('project')
            				  ->where("project_status",'=','2')
            				  ->limit ( $page->items_per_page )
            				  ->offset ( $page->offset )
            				  ->where("project_pinyin","=",$str_data)
            				  ->order_by("project_addtime","DESC")
            				  ->execute()->as_array();
            $array ['page'] = $page;
        }
        return $array;
    }
}
?>
