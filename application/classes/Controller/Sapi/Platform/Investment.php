<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 招商会
 * @author 潘宗磊
 *
 */
class Controller_Sapi_Platform_Investment extends Controller_Sapi_Basic{


    public function action_getProjectInvestId() {
        $service = new Service_Platform_Invest();
        try{
            $return = $service->getProjectInvestId();
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 获取今天正在召开的最后一次招商会
     * @author 花文刚
     */
    public function action_getTodayLastInvest(){
        $post = $this->request->post();
        $project_id = $post['project_id'];
        $service = new Service_Platform_Invest();

        try{
            $return = $service->getTodayLastInvest($project_id);
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }


    public function action_index() {
        exit("不能从外部调用");
    }
}
