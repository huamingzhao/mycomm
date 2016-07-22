<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 项目
 * @author 郁政
 *
 */
class Controller_Sapi_Platform_Project extends Controller_Sapi_Basic{
    /**
     * 根据项目名称返回项目id
     * @author 郁政
     *
     */
    public function action_getPidByPname(){
        $post = $this->request->post();
        $service = new Service_Platform_Project();
        $project_name = $post['project_name'];
        try {
            $project_id = $service->getPidByPname($project_name);
        } catch (Kohana_Exception $e) {
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $project_id);
    }

    /**
     * 根据项目ID返回项目信息
     * @author 郁政
     */
    public function action_getProjectInfoByID(){
       $post = $this->request->post();
       $service = new Service_Platform_Project();
       $project_id = $post['project_id'];
       try {
            $projectinfo = $service->getProjectInfoByID($project_id);
        } catch (Kohana_Exception $e) {
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $projectinfo->as_array());
    }

    /**
     * 根据项目ID返回企业基本信息
     * @author 郁政
     */
    public function action_getCompanyByProjectID(){
        $post = $this->request->post();
        $service = new Service_Platform_Project();
        $project_id = $post['project_id'];
        try {
            $companyinfo = $service->getCompanyByProjectID($project_id);
        } catch (Kohana_Exception $e) {
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $companyinfo->as_array());
    }

    /**
     * 显示项目统计
     *@author许晟玮
     */
    public function action_showProjectPv(){
            $get= $this->request->query();
            $projectid= Arr::get($get, 'pid');
            $begin= Arr::get($get, 'begin');
            $end= Arr::get($get, 'end');
            $stat_service= new Service_Api_Stat();
            //pv
            $stat_project_pv= $stat_service->getVisitPv( '1',$projectid,$begin,$end );
            $code= $stat_project_pv['code'];
            $result_pro= array();
            $result_web= array();
            if( $code=='200' ){
                $data= $stat_project_pv['data'];

                if( !empty($data) ){
                    foreach ( $data as $k=>$v ){
                        $result_pro[$k]['date']= $v['date'];
                        $result_pro[$k]['pv']= $v['pv'];
                        $result_web[$k]['date']= $v['date'];
                        $result_web[$k]['compv']= $v['compv'];
                    }
                }else{
                    //data is null
                }
            }else{
                //error
                exit( $code );
            }
            $return= array();
            $return['pro']= $result_pro;
            $return['web']= $result_web;
            echo json_encode($return);

    }
    //end function

}
?>