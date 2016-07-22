<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 个人资历认证
 * @author 施磊
 *
 */

class Controller_Sapi_User_Personal_Certification extends Controller_Sapi_Basic{
    //公用的model实例化
    private $model = '';
    public function before() {
        parent::before();
        $this->model = new Service_User_Person_User();
    }
    
    /**
     * 获得个人审核数据
     * @author 施磊
     * @return array
     */
    public function action_getPersonalCertification() {
        $cond = json_decode($this->request->post('cond'), TRUE);
        try {
           $return = $this->model->getPersonalCertification($cond);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
}