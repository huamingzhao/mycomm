<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 企业用户
 * @author 施磊
 *
 */
class Controller_Sapi_User_Company_Card extends Controller_Sapi_Basic{
    //公用的model实例化
    private $model = '';
    public function before() {
        parent::before();
        $this->model = new Service_User_Company_Card();
    }
    
    /*
     * 提供给外部使用的api 查询公司下的项目
     * @author 施磊
     * @return array
     */
    public function action_getCompanyCardInfo() {
        $user_id = intval($this->request->post('user_id'));
        if(!$user_id) $this->setApiReturn(405);
         $return = array();
        try {
           $return = $this->model->getCompanyCardInfo($user_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
}