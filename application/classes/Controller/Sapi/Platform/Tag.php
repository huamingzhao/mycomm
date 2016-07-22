<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 标签操作
 * @author 潘宗磊
 *
 */
class Controller_Sapi_Platform_Tag extends Controller_Sapi_Basic{

    /**
    * 添加标签
    * @author 潘宗磊
    */
    public function action_addTag() {
        $service = new Service_Platform_Tag();
        $tags_arr = $this->request->post();
        $tags_arr = json_decode($tags_arr['param'], TRUE);
        try{
            $return = $service->addTag($tags_arr);
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 标签首页
     * @author 潘宗磊
     */
    public function action_index() {
        exit("不能从外部调用");
    }
}
