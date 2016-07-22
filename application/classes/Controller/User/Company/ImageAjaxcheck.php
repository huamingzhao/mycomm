<?php defined('SYSPATH') or die('No direct script access.');
/**
 * ajax
 * @author 潘宗磊
 *
 */
class Controller_User_Company_ImageAjaxcheck extends Controller{
    /**
     * 上传企业图片
     * @author 施磊
     */
    public function action_uploadComLogo() {
        $post = Arr::map("HTML::chars", $this->request->post());
        if(isset($post['data'])) {
            $mod = new Service_User_Company_User();
            $mod->editComUser($this->userId(), $post['data']);
            //顺便更改sso下的
            $servcie= Service_Sso_Client::instance();
            $servcie->updateBasicInfoById( $this->userId(),array('user_portrait'=>$post['data']) );
            $this->jsonEnArr('200', $post['data']);
        }else{
            $this->jsonEnArr('500', '上传图片为空');
        }
    }

    /**
     * 上传项目海报
     * @author 施磊
     */
    public function action_uploadPoster() {
        $redis = Cache::instance("redis");
        $post = Arr::map("HTML::chars", $this->request->post());
        if(isset($post['data'])) {
            $mod = new Service_User_Company_Project();
            $int_project_status = $mod->get_project_status($post['projectid']);
            $molde = ORM::factory("Projectposter",$post['projectid']);
            if($int_project_status == 2){
                $redis->set($post['projectid']."_project_haibao_status",1,"2592000");
            }
             if($int_project_status == 2 && $molde->project_id > 0 && $molde->poster_status == 2){
                 $redis->set($post['projectid']."_project_haibao",json_encode($post['data']),"2592000");
                 if($molde->loaded()){
                     $molde->poster_temp_status = 1;
                     $molde->last_edittime = time();
                     $molde->add_time = time();
                     $molde->poster_unpass_reason ="";
                     $molde->update();
                     $molde->clear();
                 }
             }else{
                $mod->uploadProjectPoster($post['projectid'], $post['data']);
            }
            $this->jsonEnArr('200', $post['data']);
        }else{
            $this->jsonEnArr('500', '上传图片为空');
        }
    }

    /**
     * 返回ajax状态
     * @author 施磊
     * @param int $code 状态码
     * @param string or array $msg 提示信息
     * @param int $type 0 为 直接echo 1 是return
     * @return json
     */
    private function jsonEnArr($code, $msg, $type = 0) {
        $arr = array('code' => $code, 'msg' => $msg, 'date' => time());
        $return = json_encode($arr);
        if($type) {
            return $return;
        }else{
            echo $return;exit;
        }
    }
}