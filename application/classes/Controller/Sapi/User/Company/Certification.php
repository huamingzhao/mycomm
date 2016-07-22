<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 企业资历认证
 * @author 施磊
 *
 */

class Controller_Sapi_User_Company_Certification extends Controller_Sapi_Basic{
    //公用的model实例化
    private $model = '';
    public function before() {
        parent::before();
        $this->model = new Service_User_Company_User();
    }
    
    /**
     * 获得企业审核数据
     * @author 施磊
     * @return array
     */
    public function action_getCompanyCertification() {
        $cond = json_decode($this->request->post('cond'), TRUE);    
        try {
           $return = $this->model->getCompanyCertification($cond);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
    
    /**
     * 获得企业资质图片
     * @author 施磊
     */
    public function action_getCommonImgByComid() {
        $com_id= $this->request->post('com_id');    
        try {
           $return = $this->model->getCommonImgByCompanyId($com_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);
    }
}