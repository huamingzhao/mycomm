<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 企业用户
 * @author 施磊
 *
 */
class Controller_Sapi_User_Company_Project extends Controller_Sapi_Basic{
    //公用的model实例化
    private $model = '';
    public function before() {
        parent::before();
        $this->model = new Service_User_Company_Project();
    }

    /*
     * 提供给外部使用的api 查询公司下的项目
     * @author 施磊
     * @return array
     */
    public function action_getCompanyProjectByIds() {
        $ids = $this->request->post('ids');
        if(!$ids) $this->setApiReturn(501);
        $return = array();
        $ids = json_decode($ids, TRUE);
        if(!$ids) $this->setApiReturn(501);
        foreach ($ids as $val) {
            if($val && !Arr::get($return, $val)) {
                $return[$val]['count'] = $this->model->getProjectCount($val);
                //$return[$val]['lastName'] = Arr::get(Arr::get(Arr::get($this->model->showProject($val),'list'), 0), 'com_name', '');
            }
        }
        $this->setApiReturn(200, 0, $return);

    }

    /*
     * 提供给外部使用的api 查询公司下的项目列表
     * @author 施磊
     * @return array
     */
    public function action_getCompanyProjectBasicList() {
        $com_id = intval($this->request->post('com_id'));
        if(!$com_id) $this->setApiReturn(405);
        $return = array();
        try {
           $return = $this->model->getCompanyProjectBasicList($com_id);
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);

    }

    /*
     * 提供给外部使用的api 添加单个项目信息
    * @author 曹怀栋
    */
    public function action_addProject() {
        $form = Arr::map("HTML::chars", $this->request->post());
        //数组改成字符串
        $form = $this->model->arrayToString($form);
        //验证添加信息是否正确
        $result = $this->model->addProjectCheck($form);
        $ret = common::uploadPic($_FILES, 'project_logo',array('w'=>120, 'h'=>150));

        if($result == 1 && $ret['error'] ==""){
           $form['project_logo'] = common::getImgUrl($ret['path']);
        }else{
           $this->setApiReturn(501);
        }
        $return = "";
        try {
            $return = $this->model->addProject($form,$this->userId());
        } catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200', 'ok', $return);

    }
}